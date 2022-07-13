<?php

require_once dirname( __DIR__ ) . '/wp-db.php';

class SweetHomeStock {
	static function index(WP_REST_Request $request) {
		$post_id = $request->get_param('product');

		return new WP_REST_Response([
			'data' => SHStock::get($post_id)
		]);
	}

	static function store( WP_REST_Request $request ) {
		$post_id = $request->get_param('product');

		$data = $request->get_json_params();

		$stock = SHStock::create(
			$post_id,
			$data['mfd'],
			$data['exp'],
			$data['quantity']
		);

		return new WP_REST_Response([
			'data' => $stock
		]);
	}

	static function index_permission() {
		return current_user_can( 'manage_options' );
	}

	static function destroy(WP_REST_Request $request) {
		$id = $request->get_param('stock');

		SHStock::delete($id);

		return new WP_REST_Response();
	}

	static function quantity(WP_REST_Request $request) {
		$post_id = $request->get_param('stock');
		$symbol = '';
		switch ($request->get_param('mode')) {
			case 'increment':
				$symbol = '+';
				break;
			case 'decrement':
				$symbol = '-';
				break;
		}

		SHStock::quantity($post_id, $symbol);

		return new WP_REST_Response();
	}

}