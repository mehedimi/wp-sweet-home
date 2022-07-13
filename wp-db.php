<?php

define( 'SH_TABLE', $wpdb->prefix . 'sh_barcodes' );

class SHProduct {
	const POST_TYPE = 'sh_product';

	static function exists( $barcode ) {
		global $wpdb;

		$statement = $wpdb->prepare( "SELECT barcode FROM " . SH_TABLE . " WHERE barcode = %s LIMIT 1", $barcode );

		return $wpdb->query( $statement );
	}

	static function create( $name, $barcode ) {
		global $wpdb;

		$post_id = wp_insert_post( [
			'post_status' => 'published',
			'post_type'   => self::POST_TYPE,
			'post_title'  => $name
		], false, false );

		$wpdb->insert( SH_TABLE, [
			'product_id' => $post_id,
			'barcode'    => $barcode
		] );

		return self::find( $post_id );
	}

	static function findByBarcode( $barcode ) {
		global $wpdb;

		$statement = $wpdb->prepare(
			'SELECT * FROM ' . SH_TABLE . ' JOIN ' . $wpdb->posts . ' ON ' . SH_TABLE . '.product_id = ' . $wpdb->posts . '.ID WHERE barcode = %s',
			$barcode
		);

		return $wpdb->get_row( $statement );
	}

	static function find( $post_id ) {
		global $wpdb;

		$statement = $wpdb->prepare(
			'SELECT * FROM ' . $wpdb->posts . ' JOIN ' . SH_TABLE . ' ON ' . SH_TABLE . '.product_id = ' . $wpdb->posts . '.ID WHERE ID = %s',
			$post_id
		);

		return $wpdb->get_row( $statement );
	}

	static function get() {
		global $wpdb;

		$statement = $wpdb->prepare(
			'SELECT ' . $wpdb->posts . '.*, barcode FROM ' . SH_TABLE . ' JOIN ' . $wpdb->posts . ' ON ' . SH_TABLE . '.product_id = ' . $wpdb->posts . '.ID'
		);

		return $wpdb->get_results( $statement );
	}

	static function delete( $post_id ) {
		global $wpdb;

		$statement = $wpdb->prepare( "DELETE FROM " . $wpdb->posts . " WHERE ID = %d", $post_id );

		return $wpdb->query( $statement );
	}
}

class SHStock {
	const POST_TYPE = 'sh_stock';

	static function get( $post_id ) {
		global $wpdb;

		$statement = $wpdb->prepare(
			'SELECT * FROM ' . $wpdb->posts . ' WHERE post_parent = %d',
			$post_id
		);

		return $wpdb->get_results( $statement );
	}

	static function create( $post_id, $mfd, $exp, $quantity ) {
		global $wpdb;
		$stock_id = wp_insert_post( [
			'post_status'   => 'published',
			'post_type'     => self::POST_TYPE,
			'post_parent'   => $post_id,
			'post_date'     => date( 'Y-m-d 00:00:00', strtotime( $mfd ) ),
			'post_modified' => date( 'Y-m-d 00:00:00', strtotime( $exp ) ),
		], false, false );

		$wpdb->update( $wpdb->posts, [
			'comment_count' => $quantity
		], [ 'ID' => $stock_id ] );

		return self::find( $stock_id );
	}

	static function find( $post_id ) {
		global $wpdb;

		$statement = $wpdb->prepare(
			'SELECT * FROM ' . $wpdb->posts . ' WHERE ID = %s LIMIT 1',
			$post_id
		);

		return $wpdb->get_row( $statement );
	}

	static function delete( $post_id ) {
		global $wpdb;

		$statement = $wpdb->prepare( "DELETE FROM " . $wpdb->posts . " WHERE ID = %d", $post_id );

		return $wpdb->query( $statement );
	}

	static function quantity( $post_id, $symbol ) {
		global $wpdb;

		$statement = $wpdb->prepare( "UPDATE $wpdb->posts SET comment_count = comment_count {$symbol} 1 WHERE ID = %d", $post_id );

		return $wpdb->query( $statement );
	}
}