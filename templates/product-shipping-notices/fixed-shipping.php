<?php
/**
 * This template can be overridden by copying it to yourtheme/woocommerce/product-shipping-notices/fixed-shipping.php.
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
<div class="shipping-notice fixed-rate-shipping">
    <?php
        do_action( 'arti_psn_notice' );
        do_action( 'arti_psn_before_fixed_rate_notice' );
        do_action( 'arti_psn_notice_fixed_rate' );

        echo "$notice_html $shop_url_html";

        do_action( 'arti_psn_after_fixed_rate_notice' );
    ?>
</div>
