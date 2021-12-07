<?php

namespace Arti\PSN;

class Core {

	public function __construct(){
		add_shortcode( 'shipping-notice', [ Shipping_Notices::get_instance(), 'render_notices' ] );
	}
}

return new Core;
