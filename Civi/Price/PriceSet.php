<?php
/*
 +--------------------------------------------------------------------+
 | CiviCRM version 4.4                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2013                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*/

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2013
 *
 * Generated from xml/schema/CRM/Price/PriceSet.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 */

namespace Civi\Price;

require_once 'Civi/Core/Entity.php';

use Doctrine\ORM\Mapping as ORM;
use Civi\API\Annotation as CiviAPI;
use Civi\Core\Annotations\Field as Field;
use JMS\Serializer\Annotation as JMS;

/**
 * PriceSet
 *
 * @CiviAPI\Entity("PriceSet")
 * @CiviAPI\Permission()
 * @ORM\Table(name="civicrm_price_set", uniqueConstraints={@ORM\UniqueConstraint(name="UI_name", columns={"name"})}, indexes={@ORM\Index(name="FK_civicrm_price_set_domain_id", columns={"domain_id"}),@ORM\Index(name="FK_civicrm_price_set_financial_type_id", columns={"financial_type_id"})})
 * @ORM\Entity
 *
 */
class PriceSet extends \Civi\Core\Entity {

  /**
   * @var integer
   *
   * @JMS\Type("integer")
   * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned":true} )
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;
    
  /**
   * @var \Civi\Core\Domain
   *
   * 
   * @ORM\ManyToOne(targetEntity="Civi\Core\Domain")
   * @ORM\JoinColumns({@ORM\JoinColumn(name="domain_id", referencedColumnName="id")})
   */
  private $domain;
  
  /**
   * @var string
   *
   * @JMS\Type("string")
   * @ORM\Column(name="name", type="string", length=255, nullable=false)
   * 
   */
  private $name;
  
  /**
   * @var string
   *
   * @JMS\Type("string")
   * @ORM\Column(name="title", type="string", length=255, nullable=false)
   * @Field(localizable=true)
   * 
   */
  private $title;
  
  /**
   * @var boolean
   *
   * @JMS\Type("boolean")
   * @ORM\Column(name="is_active", type="boolean", nullable=true, options={"default": 1})
   * 
   */
  private $isActive = '1';
  
  /**
   * @var text
   *
   * @JMS\Type("text")
   * @ORM\Column(name="help_pre", type="text", length=65535, nullable=true)
   * @Field(localizable=true)
   * 
   */
  private $helpPre;
  
  /**
   * @var text
   *
   * @JMS\Type("text")
   * @ORM\Column(name="help_post", type="text", length=65535, nullable=true)
   * @Field(localizable=true)
   * 
   */
  private $helpPost;
  
  /**
   * @var string
   *
   * @JMS\Type("string")
   * @ORM\Column(name="javascript", type="string", length=64, nullable=true)
   * 
   */
  private $javascript;
  
  /**
   * @var string
   *
   * @JMS\Type("string")
   * @ORM\Column(name="extends", type="string", length=255, nullable=false)
   * 
   */
  private $extends;
  
  /**
   * @var \Civi\Financial\FinancialType
   *
   * 
   * @ORM\ManyToOne(targetEntity="Civi\Financial\FinancialType")
   * @ORM\JoinColumns({@ORM\JoinColumn(name="financial_type_id", referencedColumnName="id", onDelete="SET NULL")})
   */
  private $financialType = 'NULL';
  
  /**
   * @var boolean
   *
   * @JMS\Type("boolean")
   * @ORM\Column(name="is_quick_config", type="boolean", nullable=true, options={"default": 0})
   * 
   */
  private $isQuickConfig = '0';
  
  /**
   * @var boolean
   *
   * @JMS\Type("boolean")
   * @ORM\Column(name="is_reserved", type="boolean", nullable=true, options={"default": 0})
   * 
   */
  private $isReserved = '0';

  /**
   * Get id
   *
   * @return integer
   */
  public function getId() {
    return $this->id;
  }
    
  /**
   * Set domain
   *
   * @param \Civi\Core\Domain $domain
   * @return PriceSet
   */
  public function setDomain(\Civi\Core\Domain $domain = null) {
    $this->domain = $domain;
    return $this;
  }

