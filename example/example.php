<?php
/**
 * Module Example - Main file
 *
 * @category   	Module / other
 * @author     	eewee.fr <contact@eewee.fr>
 * @copyright  	2013 eewee.fr
 * @version   	1.0	
 * @link       	http://www.eewee.fr/
*/

// MANUEL PRESTASHOP : Créer un module Prestashop
// http://doc.prestashop.com/pages/viewpage.action?pageId=15171738#Cr%C3%A9erunmodulePrestaShop-Cr%C3%A9erunmodulePrestaShop

// Security
if (!defined('_PS_VERSION_'))
	exit;
	
// Checking compatibility with older PrestaShop and fixing it
if (!defined('_MYSQL_ENGINE_'))
	define('_MYSQL_ENGINE_', 'MyISAM');

// Loading Models
require_once(_PS_MODULE_DIR_ . 'example/models/ExampleData.php');

class Example extends Module{

    public function __construct(){
        $this->name = 'example';
        $this->tab = 'others';
        $this->version = '0.3';
        $this->author = 'Prenom NOM';
        $this->ps_versions_compliancy = array( 'min' => '1.5', 'max' => '1.6' ); 
        //$this->dependencies = array('blockcart');
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->l('Example');
        $this->description = $this->l('Description Module Example.');

        $this->confirmUninstall = $this->l('Are you sure you want to delete this module ?');

        // Si module active ET variable "EXAMOLE_CONF" vide ALORS afficher message "warning"
        if ($this->active && Configuration::get('EXAMPLE_CONF') == ''){
          $this->warning = $this->l('You have to configure your module');
        }
    }

    public function install(){
        // Install SQL
        include(dirname(__FILE__).'/sql/install.php');
        foreach ($sql as $s)
            if (!Db::getInstance()->execute($s))
                return false;

        /*
         * DEBUT : NE FONCTIONNE PAS
         * 
        // Install Tabs
        $parent_tab = new Tab();
        $parent_tab->name = 'Main Tab Example';
        $parent_tab->class_name = 'AdminMainExample';
        $parent_tab->id_parent = 0;
        $parent_tab->module = $this->name;
        $parent_tab->add();

        $tab = new Tab();
        $tab->name = 'Tab Example';
        $tab->class_name = 'AdminExample';
        $tab->id_parent = $parent_tab->id;
        $tab->module = $this->name;
        $tab->add();
        * 
        * FIN : NE FONCTIONNE PAS
        */

        // Creation variable "EXAMPLE_CONF"
        Configuration::updateValue('EXAMPLE_CONF', '');	
        Configuration::updateValue('EEWEE_TEST', 'blabla');
        // updateValue peut contenir : string, int, array serialize, objet JSON. 
        // Ex :
        // 
        // Stocker un tableau sérialisé.
        // Configuration::updateValue('MYMODULE_SETTINGS', serialize(array(true, true, false)));
        // 
        // Récupérer le tableau.
        // $configuration_array = unserialize(Configuration::get('MYMODULE_SETTINGS'));

        // Install Module ET creation hook
        return 
        parent::install() && 
        $this->registerHook('actionObjectExampleDataAddAfter') &&
        $this->registerHook('leftColumn') &&
        $this->registerHook('rightColumn') &&
        $this->registerHook('header');
    }     
  
    public function uninstall(){
        // Uninstall SQL
        include(dirname(__FILE__).'/sql/uninstall.php');
        foreach ($sql as $s){
            if (!Db::getInstance()->execute($s)){ return false; }
        }

        // Supprimer variable
        Configuration::deleteByName('EXAMPLE_CONF');
        Configuration::deleteByName('EEWEE_TEST');

        // Uninstall Tabs
        //$tab = new Tab((int)Tab::getIdFromClassName('AdminExample'));
        //$tab->delete(); 
        //$tab = new Tab((int)Tab::getIdFromClassName('AdminMainExample'));
        //$tab->delete();

        // Uninstall Module
        if (!parent::uninstall() || !$this->unregisterHook('actionObjectExampleDataAddAfter')){ return false; }

        return true;
    }
	
    public function getContent(){
        return $this->processForm().$this->displayForm();
    }
	
    private function processForm(){
        $output = '';
        if (Tools::isSubmit('submit'.$this->name)){
            // get
            $EXAMPLE_CONF       = Tools::getValue('EXAMPLE_CONF');
            $EXAMPLE_CONF2      = Tools::getValue('EXAMPLE_CONF2');
            $EXAMPLE_CONF3      = Tools::getValue('EXAMPLE_CONF3');
            $EXAMPLE_CONF4_1    = Tools::getValue('EXAMPLE_CONF4_1');
            $EXAMPLE_CONF4_2    = Tools::getValue('EXAMPLE_CONF4_2');
            $EXAMPLE_CONF5      = Tools::getValue('EXAMPLE_CONF5');

            // save
            Configuration::updateValue('EXAMPLE_CONF', $EXAMPLE_CONF);
            Configuration::updateValue('EXAMPLE_CONF2',$EXAMPLE_CONF2);
            Configuration::updateValue('EXAMPLE_CONF3',$EXAMPLE_CONF3);
            Configuration::updateValue('EXAMPLE_CONF4_1',$EXAMPLE_CONF4_1);
            Configuration::updateValue('EXAMPLE_CONF4_2',$EXAMPLE_CONF4_2);
            Configuration::updateValue('EXAMPLE_CONF5',$EXAMPLE_CONF5);
            
            // mess validation ok
            $output .= $this->displayConfirmation($this->l('Settings updated'));
        }

        return $output;
    }
	
