<?php
/*
 * Advertise_UpsellProducts Index Controller
 */
class Advertise_UpsellProducts_IndexController extends Mage_Core_Controller_Front_Action{
    /**
     * Default Action
     * Path to this method: /upsellproducts/index/index
     */
    public function indexAction(){
        // Effectively redirects to home page of store
        $this->loadLayout();
        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
        $breadcrumbs->addCrumb("home", array(
            "label" => $this->__("Home Page"),
            "title" => $this->__("Home Page"),
            "link"  => Mage::getBaseUrl()
        ));
        $this->renderLayout();
    }

    // Path to this method: /upsellproducts/index/upsellproducts
    public function upsellproductsAction() {

    }

}
?>
