<?php

/**
 * The admin-specific functionality for ConvertKit Paid Memberships Pro
 *
 * @link       http://www.convertkit.com
 * @since      1.0.0
 *
 * @package    ConvertKit_PMP
 * @subpackage ConvertKit_PMP/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    ConvertKit_PMP
 * @subpackage ConvertKit_PMP/admin
 * @author     Daniel Espinoza <daniel@growdevelopment.com>
 */
class ConvertKit_PMP_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * API functionality class
	 *
	 * @since   1.0.0
	 * @access  private
	 * @var     ConvertKit_PMP_API $api
	 */
	private $api;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string    $plugin_name       The name of this plugin.
	 * @param    string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-convertkit-pmp-api.php';

		$api_key = $this->get_option( 'api-key' );

		$this->api = new ConvertKit_PMP_API( $api_key );
	}


	/**
	 *  Register settings for the plugin.
	 *
	 * The mapping section is dynamic and depends on defined membership levels and defined tags.
	 *
	 * @since       1.0.0
	 * @return      void
	 */
	public function register_settings() {

		register_setting(
			$this->plugin_name . '-options',
			$this->plugin_name . '-options',
			array( $this, 'validate_options' )
		);

		// add_settings_section( $id, $title, $callback, $menu_slug );
		add_settings_section(
			$this->plugin_name . '-display-options',
			apply_filters( $this->plugin_name . '-display-section-title', __( 'General', 'convertkit-pmp' ) ),
			array( $this, 'display_options_section' ),
			$this->plugin_name
		);

		// add_settings_field( $id, $title, $callback, $menu_slug, $section, $args );
		add_settings_field(
			'api-key',
			apply_filters( $this->plugin_name . '-display-api-key', __( 'API Key', 'convertkit-pmp' ) ),
			array( $this, 'display_options_api_key' ),
			$this->plugin_name,
			$this->plugin_name . '-display-options'
		);

		// add_settings_field( $id, $title, $callback, $menu_slug, $section, $args );
		add_settings_field(
			'api-secret-key',
			apply_filters( $this->plugin_name . '-display-api-ksecret-ey', __( 'API Secret Key', 'convertkit-pmp' ) ),
			array( $this, 'display_options_api_secret_key' ),
			$this->plugin_name,
			$this->plugin_name . '-display-options'
		);

		// add_settings_section( $id, $title, $callback, $menu_slug );
		add_settings_section(
			$this->plugin_name . '-ck-mapping',
			apply_filters( $this->plugin_name . '-display-mapping-title', __( 'Assign Tags', 'convertkit-pmp' ) ),
			array( $this, 'display_mapping_section' ),
			$this->plugin_name
		);

		// Get all PMP membership levels
		$levels = $this->get_pmp_membership_levels();

		// Get all tags from ConvertKit
		$tags = $this->api->get_tags();

		// No PMP mappings created yet
		if ( empty ( $levels ) ){

			add_settings_field(
				'convertkit-empty-mapping',
				apply_filters( $this->plugin_name . '-display-convertkit-mapping', __( 'Mapping', 'convertkit-pmp' ) ),
				array( $this, 'display_options_empty_mapping' ),
				$this->plugin_name,
				$this->plugin_name . '-ck-mapping'
			);

		} else {
			foreach( $levels as $key => $name ) {

				add_settings_field(
					'convertkit-mapping-' . $key,
					apply_filters( $this->plugin_name . '-display-convertkit-mapping-' . $key , $name ),
					array( $this, 'display_options_convertkit_mapping' ),
					$this->plugin_name,
					$this->plugin_name . '-ck-mapping',
					array( 'key' => $key,
					       'name' => $name,
					       'tags' => $tags,
					)
				);
			}

		}

	}


	/**
	 * Adds a settings page link to a menu
	 *
	 * @since 		1.0.0
	 * @return 		void
	 */
	public function add_menu() {
		// add_options_page( $page_title, $menu_title, $capability, $menu_slug, $callback );
		add_options_page(
			apply_filters( $this->plugin_name . '-settings-page-title', __( 'Paid Memberships Pro - ConvertKit Settings', 'convertkit-pmp' ) ),
			apply_filters( $this->plugin_name . '-settings-menu-title', __( 'PMPro ConvertKit', 'convertkit-pmp' ) ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'options_page' )
		);
	}

	/**
	 * Creates the options page
	 *
	 * @since 		1.0.0
	 * @return 		void
	 */
	public function options_page() {
		?><div class="wrap"><h1><?php echo esc_html( get_admin_page_title() ); ?></h1></div>
		<form action="options.php" method="post"><?php
		settings_fields( 'convertkit-pmp-options' );
		do_settings_sections( $this->plugin_name );
		submit_button( 'Save Settings' );
		?></form><?php
	}


	/**
	 * Validates saved options
	 *
	 * @since 		1.0.0
	 * @param 		array 		$input 			array of submitted plugin options
	 * @return 		array 						array of validated plugin options
	 */
	public function validate_options( $input ) {


		return $input;
	}


	/**
	 * Creates a settings section
	 *
	 * @since 		1.0.0
	 * @param 		array 		$params 		Array of parameters for the section
	 * @return 		mixed 						The settings section
	 */
	public function display_options_section( $params ) {
		echo '<p>' . __( 'Add your API keys below to connect your ConvertKit account to this membership site.','convertkit-pmp') .'</p>';
	}


	/**
	 * Creates a settings section
	 *
	 * @since 		1.0.0
	 * @param 		array 		$params 		Array of parameters for the section
	 * @return 		mixed 						The settings section
	 */
	public function display_mapping_section( $params ) {
		echo '<p>' . __( 'Below is a list of the defined Membership Levels in Paid Memberships Pro. Assign a membership level to a ConvertKit tag that will be assigned to members of that level.','convertkit-pmp') .'</p>';
	}


	/**
	 * Adds a link to the plugin settings page
	 *
	 * @since 		1.0.0
	 * @param 		array 		$links 		The current array of links
	 * @return 		array 					The modified array of links
	 */
	public function settings_link( $links ) {

		$settings_link = sprintf( '<a href="%s">%s</a>', admin_url( 'options-general.php?page=' . $this->plugin_name ), __( 'Settings', 'convertkit-pmp' ) );
		array_unshift( $links, $settings_link );
		return $links;
	}


	/**
	 * Creates a settings input for the API key.
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function display_options_api_key() {
		$api_key = $this->get_option( 'api-key' );

		?><input type="text" id="<?php echo $this->plugin_name; ?>-options[api-key]" name="<?php echo $this->plugin_name; ?>-options[api-key]" value="<?php echo esc_attr( $api_key ); ?>" size="40" /><br/>
		<p class="description"><?php echo __( 'This field is required to add and tag subscribers in ConvertKit.', 'convertkit-pmp' ); ?> <a href="https://app.convertkit.com/account/edit" target="_blank"><?php echo __( 'Get your ConvertKit API Key', 'convertkit-pmp' ); ?></a></p><?php
	}


	/**
	 * Creates a settings input for the API secret key.
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function display_options_api_secret_key() {
		$api_secret_key = $this->get_option( 'api-secret-key' );

		?><input type="text" id="<?php echo $this->plugin_name; ?>-options[api-secret-key]" name="<?php echo $this->plugin_name; ?>-options[api-secret-key]" value="<?php echo esc_attr( $api_secret_key ); ?>" size="40" />
		<p class="description"><?php echo __( 'This field is required to add purchase data to subscribers in ConvertKit.', 'convertkit-pmp' ); ?> <a href="https://app.convertkit.com/account/edit" target="_blank"><?php echo __( 'Get your ConvertKit API Secret Key', 'convertkit-pmp' ); ?></a></p><?php
	}


	/**
	 * Empty mapping callback
	 *
	 * No PMP Membership Levels have been added yet.
	 *
	 * @since 		1.0.0
	 * @return 		mixed 			The settings field
	 */
	public function display_options_empty_mapping() {
		if ( ! defined( 'PMPRO_VERSION' ) ) { ?>
			<p><?php echo __( 'Paid Memberships Pro must be installed and activated to use this integration.', 'converkit-pmp'); ?></p>
			<?php
		} else { ?>
			<p>
				<?php echo __( 'No Membership Levels have been added yet.', 'converkit-pmp'); ?><br/>
				<?php echo sprintf( __( 'You can add one <a href="%s">here</a>.', 'converkit-pmp'), get_admin_url( null, '/admin.php?page=pmpro-membershiplevels' ) ); ?>
			</p>
			<?php
		}
	}


	/**
	 * Display mapping for the specified key.
	 *
	 * @since 1.0.0
	 * @param string $args
	 */
	public function display_options_convertkit_mapping( $args ) {

		$option_name 	= 'convertkit-mapping-' . $args['key'];
		$tag         	= $this->get_option( $option_name );
		$api_key     	= $this->get_option( 'api-key' );

		if ( empty( $api_key ) ) {
			?><p><?php echo __( 'Enter API key to retrieve list of tags.', 'convertkit-pmp' ); ?></p><?php
		} elseif( is_null( $args['tags'] ) ) {
			?><p><?php echo __( 'No tags were returned from ConvertKit.', 'convertkit-pmp' ); ?></p><?php
		} else {

			?><select id="<?php echo $this->plugin_name; ?>-options[<?php echo $option_name ?>]"
			          name="<?php echo $this->plugin_name; ?>-options[<?php echo $option_name ?>]"><?php
			if ( empty( $tag ) ) {
				?>
				<option value=""><?php echo __( 'Select a tag', 'convertkit-pmp' ); ?></option><?php
			}
			foreach ( $args['tags'] as $value => $text ) {
				?>
				<option value="<?php echo $value; ?>" <?php selected( $tag, $value ); ?>><?php echo $text; ?></option><?php
			}
			?></select><?php
		}

	}

	/**
	 * Get all PMP Membership levels
	 *
	 * Helper function to get member levels from PMP database.
	 * This is patterned on PMP's `membershiplevels.php` file.
	 * @see https://github.com/strangerstudios/paid-memberships-pro/blob/dev/adminpages/membershiplevels.php#L656
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function get_pmp_membership_levels() {

		global $wpdb;

		// Bail if Paid Memberships Pro is not active.
		if ( ! defined( 'PMPRO_VERSION' ) ) {
			return;
		}

		$sqlQuery = "SELECT * FROM $wpdb->pmpro_membership_levels ";
		$sqlQuery .= "ORDER BY id ASC";

		$result = $wpdb->get_results($sqlQuery, OBJECT);

		$levels = array();

		foreach ( $result as $_level ){
			$levels[ $_level->id ] = $_level->name;
		}

		return $levels;

	}


	/**
	 * A change membership level action is occurring in Paid Memberships Pro.
	 *
	 * If $level == 0 then the user is being removed from a membership.
	 * If $level is > 0 then the user is being added to level with that ID.
	 *
	 * @since 1.0.0
	 * @param int $level_id
	 * @param int $user_id
	 */
	public function change_membership_level( $level_id, $user_id, $cancel_level ) {
		// Get the tag IDs to add to this subscriber.
		$mapping_to_add = 'convertkit-mapping-' . $level_id;
		$tag_id_to_add = $this->get_option( $mapping_to_add );

		// Get the subscriber information.
		$user = get_userdata( $user_id );
		$user_email = $user->user_email;
		$user_name = $user->first_name . ' ' . $user->last_name;

		// Run the API call to add tag to this subscriber.
		if ( ! empty( $tag_id_to_add ) ) {
			$this->api->add_tag_to_user( $user_email, $user_name, $tag_id_to_add );
		}

		/**
		 * Option to remove other tags for other levels on level change.
		 *
		 * @param bool $remove_tags Set to true to remove other tags. Default: false.
		 * @param int $cancel_level The ID of the level previously held, if available.
		 * @return bool $remove_tags.
		 *
		 */
		$remove_tags = apply_filters( 'pmpro_convertkit_change_membership_level_remove_tags', false, $cancel_level );
		
		if ( ! empty( $remove_tags ) && ! empty( $cancel_level ) ) {
			// Get the secret API key.
			$api_secret_key = $this->get_option( 'api-secret-key' );

			// Get the tag IDs to add to remove from  this subscriber.
			$mapping_to_remove = 'convertkit-mapping-' . $cancel_level;
			$tag_id_to_remove = $this->get_option( $mapping_to_remove );

			// Run the API call to remove previous level's tag from this subscriber.
			if ( ! empty ( $tag_id_to_remove ) && ! empty ( $api_secret_key ) ) {
				$this->api->remove_tag_from_user( $user_email, $api_secret_key, $tag_id_to_remove );
			}
		}
	}


	/**		
 	 * Add payment details to ConvertKit when a new membership checkout is completed.		
 	 *		
 	 * @param int $payment_id		
 	 *		
 	 * @access public		
 	 * @since 1.1		
 	 * @return void		
 	 */		
 	public function after_checkout( $user_id, $order ) {		

		// Bail if the order is empty.
		if ( empty( $order->id ) ) {
			return;		
		}

		// Get the user information for this order.
		$user = get_userdata( $user_id );
		$user_email = $user->user_email;

		// Get the Membership Level information for this order.
		$order = new MemberOrder( $order->id );
		$order->getMembershipLevel();

		// Get the secret API key.
		$api_secret_key = $this->get_option( 'api-secret-key' );

		// Run the API call to add purchase data.
		if ( ! empty ( $api_secret_key ) ) {
			$this->api->create_purchase( $user_email, $api_secret_key, $order );
		}
	}

	/**
	 * Get the setting option requested.
	 *
	 * @since   1.0.0
	 * @param   $option_name
	 * @return  string $option
	 */
	public function get_option( $option_name ){

		$options = get_option( $this->plugin_name . '-options' );
		$option = '';

		if ( ! empty( $options[ $option_name ] ) ) {
			$option = $options[ $option_name ];
		}

		return $option;
	}


	/**
	 * Add links to the plugin row meta
	 *
	 * @since   1.1
	 * @param	$links - Links for plugin
	 * @param	$file - main plugin filename
	 * @return	array - Array of links
	 */
	public function plugin_row_meta( $links, $file ) {
		if (strpos($file, 'convertkit-pmp.php') !== false) {
			$new_links = array(
				'<a href="' . esc_url( 'https://www.paidmembershipspro.com/add-ons/convertkit/') . '" title="' . esc_attr(__( 'View Documentation', 'convertkit-pmp' ) ) . '">' . __('Docs', 'convertkit-pmp') . '</a>',
				'<a href="' . esc_url('https://www.paidmembershipspro.com/support/') . '" title="' . esc_attr(__('Visit Customer Support Forum', 'convertkit-pmp')) . '">' . __('Support', 'convertkit-pmp') . '</a>',
			);
			$links = array_merge($links, $new_links);
		}
		return $links;
	}
}
