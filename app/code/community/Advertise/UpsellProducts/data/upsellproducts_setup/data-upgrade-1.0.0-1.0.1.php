<?php
/**
 * Upgrade db
 *
 * @package Advertise_UpsellProducts
 */

$installer = $this;
$installer->startSetup();
$upsellCount = Mage::getStoreConfig('advertise_upsellproducts_options/advertise_upsell_products/advertise_prod_count');
if ($upsellCount) {
    // Copy old setting to new path
    Mage::getModel('core/config')->saveConfig('advertise_suggestedproducts_options/advertise_suggested_products/advertise_upsell_prod_count', $upsellCount );
    // Delete old path
    Mage::getModel('core/config')->deleteConfig('advertise_upsellproducts_options/advertise_upsell_products/advertise_prod_count');
}
$installer->endSetup();
?>