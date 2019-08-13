<?php
/**
 * Config.php
 *
 * @package Advertise_UpsellProducts
 */
class Advertise_UpsellProducts_Model_Config extends Varien_Object
{
    /**
     * Config keys
     */
    //const ENABLED  =         'importer/importer_group/enabled';
    const PRODUCT_COUNT =    'advertise_upsellproducts_options/advertise_upsell_products/advertise_prod_count';
    const UPSELL_HEADING  =  'advertise_upsellproducts_options/advertise_upsell_products/advertise_upsell_text';
    const ADVERTISE_EMAIL =  'advertise_settings/settings/settings_email';

    /**
     * Is the module enabled?
     *
     * @return  bool
     */
    public function getIsEnabled()
    {
        return (bool) Mage::getStoreConfig(self::ENABLED);
    }

    /**
     * Get the advertise Email settings.
     *
     * @return  string
     */
    public function getAdvertiseEmail()
    {
        return Mage::getStoreConfig(self::ADVERTISE_EMAIL);
    }

    /**
     * Get the feed download url
     *
     * @return  string
     */
    public function getAdvertiseFeed()
    {
        $restore = Mage::getSingleton('core/app') -> getRequest() -> getParam('restore', null);
        $url = 'http://i.adverti.se/feeds/magento/email:' .
                $this->getAdvertiseEmail() .
                '/site:' . $this->getBaseUrl() . 'restore:' . $restore; // No slash before restore as BaseUrl has a trailing slash already
        return $url;
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

    /**
     * Get our file download url
     *
     * Get from the advertise module if available with fallback to our own settings.
     * Leaving this in because during dev we don't have the other mod installed.
     *
     * @return string
     */
    public function getDownloadUrl()
    {
        // THIS IS FOR LOCAL TESTING ONLY
        //if (true) {
        //    return "http://advertise.local/feeds/magento/email:new-magento@adverti.se/site:magento.adverti.se/restore:true";
        //}

        Mage::log("Upsell Products getDownloadURL self::DOWNLOAD_URL=".Mage::getStoreConfig(self::DOWNLOAD_URL));
        // FOR PRODUCTION
        $advertiseEmail = $this->getAdvertiseEmail();
        if( ! empty($advertiseEmail)) {
            Mage::log("Upsell Products getDownloadURL returning (1): ".$this->getAdvertiseFeed());
            return $this->getAdvertiseFeed();
        }
        Mage::log("Upsell Products getDownloadURL returning (2): ".Mage::getStoreConfig(self::DOWNLOAD_URL));
        return Mage::getStoreConfig(self::DOWNLOAD_URL);
    }
}

?>