  /**
   * Get domain
   *
   * @return \Civi\Core\Domain
   */
  public function getDomain() {
    return $this->domain;
  }
  
  /**
   * Set name
   *
   * @param string $name
   * @return PriceSet
   */
  public function setName($name) {
    $this->name = $name;
    return $this;
  }

  /**
   * Get name
   *
   * @return string
   */
  public function getName() {
    return $this->name;
  }
  
  /**
   * Set title
   *
   * @param string $title
   * @return PriceSet
   */
  public function setTitle($title) {
    $this->title = $title;
    return $this;
  }

  /**
   * Get title
   *
   * @return string
   */
  public function getTitle() {
    return $this->title;
  }
  
  /**
   * Set isActive
   *
   * @param boolean $isActive
   * @return PriceSet
   */
  public function setIsActive($isActive) {
    $this->isActive = $isActive;
    return $this;
  }

  /**
   * Get isActive
   *
   * @return boolean
   */
  public function getIsActive() {
    return $this->isActive;
  }
  
  /**
   * Set helpPre
   *
   * @param text $helpPre
   * @return PriceSet
   */
  public function setHelpPre($helpPre) {
    $this->helpPre = $helpPre;
    return $this;
  }

  /**
   * Get helpPre
   *
   * @return text
   */
  public function getHelpPre() {
    return $this->helpPre;
  }
  
  /**
   * Set helpPost
   *
   * @param text $helpPost
   * @return PriceSet
   */
  public function setHelpPost($helpPost) {
    $this->helpPost = $helpPost;
    return $this;
  }

  /**
   * Get helpPost
   *
   * @return text
   */
  public function getHelpPost() {
    return $this->helpPost;
  }
  
  /**
   * Set javascript
   *
   * @param string $javascript
   * @return PriceSet
   */
  public function setJavascript($javascript) {
    $this->javascript = $javascript;
    return $this;
  }

  /**
   * Get javascript
   *
   * @return string
   */
  public function getJavascript() {
    return $this->javascript;
  }
  
  /**
   * Set extends
   *
   * @param string $extends
   * @return PriceSet
   */
  public function setExtends($extends) {
    $this->extends = $extends;
    return $this;
  }

  /**
   * Get extends
   *
   * @return string
   */
  public function getExtends() {
    return $this->extends;
  }
  
  /**
   * Set financialType
   *
   * @param \Civi\Financial\FinancialType $financialType
   * @return PriceSet
   */
  public function setFinancialType(\Civi\Financial\FinancialType $financialType = null) {
    $this->financialType = $financialType;
    return $this;
  }

  /**
   * Get financialType
   *
   * @return \Civi\Financial\FinancialType
   */
  public function getFinancialType() {
    return $this->financialType;
  }
  
  /**
   * Set isQuickConfig
   *
   * @param boolean $isQuickConfig
   * @return PriceSet
   */
  public function setIsQuickConfig($isQuickConfig) {
    $this->isQuickConfig = $isQuickConfig;
    return $this;
  }

  /**
   * Get isQuickConfig
   *
   * @return boolean
   */
  public function getIsQuickConfig() {
    return $this->isQuickConfig;
  }
  
  /**
   * Set isReserved
   *
   * @param boolean $isReserved
   * @return PriceSet
   */
  public function setIsReserved($isReserved) {
    $this->isReserved = $isReserved;
    return $this;
  }

  /**
   * Get isReserved
   *
   * @return boolean
   */
  public function getIsReserved() {
    return $this->isReserved;
  }

  /**
   * returns all the column names of this table
   *
   * @access public
   * @return array
   */
  public static $_fields = NULL;

