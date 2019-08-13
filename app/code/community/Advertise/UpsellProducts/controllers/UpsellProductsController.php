<?php
class Advertise_UpsellProducts_UpsellProductsController extends Mage_Core_Controller_Front_Action {
    /**
     * Default Action
     * // Path to this method: /upsellproducts/UpsellProducts/index
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_title($this->__("Adverti.se Upsell Products"));
        $this->renderLayout();
    }

    // Path to this method: /upsellproducts/UpsellProducts/getupsell
    public function getupsellAction() {
        // Create upsell products block with IDs in request
        echo $this->getLayout()->createBlock('catalog/product_list_upsell')->setTemplate('catalog/product/list/upsell.phtml')->toHtml();
    }

    public function postAction()
    {

    }

}

?>
