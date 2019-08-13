<?php
/**
 * Advertise
 * Catalog product upsell items block
 *
 * @category    Advertise
 * @package     Advertise_UpsellProducts
 */

class Advertise_UpsellProducts_Block_Upsell extends Mage_Catalog_Block_Product_List_Upsell
{
    /**
     * Default MAP renderer type
     *
     * @var string
     */
    protected $_mapRenderer = 'msrp_noform';

    protected $_itemCollection;

    protected function _prepareData() {
        // Get product
        //$product = Mage::registry('product');
        $prodid = $this->getRequest()->getParam('id');
        $product = Mage::getModel('catalog/product')->load($prodid);

        // Get upsell product IDs from request
        $ids = $this->getRequest()->getParam('ids');
        if (!$ids) {
            $ids = array();
        }
        array_push($ids, '0');

        // Get count of upsell products required
        $upsellCount = Mage::getStoreConfig('advertise_suggestedproducts_options/advertise_suggested_products/advertise_upsell_prod_count');

        // Get collection of products
        $upsells = Mage::getResourceModel('catalog/product_collection');

        // Select which fields to load into the product
        // * will load all fields but it is possible to pass an array of select fields to load
        $upsells->addAttributeToSelect('*');

        // Add list of IDs to select
        $i = 0;
        $conditions = array();
        foreach ($ids as $id) {
            if ($i < $upsellCount) {
                array_push($conditions, array('attribute' => 'entity_id', 'eq' => $id));
            } else {
                // Have enough upsell products
                break;
            }
            $i++;
        }
        $upsells->addAttributeToFilter($conditions);

        // Ensure the product is enabled
        $upsells->addAttributeToFilter('status', 1);

        // For DEBUG: Print out the SQL query generated by the collection object so far
        // Mage::log('SELECT QUERY: ' . $upsells->getSelect());

        // Load the collection
        $upsells->load();

        $this->_itemCollection = new Varien_Data_Collection();
        foreach ($upsells as $item) {
            $this->_itemCollection->addItem($item);
        }

        // If we've got enough upsell products then we're done.
        if (count($this->_itemCollection) == $upsellCount) {
            foreach ($this->_itemCollection as $product) {
                $product->setDoNotUseCategoryId(true);
            }
            return $this;
        }

        // Not enough upsell products yet so look in Magento DB to see if any are set for product.
        // This section based on base Magento code from Upsell.php
        $upsells2 = $product->getUpSellProductCollection()
                            ->setPositionOrder()
                            ->addStoreFilter()
        ;

        if (Mage::helper('catalog')->isModuleEnabled('Mage_Checkout')) {
            Mage::getResourceSingleton('checkout/cart')->addExcludeProductFilter($upsells2,
                    Mage::getSingleton('checkout/session')->getQuoteId()
            );
            $this->_addProductAttributesAndPrices($upsells2);
        }
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($upsells2);
        if ($this->getItemLimit('upsell') > 0) {
            $upsells2->setPageSize($this->getItemLimit('upsell'));
        }
        $upsells2->load();

        /**
         * Updating collection with desired items
         */
        // TODO: Check why this is here for Upsells but not Related Products.
        // TODO: Check if this causes problems for e.g. recognising the items added earlier by Adverti.se
        Mage::dispatchEvent('catalog_product_upsell', array(
            'product'       => $product,
            'collection'    => $this->_itemCollection,
            'limit'         => $this->getItemLimit()
        ));

        // Add items from DB to related products
        foreach ($upsells2 as $item) {
            $this->_itemCollection->addItem($item);
            // If we've got enough upsell products then we're done.
            if (count($this->_itemCollection) == $upsellCount) {
                foreach ($this->_itemCollection as $product) {
                    $product->setDoNotUseCategoryId(true);
                }
                return $this;
            }
        }

        // Still haven't got the required number of upsell products so fill up with random products
        $numRandomsToGet = $upsellCount - count($this->_itemCollection);

        // Get a list of 100 random product IDs
        // (To execute SQL query directly: 1. Get the resource model, 2. Retrieve the read connection, 3. Execute the query)
        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');
        $query = 'SELECT MAX(entity_id) AS maxid FROM ' . $resource->getTableName('catalog/product');
        $maxid = $readConnection->fetchOne($query);
        if (!$maxid) {
            $maxid = $prodid;
        }
        $randids = array();
        for ($i = 0; $i <= 100; $i++) {
            array_push($randids , rand(1,$maxid));
        }

        // Select random products
        $randCollection = Mage::getResourceModel('catalog/product_collection');
        Mage::getModel('catalog/layer')->prepareProductCollection($randCollection);
        // Select from the 100 random IDs
        $randCollection->addIdFilter($randids, false);
        $randCollection->addStoreFilter();
        $randCollection->getSelect()->limit($numRandomsToGet);
        // Don't get items we already have
        $exclude = $this->_itemCollection->getAllIds();
        $toexclude = array();
        foreach($exclude as $ex) {
            $toexclude[] = $ex;
        }
        // Don't include the current item as an upsell product
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            $toexclude[] = $id;
        }
        // Don't include basket items as upsell products
        $session = Mage::getSingleton('checkout/session');
        foreach ($session->getQuote()->getAllVisibleItems() as $item) {
            $toexclude[] = $item->getProductId();
        }
        $randCollection->addIdFilter($toexclude, true);
        // Mage::log('RANDOM UPSELL ITEM SELECT QUERY:' . $randCollection->getSelect());
        foreach($randCollection as $randProduct)
        {
            $this->_itemCollection->addItem($randProduct);
        }

        foreach ($this->_itemCollection as $product) {
            $product->setDoNotUseCategoryId(true);
        }
        return $this;

    }

}
