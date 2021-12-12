<?php

namespace Arti\PSN;

use function Arti\PSN\Marketplace\functions\{
	get_shipping_settings,
	get_shop_url,
	get_vendor_id_from_product,
	get_vendor_methods,
	get_vendor_zones,
	get_zone_locations
};

class Shipping_Notices {

	private static $instance = null;
	private const TYPE_DEFAULT = 'dafault';

	private function __construct(){}

	public static function get_instance(){
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function render_notices(){

		if ( ! is_product() ) {
			return;
		}

		$vendor_id = get_vendor_id_from_product( get_the_id() );

		if(
			( $cached = get_transient( 'arti_psn_shipping_notice_' . $vendor_id ) ) &&
			!apply_filters( 'arti_psn_shipping_notice_update', false )
		){
			return $cached;
		}

		$notices = [];

		$results = get_vendor_methods( intval( $vendor_id ) );

		if( empty( $results ) ){
			echo $this->get_default_notice( $vendor_id );
			return;
		}

		$location_groups = [];

		$has_free_shipping = false;

		foreach ( $results as $key => $result ) {

			if( 'free_shipping' !== $result->method_id || !wc_string_to_bool( $result->is_enabled ) ){

				// We only need one default.
				$notices[self::TYPE_DEFAULT] = $this->get_default_notice( $vendor_id );

				continue;
			}

			$has_free_shipping = true;

			$locations = get_zone_locations( $result->zone_id, $vendor_id );

			if( empty( $locations ) ){
				// Get the locations from the "parent" zone, ie, the one from WooCommerce.
				$locations = (new \WC_Shipping_Zone( $result->zone_id ))->get_zone_locations();
			}

			$settings = get_shipping_settings( $result );

			$expanded_locations = $this->expand_locations( $locations );

			$min_amount = $settings['min_amount'];

			$group_by_min_amount = apply_filters( 'arti_psn_group_notices_by_min_amount', false );

			if( wc_string_to_bool( $group_by_min_amount ) ){

				if( !isset( $location_groups[$min_amount] ) ){
					$location_groups[$min_amount]['expanded_locations'] = [];
					$location_groups[$min_amount]['min_amount'] = $min_amount;
				}

				$location_groups[$min_amount]['expanded_locations'] = array_merge( $location_groups[$min_amount]['expanded_locations'], $expanded_locations );

			} else {
				$location_groups[] = [ 'expanded_locations' => $expanded_locations, 'min_amount' => $min_amount ];
			}

		}

		if( apply_filters( 'arti_psn_remove_default_when_free_shipping_present', false ) && $has_free_shipping ){
			unset( $notices[self::TYPE_DEFAULT] );
		}

		foreach( $location_groups as $location_group ){

			$expanded_locations = $location_group['expanded_locations'];
			$min_amount = $location_group['min_amount'];

			$notices[] = $this->get_free_shipping_notice( $expanded_locations, $min_amount, $vendor_id );
		}

		$html = implode( '', $notices );

		set_transient( 'arti_psn_shipping_notice_' . $vendor_id, $html, DAY_IN_SECONDS );

		echo $html;

	}

	/**
	 * Exapnd the locations to their human readable names.
	 * @param  array $locations
	 * @return array
	 */
	protected function expand_locations( array $locations ){

		$all_states = WC()->countries->get_states();

		$states = [];

		foreach( $locations as $location ){

			$location = (array) $location;

			if( 'state' !== $location['type'] ){
				continue;
			}

			$location_parts = explode( ':', $location['code'] );

			$country_abbr = $location_parts[0];
			$state_abbr = $location_parts[1];
			$states[] = $all_states[$country_abbr][$state_abbr];

		}

		return $states;

	}

	// Notice templates.

	/**
	 * Format the free shipping notice HTML.
	 * @param  array $location_group
	 * @param  int|string $min_amount
	 * @param  int|string $vendor_id
	 * @return string
	 */
	protected function get_free_shipping_notice( $location_group, $min_amount, $vendor_id ){

		sort( $location_group );

		$locations_str = implode( ', ', $location_group );

		$location_group_length = count( $location_group );

		if( 1 < $location_group_length ){
			/**
			 * @todo Cover RTL languages and/or languages that don't use "and".
			 * @var $locations_str Replace the last comma witb "and".
			 */
			$locations_str = substr_replace( $locations_str, __( ' and', 'arti-psn' ), strrpos( $locations_str, ',' ), 1 );
		}

		/* translators: 1: location group (eg, list of states) 2: order amount */
		$notice_template = _n(
			'<strong>Free shipping</strong> to the state of %1$s for orders over %2$s with products from this vendor.',
			'<strong>Free shipping</strong> to the states of %1$s for orders over %2$s with products from this vendor.',
			$location_group_length,
			'arti-psn'
		);

		if( 0 === intval( $min_amount ) ){
			/* translators: 1: location group (eg, list of states) */
			$notice_template = _n(
				'<strong>Free shipping</strong> to the state of %1$s with products from this vendor.',
				'<strong>Free shipping</strong> to the states of %1$s with products from this vendor.',
				$location_group_length,
				'arti-psn'
			);
		}

		if( empty( $location_group ) ){
			/* translators: 2: order amount */
			$notice_template = __( '<strong>Free shipping</strong> for orders over %2$s with products from this vendor.', 'arti-psn' );
		}

		$notice_template = apply_filters( 'arti_psn_free_shipping_notice_template', $notice_template, $location_group, $min_amount );

		$notice_html = sprintf( $notice_template, $locations_str, wc_price( $min_amount ) );

		$shop_url = get_shop_url( $vendor_id );
		/* translators: %s: vendor shop URL */
		$shop_url_template = __( 'Check all options <a href="%s" title="All products from this vendor">here</a>.', 'arti-psn' );
		$shop_url_template = apply_filters( 'arti_psn_free_shipping_url_template', $shop_url_template );

		$shop_url_html = sprintf( $shop_url_template, esc_attr( $shop_url ) );

		$html = wc_get_template_html(
			'product-shipping-notices/free-shipping.php',
			[ 'notice_html' => $notice_html, 'shop_url_html' => $shop_url_html ],
			'',
			ARTI_PSN_TEMPLATE
		);

		return apply_filters( 'arti_psn_get_free_shipping_notice', $html, $vendor_id, $location_group, $min_amount );

	}

	protected function get_default_notice( $vendor_id ){

		// This sentence will continue later.
		$notice_html = __( 'Save more by adding products of the same vendor to your cart', 'arti-psn' );

		$shop_url = esc_url( get_shop_url( $vendor_id ) );
		/* translators: %s: vendor shop URL */
		$shop_url_template = __( '<a href="%s" title="All products from this vendor">here</a>.', 'arti-psn' );
		$shop_url_template = apply_filters( 'arti_psn_default_url_template', $shop_url_template );

		$shop_url_html = sprintf( $shop_url_template, esc_attr( $shop_url ) );

		$html = wc_get_template_html(
			'product-shipping-notices/default.php',
			[ 'notice_html' => $notice_html, 'shop_url_html' => $shop_url_html ],
			'',
			ARTI_PSN_TEMPLATE
		);

		return return apply_filters( 'arti_psn_get_default_notice', $html, $vendor_id );

	}

}
