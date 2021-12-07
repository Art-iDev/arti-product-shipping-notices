<?php
namespace Arti\PSN\Marketplace\functions;

function get_vendor_id_from_product( $product_id ){

	global $WCFM;
	return (int) $WCFM->wcfm_vendor_support->wcfm_get_vendor_id_from_product( $product_id );

}

function get_vendor_methods( int $vendor_id ){

	global $wpdb;

	$sql = "SELECT * FROM {$wpdb->prefix}wcfm_marketplace_shipping_zone_methods WHERE `vendor_id`={$vendor_id}";
	return $wpdb->get_results( $sql );

}

function get_zone_locations( int $zone_id, int $vendor_id ){
	return \WCFMmp_Shipping_Zone::get_locations( $zone_id, $vendor_id );
}

function get_shop_url( $vendor_id ){
	return wcfmmp_get_store( $vendor_id )->get_shop_url();
}

function get_shipping_settings( $result ){
	$settings = !empty( $result->settings ) ? maybe_unserialize( $result->settings ) : array();
	return array_map( 'stripslashes_deep', maybe_unserialize( $settings ) );
}