    public function displayForm(){
        //-------------------------------------------------------------------
        // METHODE 1 : http://doc.prestashop.com/display/PS15/HelperForm
        //-------------------------------------------------------------------

        // Get default Language
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

        $options_select = array(    
            array(
                'id_option' => 1,
                'name' => 'Method 1'
            ),
            array(
                'id_option' => 2,
                'name' => 'Method 2'
            )
        );
        
        $options_checkbox = array(    
            array(
                'id_option' => 1,
                'name' => 'Method 1'
            ),
            array(
                'id_option' => 2,
                'name' => 'Method 2'
            )
        );
        
        // Init Fields form array
        $fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Settings'),
            ),
            'input' => array(
                
                // Text
                array(
                    'type' => 'text',
                    'label' => $this->l('Configuration value'),
                    'name' => 'EXAMPLE_CONF',
                    'size' => 20,
                    'required' => true
                ),
                
                // Text
                array(
                    'type' => 'text',
                    'label' => $this->l('Configuration value 2'),
                    'name' => 'EXAMPLE_CONF2',
                    'size' => 20,
                    'required' => true
                ),
                
                // Select
                array(
                    'type'      => 'select',
                    'label'     => $this->l('Shipping method:'),
                    'name'      => 'EXAMPLE_CONF3',
                    'required'  => true,
                    'options' => array(
                        'query' => $options_select,
                        'id'    => 'id_option',
                        'name'  => 'name',
                        'val'   => 'val'
                    )
                ),
                
                // Checkbox
                array(
                    'name'    => 'EXAMPLE_CONF4',
                    'type'    => 'checkbox',
                    'label'   => $this->l('Options'),
                    'values'  => array(
                        'query' => $options_checkbox,
                        'id'    => 'id_option',
                        'name'  => 'name'
                    ),
                    'desc'    => $this->l('Choose options.')
                ),
                
                // Radio
                array(
                    'type'      => 'radio',
                    'label'     => $this->l('Enable this option'),
                    'name'      => 'EXAMPLE_CONF5',
                    'required'  => true,
                    'class'     => 't',
                    'is_bool'   => true,
                    'values'    => array(
                      array(
                        'id'    => 'active_on',
                        'value' => 1,
                        'label' => $this->l('Enabled')
                      ),
                      array(
                        'id'    => 'active_off',
                        'value' => 0,
                        'label' => $this->l('Disabled')
                      )
                    ),
                    'desc'      => $this->l('Are you a customer too?')
                )
                
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'button'
            )
        );
        
        $helper = new HelperForm();

        // Module, token and currentIndex
        $helper->module = $this;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;

        // Language
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;

        // Title and toolbar
        $helper->title = $this->displayName;
        $helper->show_toolbar = true;        // false -> remove toolbar
        $helper->toolbar_scroll = true;      // yes - > Toolbar is always visible on the top of the screen.
        $helper->submit_action = 'submit'.$this->name;
        $helper->toolbar_btn = array(
            'save' =>
            array(
                'desc' => $this->l('Save'),
                'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.
                '&token='.Tools::getAdminTokenLite('AdminModules'),
            ),
            'back' => array(
                'href' => AdminController::$currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
                'desc' => $this->l('Back to list')
            )
        );

        // Load current value
        $helper->fields_value['EXAMPLE_CONF']  = Configuration::get('EXAMPLE_CONF');
        $helper->fields_value['EXAMPLE_CONF2'] = Configuration::get('EXAMPLE_CONF2');
        $helper->fields_value['EXAMPLE_CONF3'] = Configuration::get('EXAMPLE_CONF3');
        $helper->fields_value['EXAMPLE_CONF4_1'] = Configuration::get('EXAMPLE_CONF4_1');
        $helper->fields_value['EXAMPLE_CONF4_2'] = Configuration::get('EXAMPLE_CONF4_2');
        $helper->fields_value['EXAMPLE_CONF5'] = Configuration::get('EXAMPLE_CONF5');
        
        return $helper->generateForm($fields_form);

    
        /*
        //-------------------------------------------------------------------
        // METHODE 2
        //-------------------------------------------------------------------

        if(isset($errors))
            $this->context->smarty->assign('errors', $errors);

        $this->context->smarty->assign('request_uri', Tools::safeOutput($_SERVER['REQUEST_URI']));
        $this->context->smarty->assign('path', $this->_path);
        $this->context->smarty->assign('EXAMPLE_CONF', pSQL(Tools::getValue('EXAMPLE_CONF', Configuration::get('EXAMPLE_CONF'))));
        $this->context->smarty->assign('EXAMPLE_CONF2',pSQL(Tools::getValue('EXAMPLE_CONF2', Configuration::get('EXAMPLE_CONF2'))));
        $this->context->smarty->assign('submitName', 'submit'.$this->name);

        return $this->display(__FILE__, 'views/templates/admin/configure.tpl');
        */
    }
	
	
    public function hookActionObjectExampleDataAddAfter($params){
        echo "hookActionObjectExampleDataAddAfter<hr /><hr /><hr /><hr />";
        return true;
    }

    public function hookDisplayLeftColumn($params){
        $this->context->smarty->assign(
            array(
                'example_name' => Configuration::get('EXAMPLE_CONF'),
                'example_link' => $this->context->link->getModuleLink('example', 'totofile')
            )
        );
        return $this->display(__FILE__, 'example-column.tpl');
    }
  
    public function hookDisplayRightColumn($params){
        return $this->hookDisplayLeftColumn($params);
    }

    public function hookDisplayHeader(){
        $this->context->controller->addCSS($this->_path.'views/css/style.css', 'all');
    }  
        
}