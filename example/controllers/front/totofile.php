<?php
class exampletotofileModuleFrontController extends ModuleFrontController{
    
    public function initContent(){
        // obligatoire
        parent::initContent();
        // templateprestashop 
       $this->setTemplate('totofile.tpl');
    }
    
}