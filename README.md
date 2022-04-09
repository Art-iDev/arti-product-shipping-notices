[![Donate with PayPal](https://img.shields.io/badge/paypal-Donate%20with%20paypal-blue?style=for-the-badge&logo=paypal&link=https://www.paypal.com/donate/?hosted_button_id=TZ984YJ3SJEQA)](https://www.paypal.com/donate/?hosted_button_id=TZ984YJ3SJEQA)

# Product Shipping Notices for WooCommerce based marketplaces

Add automated notices to you product page based on vendor's shipping configurations.

Currently, the marketplace supported is WCFM, but plans are to add most WooCommerce marketplace providers existent.

# How to use
Download the latest release [here](https://github.com/Art-iDev/arti-product-shipping-notices/releases) and install normally. 
To show the notices in you product page, you have the following options:

```PHP
<?php
// Render in whichever action you want.
add_action( 'woocommerce_after_add_to_cart_button', [ \Arti\PSN\Shipping_Notices::get_instance(), 'render_notices' ] );

// Use directly in a template file.
\Arti\PSN\Shipping_Notices::get_instance()->render_notices();

// Use the shortcode.
// [shipping-notice] or
do_shortcode( '[shipping-notice]' );

```

## Hooks
Available hooks are:
### Filters
* `arti_psn_shipping_notice_update`
* `arti_psn_group_notices_by_min_amount`
* `arti_psn_remove_default_when_free_shipping_present`
* `arti_psn_free_shipping_notice_template`
* `arti_psn_free_shipping_url_template`

You may also check the files in the "templates" dir for additional actions. 

## CSS
The plugin doesn't have styles applied to it, so you must implement your own. The available selectors are:

* `.shipping-notice` for the outer box;
* `.shipping-notice.default`
* `.shipping-notice.free-shipping`

Example adding some padding and background color with Storefront theme:

![image](https://user-images.githubusercontent.com/700448/145720489-d9bbabff-9f7e-47fa-b071-6afe643a3b70.png)

## Templates
You can copy the files in the [`templates`](templates) dir into your theme dir, and edit it to make them suitable to your needs.
