<?php
namespace Civi\Core;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\FileCacheReader;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Tools\Setup;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

// TODO use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class Container
 * @package Civi\Core
 */
class Container {

  const SELF = 'civi_container_factory';

  /**
   * @var ContainerBuilder
   */
  private static $singleton;

  /**
   * @param bool $reset whether to forcibly rebuild the entire container
   * @return \Symfony\Component\DependencyInjection\TaggedContainerInterface
   */
  public static function singleton($reset = FALSE) {
    if ($reset || self::$singleton === NULL) {
      $c = new self();
      self::$singleton = $c->createContainer();
    }
    return self::$singleton;
  }

  /**
   * @var ContainerBuilder
   * @return \Symfony\Component\DependencyInjection\ContainerBuilder
   */
  public function createContainer() {
    $civicrm_base_path = dirname(dirname(__DIR__));
    $container = new ContainerBuilder();
    $container->setParameter('civicrm_base_path', $civicrm_base_path);
//    $container->setParameter('cache_dir', \CRM_Utils_Path::join(dirname(CIVICRM_TEMPLATE_COMPILEDIR), 'cache'));
    $container->setParameter('cache_dir', \CRM_Utils_Path::join(CIVICRM_TEMPLATE_COMPILEDIR, 'cache'));
    $container->set(self::SELF, $this);

// TODO Move configuration to an external file; define caching structure
//    if (empty($configDirectories)) {
//      throw new \Exception(__CLASS__ . ': Missing required properties (civicrmRoot, configDirectories)');
//    }
//    $locator = new FileLocator($configDirectories);
//    $loaderResolver = new LoaderResolver(array(
//      new YamlFileLoader($container, $locator)
//    ));
//    $delegatingLoader = new DelegatingLoader($loaderResolver);
//    foreach (array('services.yml') as $file) {
//      $yamlUserFiles = $locator->locate($file, NULL, FALSE);
//      foreach ($yamlUserFiles as $file) {
//        $delegatingLoader->load($file);
//      }
//    }

    $container->setDefinition('annotation_reader', new Definition(
      '\Doctrine\ORM\Mapping\Driver\AnnotationDriver',
      array('%civicrm_base_path%', '%cache_dir%/cache/annotations')
    ))
      ->setFactoryService(self::SELF)->setFactoryMethod('createAnnotationReader');

    $container->setDefinition('doctrine_configuration', new Definition(
      '\Doctrine\ORM\Configuration',
      array('%civicrm_base_path%', new Reference('annotation_reader'))
    ))
      ->setFactoryService(self::SELF)->setFactoryMethod('createDoctrineConfiguration');

    $container->setDefinition('entity_manager', new Definition(
      '\Doctrine\ORM\EntityManager',
      array(new Reference('doctrine_configuration'))
    ))
      ->setFactoryService(self::SELF)->setFactoryMethod('createEntityManager');

    $container->setDefinition('civi_api_registry', new Definition(
      '\Civi\API\Registry',
      array(new Reference('doctrine_configuration'), new Reference('annotation_reader'))
    ));

    $container->setDefinition('civi_api_security', new Definition(
      '\Civi\API\Security',
      array(new Reference('annotation_reader'))
    ));

    $container->setDefinition('dispatcher', new Definition(
      '\Symfony\Component\EventDispatcher\EventDispatcher',
      array()
    ))
      ->setFactoryService(self::SELF)->setFactoryMethod('createEventDispatcher');

    $container->setDefinition('magic_function_provider', new Definition(
      '\Civi\API\Provider\MagicFunctionProvider',
      array()
    ));

    $container->setDefinition('civi_api_kernel', new Definition(
      '\Civi\API\Kernel',
      array(new Reference('dispatcher'), new Reference('civi_api_registry'), new Reference('annotation_reader'), new Reference('magic_function_provider'))
    ))
      ->setFactoryService(self::SELF)->setFactoryMethod('createApiKernel');

    return $container;
  }

  /**
   * @param string $civicrm_base_path
   * @param string $annotation_cache_path
   * @return \Doctrine\Common\Annotations\Reader
   */
  public function createAnnotationReader($civicrm_base_path, $annotation_cache_path) {
    \CRM_Utils_Path::mkdir_p_if_not_exists($annotation_cache_path);

    AnnotationRegistry::registerFile(
      \CRM_Utils_Path::join($civicrm_base_path, 'vendor', 'doctrine', 'orm', 'lib', 'Doctrine', 'ORM', 'Mapping', 'Driver', 'DoctrineAnnotations.php')
    );
    AnnotationRegistry::registerAutoloadNamespace('Civi',
      \CRM_Utils_Path::join($civicrm_base_path)
    );
    AnnotationRegistry::registerAutoloadNamespace('JMS\Serializer',
      \CRM_Utils_Path::join($civicrm_base_path, 'vendor', 'jms', 'serializer', 'src')
    );

    $annotation_reader = new AnnotationReader();
    $file_cache_reader = new FileCacheReader($annotation_reader, $annotation_cache_path, TRUE);

    return $file_cache_reader;
  }

