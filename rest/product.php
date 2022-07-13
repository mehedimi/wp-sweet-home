<?php

require_once dirname( __DIR__ ) . '/wp-db.php';

class SweetHomeProduct {

	static function index() {
		return new WP_REST_Response( [
			'data' => SHProduct::get(),
		] );
	}

	static function index_permission() {
		return current_user_can( 'manage_options' );
	}

	static function store( WP_REST_Request $request ) {

		$data = $request->get_json_params();

		if (SHProduct::exists( $data['barcode'] )) {
			return new WP_REST_Response([
				'barcode' => 'This product has already exists'
			], 422);
		}

		return new WP_REST_Response([
			'data' => SHProduct::create($data['name'], $data['barcode'])
		]);
	}

	static function show(WP_REST_Request $request) {
		$barcode = $request->get_param('barcode');

		$product = SHProduct::findByBarcode($barcode);

		if (! $product) {
			return new WP_REST_Response([
				'message' => 'No record found!'
			], 404);
		}

		return new WP_REST_Response([
			'data' => $product
		]);
	}

	static function destroy(WP_REST_Request $request) {
		$id = $request->get_param('product');

		SHProduct::delete($id);

		return new WP_REST_Response();
	}
}