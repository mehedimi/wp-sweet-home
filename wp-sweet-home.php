<?php
/**
 * Plugin Name: WP Sweet Home
 */

if (!defined('ABSPATH')) {
	exit('Forbidden');
}

require_once __DIR__ . '/wp-rest.php';

add_action('rest_api_init', [SweetHomeRest::class, 'handle']);

register_activation_hook( __FILE__, 'sweet_home_activation' );

function sweet_home_activation() {
	global $wpdb;

	if (! function_exists('dbDelta')) {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	}
	$sql = <<<SQL
	CREATE TABLE IF NOT EXISTS {$wpdb->prefix}sh_barcodes (
    barcode varchar(255),
    product_id bigint unsigned,
    primary key(barcode),
    FOREIGN KEY (product_id) REFERENCES {$wpdb->posts}(ID) ON DELETE CASCADE
);
SQL;
	dbDelta($sql);
}