  /**
   * @param \Doctrine\Common\Annotations\Reader $annotation_reader
   * @return \Doctrine\ORM\Configuration
   */
  public function createDoctrineConfiguration($civicrm_base_path, $annotation_reader) {
    $metadata_path = \CRM_Utils_Path::join($civicrm_base_path, 'Civi');
    $driver = new AnnotationDriver($annotation_reader, $metadata_path);

    // FIXME Doesn't seem like a good idea to use filesystem as the query cache
//    $doctrine_cache_path = \CRM_Utils_Path::join(dirname(CIVICRM_TEMPLATE_COMPILEDIR), 'cache', 'doctrine');
//    \CRM_Utils_Path::mkdir_p_if_not_exists($doctrine_cache_path);
//    $doctrine_cache = new FilesystemCache($doctrine_cache_path);
    $doctrine_cache = NULL;

    $config = Setup::createConfiguration(TRUE, NULL, $doctrine_cache);
    $config->setMetadataDriverImpl($driver);

    return $config;
  }

  /**
   * @param \Doctrine\ORM\Configuration $config
   * @return \Doctrine\ORM\EntityManager
   */
  public function createEntityManager($config) {
    $dbSettings = new \CRM_DB_Settings();
    $em = EntityManager::create($dbSettings->toDoctrineArray(), $config);
    $event_manager = $em->getEventManager();
    $event_manager->addEventSubscriber(new \Doctrine\DBAL\Event\Listeners\MysqlSessionInit('utf8', 'utf8_unicode_ci'));
    return $em;
  }

  /**
   * @return \Symfony\Component\EventDispatcher\EventDispatcher
   */
  public function createEventDispatcher() {
    $dispatcher = new \Symfony\Component\EventDispatcher\EventDispatcher();
    $dispatcher->addListener('hook_civicrm_post::Activity', array('\Civi\CCase\Events', 'fireCaseChange'));
    $dispatcher->addListener('hook_civicrm_post::Case', array('\Civi\CCase\Events', 'fireCaseChange'));
    $dispatcher->addListener('hook_civicrm_caseChange', array('\Civi\CCase\Events', 'delegateToXmlListeners'));
    $dispatcher->addListener('hook_civicrm_caseChange', array('\Civi\CCase\SequenceListener', 'onCaseChange_static'));
    return $dispatcher;
  }

  /**
   * @param \Symfony\Component\EventDispatcher\EventDispatcher $dispatcher
   * @param \Civi\API\Registry $apiRegistry
   * @param \Doctrine\Common\Annotations\Reader $annotationReader
   * @param $magicFunctionProvider
   *
   * @return \Civi\API\Kernel
   */
  public function createApiKernel($dispatcher, $apiRegistry, $annotationReader, $magicFunctionProvider) {
    $doctrineCrudProvider = new \Civi\API\Provider\DoctrineCrudProvider($apiRegistry);

    $dispatcher->addSubscriber(new \Civi\API\Subscriber\ChainSubscriber());
    $dispatcher->addSubscriber(new \Civi\API\Subscriber\TransactionSubscriber());
    $dispatcher->addSubscriber(new \Civi\API\Subscriber\I18nSubscriber());
    $dispatcher->addSubscriber($magicFunctionProvider);
    $dispatcher->addSubscriber($doctrineCrudProvider);
    $dispatcher->addSubscriber(new \Civi\API\Subscriber\AnnotationPermissionCheck($annotationReader));
    $dispatcher->addSubscriber(new \Civi\API\Subscriber\PermissionCheck());
    $dispatcher->addSubscriber(new \Civi\API\Subscriber\APIv3SchemaAdapter());
    $dispatcher->addSubscriber(new \Civi\API\Subscriber\WrapperAdapter(array(
      \CRM_Utils_API_HTMLInputCoder::singleton(),
      \CRM_Utils_API_NullOutputCoder::singleton(),
      \CRM_Utils_API_ReloadOption::singleton(),
      \CRM_Utils_API_MatchOption::singleton(),
    )));
    $dispatcher->addSubscriber(new \Civi\API\Subscriber\XDebugSubscriber());
    $kernel = new \Civi\API\Kernel($dispatcher);

    $reflectionProvider = new \Civi\API\Provider\ReflectionProvider($kernel);
    $dispatcher->addSubscriber($reflectionProvider);

    $kernel->setApiProviders(array(
      $reflectionProvider,
      $magicFunctionProvider,
      $doctrineCrudProvider,
    ));

    return $kernel;
  }
}