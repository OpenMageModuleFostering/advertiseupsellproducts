Step 1
======
Find the template file upsell.phtml used by your theme. You should find it in:
    app/design/frontend/<theme-package>/<theme-name>/template/catalog/product/list

Add these lines at the top of the file:
<div id="advupsell">
<script type="text/javascript">if(adv_upsell_reload){loadAdvertiseUpsellProducts();adv_upsell_reload=false;}</script>

And add this line at the end of the file:
</div>

Step 2
======
Find the template file head.phtml used by your theme. You should find it in:
    app/design/frontend/<theme-package>/<theme-name>/template/page/html

Add this script tag within the head:
<script type="text/javascript"><?php echo Mage::helper('advertise_retailintelligence')->getAdvertiseHeaderScript(); ?></script>