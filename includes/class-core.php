<?php

namespace Arti\PSN;

class Core {

	public function __construct(){

		add_shortcode( 'shipping-notice', [ Shipping_Notices::get_instance(), 'render_notices' ] );
		add_action( 'wcfm_vendor_shipping_settings_update', [ $this, 'update_vendor_notice_cache' ] );

	}

	public function update_vendor_notice_cache( $vendor_id ){
		delete_transient( 'arti_psn_shipping_notice_' . $vendor_id );
	}
}

return new Core;
