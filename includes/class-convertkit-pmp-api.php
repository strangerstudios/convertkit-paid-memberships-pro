<?php

/**
 * ConvertKit API specific functionality
 *
 * @link       https://convertkit.com
 * @since      1.0.0
 *
 * @package    ConvertKit_PMP
 * @subpackage ConvertKit_PMP/includes
 */

/**
 * ConvertKit API specific functionality.
 *
 * Handles all API calls.
 *
 * @package    ConvertKit_PMP
 * @subpackage ConvertKit_PMP/includes
 * @author     Daniel Espinoza <daniel@growdevelopment.com>
 */
class ConvertKit_PMP_API {

	/** @var  string $api_version */
	protected $api_version = 'v3';

	/** @var  string $api_url */
	protected $api_url = 'https://api.convertkit.com';

	/** @var  string $api_key The customer's ConvertKit API key */
	protected $api_key;

	/** @var  array $tags Tags in the customer's account */
	protected $tags;

	/**
	 * Initialize the class.
	 *
	 * @since    1.0.0
	 * @param    string $api_key
	 */
	public function __construct( $api_key ) {

		$this->api_key = $api_key;

	}


	/**
	 * Get an array of tags and IDs from the API
	 *
	 * @return mixed
	 */
	public function get_tags() {

		$tags = get_transient( 'convertkit_pmp_tag_data' );

		if ( false === $tags || empty( $tags ) ) {

			// Get the API key.
			$api_key = $this->api_key;
			if ( '' == $api_key ) {
				return array();
			}

			// Build the request URL.
			$query_args = array();
			$request_url = $this->api_url . '/' . $this->api_version . '/tags';
			$query_args['api_key'] = $api_key;
			$request_url = add_query_arg( $query_args, $request_url );

			// Retrive the data from ConvertKit.
			$data = wp_remote_get(
				$request_url,
				array(
					'body'    => '',
					'timeout' => 30,
					'headers' => array(
						'Content-Type' => 'application/json'
					)
				)
			);

			if ( ! is_wp_error( $data ) ) {
				$tags = json_decode( $data['body'] );
				$tags = $tags->tags;
				set_transient( 'convertkit_pmp_tag_data', $tags, 24*24 );
			}

			if ( defined( 'CK_DEBUG') ) {
				$this->log( "Request url: " . $request_url );
			}

		}

		if ( ! empty( $tags ) ) {
			foreach( $tags as $key => $tag ) {
				$this->tags[ $tag->id ] = $tag->name;
			}
		}

		return $this->tags;

	}


	/**
	 *
	 * @param string $user_email
	 * @param string $user_name
	 * @param array $subscribe_tags
	 * @param array $subscribe_fields
	 */
	public function add_tag_to_user( $user_email, $user_name, $subscribe_tags, $subscribe_fields ) {
		// Get the API key.
		$api_key = $this->api_key;
		if ( '' == $api_key ) {
			return;
		}

		// Add args for this API endpoint.
		$args = array(
			'first_name' => $user_name,
			'email' => $user_email,
		);

		// If there are custom fields, add them to the body of the API request.
		if ( ! empty( $subscribe_fields ) ) {
			$args['fields'] = $subscribe_fields;
		}

		// If there is more than one tag, add the rest as additional tags in the body of the API request.
		if ( count( $subscribe_tags ) > 1 ) {
			$primary_tag_id = array_shift( $subscribe_tags );
			$args['tags'] = $subscribe_tags;
		} else {
			// Set the primary tag ID for the API request to the only item in the array of $subscribe_tags.
			$primary_tag_id = array_values( $subscribe_tags )[0];
		}

		// Build the request URL.
		$query_args = array();
		$request_url = $this->api_url . '/' . $this->api_version . '/tags/' . $primary_tag_id . '/subscribe';
		$query_args['api_key'] = $api_key;
		$request_url = add_query_arg( $query_args, $request_url );

		// Send the data to ConvertKit.
		$request = wp_remote_post(
			$request_url,
			array(
				'body'    => json_encode( $args ),
				'timeout' => 30,
				'headers' => array(
					'Content-Type' => 'application/json'
				)
			)
		);

		if ( defined( 'CK_DEBUG') ) {
			$this->log( "Request url: " . $request_url );
			$this->log( "Request args: " . print_r( $args, true ) );
		}
	}

	/**
	 *
	 * @param string $user_email
	 * @param string $api_secret_key
	 * @param int $tag_id
	 */
	public function remove_tag_from_user( $user_email, $api_secret_key, $tag_id ) {

		// Add args for this API endpoint.
		$args = array(
			'email' => $user_email,
			'api_secret' => $api_secret_key,
		);

		// Build the request URL.
		$query_args = array();
		$request_url = $this->api_url . '/' . $this->api_version . '/tags/' . $tag_id . '/unsubscribe';

		// Send the data to ConvertKit.
		$request = wp_remote_post(
			$request_url,
			array(
				'body'    => json_encode( $args ),
				'timeout' => 30,
				'headers' => array(
					'Content-Type' => 'application/json'
				)
			)
		);

		if ( defined( 'CK_DEBUG') ) {
			$this->log( "Request url: " . $request_url );
			$this->log( "Request args: " . print_r( $args, true ) );
		}

	}


	/**
	 *
	 * @param string $user_email
	 * @param string $api_secret_key
	 * @param int $tag_id
	 */
	public function create_purchase( $user_email, $api_secret_key, $order ) {
		global $pmpro_currency;

		$args = array(
			'api_secret' => $api_secret_key,
			'integration_key' => 'slxewn3xiWdPSTkLXKo2lQ',
			'purchase'        => array(
				'integration'      => 'Paid Memberships Pro',
				'transaction_id'   => $order->code,
				'email_address'    => $user_email,
				'currency'         => $pmpro_currency,
				'transaction_time' => date( "Y-m-d H:i:s", $order->timestamp ),
				'subtotal'         => $order->subtotal,
				'total'            => $order->total,
				'status'           => 'paid',
				'products'         => array(
					array(
						'pid'        => 'pmpro-' . $order->membership_level->membership_id,
						'lid'        => 1,
						'name'       => $order->membership_level->name,
						'unit_price' => pmpro_round_price( $order->membership_level->initial_payment ),
						'quantity'   => 1
					)
				)
			)
		);

		// Build the request URL.
		$request_url = $this->api_url . '/' . $this->api_version . '/purchases';

		// Send the data to ConvertKit.
		$request = wp_remote_post(
			$request_url,
			array(
				'body'    => json_encode( $args ),
				'timeout' => 30,
				'headers' => array(
					'Content-Type' => 'application/json'
				)
			)
		);

		if ( ! is_wp_error( $request ) && function_exists( 'add_pmpro_membership_order_meta' ) ) {
			$purchase = json_decode( $request['body'] );
			add_pmpro_membership_order_meta( $order->id, 'convertkit_pmp_purchase_id', $purchase->id );
		}

		if ( defined( 'CK_DEBUG') ) {
			$this->log( "Request url: " . $request_url );
			$this->log( "Request args: " . print_r( $args, true ) );
		}
	}


	/**
	 * Log API calls and updates.
	 *
	 * @since 1.0.0
	 * @param string $message Message to put in the log.
	 */
	public function log( $message ) {

		$log     = fopen( plugin_dir_path( __FILE__ ) . '/log.txt', 'a+' );
		$message = '[' . date( 'd-m-Y H:i:s' ) . '] ' . $message . PHP_EOL;
		fwrite( $log, $message );
		fclose( $log );

	}


}