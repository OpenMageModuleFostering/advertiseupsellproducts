<?php
/**
 * UpsellProducts.php
 *
 * Backend block
 * 
 * @package Advertise_UpsellProducts
 */
class Advertise_UpsellProducts_Block_Adminhtml_UpsellProducts extends Mage_Adminhtml_Block_Template
{
    const ADVERTISE_EMAIL = 'advertise_settings/settings/settings_email';

    public function getAdvertiseEmail() {
        return Mage::getStoreConfig(self::ADVERTISE_EMAIL);
    }

    /**
     * Get the base url
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return str_replace('http://', '', Mage::getStoreConfig('web/unsecure/base_url'));
    }

}
?>