  static function &fields( ) {
    if ( !self::$_fields) {
      self::$_fields = array (
        'id' => array(
          'name' => 'id',
          'propertyName' => 'id',
          'type' => \CRM_Utils_Type::T_INT,
          'title' => ts('Price Set ID'),
          'required' => true,
        ),
        'domain_id' => array(
          'name' => 'domain_id',
          'propertyName' => 'domain',
          'type' => \CRM_Utils_Type::T_INT,
          'title' => ts('Price Set Domain'),
          'FKClassName' => 'CRM_Core_DAO_Domain',
          'html' => array(
            'type' => 'Text',
          ),
          'pseudoconstant' => array(
            'table' => 'civicrm_domain',
            'keyColumn' => 'id',
            'labelColumn' => 'name',
          )
        ),
        'name' => array(
          'name' => 'name',
          'propertyName' => 'name',
          'type' => \CRM_Utils_Type::T_STRING,
          'title' => ts('Name'),
          'required' => true,
          'maxlength' => 255,
          'size' => \CRM_Utils_Type::HUGE,
          'html' => array(
            'type' => 'Text',
          ),
        ),
        'title' => array(
          'name' => 'title',
          'propertyName' => 'title',
          'type' => \CRM_Utils_Type::T_STRING,
          'title' => ts('Price Set Title'),
          'required' => true,
          'maxlength' => 255,
          'size' => \CRM_Utils_Type::HUGE,
          'html' => array(
            'type' => 'Text',
          ),
          'localizable' => true,
        ),
        'is_active' => array(
          'name' => 'is_active',
          'propertyName' => 'isActive',
          'type' => \CRM_Utils_Type::T_BOOLEAN,
          'title' => ts('Price Set Is Active?'),
          'default' => '1',
          'html' => array(
            'type' => 'CheckBox',
          ),
        ),
        'help_pre' => array(
          'name' => 'help_pre',
          'propertyName' => 'helpPre',
          'type' => \CRM_Utils_Type::T_TEXT,
          'title' => ts('Price Set Pre Help'),
          'maxlength' => 65535,
          'rows' => 4,
          'cols' => 80,
          'html' => array(
            'type' => 'TextArea',
          ),
          'localizable' => true,
        ),
        'help_post' => array(
          'name' => 'help_post',
          'propertyName' => 'helpPost',
          'type' => \CRM_Utils_Type::T_TEXT,
          'title' => ts('Price Set Post Help'),
          'maxlength' => 65535,
          'rows' => 4,
          'cols' => 80,
          'html' => array(
            'type' => 'TextArea',
          ),
          'localizable' => true,
        ),
        'javascript' => array(
          'name' => 'javascript',
          'propertyName' => 'javascript',
          'type' => \CRM_Utils_Type::T_STRING,
          'title' => ts('Price Set Javascript'),
          'maxlength' => 64,
          'size' => \CRM_Utils_Type::BIG,
          'html' => array(
            'type' => 'Text',
          ),
        ),
        'extends' => array(
          'name' => 'extends',
          'propertyName' => 'extends',
          'type' => \CRM_Utils_Type::T_STRING,
          'title' => ts('Price Set Extends'),
          'required' => true,
          'maxlength' => 255,
          'size' => \CRM_Utils_Type::HUGE,
          'html' => array(
            'type' => 'Text',
          ),
          'pseudoconstant' => array(
            'table' => 'civicrm_component',
            'keyColumn' => 'id',
            'labelColumn' => 'name',
          )
        ),
        'financial_type_id' => array(
          'name' => 'financial_type_id',
          'propertyName' => 'financialType',
          'type' => \CRM_Utils_Type::T_INT,
          'title' => ts('Financial Type'),
          'default' => 'NULL',
          'FKClassName' => 'CRM_Financial_DAO_FinancialType',
          'html' => array(
            'type' => 'Select',
          ),
          'pseudoconstant' => array(
            'table' => 'civicrm_financial_type',
            'keyColumn' => 'id',
            'labelColumn' => 'name',
          )
        ),
        'is_quick_config' => array(
          'name' => 'is_quick_config',
          'propertyName' => 'isQuickConfig',
          'type' => \CRM_Utils_Type::T_BOOLEAN,
          'title' => ts('Is Price Set Quick Config?'),
          'html' => array(
            'type' => 'CheckBox',
          ),
        ),
        'is_reserved' => array(
          'name' => 'is_reserved',
          'propertyName' => 'isReserved',
          'type' => \CRM_Utils_Type::T_BOOLEAN,
          'title' => ts('Price Set Is Reserved'),
          'html' => array(
            'type' => 'CheckBox',
          ),
        ),
      );
     }
    return self::$_fields;
  }
}