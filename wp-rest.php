<?php

require_once __DIR__ . '/rest/product.php';
require_once __DIR__ . '/rest/stock.php';

class SweetHomeRest {
	const REST_NAMESPACE = 'sweet-home/v1';

	public static function handle() {
		register_rest_route(self::REST_NAMESPACE, 'products', [
			'methods' => 'GET',
			'callback' => [
				SweetHomeProduct::class,
				'index'
			],
			'permission_callback' => [
				SweetHomeProduct::class,
				'index_permission'
			]
		]);

		register_rest_route(self::REST_NAMESPACE, 'products', [
			'methods' => 'POST',
			'callback' => [
				SweetHomeProduct::class,
				'store'
			],
			'permission_callback' => [
				SweetHomeProduct::class,
				'index_permission'
			],
		]);

		register_rest_route(self::REST_NAMESPACE, 'products/(?P<barcode>\w+)', [
			'methods' => 'GET',
			'callback' => [
				SweetHomeProduct::class,
				'show'
			],
			'permission_callback' => [
				SweetHomeProduct::class,
				'index_permission'
			],
		]);

		register_rest_route(self::REST_NAMESPACE, 'products/(?P<product>\d+)', [
			'methods' => 'DELETE',
			'callback' => [
				SweetHomeProduct::class,
				'destroy'
			],
			'permission_callback' => [
				SweetHomeProduct::class,
				'index_permission'
			],
		]);

		register_rest_route(self::REST_NAMESPACE, 'products/(?P<product>\d+)/stocks', [
			'methods' => 'POST',
			'callback' => [
				SweetHomeStock::class,
				'store'
			],
			'permission_callback' => [
				SweetHomeStock::class,
				'index_permission'
			],
		]);

		register_rest_route(self::REST_NAMESPACE, 'products/(?P<product>\d+)/stocks', [
			'methods' => 'GET',
			'callback' => [
				SweetHomeStock::class,
				'index'
			],
			'permission_callback' => [
				SweetHomeStock::class,
				'index_permission'
			],
		]);

		register_rest_route(self::REST_NAMESPACE, 'stocks/(?P<stock>\d+)', [
			'methods' => 'DELETE',
			'callback' => [
				SweetHomeStock::class,
				'destroy'
			],
			'permission_callback' => [
				SweetHomeStock::class,
				'index_permission'
			],
		]);

		register_rest_route(self::REST_NAMESPACE, 'stocks/(?P<stock>\d+)/quantity/(?P<mode>increment|decrement)', [
			'methods' => 'PATCH',
			'callback' => [
				SweetHomeStock::class,
				'quantity'
			],
			'permission_callback' => [
				SweetHomeStock::class,
				'index_permission'
			],
		]);
	}
}