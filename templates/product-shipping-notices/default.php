<?php
/**
 * This template can be overridden by copying it to yourtheme/woocommerce/product-shipping-notices/default.php.
 *
 * Available vars:
 *
 * $notice_html
 * @var string
 *
 * $shop_url_html
 * @var string
 */

?>
<div class="shipping-notice default">
    <?php
        do_action( 'arti_psn_notice' );
        do_action( 'arti_psn_before_default_notice' );
        do_action( 'arti_psn_notice_default' );

        echo "$notice_html $shop_url_html";

        do_action( 'arti_psn_after_default_notice' );
    ?>
</div>
