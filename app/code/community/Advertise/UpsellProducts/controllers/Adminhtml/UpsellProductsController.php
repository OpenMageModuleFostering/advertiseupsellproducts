<?php
/**
 * UpsellProductsController.php
 *
 * Backend controller
 *
 * @package Advertise_UpsellProducts
 */
class Advertise_Importer_Adminhtml_UpsellProductsController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Default Action
     */
	public function indexAction()
    {
       $this->loadLayout();
	   $this->_title($this->__("Upsell Products"));
	   $this->renderLayout();
    }
    
    public function postAction()
    {
    }
}