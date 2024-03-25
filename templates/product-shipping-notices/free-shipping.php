<?php
/**
 * This template can be overridden by copying it to yourtheme/woocommerce/product-shipping-notices/free-shipping.php.
 *
 * Available vars:
 *
 * $notice_html
 * @var string
 *
 * $shop_url_html
 * @var string
 *
 * $wrapper_classes
 * @var string
 *
 */

?>
<div class="shipping-notice free-shipping <?php echo $wrapper_classes?>">
    <?php
        do_action( 'arti_psn_notice' );
        do_action( 'arti_psn_before_free_shipping_notice' );
        do_action( 'arti_psn_notice_free_shipping' );

        echo "$notice_html $shop_url_html";

        do_action( 'arti_psn_after_free_shipping_notice' );
    ?>
</div>
