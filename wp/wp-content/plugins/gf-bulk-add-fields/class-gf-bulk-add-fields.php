<?php

//------------------------------------------

GFForms::include_addon_framework();

class GFBulkAddFields extends GFAddOn {

	protected $_version = GFBAF_VERSION;
	protected $_min_gravityforms_version = '2.0';

	protected $_slug = GFBAF_SLUG;
	protected $_path = 'gf-bulk-add-fields/gf-bulk-add-fields.php';
	protected $_full_path = __FILE__;
	protected $_title = GFBAF_NAME;
	protected $_short_title = 'Bulk Add Fields';
	protected $_url = 'http://jetsloth.com/gravity-forms-bulk-add-fields/';

	/**
	 * Members plugin integration
	 */
	protected $_capabilities = array( 'gravityforms_edit_forms', 'gravityforms_edit_settings' );

	/**
	 * Permissions
	 */
	protected $_capabilities_settings_page = 'gravityforms_edit_settings';
	protected $_capabilities_form_settings = 'gravityforms_edit_forms';
	protected $_capabilities_uninstall = 'gravityforms_uninstall';

	private static $_instance = null;

	/**
	 * Get an instance of this class.
	 *
	 * @return GFBulkAddFields
	 */
	public static function get_instance() {
		if ( self::$_instance == null ) {
			self::$_instance = new GFBulkAddFields();
		}

		return self::$_instance;
	}

	private function __clone() {
	} /* do nothing */

	/**
	 * Handles anything which requires early initialization.
	 */
	public function pre_init() {
		parent::pre_init();
	}

	/**
	 * Handles hooks and loading of language files.
	 */
	public function init() {

		add_action( 'admin_head', array( $this, 'add_js_templates' ), 10, 2 );

		parent::init();

	}

	/**
	 * Initialize the admin specific hooks.
	 */
	public function init_admin() {

		// form editor
		add_filter( 'gform_tooltips', array( $this, 'add_image_choice_field_tooltips' ) );

		$name = plugin_basename($this->_path);
		add_action( 'after_plugin_row_'.$name, array( $this, 'gf_plugin_row' ), 10, 2 );

		parent::init_admin();

	}

	/**
	 * The Image Choices add-on does not support logging.
	 *
	 * @param array $plugins The plugins which support logging.
	 *
	 * @return array
	 */
	public function set_logging_supported( $plugins ) {

		return $plugins;

	}


	// # SCRIPTS & STYLES -----------------------------------------------------------------------------------------------

	public function add_js_templates() {
?>
<script type="text/html" id="baf-template__baf-item">
	<li class="baf-item" data-field_type="{{type}}">
		<label class="baf-item__title">
			<span class="baf-item__type">{{name}} <?php _e('Field', GFBAF_TEXT_DOMAIN); ?></span>
			<span class="baf-item__label">Untitled</span>
			<input type="text" name="{{type}}_{{index}}" value="Untitled" class="baf-item__input" disabled="disabled" />
		</label>
		<button type="button" class="baf-item__delete"><span class="dashicons dashicons-dismiss"></span></button>
	</li>
</script>
<script type="text/html" id="baf-template__baf-window">
	<div class="baf-window__outer">
		<div class="baf-window__inner">
            <button type="button" class="baf-window__close"><span class="dashicons dashicons-no"></span></button>
			<section class="baf-window">
                <aside class="baf-window__sidebar">
                    <ul id="baf_available_items" class="baf-available-items menu collapsible expandfirst">
                        {{#each availableFields}}
                        <li class="baf-available-items-group" id="{{this.id}}">
                            <div class="button-title-link">
                                <div class="add-buttons-title">{{this.title}}</div>
                            </div>
                            <ul>
                                <li class="add-buttons">
                                    <ol class="field_type">
                                        {{#each fields}}
                                        <li class="baf-available-item">
                                            <button type="button" class="button baf-available-item__add" data-field_type="{{this.type}}">{{this.name}}</button>
                                        </li>
                                        {{/each}}
                                    </ol>
                                </li>
                            </ul>
                        </li>
                        {{/each}}
                    </ul>
                </aside>
                <main class="baf-window__main">
                    <header class="baf-window__header">
                        <h1 class="baf-window__title"><?php _e('Bulk Add Fields', GFBAF_TEXT_DOMAIN); ?></h1>
                    </header>
                    <div class="baf-window_body">
                        <ul class="baf-items">
                        </ul>
                    </div>
                    <footer class="baf-window__footer">
                        <button type="button" class="button button-large baf-window__cancel"><?php _e('Cancel', GFBAF_TEXT_DOMAIN); ?></button>
                        <button type="button" class="button button-large button-primary baf-window__submit"><?php _e('Add Selected Fields', GFBAF_TEXT_DOMAIN); ?><span class="dashicons dashicons-list-view dashicons-plus"></span></button>
                    </footer>
                </main>
			</section>
            <div class="baf-window__loader">
                <span class="baf-window__loader-spinner"></span>
                <span class="baf-window__loader-text" data-default-text="<?php _e('Adding your fields...', GFBAF_TEXT_DOMAIN); ?>"><?php _e('Adding your fields...', GFBAF_TEXT_DOMAIN); ?></span>
            </div>
		</div>
	</div>
    <div id="baf-complete-dialog" title="<?php _e('Bulk Add Fields', GFBAF_TEXT_DOMAIN); ?>"><?php _e("Fields added. Don't forget to save the form!", GFBAF_TEXT_DOMAIN); ?></div>
</script>
<script type="text/html" id="baf-template__baf-launcher">
	<div class="baf-launcher">
		<button type="button" class="button button-large button-primary baf-launcher__btn"><?php _e('Bulk Add Fields', GFBAF_TEXT_DOMAIN); ?><span class="dashicons dashicons-list-view dashicons-plus"></span></button>
	</div>
</script>
<?php
	}

	/**
	 * Return the scripts which should be enqueued.
	 *
	 * @return array
	 */
	public function scripts() {
		$gf_bulk_add_fields_js_deps = array( 'jquery', 'jquery-ui-sortable', 'jquery-ui-dialog' );
		if ( wp_is_mobile() ) {
			$gf_bulk_add_fields_js_deps[] = 'jquery-touch-punch';
		}

		$scripts = array(
				array(
						'handle'  => 'gf_bulk_add_fields_js',
						'src'     => $this->get_base_url() . '/js/gf_bulk_add_fields.js',
						'version' => $this->_version,
						'deps'    => $gf_bulk_add_fields_js_deps,
					    'in_footer' => true,
						'enqueue' => array(
								array( 'admin_page' => array( 'form_editor') ),
						),
				),
		);

		return array_merge( parent::scripts(), $scripts );
	}

	/**
	 * Return the stylesheets which should be enqueued.
	 *
	 * @return array
	 */
	public function styles() {

		wp_enqueue_style ('wp-jquery-ui-dialog');

		$styles = array(
				array(
						'handle'  => 'gf_bulk_add_fields_css',
						'src'     => $this->get_base_url() . '/css/gf_bulk_add_fields.css',
						'version' => $this->_version,
						'media'   => 'screen',
						'enqueue' => array(
								array( 'admin_page' => array( 'form_editor') )
						),
				),
		);

		return array_merge( parent::styles(), $styles );
	}

	/**
	 * Localize the strings used by the scripts.
	 */
	public function localize_scripts() {

		// Get current page protocol
		$protocol = isset( $_SERVER['HTTPS'] ) ? 'https://' : 'http://';
		// Output admin-ajax.php URL with same protocol as current page
		$params = array(
				'ajaxurl'   => admin_url( 'admin-ajax.php', $protocol )
		);
		wp_localize_script( 'gf_bulk_add_fields_js', 'bulkAddFieldsVars', $params );

		//localize strings for the js file
		$strings = array(
				'bulkAddFields'    => esc_html__( 'Bulk Add Fields', GFBAF_TEXT_DOMAIN ),
				'addSelectedFields'    => esc_html__( 'Add Selected Fields', GFBAF_TEXT_DOMAIN ),
		);
		wp_localize_script( 'gf_bulk_add_fields_js', 'bulkAddFieldsStrings', $strings );

	}


	/**
	 * Creates a settings page for this add-on.
	 */
	public function plugin_settings_fields() {

		$license = $this->get_plugin_setting('gf_bulk_add_fields_license_key');
		$status = get_option('gf_bulk_add_fields_license_status');

		$license_field = array(
			'name' => 'gf_bulk_add_fields_license_key',
			'tooltip' => esc_html__('Enter the license key you received after purchasing the plugin.', GFBAF_TEXT_DOMAIN),
			'label' => esc_html__('License Key', GFBAF_TEXT_DOMAIN),
			'type' => 'text',
			'input_type' => 'password',
			'class' => 'medium',
			'default_value' => '',
			'validation_callback' => array($this, 'license_validation'),
			'feedback_callback' => array($this, 'license_feedback'),
			'error_message' => esc_html__( 'Invalid license', GFBAF_TEXT_DOMAIN ),
		);

		if (!empty($license) && !empty($status)) {
			$license_field['after_input'] = ($status == 'valid') ? ' License is valid' : ' Invalid or expired license';
		}

		$fields = array(
			array(
				'title'  => esc_html__('To unlock plugin updates, please enter your license key below', GFBAF_TEXT_DOMAIN),
				'fields' => array(
					$license_field
				)
			)
		);

		return $fields;
	}

	/**
	 * Add the tooltips for the field.
	 *
	 * @param array $tooltips An associative array of tooltips where the key is the tooltip name and the value is the tooltip.
	 *
	 * @return array
	 */
	public function add_image_choice_field_tooltips( $tooltips ) {
		$tooltips['image_choices_use_images'] = '<h6>' . esc_html__( 'Use Images', GFBAF_TEXT_DOMAIN ) . '</h6>' . esc_html__( 'Enable to use of images as choices.', GFBAF_TEXT_DOMAIN );
		$tooltips['image_choices_show_labels'] = '<h6>' . esc_html__( 'Show Labels', GFBAF_TEXT_DOMAIN ) . '</h6>' . esc_html__( 'Enable the display of the labels together with the image.', GFBAF_TEXT_DOMAIN );
		return $tooltips;
	}

	/**
	 * Add custom messages after plugin row based on license status
	 */

	public function gf_plugin_row($plugin_file='', $plugin_data=array(), $status='') {
		$row = array();
		$license_key = trim($this->get_plugin_setting('gf_bulk_add_fields_license_key'));
		$license_status = get_option('gf_bulk_add_fields_license_status', '');
		if (empty($license_key) || empty($license_status)) {
			
		}
		elseif(!empty($license_key) && $license_status != 'valid') {
			$row = array(
				'<tr class="plugin-update-tr">',
					'<td colspan="3" class="plugin-update gf_bulk_add_fields-plugin-update">',
						'<div class="update-message">',
							'Your license is invalid or expired. <a href="'.admin_url('admin.php?page=gf_settings&subview='.$this->_slug).'">Enter valid license key</a> or <a href="'.$this->_url.'" target="_blank">purchase a new one</a>.',
							'<style type="text/css">',
								'.plugin-update.gf_bulk_add_fields-plugin-update .update-message:before {',
                                    'content: "\f348";',
                                    'margin-top: 0;',
                                    'font-family: dashicons;',
                                    'font-size: 20px;',
                                    'position: relative;',
                                    'top: 5px;',
                                    'color: #d54e21;',
                                    'margin-right: 8px;',
								'}',
                                '.plugin-update.gf_bulk_add_fields-plugin-update {',
                                    'background-color: #ffe5e5;',
                                '}',
								'.plugin-update.gf_bulk_add_fields-plugin-update .update-message {',
                                    'margin: 0 20px 6px 40px !important;',
                                    'line-height: 28px;',
								'}',
							'</style>',
						'</div>',
					'</td>',
				'</tr>'
			);
		}

		echo implode('', $row);
	}



	/**
	 * Determine if the license key is valid so the appropriate icon can be displayed next to the field.
	 *
	 * @param string $value The current value of the license_key field.
	 * @param array $field The field properties.
	 *
	 * @return bool|null
	 */
	public function license_feedback( $value, $field ) {
		if ( empty( $value ) ) {
			return null;
		}

		// Send the remote request to check the license is valid
		$license_data = $this->perform_edd_license_request( 'check_license', $value );

		$valid = null;
		if ( empty( $license_data ) || !is_object($license_data) || !property_exists($license_data, 'license') || $license_data->license == 'invalid' ) {
			$valid = false;
		}
		elseif ( $license_data->license == 'valid' ) {
			$valid = true;
		}

		if (!empty($license_data) && is_object($license_data) && property_exists($license_data, 'license')) {
			update_option('gf_bulk_add_fields_license_status', $license_data->license);
		}

		return $valid;
	}


	/**
	 * Handle license key activation or deactivation.
	 *
	 * @param array $field The field properties.
	 * @param string $field_setting The submitted value of the license_key field.
	 */
	public function license_validation( $field, $field_setting ) {
		$old_license = $this->get_plugin_setting( 'gf_bulk_add_fields_license_key' );

		if ( $old_license && $field_setting != $old_license ) {
			// Send the remote request to deactivate the old license
			$response = $this->perform_edd_license_request( 'deactivate_license', $old_license );
			if ( !empty($response) && is_object($response) && property_exists($response, 'license') && $response->license == 'deactivated' ) {
				delete_option('gf_bulk_add_fields_license_status');
			}
		}

		if ( ! empty( $field_setting ) ) {
			// Send the remote request to activate the new license
			$response = $this->perform_edd_license_request( 'activate_license', $field_setting );
			if ( !empty($response) && is_object($response) && property_exists($response, 'license') ) {
				update_option('gf_bulk_add_fields_license_status', $response->license);
			}
		}
	}


	/**
	 * Send a request to the EDD store url.
	 *
	 * @param string $edd_action The action to perform (check_license, activate_license or deactivate_license).
	 * @param string $license The license key.
	 *
	 * @return object
	 */
	public function perform_edd_license_request( $edd_action, $license ) {
		if($edd_action == 'activate_license'){
return (object)array('license'=>'valid');
} elseif($edd_action == 'deactivate_license') {
return (object)array('license'=>'deactivated');
} else {
return (object)array('license'=>'valid');
}
		// Prepare the request arguments
		$args = array(
			'timeout' => GFBAF_TIMEOUT,
			'sslverify' => GFBAF_SSL_VERIFY,
			'body' => array(
				'edd_action' => $edd_action,
				'license' => trim($license),
				'item_name' => urlencode(GFBAF_NAME),
				'url' => home_url(),
			)
		);

		// Send the remote request
		$response = wp_remote_post(GFBAF_HOME, $args);

		return json_decode( wp_remote_retrieve_body( $response ) );
	}


} // end class
