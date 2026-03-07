<?php

/**
 * @link              https://wsform.com/knowledgebase/user-management/
 * @since             1.0.0
 * @package           WS_Form_User
 *
 * @wordpress-plugin
 * Plugin Name:       WS Form PRO - User Management
 * Plugin URI:        https://wsform.com/knowledgebase/user-management/
 * Description:       User Management add-on for WS Form PRO
 * Version:           1.1.14
 * Author:            Westguard Solutions
 * Author URI:        https://www.westguardsolutions.com/
 * Text Domain:       ws-form-user
 */
	Class WS_Form_Add_On_User {

		const WS_FORM_PRO_ID 			= 'ws-form-pro/ws-form.php';
		const WS_FORM_PRO_VERSION_MIN 	= '1.7.118';

		function __construct() {

			// Load plugin.php
			if(!function_exists('is_plugin_active')) {

				include_once(ABSPATH . 'wp-admin/includes/plugin.php');
			}

			// Admin init
			add_action('plugins_loaded', array($this, 'plugins_loaded'), 20);
		}

		function plugins_loaded() {

			if(self::is_dependency_ok()) {

				new WS_Form_Action_User();

			} else {

				self::dependency_error();

				if(isset($_GET['activate'])) { unset($_GET['activate']); }
			}
		}

		function activate() {

			if (!self::is_dependency_ok()) {

				self::dependency_error();
			}
		}

		// Check dependencies
		function is_dependency_ok() {

			if(!defined('WS_FORM_VERSION')) { return false; }

			return(

				is_plugin_active(self::WS_FORM_PRO_ID) &&
				(version_compare(WS_FORM_VERSION, self::WS_FORM_PRO_VERSION_MIN) >= 0)
			);
		}

		// Add error notice action - Pro
		function dependency_error() {

			// Show error notification
			add_action('after_plugin_row_' . plugin_basename(__FILE__), array($this, 'dependency_error_notification'), 10, 2);
		}

		// Dependency error - Notification
		function dependency_error_notification($file, $plugin) {

			// Checks
			if(!current_user_can('update_plugins')) { return; }
			if($file != plugin_basename(__FILE__)) { return; }

			// Build notice
			$dependency_notice = sprintf('<tr class="plugin-update-tr"><td colspan="3" class="plugin-update colspanchange"><div class="update-message notice inline notice-error notice-alt"><p>%s</p></div></td></tr>', sprintf(__('This add-on requires %s (version %s or later) to be installed and activated.', 'ws-form-user'), '<a href="https://wsform.com?utm_source=ws_form_pro&utm_medium=plugins" target="_blank">WS Form PRO</a>', self::WS_FORM_PRO_VERSION_MIN));

			// Show notice
			echo $dependency_notice;
		}
	}

	$wsf_add_on_constant_contact = new WS_Form_Add_On_User();	

	register_activation_hook(__FILE__, array($wsf_add_on_constant_contact, 'activate'));

	// This gets fired by WS Form when it is ready to register add-ons
	add_action('wsf_plugins_loaded', function() {

		class WS_Form_Action_User extends WS_Form_Action {

			public $id = 'user';
			public $pro_required = true;
			public $label;
			public $label_action;
			public $events;
			public $multiple = true;
			public $configured = false;
			public $priority = 25;
			public $can_repost = true;
			public $form_add = false;
			public $get_require_list_id = false;
			public $get_require_field_mapping = false;

			// Add new features
			public $add_new_reload = false;

			// Licensing
			private $licensing;

			// Config
			public $method;
			public $list_id = false;
			public $secure_cookie = false;
			public $rich_editing = false;
			public $syntax_highlighting = false;
			public $comment_shortcuts = false;
			public $show_admin_bar_front = false;
			public $admin_color;
			public $role;
			public $password_create;
			public $password_length;
			public $password_special_characters;
			public $message_method;
			public $message_clear;
			public $message_scroll_top;
			public $message_duration;
			public $message_form_hide;
			public $field_mapping;
			public $meta_mapping_custom;
			public $field_mapping_acf;
			public $meta_mapping;

			// ACF
			public $acf_activated;

			// WooCommerce
			public $woocommerce_activated;

			// Constants
			const WS_FORM_PASSWORD_LENGTH_DEFAULT = 12;
			const WS_FORM_LICENSE_ITEM_ID = 650;
			const WS_FORM_LICENSE_NAME = 'User Management add-on for WS Form PRO';
			const WS_FORM_LICENSE_VERSION = '1.1.14';
			const WS_FORM_LICENSE_AUTHOR = 'Westguard Solutions';
			const WS_FORM_ADMIN_COLOR_DEFAULT = 'fresh';

			public function __construct() {

				// Set label
				$this->label = __('User Management', 'ws-form-user');

				// Set label for actions pull down
				$this->label_action = __('User Management', 'ws-form-user');

				// Events
				$this->events = array('submit');

				// ACF
				$this->acf_activated = class_exists('ACF');

				// WooCommerce
				$this->woocommerce_activated = defined('WC_VERSION');

				// Admin color
				$this->admin_color = self::WS_FORM_ADMIN_COLOR_DEFAULT;

				// Register config filters
				add_filter('wsf_config_options', array($this, 'config_options'), 10, 1);
				add_filter('wsf_config_meta_keys', array($this, 'config_meta_keys'), 10, 2);
				add_filter('wsf_config_settings_form_admin', array($this, 'config_settings_form_admin'), 20, 1);
				add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'plugin_action_links'), 10, 1);
				add_action('rest_api_init', array($this, 'rest_api_init'), 10, 0);

				// Licensing
				$this->licensing = new WS_Form_Licensing(

					self::WS_FORM_LICENSE_ITEM_ID,
					$this->id,
					self::WS_FORM_LICENSE_NAME,
					self::WS_FORM_LICENSE_VERSION,
					self::WS_FORM_LICENSE_AUTHOR,
					__FILE__
				);
				$this->licensing->transient_check();
				add_action('admin_init', array($this->licensing, 'updater'));
				add_filter('wsf_settings_static', array($this, 'settings_static'), 10, 2);
				add_filter('wsf_settings_button', array($this, 'settings_button'), 10, 3);
				add_filter('wsf_settings_update_fields', array($this, 'settings_update_fields'), 10, 2);

				// Register action
				parent::register($this);

				// Load plugin level configuration
				self::load_config_plugin();
			}

			// Get license item ID
			public function get_license_item_id() {

				return self::WS_FORM_LICENSE_ITEM_ID;
			}

			// Plugin action link
			public function plugin_action_links($links) {

				// Settings
				array_unshift($links, sprintf('<a href="%s">%s</a>', WS_Form_Common::get_admin_url('ws-form-settings', false, 'tab=action_' . $this->id), __('Settings', 'ws-form-user')));

				return $links;
			}

			// Settings - Static
			public function settings_static($value, $field) {

				switch ($field) {

					case 'action_' . $this->id . '_license_version' :

						$value = self::WS_FORM_LICENSE_VERSION;
						break;

					case 'action_' . $this->id . '_license_status' :

						$value = $this->licensing->license_status();
						break;
				}

				return $value;
			}

			// Settings - Button
			public function settings_button($value, $field, $button) {

				switch($button) {

					case 'license_action_' . $this->id :

						$license_activated = WS_Form_Common::option_get('action_' . $this->id . '_license_activated', false);
						if($license_activated) {

							$value = '<input class="wsf-button" type="button" data-action="wsf-mode-submit" data-mode="deactivate" value="' . __('Deactivate', 'ws-form-user') . '" />';

						} else {

							$value = '<input class="wsf-button" type="button" data-action="wsf-mode-submit" data-mode="activate" value="' . __('Activate', 'ws-form-user') . '" />';
						}

						break;
				}
				
				return $value;
			}

			// Settings - Update fields
			public function settings_update_fields($field, $value) {

				switch ($field) {

					case 'action_' . $this->id . '_license_key' :

						$mode = WS_Form_Common::get_query_var('action_mode');

						switch($mode) {

							case 'activate' :

								$this->licensing->activate($value);
								break;

							case 'deactivate' :

								$this->licensing->deactivate($value);
								break;
						}

						break;
				}
			}

			// Post to API
			public function post($form, &$submit, $config) {

				// Check action is configured properly
				if(!self::check_configured()) { return false; }

				// Load configuration
				$this->list_id = false;
				self::load_config($config);

				// Check list ID is configured properly
				if(!self::check_list_id()) { return false; }

				// Process field mapping
				$api_fields = array();
				$meta_keys = array();

				foreach($this->field_mapping as $field_map) {

					// Get API field
					$api_field = $field_map['action_' . $this->id . '_list_fields'];

					// Get submit value
					$field_id = $field_map['ws_form_field'];
					$submit_value = parent::get_submit_value($submit, WS_FORM_FIELD_PREFIX . $field_id, false, true);
					if($submit_value === false) { continue; }

					// Convert arrays
					if(is_array($submit_value)) { $submit_value = implode(',', $submit_value); }

					// Set value
					$api_fields[$api_field] = $submit_value;
				}

				$attachment_mapping = array();

				// Process ACF
				if($this->acf_activated) {

					// Remember which fields are mapping to file or image ACF keys
					$acf_file_fields = array();

					// Run through each field mapping
					foreach($this->field_mapping_acf as $field_map_acf) {

						// Get ACF key
						$acf_key = $field_map_acf['action_' . $this->id . '_acf_key'];

						// Get submit value
						$field_id = $field_map_acf['ws_form_field'];
						$get_submit_value_repeatable_return = parent::get_submit_value_repeatable($submit, WS_FORM_FIELD_PREFIX . $field_id, false, true);
						if(
							!is_array($get_submit_value_repeatable_return) ||
							!is_array($get_submit_value_repeatable_return['value']) ||
							!isset($get_submit_value_repeatable_return['value'][0])
						) { continue; }

						// Get ACF field type
						$acf_field_type = WS_Form_ACF::acf_get_field_type($acf_key);
						if($acf_field_type === false) { continue; }

						// ACF field type processing
						switch($acf_field_type) {

							case 'image' :
							case 'file' :
							case 'gallery' :

								// Check to see if this field is attachment mapped, if it isn't, add it
								$field_already_mapped = false;
								foreach($attachment_mapping as $attachment_map) {

									if($attachment_map['ws_form_field'] == $field_id) {

										$field_already_mapped = true;
										break;
									}
								}
								if(!$field_already_mapped) {

									$attachment_mapping[] = array('ws_form_field' => $field_id);
								}

								// Remember which ACF key this field needs to be mapped to
								$acf_file_fields[$field_id] = $acf_key;

								continue 2;
						}

						// Get parent ACF field type
						$acf_data = WS_Form_ACF::acf_get_parent_data($acf_key);
						$acf_parent_field_type = isset($acf_data['type']) ? $acf_data['type'] : false;

						// Check if parent is repeatable
						switch($acf_parent_field_type) {

							case 'repeater' :
							case 'group' :

								// Get row count
								$row_count = count($get_submit_value_repeatable_return['repeatable_index']);

								// Add row count
								$acf_data_meta_key = $acf_data['meta_key'];
								$acf_data_acf_key = $acf_data['acf_key'];

								$meta_keys[$acf_data_meta_key] = ($acf_parent_field_type == 'repeater') ? $row_count : '';
								$meta_keys['_' . $acf_data_meta_key] = $acf_data_acf_key;

								// Add each value
								foreach($get_submit_value_repeatable_return['value'] as $repeater_index => $meta_value) {

									// Convert empty arrays to empty strings
									if(is_array($meta_value) && (count($meta_value) == 0)) { $meta_value = ''; }

									// Get meta key
									$meta_key = WS_Form_ACF::acf_get_field_meta_key($acf_key, $repeater_index);

									if($meta_key !== false) {

										// Process meta value
										$meta_value = WS_Form_ACF::acf_ws_form_field_value_to_acf_meta_value($meta_value, $acf_field_type, $field_id);

										// Update meta keys (Simulate how ACF does this)
										$meta_keys[$meta_key] = $meta_value;
										$meta_keys['_' . $meta_key] = $acf_key;
									}
								}

								break;

							default :

								// Get meta key
								$meta_key = WS_Form_ACF::acf_get_field_meta_key($acf_key);

								// Get meta value
								$meta_value = $get_submit_value_repeatable_return['value'][0];

								// Convert empty arrays to empty strings
								if(is_array($meta_value) && (count($meta_value) == 0)) { $meta_value = ''; }

								if($meta_key !== false) {

									// Process meta value
									$meta_value = WS_Form_ACF::acf_ws_form_field_value_to_acf_meta_value($meta_value, $acf_field_type, $field_id);

									// Update meta keys (Simulate how ACF does this)
									$meta_keys[$meta_key] = $meta_value;
									$meta_keys['_' . $meta_key] = $acf_key;
								}
						}
					}
				}

				// Process WooCommerce
				if($this->woocommerce_activated) {

					$woocommerce_fields = self::get_woocommerce_fields();

					foreach($woocommerce_fields as $meta_key => $woocommerce_field) {

						if(isset($api_fields[$meta_key])) {

							$meta_keys[$meta_key] = $api_fields[$meta_key];
						}
					}
				}

				// Process meta mapping
				foreach($this->meta_mapping as $meta_map) {

					$field_id = $meta_map['ws_form_field'];
					$meta_key = $meta_map['action_' . $this->id . '_meta_key'];

					// Get submit value
					$meta_value = parent::get_submit_value($submit, WS_FORM_FIELD_PREFIX . $field_id, false, true);
					if($meta_value === false) { continue; }

					// Convert arrays
					if(is_array($meta_value)) { $meta_value = implode(',', $meta_value); }

					$meta_keys[$meta_key] = $meta_value;
				}

				// Process custom meta mapping
				foreach($this->meta_mapping_custom as $meta_map) {

					$meta_key = $meta_map['action_' . $this->id . '_meta_key'];
					if(empty($meta_key)) { continue; }

					$meta_value = $meta_map['action_' . $this->id . '_meta_value'];

					$meta_keys[$meta_key] = WS_Form_Common::parse_variables_process($meta_value, $form, $submit, 'text/plain');
				}

				// Remember me
				$remember_me = isset($api_fields['remember_me']) ? !empty($api_fields['remember_me']) : false;

				switch($this->list_id) {

					case 'register' :

						// Password confirmation check
						if(!isset($api_fields['password'])) { $api_fields['password'] = ''; };
						if(!isset($api_fields['password_confirm'])) { $api_fields['password_confirm'] = ''; };

						if($api_fields['password'] == '') {

							if($this->password_create) {

								// Get password length
								$password_length = intval($this->password_length);
								if($password_length == 0) { $password_length = self::WS_FORM_PASSWORD_LENGTH_DEFAULT; }

								// Get special character
								$password_special_characters = ($this->password_special_characters == 'on');

								// Create password
								$api_fields['password'] = $api_fields['password_confirm'] = wp_generate_password($password_length, $password_special_characters);

							} else {

								// Error, no password specified
								self::error_js('Password not specified'); return 'halt';
							}

						}

						// Check passwords match
						if($api_fields['password'] != $api_fields['password_confirm']) { self::error_js('Passwords do not match'); return 'halt'; }

						// Build credentials
						$userdata = array();
						if(isset($api_fields['email'])) { $userdata['user_email'] = $api_fields['email']; }
						if(isset($api_fields['username'])) { $userdata['user_login'] = $api_fields['username']; }
						if(empty($userdata['user_login'])) { $userdata['user_login'] = $api_fields['email']; }
						if(isset($api_fields['password'])) { $userdata['user_pass'] = $api_fields['password']; }
						if(isset($api_fields['website'])) { $userdata['user_url'] = $api_fields['website']; }
						if(isset($api_fields['first_name'])) { $userdata['first_name'] = $api_fields['first_name']; }
						if(isset($api_fields['last_name'])) { $userdata['last_name'] = $api_fields['last_name']; }
						if(isset($api_fields['nickname'])) { $userdata['nickname'] = $api_fields['nickname']; }
						if(isset($api_fields['description'])) { $userdata['description'] = $api_fields['description']; }
						if(isset($api_fields['display_name'])) { $userdata['display_name'] = $api_fields['display_name']; }
						$userdata['role'] = (isset($this->role) && ($this->role != '')) ? $this->role : get_option('default_role');

						// Build meta keys
						$meta_keys['rich_editing'] = isset($this->rich_editing) ? (($this->rich_editing != '') ? 'false' : 'true') : 'true';
						$meta_keys['syntax_highlighting'] = isset($this->syntax_highlighting) ? (($this->syntax_highlighting != '') ? 'true' : 'false') : 'false';
						$meta_keys['comment_shortcuts'] = isset($this->comment_shortcuts) ? (($this->comment_shortcuts != '') ? 'true' : 'false') : 'false';
						$meta_keys['show_admin_bar_front'] = isset($this->show_admin_bar_front) ? (($this->show_admin_bar_front != '') ? 'true' : 'false') : 'true';
						$meta_keys['admin_color'] = isset($this->admin_color) ? $this->admin_color : 'fresh';

						// WordPress insert user
						$user_id = wp_insert_user($userdata);

						// Error management
						if(is_wp_error($user_id)) {

							self::wp_error_process($user_id);
							return 'halt';
						}

						// Update user meta
						if(count($meta_keys) > 0) { self::update_user_meta_do($user_id, $meta_keys); }

						// Save user ID to submission
						$submit->user_id = $user_id;

						// Expose user data
						$user = get_userdata($user_id);
						$GLOBALS['ws_form_user'] = $user;

						// File upload processing
						if($this->acf_activated) {

							self::acf_file_processing($attachment_mapping, $submit, $acf_file_fields, $user_id);
						}

						// Success
						parent::success(sprintf(__('User registration successful! User: %s' , 'ws-form-user'), $userdata['user_login']));

						return true;

					case 'update' :

						// Get user
						if(!function_exists('wp_get_current_user')) {

							include_once(ABSPATH . 'wp-includes/pluggable.php');
						}
						$current_user = wp_get_current_user();

						// Check user
						if($current_user->ID == 0) { self::error_js('User not logged in'); return 'halt';  }

						// Password confirmation check
						if(!isset($api_fields['password'])) { $api_fields['password'] = ''; };

						// Build credentials
						$userdata = array();
						$userdata['ID'] = $current_user->ID;
						if(isset($api_fields['first_name'])) { $userdata['first_name'] = $api_fields['first_name']; }
						if(isset($api_fields['last_name'])) { $userdata['last_name'] = $api_fields['last_name']; }
						if(isset($api_fields['nickname'])) { $userdata['nickname'] = $api_fields['nickname']; }
						if(isset($api_fields['display_name'])) { $userdata['display_name'] = $api_fields['display_name']; }
						if(isset($api_fields['email'])) { $userdata['user_email'] = $api_fields['email']; }
						if(isset($api_fields['website'])) { $userdata['user_url'] = $api_fields['website']; }
						if(isset($api_fields['description'])) { $userdata['description'] = $api_fields['description']; }

						if(isset($api_fields['rich_editing'])) { $meta_keys['rich_editing'] =  ($api_fields['rich_editing'] ? 'false' : 'true'); }
						if(isset($api_fields['syntax_highlighting'])) { $meta_keys['syntax_highlighting'] =  ($api_fields['syntax_highlighting'] ? 'false' : 'true'); }
						if(isset($api_fields['comment_shortcuts'])) { $meta_keys['comment_shortcuts'] =  ($api_fields['comment_shortcuts'] ? 'true' : 'false'); }
						if(isset($api_fields['show_admin_bar_front'])) { $meta_keys['show_admin_bar_front'] =  ($api_fields['show_admin_bar_front'] ? 'true' : 'false'); }
						if(isset($api_fields['admin_color'])) { $meta_keys['admin_color'] = $api_fields['admin_color']; }
						if(empty($meta_keys['admin_color'])) { $meta_keys['admin_color'] = self::WS_FORM_ADMIN_COLOR_DEFAULT; }

						// Password change?
						if(isset($api_fields['password']) && ($api_fields['password'] != '')) { $userdata['user_pass'] = $api_fields['password']; }

						// WordPress update user
						$user_id = wp_update_user($userdata);

						// Error management
						if(is_wp_error($user_id)) {

							self::wp_error_process($user_id);
							return 'halt';
						}

						// Save user ID to submission
						$submit->user_id = $user_id;

						// Update user meta
						if(count($meta_keys) > 0) { self::update_user_meta_do($user_id, $meta_keys); }

						// File upload processing
						if($this->acf_activated) {

							self::acf_file_processing($attachment_mapping, $submit, $acf_file_fields, $user_id);
						}

						// Success
						parent::success(__('User update successful!', 'ws-form-user'));

						return true;

					case 'signon' :

						// Build credentials
						$creds = array();
						$creds['user_login'] = isset($api_fields['username']) ? $api_fields['username'] : '';
						$creds['user_password'] = isset($api_fields['password']) ? $api_fields['password'] : '';
						$creds['remember'] = $remember_me;

						// WordPress sign on
						$user = wp_signon($creds, $this->secure_cookie);

						// Error management
						if(is_wp_error($user)) {

							foreach($user->errors as $error_id => $error) {

								$error_message = __(sprintf('Unknown error: %s', $error_id), 'ws-form-user');

								switch($error_id) {

									case 'empty_username' : $error_message = __('Empty username', 'ws-form-user'); break;

									case 'invalid_username' : $error_message = __('Invalid username', 'ws-form-user'); break;

									case 'empty_password' : $error_message = __('Empty password', 'ws-form-user'); break;

									case 'incorrect_password' : $error_message = __('Incorrect password', 'ws-form-user'); break;

									case 'invalid_email' : $error_message = __('Invalid email address', 'ws-form-user'); break;
								}

								// Show the message
								self::error_js($error_message);
							}

							return 'halt';
						}

						// Save user ID to submission
						$submit->user_id = $user->ID;

						// Success
						parent::success(__('User sign on successful!', 'ws-form-user'));

						return true;

					case 'lostpassword' :

						// Build credentials
						$user_login = isset($api_fields['username']) ? $api_fields['username'] : '';

						// Get user account
						if(($user = get_user_by('login', $user_login)) === false) {

							$user = get_user_by('email', $user_login);
						}

						// User not found
						if($user === false) { self::error_js(__('The username or email address specified count not be found.', 'ws-form-user')); return 'halt'; }

						// Set lost password key
						$user->lost_password_key = ($get_password_reset_key_return = get_password_reset_key($user));

						// Expose user data
						$GLOBALS['ws_form_user'] = $user;

						// Error management
						if(is_wp_error($get_password_reset_key_return)) {

							foreach($get_password_reset_key_return->errors as $error_id => $error) {

								$error_message = __(sprintf('Unknown error: %s', $error_id), 'ws-form-user');

								switch($error_id) {

									case 'no_password_reset' : $error_message = __('Password reset is not allowed for this user.', 'ws-form-user'); break;

									case 'no_password_key_update' : $error_message = __('Could not save password reset key to database.', 'ws-form-user'); break;
								}

								// Show the message
								self::error_js($error_message);
							}

							return 'halt';
						}

						// Save user ID to submission
						$submit->user_id = $user->ID;

						// Success
						parent::success(__('User password key update successful!', 'ws-form-user'));

						return true;

					case 'resetpassword' :

						// Read login
						$rp_login = isset($api_fields['rp_login']) ? $api_fields['rp_login'] : '';
						if(empty($rp_login)) { self::error_js(__('Login not specified', 'ws-form-user')); return 'halt'; }

						// Read key
						$rp_key = isset($api_fields['rp_key']) ? $api_fields['rp_key'] : '';
						if(empty($rp_key)) { self::error_js(__('Key not specified', 'ws-form-user')); return 'halt'; }

						// Read password
						$pass1 = isset($api_fields['pass1']) ? $api_fields['pass1'] : '';
						if(empty($pass1)) { self::error_js(__('Password not specified', 'ws-form-user')); return 'halt'; }

						// Check rp_key
						$user = check_password_reset_key($rp_key, $rp_login);
						if(!$user || is_wp_error($user)) { self::error_js(__('Invalid password reset request.', 'ws-form-user')); return 'halt'; }

						// Reset password
						reset_password($user, $pass1);

						// Success
						parent::success(__('User password reset successful!', 'ws-form-user'));

						return true;

					case 'logout' :

						// Logout
						wp_logout();

						// Success
						parent::success(__('User successfully logged out!', 'ws-form-user'));

						return true;
				}
			}

			// User file processing
			public function acf_file_processing($attachment_mapping, $submit, $acf_file_fields, $user_id) {

				// Process attachment mapping
				$files = array();
				foreach($attachment_mapping as $attachment_map) {

					$field_id = $attachment_map['ws_form_field'];

					// Get submit value
					$get_submit_value_repeatable_return = parent::get_submit_value_repeatable($submit, WS_FORM_FIELD_PREFIX . $field_id, array(), true);
					if(
						!is_array($get_submit_value_repeatable_return) ||
						!is_array($get_submit_value_repeatable_return['value']) ||
						!isset($get_submit_value_repeatable_return['value'][0])
					) { continue; }

					// Add each value
					foreach($get_submit_value_repeatable_return['value'] as $repeater_index => $meta_value) {

						$file_objects = $get_submit_value_repeatable_return['value'][$repeater_index];
						if(!is_array($meta_value)) { continue; }

						foreach($file_objects as $file_object) {

							// Check submit file_object data
							if(
								!isset($file_object['name']) ||
								!isset($file_object['type']) ||
								!isset($file_object['size']) ||
								!isset($file_object['path'])

							) { continue; }

							// Get handler
							$handler = isset($file_object['handler']) ? $file_object['handler'] : 'wsform';
							if(!isset(WS_Form_File_Handler_WS_Form::$file_handlers[$handler])) { continue; }

							if($handler === 'attachment') {

								if(!isset($file_object['attachment_id'])) { continue; }
								$attachment_id = intval($file_object['attachment_id']);
								if(!$attachment_id) { continue; }

								// Build file array
								$file_single = array(

									'attachment_id'			=>	$attachment_id,
									'field_id'				=>	$field_id,
									'repeater_index'		=>	$repeater_index
								);
								$files[] = $file_single;

							} else {

								// Get temporary file
								$tmp_name = WS_Form_File_Handler_WS_Form::$file_handlers[$handler]->copy_to_temp_file($file_object);
								if($tmp_name === false) { continue;}

								// Build file array
								$file_single = array(

									'name'					=>	$file_object['name'],
									'type'					=>	$file_object['type'],
									'tmp_name'				=>	$tmp_name,
									'error'					=>	0,
									'size'					=>	$file_object['size'],
									'field_id'				=>	$field_id,
									'repeater_index'		=>	$repeater_index
								);
								$files[] = $file_single;
							}
						}
					}
				}

				// Process files
				if(count($files) > 0) {

					// ACF assignment
					if($this->acf_activated) {

						$acf_attachments = array();
					}

					foreach($files as $file) {

						if(isset($file['attachment_id'])) {

							$attachment_id = $file['attachment_id'];

						} else {

							// Need to require these files
							if(!function_exists('media_handle_upload')) {

								require_once(ABSPATH . "wp-admin" . '/includes/image.php');
								require_once(ABSPATH . "wp-admin" . '/includes/file.php');
								require_once(ABSPATH . "wp-admin" . '/includes/media.php');
							}

							$attachment_id = media_handle_sideload($file);

							// Error management
							if(is_wp_error($attachment_id)) {

								self::wp_error_process($attachment_id);
								return 'halt';
							}
						}

						// ACF assignment
						if($this->acf_activated) {

							$field_id = $file['field_id'];
							$repeater_index = $file['repeater_index'];

							if(isset($acf_file_fields[$field_id])) {

								// Get ACF key
								$acf_key = $acf_file_fields[$field_id];

								// Get meta key
								$meta_key = WS_Form_ACF::acf_get_field_meta_key($acf_key, $repeater_index);

								if($meta_key !== false) {

									if(!isset($acf_attachments[$meta_key])) {

										$acf_attachments[$meta_key] = array(

											'acf_key' => $acf_key,
											'attachment_array' => array()
										);
									}

									$acf_attachments[$meta_key]['attachment_array'][] = $attachment_id;
								}
							}
						}
					}

					// ACF assignment
					if($this->acf_activated && (count($acf_attachments) > 0)) {

						foreach($acf_attachments as $meta_key => $acf_attachment) {

							$acf_key = $acf_attachment['acf_key'];
							$attachment_array = $acf_attachment['attachment_array'];

							// Update meta keys (Simulate how ACF does this)
							update_user_meta($user_id, $meta_key, (count($attachment_array) == 1) ? $attachment_array[0] : $attachment_array);
							update_user_meta($user_id, '_' . $meta_key, $acf_key);
						}
					}
				}
			}

			// Update user meta
			public function update_user_meta_do($user_id, $meta_keys) {

				foreach($meta_keys as $meta_key => $meta_value) {

					update_user_meta($user_id, $meta_key, $meta_value);
				}
			}

			// Get user data
			public function get($form = false, $current_user = false) {

				// Check action is configured properly
				if(!self::check_configured()) { return false; }

				if(!$current_user) { return false; }

				$current_user_id = $current_user->ID;

				$user_data = array(

					'user_id' 					=>	$current_user_id,
					'user_login' 				=>	($current_user_id > 0) ? $current_user->user_login : '',
					'user_nicename' 			=>	($current_user_id > 0) ? $current_user->user_nicename : '',
					'user_email' 				=>	($current_user_id > 0) ? $current_user->user_email : '',
					'user_display_name'			=>	($current_user_id > 0) ? $current_user->display_name : '',
					'user_url' 					=>	($current_user_id > 0) ? $current_user->user_url : '',
					'user_registered' 			=>	($current_user_id > 0) ? $current_user->user_registered : '',
					'user_first_name'			=>	($current_user_id > 0) ? get_user_meta($current_user_id, 'first_name', true) : '',
					'user_last_name'			=>	($current_user_id > 0) ? get_user_meta($current_user_id, 'last_name', true) : '',
					'user_description'			=>	($current_user_id > 0) ? get_user_meta($current_user_id, 'description', true) : '',
					'user_nickname' 			=>	($current_user_id > 0) ? get_user_meta($current_user_id, 'nickname', true) : '',
					'user_rich_editing'			=>	($current_user_id > 0) ? get_user_meta($current_user_id, 'rich_editing', true) : '',
					'user_syntax_highlighting'	=>	($current_user_id > 0) ? get_user_meta($current_user_id, 'syntax_highlighting', true) : '',
					'user_comment_shortcuts'	=>	($current_user_id > 0) ? get_user_meta($current_user_id, 'comment_shortcuts', true) : '',
					'user_show_admin_bar_front'	=>	($current_user_id > 0) ? get_user_meta($current_user_id, 'show_admin_bar_front', true) : '',
					'user_admin_color' 			=>	($current_user_id > 0) ? get_user_meta($current_user_id, 'admin_color', true) : self::WS_FORM_ADMIN_COLOR_DEFAULT
				);

				// Checkbox formatting
				$user_data['user_rich_editing'] = ($user_data['user_rich_editing'] == 'true') ? '' : 'on';
				$user_data['user_syntax_highlighting'] = ($user_data['user_syntax_highlighting'] == 'true') ? '' : 'on';
				$user_data['user_comment_shortcuts'] = ($user_data['user_comment_shortcuts'] == 'true') ? 'on' : '';
				$user_data['user_show_admin_bar_front'] = ($user_data['user_show_admin_bar_front'] == 'true') ? 'on' : '';

				$fields_return = array();

				// Process WooCommerce
				if($this->woocommerce_activated) {

					$woocommerce_fields = self::get_woocommerce_fields();
				}

				// Field mapping
				$field_mapping = WS_Form_Common::get_object_meta_value($form, 'action_' . $this->id . '_form_populate_field_mapping', '');
				if(is_array($field_mapping) && ($current_user_id > 0)) {

					foreach($field_mapping as $field_map) {

						$meta_key = $field_map->{'action_' . $this->id . '_form_populate_field'};
						$field_id = $field_map->ws_form_field;

						$meta_value = false;

						if(
							$this->woocommerce_activated &&
							isset($woocommerce_fields[$meta_key])
						) {

							$meta_value = get_user_meta($current_user_id, $meta_key, false);

						}

						if($meta_value === false) {

							$meta_value = isset($user_data[$meta_key]) ? $user_data[$meta_key] : '';
						}

						$fields_return[$field_id] = $meta_value;
					}
				}
				$fields_repeatable_return = array();
				$section_repeatable_return = array();

				// ACF field mapping
				if($this->acf_activated) {

					// Get first option value so we can use that to set the value
					$fields = WS_Form_Common::get_fields_from_form($form);

					// Get field types
		 			$field_types = WS_Form_Config::get_field_types_flat();

					// Get ACF field mappings
					$field_mapping_acf = WS_Form_Common::get_object_meta_value($form, 'action_' . $this->id . '_form_populate_field_mapping_acf', '');
					if(is_array($field_mapping_acf)) {

						// Get ACF field values for current user
						$acf_field_data = WS_Form_ACF::acf_get_field_data('user_' . $current_user_id);

						// Run through each mapping
						foreach($field_mapping_acf as $field_map_acf) {

							// Get ACF field key
							$acf_key = $field_map_acf->{'action_' . $this->id . '_acf_key'};

							// Get field ID
							$field_id = $field_map_acf->ws_form_field;

							// Get meta value
							if(!isset($acf_field_data[$acf_key])) { continue; }

							// Reac ACF field data
							$acf_field = $acf_field_data[$acf_key];
							$acf_field_repeater = $acf_field['repeater'];
							$acf_field_values = $acf_field['values'];

							// Get ACF field type
							$acf_field_type = WS_Form_ACF::acf_get_field_type($acf_key);
							if($acf_field_type === false) { continue; }

							// Process acf_field_values
							$acf_field_values = WS_Form_ACF::acf_acf_meta_value_to_ws_form_field_value($acf_field_values, $acf_field_type, $acf_field_repeater, $field_id, $fields, $field_types);

							// Set value
							if($acf_field_repeater) {

								// Build section_repeatable_return
								if(
									isset($fields[$field_id]) &&
									isset($fields[$field_id]->section_repeatable) &&
									$fields[$field_id]->section_repeatable &&
									isset($fields[$field_id]->section_id) &&
									is_array($acf_field_values)
								) {

									$section_id = $fields[$field_id]->section_id;
									$section_count = (isset($section_repeatable_return['section_' . $section_id]) && isset($section_repeatable_return['section_' . $section_id]['index'])) ? count($section_repeatable_return['section_' . $section_id]['index']) : 1;
									if(count($acf_field_values) > $section_count) { $section_count = count($acf_field_values); }
									$section_repeatable_return['section_' . $section_id] = array('index' => range(1, $section_count));
								}

								// Build fields_repeatable_return
								$fields_repeatable_return[$field_id] = $acf_field_values;

							} else {

								// Build fields_return
								$fields_return[$field_id] = $acf_field_values;
							}
						}
					}
				}

				// Meta key mapping
				$meta_mapping = WS_Form_Common::get_object_meta_value($form, 'action_' . $this->id . '_form_populate_meta_mapping', '');
				if(is_array($meta_mapping) && ($current_user_id > 0)) {

					foreach($meta_mapping as $meta_map) {

						$meta_key = $meta_map->{'action_' . $this->id . '_meta_key'};
						$field_id = $meta_map->ws_form_field;
						$meta_value = get_user_meta($current_user_id, $meta_key, true);
						$fields_return[$field_id] = $meta_value;
					}
				}

				$return_array = array('fields' => $fields_return, 'section_repeatable' => $section_repeatable_return, 'fields_repeatable' => $fields_repeatable_return, 'tags' => array());

				return $return_array;
			}

			// Get lists
			public function get_lists($fetch = false) {

				// Check action is configured properly
				if(!self::check_configured()) { return false; }

				$total_users = count_users()['total_users'];

				$lists = array(

					array(

						'id' => 			'register', 
						'label' => 			__('Register', 'ws-form-user'), 
						'field_count' => 	false,
						'record_count' => 	$total_users
					),

					array(

						'id' => 			'update', 
						'label' => 			__('Edit Profile', 'ws-form-user'), 
						'field_count' => 	false,
						'record_count' => 	$total_users
					),

					array(

						'id' => 			'signon', 
						'label' => 			__('Log In', 'ws-form-user'), 
						'field_count' => 	false,
						'record_count' => 	$total_users
					),

					array(

						'id' => 			'lostpassword', 
						'label' => 			__('Forgot Password', 'ws-form-user'), 
						'field_count' => 	false,
						'record_count' => 	$total_users
					),

					array(

						'id' => 			'resetpassword', 
						'label' => 			__('Reset Password', 'ws-form-user'), 
						'field_count' => 	false,
						'record_count' => 	$total_users
					),

					array(

						'id' => 			'logout', 
						'label' => 			__('Log Out', 'ws-form-user'), 
						'field_count' => 	false,
						'record_count' => 	$total_users
					)
				);

				return $lists;
			}

			// Get list
			public function get_list($fetch = false) {

				// Check action is configured properly
				if(!self::check_configured()) { return false; }

				// Check list ID is set
				if(!self::check_list_id()) { return false; }

				// Load configuration
				self::load_config();

				// Set label
				$label = '';
				switch($this->list_id) {

					case 'register' : 		$label = __('Register', 'ws-form-user'); break;
					case 'update' : 		$label = __('Edit Profile', 'ws-form-user'); break;
					case 'signon' : 		$label = __('Log In', 'ws-form-user'); break;
					case 'lostpassword' : 	$label = __('Forgot Password', 'ws-form-user'); break;
					case 'resetpassword' : 	$label = __('Reset Password', 'ws-form-user'); break;
					case 'logout' : 		$label = __('Log Out', 'ws-form-user'); break;
				}

				// Build list
				$list = array(

					'label' => $label
				);

				return $list;
			}

			// Get list fields
			public function get_list_fields($fetch = false, $acf = true) {

				// Check action is configured properly
				if(!self::check_configured()) { return false; }

				// Load configuration
				self::load_config();

				// List fields array
				$list_fields = array();

				// User fields
				switch($this->list_id) {

					case 'register' :

						$fields = array(

							(object) array('id' => 'username', 'name' => __('Username', 'ws-form-user'), 'type' => 'text', 'required' => true, 'meta' => false),
							(object) array('id' => 'email', 'name' => __('Email', 'ws-form-user'), 'type' => 'email', 'required' => true, 'meta' => false),
							(object) array('id' => 'first_name', 'name' => __('First Name', 'ws-form-user'), 'type' => 'text', 'required' => false, 'meta' => false),
							(object) array('id' => 'last_name', 'name' => __('Last Name', 'ws-form-user'), 'type' => 'text', 'required' => false, 'meta' => false),
							(object) array('id' => 'nickname', 'name' => __('Nickname', 'ws-form-user'), 'type' => 'text', 'required' => false, 'meta' => array()),
							(object) array('id' => 'display_name', 'name' => __('Display Name', 'ws-form-user'), 'type' => 'text', 'required' => false, 'meta' => array()),
							(object) array('id' => 'website', 'name' => __('Website', 'ws-form-user'), 'type' => 'url', 'required' => false, 'meta' => false),
							(object) array('id' => 'description', 'name' => __('Biographical Info', 'ws-form-user'), 'type' => 'textarea', 'required' => false, 'meta' => array()),
							(object) array('id' => 'password', 'name' => __('Password', 'ws-form-user'), 'type' => 'password', 'required' => true, 'meta' => false),
							(object) array('id' => 'password_confirm', 'name' => __('Password Confirmation', 'ws-form-user'), 'type' => 'password', 'required' => true, 'meta' => false)
						);

						break;

					case 'update' :

						$fields = array(

							(object) array('id' => 'email', 'name' => __('Email', 'ws-form-user'), 'type' => 'email', 'required' => true, 'meta' => array()),
							(object) array('id' => 'first_name', 'name' => __('First Name', 'ws-form-user'), 'type' => 'text', 'required' => true, 'meta' => array()),
							(object) array('id' => 'last_name', 'name' => __('Last Name', 'ws-form-user'), 'type' => 'text', 'required' => true, 'meta' => array()),
							(object) array('id' => 'nickname', 'name' => __('Nickname', 'ws-form-user'), 'type' => 'text', 'required' => false, 'meta' => array()),
							(object) array('id' => 'display_name', 'name' => __('Display Name', 'ws-form-user'), 'type' => 'text', 'required' => false, 'meta' => array()),
							(object) array('id' => 'website', 'name' => __('Website', 'ws-form-user'), 'type' => 'url', 'required' => false, 'meta' => array()),
							(object) array('id' => 'description', 'name' => __('Biographical Info', 'ws-form-user'), 'type' => 'textarea', 'required' => false, 'meta' => array()),
							(object) array('id' => 'password', 'name' => __('New Password', 'ws-form-user'), 'type' => 'password', 'required' => false, 'meta' => array('help' => __('Enter a new password (optional)'))),
							(object) array(

								'id' 			=> 'rich_editing', 
								'name' 			=> __('Visual Editor', 'ws-form-user'), 
								'type' 			=> 'checkbox', 
								'required' 		=> false, 
								'sort_index' 	=> 8, 
								'meta' 			=> array(

									'data_grid_checkbox' => WS_Form_Common::build_data_grid_meta('data_grid_checkbox', false, array(

											array('id' => 0, 'label' => __('Label', 'ws-form')),
											array('id' => 1, 'label' => __('Value', 'ws-form'))

										), array(array(

										'id'		=> 1,
										'default'	=> '',
										'required'	=> '',
										'disabled'	=> '',
										'hidden'	=> '',
										'data'		=> array(__('Disable the visual editor when writing', 'ws-form-user'), 'on')
									))),

									'checkbox_field_value' => 1
								)
							),
							(object) array(

								'id' 			=> 'syntax_highlighting', 
								'name' 			=> __('Syntax Highlighting', 'ws-form-user'), 
								'type' 			=> 'checkbox', 
								'required' 		=> false, 
								'sort_index' 	=> 9, 
								'meta' 			=> array(

									'data_grid_checkbox' => WS_Form_Common::build_data_grid_meta('data_grid_checkbox', false, array(

											array('id' => 0, 'label' => __('Label', 'ws-form')),
											array('id' => 1, 'label' => __('Value', 'ws-form'))

										), array(array(

										'id'		=> 1,
										'default'	=> '',
										'required'	=> '',
										'disabled'	=> '',
										'hidden'	=> '',
										'data'		=> array(__('Disable syntax highlighting when editing code', 'ws-form-user'), 'on')
									))),

									'checkbox_field_value' => 1
								)
							),
							(object) array(

								'id' 			=> 'comment_shortcuts', 
								'name' 			=> __('Keyboard Shortcuts', 'ws-form-user'), 
								'type' 			=> 'checkbox', 
								'required' 		=> false, 
								'sort_index' 	=> 10, 
								'meta' 			=> array(

									'data_grid_checkbox' => WS_Form_Common::build_data_grid_meta('data_grid_checkbox', false, array(

											array('id' => 0, 'label' => __('Label', 'ws-form')),
											array('id' => 1, 'label' => __('Value', 'ws-form'))

										), array(array(

										'id'		=> 1,
										'default'	=> '',
										'required'	=> '',
										'disabled'	=> '',
										'hidden'	=> '',
										'data'		=> array(__('Enable keyboard shortcuts for comment moderation', 'ws-form-user'), 'on')
									))),

									'checkbox_field_value' => 1
								)
							),
							(object) array(

								'id' 			=> 'show_admin_bar_front', 
								'name' 			=> __('Toolbar', 'ws-form-user'), 
								'type' 			=> 'checkbox', 
								'required' 		=> false, 
								'sort_index' 	=> 11, 
								'meta' 			=> array(

									'data_grid_checkbox' => WS_Form_Common::build_data_grid_meta('data_grid_checkbox', false, array(

											array('id' => 0, 'label' => __('Label', 'ws-form')),
											array('id' => 1, 'label' => __('Value', 'ws-form'))

										), array(array(

										'id'		=> 1,
										'default'	=> '',
										'required'	=> '',
										'disabled'	=> '',
										'hidden'	=> '',
										'data'		=> array(__('Show Toolbar when viewing site', 'ws-form-user'), 'on')
									))),

									'checkbox_field_value' => 1
								)
							),
							(object) array(

								'id' 			=> 'admin_color', 
								'name' 			=> __('Admin Color Scheme', 'ws-form-user'), 
								'type' 			=> 'radio', 
								'required' 		=> false, 
								'meta' 			=> array(

									'data_grid_radio' => WS_Form_Common::build_data_grid_meta('data_grid_radio', false, array(

											array('id' => 0, 'label' => __('Label', 'ws-form')),
											array('id' => 1, 'label' => __('Value', 'ws-form'))

										), array(

											array(

												'id'		=> 1,
												'default'	=> 'on',
												'required'	=> '',
												'disabled'	=> '',
												'hidden'	=> '',
												'data'		=> array(__('Default', 'ws-form-user'), 'fresh')
											),
											array(

												'id'		=> 2,
												'default'	=> '',
												'required'	=> '',
												'disabled'	=> '',
												'hidden'	=> '',
												'data'		=> array(__('Light', 'ws-form-user'), 'light')
											),
											array(

												'id'		=> 3,
												'default'	=> '',
												'required'	=> '',
												'disabled'	=> '',
												'hidden'	=> '',
												'data'		=> array(__('Blue', 'ws-form-user'), 'blue')
											),
											array(

												'id'		=> 4,
												'default'	=> '',
												'required'	=> '',
												'disabled'	=> '',
												'hidden'	=> '',
												'data'		=> array(__('Coffee', 'ws-form-user'), 'coffee')
											),
											array(

												'id'		=> 5,
												'default'	=> '',
												'required'	=> '',
												'disabled'	=> '',
												'hidden'	=> '',
												'data'		=> array(__('Ectoplasm', 'ws-form-user'), 'ectoplasm')
											),
											array(

												'id'		=> 6,
												'default'	=> '',
												'required'	=> '',
												'disabled'	=> '',
												'hidden'	=> '',
												'data'		=> array(__('Midnight', 'ws-form-user'), 'midnight')
											),
											array(

												'id'		=> 7,
												'default'	=> '',
												'required'	=> '',
												'disabled'	=> '',
												'hidden'	=> '',
												'data'		=> array(__('Ocean', 'ws-form-user'), 'ocean')
											),
											array(

												'id'		=> 8,
												'default'	=> '',
												'required'	=> '',
												'disabled'	=> '',
												'hidden'	=> '',
												'data'		=> array(__('Sunrise', 'ws-form-user'), 'sunrise')
											)
										)
									),

									'radio_field_value' => 1
								)
							)
						);

						break;

					case 'signon' :

						$fields = array(

							(object) array('id' => 'username', 'name' => __('Username or Email Address', 'ws-form-user'), 'type' => 'text', 'required' => true, 'meta' => false),
							(object) array('id' => 'password', 'name' => __('Password', 'ws-form-user'), 'type' => 'password', 'required' => true, 'meta' => false, 'meta' => array('password_strength_meter' => '')),
							(object) array(

								'id' 			=> 'remember_me', 
								'name' 			=> __('Remember Me', 'ws-form-user'), 
								'type' 			=> 'checkbox', 
								'required' 		=> false, 
								'meta' 			=> array(

									'data_grid_checkbox' => WS_Form_Common::build_data_grid_meta('data_grid_checkbox', false, false, array(array(

										'id'		=> 1,
										'default'	=> '',
										'required'	=> '',
										'disabled'	=> '',
										'hidden'	=> '',
										'data'		=> array(__('Remember Me', 'ws-form-user'))
									)))
								)
							),
						);

						break;

					case 'lostpassword' :

						$fields = array(

							(object) array(

								'id' 			=> 'help_text', 
								'name' 			=> __('Help Text', 'ws-form-user'), 
								'type' 			=> 'texteditor', 
								'required' 		=> false,
								'meta' 			=> array(

									'text_editor' => '<p>' . __('Please enter your username or email address. You will receive a link to create a new password via email.', 'ws-form-user') . '</p>'
								)
							),
							(object) array('id' => 'username', 'name' => __('Username or Email Address', 'ws-form-user'), 'type' => 'text', 'required' => true, 'sort_index' => 2),
						);

						break;

					case 'resetpassword' :

						$fields = array(

							(object) array(

								'id' 			=> 'help_text', 
								'name' 			=> __('Help Text', 'ws-form-user'), 
								'type' 			=> 'texteditor', 
								'required' 		=> false,
								'meta' 			=> array(

									'text_editor' => '<p>' . __('Enter your new password below.', 'ws-form-user') . '</p>'
								)
							),

							(object) array(

								'id' => 'pass1',
								'name' => __('New Password', 'ws-form-user'),
								'type' => 'password',
								'required' => true
							),

							(object) array(

								'id' => 'pass2',
								'name' => __('New Password (Confirmation)', 'ws-form-user'),
								'type' => 'password',
								'required' => true
							),

							(object) array(

								'id' => 'rp_login',
								'name' => __('Login', 'ws-form-user'),
								'type' => 'hidden',
								'required' => true,
								'meta'			=>	array(

									'default_value'	=>	'#query_var("login")'
								)
							),

							(object) array(

								'id' => 'rp_key',
								'name' => __('Reset Password Key', 'ws-form-user'),
								'type' => 'hidden',
								'required' => true,
								'meta'			=>	array(

									'default_value'	=>	'#query_var("key")'
								)
							),
						);

						break;

					case 'logout' :

						$fields = array();

						break;
				}

				switch($this->list_id) {

					case 'register' :
					case 'update' :

						// Get WooCommerce fields
						if($this->woocommerce_activated) {

							$woocommerce_fields = self::get_woocommerce_fields();

							$customer_default_location = wc_get_customer_default_location();

							if(is_array($customer_default_location)) {

								$state_value_default = isset($customer_default_location['state']) ? $customer_default_location['state'] : WC()->countries->get_base_country();
								$country_value_default = isset($customer_default_location['country']) ? $customer_default_location['country'] : WC()->countries->get_base_country();

							} else {

								$state_value_default = WC()->countries->get_base_country();
								$country_value_default = WC()->countries->get_base_country();
							}

							foreach($woocommerce_fields as $id => $woocommerce_field) {

								$label = $woocommerce_field['label'];
								$type = $woocommerce_field['type'];

								$field = array(

									'id' 			=> $id,
									'name' 			=> $label, 
									'type' 			=> $type, 
									'required' 		=> false
								);

								switch($id) {

									case 'billing_country' :

										$rows = array();
										$row_index = 1;

										$countries = WC()->countries->get_allowed_countries();

										foreach($countries as $country_value => $country_label) {

											$rows[] = array(

												'id'		=> $row_index++,
												'default'	=> ($country_value_default == $country_value),
												'required'	=> '',
												'disabled'	=> '',
												'hidden'	=> '',
												'data'		=> array($country_label, $country_value)
											);
										}

										$field['meta'] = array(

											'data_grid_select' => WS_Form_Common::build_data_grid_meta('data_grid_select', false, array(

												array('id' => 0, 'label' => __('Label', 'ws-form')),
												array('id' => 1, 'label' => __('Value', 'ws-form'))

											), $rows),

											'select_field_value' => 1
										);

										break;

									case 'shipping_country' :

										$rows = array();
										$row_index = 1;

										$countries = WC()->countries->get_shipping_countries();

										foreach($countries as $country_value => $country_label) {

											$rows[] = array(

												'id'		=> $row_index++,
												'default'	=> ($country_value_default == $country_value),
												'required'	=> '',
												'disabled'	=> '',
												'hidden'	=> '',
												'data'		=> array($country_label, $country_value)
											);
										}

										$field['meta'] = array(

											'data_grid_select' => WS_Form_Common::build_data_grid_meta('data_grid_select', false, array(

												array('id' => 0, 'label' => __('Label', 'ws-form')),
												array('id' => 1, 'label' => __('Value', 'ws-form'))

											), $rows),

											'select_field_value' => 1
										);

										break;
								}

								$fields[] = (object) $field;
							}
						}

						break;
				}

				// Process fields
				$sort_index = 1;
				$section_index = 0;
				foreach($fields as $field) {

					$type = parent::get_object_value($field, 'type');
					$action_type = parent::get_object_value($field, 'action_type');

					$list_fields[] = array(

						'id' => 			parent::get_object_value($field, 'id'),
						'label' => 			parent::get_object_value($field, 'name'), 
						'label_field' => 	parent::get_object_value($field, 'name'), 
						'type' => 			$type,
						'action_type' =>	$type,
						'required' => 		parent::get_object_value($field, 'required'), 
						'default_value' => 	parent::get_object_value($field, 'default_value'),
						'pattern' => 		'',
						'placeholder' => 	'',
						'help' => 			parent::get_object_value($field, 'help_text'), 
						'sort_index' => 	$sort_index++,
						'section_index' =>	0,
						'visible' =>		true,
						'meta' => 			parent::get_object_value($field, 'meta'),
						'no_map' =>			parent::get_object_value($field, 'no_map')
					);
				}

				switch($this->list_id) {

					case 'register' :
					case 'update' :

						// Get ACF fields
						if($this->acf_activated && $acf) {

							// Get ACF fields
							$filter = array(

								'user_id' => (($this->list_id == 'register') ? 'new' : get_current_user_id())
							);
							switch($this->list_id) {

								case 'register' :

									$filter['user_form'] = 'add';
									break;

								case 'update' :

									$filter['user_form'] = 'edit';
									break;
							}
							$groups = acf_get_field_groups($filter);

							$fields = array();

							foreach($groups as $group) {

								if(!isset($group['key'])) { continue; }
								$fields = array_merge($fields, acf_get_fields($group['key']));
							}

							$acf_fields_to_list_fields_return = WS_Form_ACF::acf_fields_to_list_fields($fields, 0, 0, $sort_index);
							$list_fields = array_merge($list_fields, $acf_fields_to_list_fields_return['list_fields']);
						}

						break;
				}

				return $list_fields;
			}

			// Get WooCommerce fields
			public function get_woocommerce_fields() {

				return array(

					'billing_first_name'  => array('label' => __('Billing First Name', 'woocommerce'), 'type' => 'text'),
					'billing_last_name'   => array('label' => __('Billing Last Name', 'woocommerce'), 'type' => 'text'),
					'billing_company'     => array('label' => __('Billing Company', 'woocommerce'), 'type' => 'text'),
					'billing_address_1'   => array('label' => __('Billing Address 1', 'woocommerce'), 'type' => 'text'),
					'billing_address_2'   => array('label' => __('Billing Address 2', 'woocommerce'), 'type' => 'text'),
					'billing_city'        => array('label' => __('Billing City', 'woocommerce'), 'type' => 'text'),
					'billing_state'       => array('label' => __('Billing State', 'woocommerce'), 'type' => 'text'),
					'billing_postcode'    => array('label' => __('Billing Postal/Zip Code', 'woocommerce'), 'type' => 'text'),
					'billing_country'     => array('label' => __('Billing Country / Region', 'woocommerce'), 'type' => 'select'),
					'billing_phone'       => array('label' => __('Billing Phone Number', 'woocommerce'), 'type' => 'text'),
					'billing_email'       => array('label' => __('Billing Email Address', 'woocommerce'), 'type' => 'text'),

					'shipping_first_name' => array('label' => __('Shipping First Name', 'woocommerce'), 'type' => 'text'),
					'shipping_last_name'  => array('label' => __('Shipping Last Name', 'woocommerce'), 'type' => 'text'),
					'shipping_company'    => array('label' => __('Shipping Company', 'woocommerce'), 'type' => 'text'),
					'shipping_address_1'  => array('label' => __('Shipping Address 1', 'woocommerce'), 'type' => 'text'),
					'shipping_address_2'  => array('label' => __('Shipping Address 2', 'woocommerce'), 'type' => 'text'),
					'shipping_city'       => array('label' => __('Shipping City', 'woocommerce'), 'type' => 'text'),
					'shipping_state'      => array('label' => __('Shipping State', 'woocommerce'), 'type' => 'text'),
					'shipping_postcode'   => array('label' => __('Shipping Postal/Zip Code', 'woocommerce'), 'type' => 'text'),
					'shipping_country'    => array('label' => __('Shipping Country / Region', 'woocommerce'), 'type' => 'select')
				);
			}

			// Get list fields meta data (Returns group and section data such as label and whether or not a section is repeatable)
			public function get_list_fields_meta_data() {

				$group_meta_data = array();
				$section_meta_data = array();

				// Get ACF fields
				if($this->acf_activated) {

					// Get ACF fields
					$filter = array(

						'user_id' => (($this->list_id == 'register') ? 'new' : get_current_user_id())
					);
					switch($this->list_id) {

						case 'register' :

							$filter['user_form'] = 'add';
							break;

						case 'update' :

							$filter['user_form'] = 'edit';
							break;
					}
					$groups = acf_get_field_groups($filter);

					$fields = array();

					foreach($groups as $group) {

						if(!isset($group['key'])) { continue; }
						$fields = array_merge($fields, acf_get_fields($group['key']));
					}

					$acf_fields_to_meta_data_return = WS_Form_ACF::acf_fields_to_meta_data($fields, 0, 0, 1);
					$group_meta_data = $acf_fields_to_meta_data_return['group_meta_data'];
					$section_meta_data = $acf_fields_to_meta_data_return['section_meta_data'];
				}

				return array('group_meta_data' => $group_meta_data, 'section_meta_data' => $section_meta_data);
			}

			// Get form fields
			public function get_fields() {

				switch($this->list_id) {

					case 'register' :

						$form_fields = array(

							'submit' => array(

								'type'			=>	'submit',
								'label'			=>	__('Register', 'ws-form-user')
							)
						);

						break;

					case 'update' :

						$form_fields = array(

							'submit' => array(

								'type'			=>	'submit',
								'label'			=>	__('Update Profile', 'ws-form-user')
							)
						);

						break;

					case 'signon' :

						$form_fields = array(

							'submit' => array(

								'type'			=>	'submit',
								'label'			=>	__('Log In', 'ws-form-user')
							)
						);

						break;

					case 'lostpassword' :

						$form_fields = array(

							'submit' => array(

								'type'			=>	'submit',
								'label'			=>	__('Get New Password', 'ws-form-user')
							)
						);

						break;

					case 'resetpassword' :

						$form_fields = array(

							'submit' => array(

								'type'			=>	'submit',
								'label'			=>	__('Reset Password', 'ws-form-user'),
							)
						);

						break;

					case 'logout' :

						$form_fields = array(

							'submit' => array(

								'type'			=>	'submit',
								'label'			=>	__('Log Out', 'ws-form-user'),
							)
						);

						break;
				}

				return $form_fields;
			}

			// Get form actions
			public function get_actions($form_field_id_lookup_all) {

				switch($this->list_id) {

					case 'register' :

						$form_actions = array(

							$this->id => array(

								'meta'	=> array(

									'action_' . $this->id . '_list_id'			=>	$this->list_id,
									'action_' . $this->id . '_field_mapping'	=>	'field_mapping'
								)
							),

							'message' => array(

								'meta'	=> array(

									'action_message_message'	=> __('Thank you for registering.', 'ws-form-user')
								)
							)
						);

						break;

					case 'update' :

						$form_actions = array(

							$this->id => array(

								'meta'	=> array(

									'action_' . $this->id . '_list_id'			=>	$this->list_id,
									'action_' . $this->id . '_field_mapping'	=>	'field_mapping'
								)
							),

							'message' => array(

								'meta'	=> array(

									'action_message_message'	=> __('Your profile has been successfully updated.', 'ws-form-user')
								)
							)
						);

						break;

					case 'signon' :

						$form_actions = array(

							$this->id => array(

								'meta'	=> array(

									'action_' . $this->id . '_list_id'			=>	$this->list_id,
									'action_' . $this->id . '_field_mapping'	=>	'field_mapping'
								)
							)
						);

						break;

					case 'lostpassword' :

						$message_textarea = __("Someone has requested a password reset for the following account:\n\nSite Name: #blog_name\n\nUsername: #user_login\n\nIf this was a mistake, just ignore this email and nothing will happen.\n\nTo reset your password, visit the following address:\n\n#user_lost_password_url", 'ws-form-user');
						$message_text_editor = '<p>' . implode("</p><p>", explode("\n\n", $message_textarea)) . '</p>';
						$message_html_editor = '<p>' . implode("</p>\n\n<p>", explode("\n\n", $message_textarea)) . '</p>';

						$form_actions = array(

							$this->id => array(

								'meta'	=> array(

									'action_' . $this->id . '_list_id'			=>	$this->list_id,
									'action_' . $this->id . '_field_mapping'	=>	'field_mapping'
								)
							),

							'email' => array(

								'meta'	=> array(

									'action_email_to'			=> array(

										array(

											'action_email_email' 	=> '#user_email',
											'action_email_name' 	=> '#blog_name'
										)
									),

									'action_email_subject'		=> __('Password Reset', 'ws-form-user'),

									'action_email_message_textarea'		=>	$message_textarea,
									'action_email_message_text_editor'	=>	$message_text_editor,
									'action_email_message_html_editor'	=>	$message_html_editor
								)
							),

							'message' => array(

								'meta'	=> array(

									'action_message_message'	=> __('Check your email for the confirmation link.', 'ws-form-user')
								)
							)
						);

						break;

					case 'resetpassword' :

						$form_actions = array(

							$this->id => array(

								'meta'	=> array(

									'action_' . $this->id . '_list_id'			=>	$this->list_id,
									'action_' . $this->id . '_field_mapping'	=>	'field_mapping'
								)
							),

							'message' => array(

								'meta'	=> array(

									'action_message_message'	=> __('Your password was successfully reset.', 'ws-form-user')
								)
							)
						);

						break;

					case 'logout' :

						$form_actions = array(

							$this->id => array(

								'meta'	=> array(

									'action_' . $this->id . '_list_id'			=>	$this->list_id
								)
							),

							'message' => array(

								'meta'	=> array(

									'action_message_message'	=> __('You were successfully logged out.', 'ws-form-user'),
									'action_message_duration'	=> '2000'
								)
							),

							'redirect' => array(

								'meta'	=> array(

									'url'	=> '/'
								)
							)
						);

						break;
				}

				switch($this->list_id) {

					case 'register' :
					case 'update' :

						if($this->acf_activated) {

							$list_fields = array();

							// Get ACF fields
							$filter = array(

								'user_id' => (($this->list_id == 'register') ? 'new' : get_current_user_id())
							);
							switch($this->list_id) {

								case 'register' :

									$filter['user_form'] = 'add';
									break;

								case 'update' :

									$filter['user_form'] = 'edit';
									break;
							}
							$groups = acf_get_field_groups($filter);

							$fields = array();

							foreach($groups as $group) {

								if(!isset($group['key'])) { continue; }
								$fields = array_merge($fields, acf_get_fields($group['key']));
							}

							$acf_fields_to_list_fields_return = WS_Form_ACF::acf_fields_to_list_fields($fields);
							$list_fields = $acf_fields_to_list_fields_return['list_fields'];

							$form_actions[$this->id]['meta']['action_' . $this->id . '_field_mapping_acf'] = array();

							foreach($list_fields as $list_field) {

								if(!isset($form_field_id_lookup_all[$list_field['id']])) { continue; }

								$form_actions[$this->id]['meta']['action_' . $this->id . '_field_mapping_acf'][] = array(

									'ws_form_field' => $form_field_id_lookup_all[$list_field['id']],
									'action_' . $this->id . '_acf_key' => $list_field['id']
								);
							}
						}

						break;
				}

				return $form_actions;
			}

			// Get conditionals
			public function get_conditionals() {

				switch($this->list_id) {

					case 'register' :

						$form_conditionals = array(

							array(

								'label'			=>	__('Check passwords match', 'ws-form-user'),

								'conditional'	=> array(

									'if'	=>	array(

										array(

											'conditions'	=>	array(

												array(

													'id' => 1,
													'object' => 'field',
													'object_id' => 'password',
													'object_row_id' => false,
													'logic' => 'field_match_not',
													'value' => 'password_confirm',
													'case_sensitive' => true,
													'logic_previous' => '||'
												)
											),

											'logic_previous' => '||'
										)
									),

									'then'	=> array(

										array(

											'id' => 1,
											'object' => 'field',
											'object_id' => 'password_confirm',
											'object_row_id' => false,
											'action' => 'set_custom_validity',
											'value' => __('Passwords do not match', 'ws-form-user')
										)
									),

									'else'	=> array(

										array(

											'id' => 1,
											'object' => 'field',
											'object_id' => 'password_confirm',
											'object_row_id' => false,
											'action' => 'set_custom_validity',
											'value' => ''
										)
									)
								)
							)
						);

						break;

					case 'resetpassword' :

						$form_conditionals = array(

							array(

								'label'			=>	__('Check passwords match', 'ws-form-user'),

								'conditional'	=> array(

									'if'	=>	array(

										array(

											'conditions'	=>	array(

												array(

													'id' => 1,
													'object' => 'field',
													'object_id' => 'pass1',
													'object_row_id' => false,
													'logic' => 'field_match_not',
													'value' => 'pass2',
													'case_sensitive' => true,
													'logic_previous' => '||'
												)
											),

											'logic_previous' => '||'
										)
									),

									'then'	=> array(

										array(

											'id' => 1,
											'object' => 'field',
											'object_id' => 'pass2',
											'object_row_id' => false,
											'action' => 'set_custom_validity',
											'value' => __('Passwords do not match', 'ws-form-user')
										)
									),

									'else'	=> array(

										array(

											'id' => 1,
											'object' => 'field',
											'object_id' => 'pass2',
											'object_row_id' => false,
											'action' => 'set_custom_validity',
											'value' => ''
										)
									)
								)
							)
						);

						break;

					default :

						$form_conditionals = array();
				}

				return $form_conditionals;
			}

			// Get form meta
			public function get_meta($form_field_id_lookup_all) {

				$form_meta = array('submit_reload' => '');

				switch($this->list_id) {

					case 'update' :

						$form_meta['form_populate_enabled'] = 'on';

						$form_meta['action_' . $this->id . '_form_populate_field_mapping'] = array(

							array('action_user_form_populate_field' => 'user_first_name', 'ws_form_field' => self::form_field_id_lookup('first_name', $form_field_id_lookup_all)),
							array('action_user_form_populate_field' => 'user_last_name', 'ws_form_field' => self::form_field_id_lookup('last_name', $form_field_id_lookup_all)),
							array('action_user_form_populate_field' => 'user_nickname', 'ws_form_field' => self::form_field_id_lookup('nickname', $form_field_id_lookup_all)),
							array('action_user_form_populate_field' => 'user_display_name', 'ws_form_field' => self::form_field_id_lookup('display_name', $form_field_id_lookup_all)),
							array('action_user_form_populate_field' => 'user_email', 'ws_form_field' => self::form_field_id_lookup('email', $form_field_id_lookup_all)),
							array('action_user_form_populate_field' => 'user_url', 'ws_form_field' => self::form_field_id_lookup('website', $form_field_id_lookup_all)),
							array('action_user_form_populate_field' => 'user_description', 'ws_form_field' => self::form_field_id_lookup('description', $form_field_id_lookup_all)),
							array('action_user_form_populate_field' => 'user_rich_editing', 'ws_form_field' => self::form_field_id_lookup('rich_editing', $form_field_id_lookup_all)),
							array('action_user_form_populate_field' => 'user_syntax_highlighting', 'ws_form_field' => self::form_field_id_lookup('syntax_highlighting', $form_field_id_lookup_all)),
							array('action_user_form_populate_field' => 'user_comment_shortcuts', 'ws_form_field' => self::form_field_id_lookup('comment_shortcuts', $form_field_id_lookup_all)),
							array('action_user_form_populate_field' => 'user_show_admin_bar_front', 'ws_form_field' => self::form_field_id_lookup('show_admin_bar_front', $form_field_id_lookup_all)),
							array('action_user_form_populate_field' => 'user_admin_color', 'ws_form_field' => self::form_field_id_lookup('admin_color', $form_field_id_lookup_all))
						);

						if($this->woocommerce_activated) {

							$woocommerce_fields = self::get_woocommerce_fields();

							foreach($woocommerce_fields as $meta_key => $woocommerce_field) {

								$form_meta['action_' . $this->id . '_form_populate_field_mapping'][] = array('action_user_form_populate_field' => $meta_key, 'ws_form_field' => self::form_field_id_lookup($meta_key, $form_field_id_lookup_all));
							}
						}

						if($this->acf_activated) {

							$list_fields = array();

							// Get ACF fields
							$filter = array(

								'user_id' => (($this->list_id == 'register') ? 'new' : get_current_user_id())
							);
							switch($this->list_id) {

								case 'register' :

									$filter['user_form'] = 'add';
									break;

								case 'update' :

									$filter['user_form'] = 'edit';
									break;
							}
							$groups = acf_get_field_groups($filter);

							$fields = array();

							foreach($groups as $group) {

								if(!isset($group['key'])) { continue; }
								$fields = array_merge($fields, acf_get_fields($group['key']));

								$acf_fields_to_list_fields_return = WS_Form_ACF::acf_fields_to_list_fields($fields);
								$list_fields = $acf_fields_to_list_fields_return['list_fields'];
							}

							foreach($list_fields as $list_field) {

								if(!isset($form_field_id_lookup_all[$list_field['id']])) { continue; }

								$form_meta['action_' . $this->id . '_form_populate_field_mapping_acf'][] = array(

									'action_' . $this->id . '_acf_key' => $list_field['id'],
									'ws_form_field' => $form_field_id_lookup_all[$list_field['id']]
								);
							}
						}

						break;
				}

				return $form_meta;
			}

			// Perform form field ID lookup
			public function form_field_id_lookup($input, $form_field_id_lookup) {

				return (isset($form_field_id_lookup[$input])) ? $form_field_id_lookup[$input] : $input;
			}

			// Get settings
			public function get_action_settings() {

				$settings = array(

					'meta_keys'		=> array(

						'action_' . $this->id . '_list_id',
						'action_' . $this->id . '_field_mapping',
						'action_' . $this->id . '_meta_mapping',
						'action_' . $this->id . '_meta_mapping_custom',
						'action_' . $this->id . '_secure_cookie',
						'action_' . $this->id . '_rich_editing',
						'action_' . $this->id . '_syntax_highlighting',
						'action_' . $this->id . '_comment_shortcuts',
						'action_' . $this->id . '_show_admin_bar_front',
						'action_' . $this->id . '_admin_color',
						'action_' . $this->id . '_role',
						'action_' . $this->id . '_password_create',
						'action_' . $this->id . '_password_length',
						'action_' . $this->id . '_password_special_characters',
						'action_' . $this->id . '_message_method',
						'action_' . $this->id . '_message_clear',
						'action_' . $this->id . '_message_scroll_top',
						'action_' . $this->id . '_message_form_hide',
						'action_' . $this->id . '_message_duration'
					)
				);

				// ACF
				if($this->acf_activated) {

					array_splice( $settings['meta_keys'], 2, 0, 'action_' . $this->id . '_field_mapping_acf');
				}

				// Wrap settings so they will work with sidebar_html function in admin.js
				$settings = parent::get_settings_wrapper($settings);

				// Add labels
				$settings->label = $this->label;
				$settings->label_action = $this->label_action;

				// Add multiple
				$settings->multiple = $this->multiple;

				// Add events
				$settings->events = $this->events;

				// Add can_repost
				$settings->can_repost = $this->can_repost;

				// Apply filter
				$settings = apply_filters('wsf_action_' . $this->id . '_settings', $settings);

				return $settings;
			}

			// Check action is configured properly
			public function check_configured() {

				if(!$this->configured) { return self::error(__('Action not configured', 'ws-form-user') . ' (' . $this->label . ''); }

				return $this->configured;
			}

			// Check list ID is set
			public function check_list_id() {

				if($this->list_id === false) { return self::error(__('List ID is not set', 'ws-form-user')); }

				return ($this->list_id !== false);
			}

			// Meta keys for this action
			public function config_meta_keys($meta_keys = array(), $form_id = 0) {

				// Build config_meta_keys
				$config_meta_keys = array(

					// List ID
					'action_' . $this->id . '_list_id'	=> array(

						'label'							=>	__('Method', 'ws-form-user'),
						'type'							=>	'select',
						'help'							=>	__('Which user method do you want to run?', 'ws-form-user'),
						'options'						=>	'action_api_populate',
						'options_blank'					=>	__('Select...', 'ws-form-user'),
						'options_action_id_meta_key'	=>	'action_id',
						'options_action_api_populate'	=>	'lists',
						'default'						=>	'signon'
					),

					// Secure cookies
					'action_' . $this->id . '_secure_cookie'	=> array(

						'label'						=>	__('Secure Cookies?', 'ws-form-user'),
						'type'						=>	'checkbox',
						'default'					=>	is_ssl() ? 'on' : '',
						'condition'					=>	array(

							array(

								'logic'			=>	'==',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	'login'
							)
						)
					),

					// Create password
					'action_' . $this->id . '_password_create'	=> array(

						'label'						=>	__('Create Password (If blank)', 'ws-form'),
						'type'						=>	'checkbox',
						'default'					=>	'',
						'condition'					=>	array(

							array(

								'logic'			=>	'==',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	'register'
							)
						)
					),

					// Create password - Length
					'action_' . $this->id . '_password_length'	=> array(

						'label'						=>	__('Password Length', 'ws-form'),
						'type'						=>	'number',
						'default'					=>	self::WS_FORM_PASSWORD_LENGTH_DEFAULT,
						'condition'					=>	array(

							array(

								'logic'			=>	'==',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	'register'
							),

							array(

								'logic_previous'	=>	'&&',
								'logic'				=>	'==',
								'meta_key'			=>	'action_' . $this->id . '_password_create',
								'meta_value'		=>	'on'
							)
						)					
					),

					// Create password - Special characters
					'action_' . $this->id . '_password_special_characters'	=> array(

						'label'						=>	__('Use Special Characters', 'ws-form'),
						'type'						=>	'checkbox',
						'default'					=>	'',
						'condition'					=>	array(

							array(

								'logic'			=>	'==',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	'register'
							),

							array(

								'logic_previous'	=>	'&&',
								'logic'				=>	'==',
								'meta_key'			=>	'action_' . $this->id . '_password_create',
								'meta_value'		=>	'on'
							)
						)					
					),

					// Disable the visual editor when writing
					'action_' . $this->id . '_rich_editing'	=> array(

						'label'						=>	__('Disable the Visual Editor When Writing', 'ws-form'),
						'type'						=>	'checkbox',
						'default'					=>	'',
						'condition'					=>	array(

							array(

								'logic'			=>	'==',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	'register'
							)
						)
					),

					// Disable syntax highlighting when editing code
					'action_' . $this->id . '_syntax_highlighting'	=> array(

						'label'						=>	__('Disable Syntax Highlighting When Editing Code', 'ws-form'),
						'type'						=>	'checkbox',
						'default'					=>	'',
						'condition'					=>	array(

							array(

								'logic'			=>	'==',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	'register'
							)
						)
					),

					// Enable keyboard shortcuts for comment moderation.
					'action_' . $this->id . '_comment_shortcuts'	=> array(

						'label'						=>	__('Enable Keyboard Shortcuts for Comment Moderation', 'ws-form'),
						'type'						=>	'checkbox',
						'default'					=>	'',
						'condition'					=>	array(

							array(

								'logic'			=>	'==',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	'register'
							)
						)
					),

					// Show toolbar when viewing site
					'action_' . $this->id . '_show_admin_bar_front'	=> array(

						'label'						=>	__('Show Toolbar When Viewing Site', 'ws-form'),
						'type'						=>	'checkbox',
						'default'					=>	'',
						'condition'					=>	array(

							array(

								'logic'			=>	'==',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	'register'
							)
						)
					),

					// Admin Color Scheme
					'action_' . $this->id . '_admin_color'	=> array(

						'label'						=>	__('Admin Color Scheme', 'ws-form-user'),
						'type'						=>	'select',
						'options'					=>	array(

							array('value' => 'fresh', 'text' => __('Default', 'ws-form-user')),
							array('value' => 'light', 'text' => __('Light', 'ws-form-user')),
							array('value' => 'blue', 'text' => __('Blue', 'ws-form-user')),
							array('value' => 'coffee', 'text' => __('Coffee', 'ws-form-user')),
							array('value' => 'ectoplasm', 'text' => __('Ectoplasm', 'ws-form-user')),
							array('value' => 'midnight', 'text' => __('Midnight', 'ws-form-user')),
							array('value' => 'ocean', 'text' => __('Ocean', 'ws-form-user')),
							array('value' => 'sunrise', 'text' => __('Sunrise', 'ws-form-user'))
						),
						'default'					=>	self::WS_FORM_ADMIN_COLOR_DEFAULT,
						'condition'					=>	array(

							array(

								'logic'			=>	'==',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	'register'
							)
						)
					),

					// Field mapping
					'action_' . $this->id . '_field_mapping'	=> array(

						'label'						=>	__('Field Mapping', 'ws-form-user'),
						'type'						=>	'repeater',
						'help'						=>	__('Map WS Form fields to user fields.', 'ws-form-user'),
						'meta_keys'					=>	array(

							'ws_form_field',
							'action_' . $this->id . '_list_fields'
						),
						'meta_keys_unique'			=>	array(

							'action_' . $this->id . '_list_fields'
						),
						'auto_map'					=>	true,
						'condition'					=>	array(

							array(

								'logic'			=>	'!=',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	''
							),

							array(

								'logic_previous'	=>	'&&',
								'logic'				=>	'!=',
								'meta_key'			=>	'action_' . $this->id . '_list_id',
								'meta_value'		=>	'logout'
							)
						)
					),

					// Meta mapping
					'action_' . $this->id . '_meta_mapping'	=> array(

						'label'						=>	__('Meta Mapping', 'ws-form-user'),
						'type'						=>	'repeater',
						'help'						=>	__('Map WS Form fields to user meta fields.', 'ws-form-user'),
						'meta_keys'					=>	array(

							'ws_form_field',
							'action_' . $this->id . '_meta_key'
						),
						'condition'					=>	array(

							array(

								'logic'				=>	'==',
								'meta_key'			=>	'action_' . $this->id . '_list_id',
								'meta_value'		=>	'register'
							),

							array(
								'logic_previous'	=>	'||',
								'logic'				=>	'==',
								'meta_key'			=>	'action_' . $this->id . '_list_id',
								'meta_value'		=>	'update'
							)
						)
					),

					// Custom meta mapping
					'action_' . $this->id . '_meta_mapping_custom'	=> array(

						'label'						=>	__('Custom Meta Mapping', 'ws-form-post'),
						'type'						=>	'repeater',
						'help'						=>	__('Map custom values to meta keys.', 'ws-form-post'),
						'meta_keys'					=>	array(

							'action_' . $this->id . '_meta_key',
							'action_' . $this->id . '_meta_value'
						),
						'condition'					=>	array(

							array(

								'logic'				=>	'==',
								'meta_key'			=>	'action_' . $this->id . '_list_id',
								'meta_value'		=>	'register'
							),

							array(
								'logic_previous'	=>	'||',
								'logic'				=>	'==',
								'meta_key'			=>	'action_' . $this->id . '_list_id',
								'meta_value'		=>	'update'
							)
						)
					),

					// Meta field
					'action_' . $this->id . '_meta_key'	=> array(

						'label'						=>	__('Meta Field', 'ws-form-user'),
						'type'						=>	'text'
					),

					// Meta value
					'action_' . $this->id . '_meta_value'	=> array(

						'label'						=>	__('Meta Value', 'ws-form-post'),
						'type'						=>	'text'
					),

					// Message - Method
					'action_' . $this->id . '_message_method'	=> array(

						'label'						=>	__('Error Messages', 'ws-form-user'),
						'type'						=>	'select',
						'help'						=>	__('Where should error messages be added?', 'ws-form-user'),
						'options'					=>	array(

							array('value' => 'before', 'text' => __('Before Form', 'ws-form-user')),
							array('value' => 'after', 'text' => __('After Form', 'ws-form-user'))
						),
						'default'					=>	'before',
						'condition'					=>	array(

							array(

								'logic'			=>	'!=',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	''
							),

							array(

								'logic_previous'	=>	'&&',
								'logic'				=>	'!=',
								'meta_key'			=>	'action_' . $this->id . '_list_id',
								'meta_value'		=>	'logout'
							)
						)
					),

					// Message - Duration
					'action_' . $this->id . '_message_duration'	=> array(

						'label'						=>	__('Error Message Duration (ms)', 'ws-form-user'),
						'type'						=>	'number',
						'help'						=>	__('Duration in milliseconds error message shown', 'ws-form-user'),
						'default'					=>	'4000',
						'condition'					=>	array(

							array(

								'logic'			=>	'!=',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	''
							),

							array(

								'logic_previous'	=>	'&&',
								'logic'				=>	'!=',
								'meta_key'			=>	'action_' . $this->id . '_list_id',
								'meta_value'		=>	'logout'
							)
						)
					),

					// Form - Clear other messages
					'action_' . $this->id . '_message_clear'	=> array(

						'label'						=>	__('Clear Other Messages On Error', 'ws-form'),
						'type'						=>	'checkbox',
						'help'						=>	__('Clear any other messages when error message shown?', 'ws-form'),
						'default'					=>	'on',
						'condition'					=>	array(

							array(

								'logic'			=>	'!=',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	''
							),

							array(

								'logic_previous'	=>	'&&',
								'logic'				=>	'!=',
								'meta_key'			=>	'action_' . $this->id . '_list_id',
								'meta_value'		=>	'logout'
							)
						)
					),

					// Form - Scroll to top
					'action_' . $this->id . '_message_scroll_top'	=> array(

						'label'						=>	__('Scroll To Top On Error', 'ws-form'),
						'type'						=>	'checkbox',
						'help'						=>	__('Scroll to top of page when error message shown?', 'ws-form'),
						'default'					=>	'',
						'condition'					=>	array(

							array(

								'logic'			=>	'!=',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	''
							),

							array(

								'logic_previous'	=>	'&&',
								'logic'				=>	'!=',
								'meta_key'			=>	'action_' . $this->id . '_list_id',
								'meta_value'		=>	'logout'
							)
						)
					),

					// Message - Form Hide
					'action_' . $this->id . '_message_form_hide'	=> array(

						'label'						=>	__('Hide Form On Error', 'ws-form-user'),
						'type'						=>	'checkbox',
						'help'						=>	__('Hide form when error message shown?', 'ws-form-user'),
						'default'					=>	'',
						'condition'					=>	array(

							array(

								'logic'			=>	'!=',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	''
							),

							array(

								'logic_previous'	=>	'&&',
								'logic'				=>	'!=',
								'meta_key'			=>	'action_' . $this->id . '_list_id',
								'meta_value'		=>	'logout'
							)
						)
					),

					// List fields
					'action_' . $this->id . '_list_fields'	=> array(

						'label'							=>	__('User Field', 'ws-form-user'),
						'type'							=>	'select',
						'options'						=>	'action_api_populate',
						'options_blank'					=>	__('Select...', 'ws-form-user'),
						'options_action_id'				=>	$this->id,
						'options_list_id_meta_key'		=>	'action_' . $this->id . '_list_id',
						'options_action_api_populate'	=>	'list_fields'
					),

					// Role
					'action_' . $this->id . '_role'	=> array(

						'label'						=>	__('Role', 'ws-form-user'),
						'type'						=>	'select',
						'help'						=>	__('Role user will be assigned to.', 'ws-form-user'),
						'options'					=>	array(),
						'default'					=>	get_option('default_role'),
						'condition'					=>	array(

							array(

								'logic'			=>	'==',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	'register'
							)
						)
					),

					// Form auto-populate

					// Field mapping
					'action_' . $this->id . '_form_populate_field_mapping'	=> array(

						'label'						=>	__('Field Mapping', 'ws-form'),
						'type'						=>	'repeater',
						'help'						=>	__('Map user fields to WS Form fields', 'ws-form'),
						'meta_keys'					=>	array(

							'action_' . $this->id . '_form_populate_field',
							'ws_form_field_edit'
						),
						'meta_keys_unique'			=>	array(

							'ws_form_field_edit'
						),
						'condition'					=>	array(

							array(

								'logic'				=>	'==',
								'meta_key'			=>	'form_populate_enabled',
								'meta_value'		=>	'on'
							),

							array(

								'logic'				=>	'==',
								'meta_key'			=>	'form_populate_action_id',
								'meta_value'		=>	'user',
								'logic_previous'	=>	'&&'
							)
						)
					),

					// User fields
					'action_' . $this->id . '_form_populate_field'	=> array(

						'label'							=>	__('User Field', 'ws-form-user'),
						'type'							=>	'select',
						'options'						=>	array(

							array('value' => 'user_id', 'text' => __('ID', 'ws-form-user')),
							array('value' => 'user_login', 'text' => __('Username', 'ws-form-user')),
							array('value' => 'user_first_name', 'text' => __('First Name', 'ws-form-user')),
							array('value' => 'user_last_name', 'text' => __('Last Name', 'ws-form-user')),
							array('value' => 'user_display_name', 'text' => __('Display Name', 'ws-form-user')),
							array('value' => 'user_nicename', 'text' => __('Nice Name', 'ws-form-user')),
							array('value' => 'user_nickname', 'text' => __('Nickname', 'ws-form-user')),
							array('value' => 'user_email', 'text' => __('Email', 'ws-form-user')),
							array('value' => 'user_url', 'text' => __('Website', 'ws-form-user')),
							array('value' => 'user_registered', 'text' => __('Registered Date', 'ws-form-user')),
							array('value' => 'user_description', 'text' => __('Biographical Info', 'ws-form-user')),
							array('value' => 'user_rich_editing', 'text' => __('Visual Editor', 'ws-form-user')),
							array('value' => 'user_syntax_highlighting', 'text' => __('Syntax Highlighting', 'ws-form-user')),
							array('value' => 'user_comment_shortcuts', 'text' => __('Keyboard Shortcuts', 'ws-form-user')),
							array('value' => 'user_show_admin_bar_front', 'text' => __('Toolbar', 'ws-form-user')),
							array('value' => 'user_admin_color', 'text' => __('Admin Color Scheme', 'ws-form-user')),
						),
						'options_blank'					=>	__('Select...', 'ws-form-user')
					),

					// Meta mapping
					'action_' . $this->id . '_form_populate_meta_mapping'	=> array(

						'label'						=>	__('Meta Mapping', 'ws-form-post'),
						'type'						=>	'repeater',
						'help'						=>	__('Map user meta key values to WS Form fields.', 'ws-form-post'),
						'meta_keys'					=>	array(

							'action_' . $this->id . '_meta_key',
							'ws_form_field'
						),
						'meta_keys_unique'			=>	array(

							'ws_form_field'
						),
						'condition'	=>	array(

							array(

								'logic'			=>	'==',
								'meta_key'		=>	'form_populate_action_id',
								'meta_value'	=>	'user'
							),

							array(

								'logic'			=>	'==',
								'meta_key'		=>	'form_populate_enabled',
								'meta_value'	=>	'on'
							)
						)
					)
				);

				// Do not show list selector for user (Auto populate)
				$meta_keys['form_populate_list_id']['condition'][] = array(

					'logic'				=>	'!=',
					'meta_key'			=>	'form_populate_action_id',
					'meta_value'		=>	'user',
					'logic_previous'	=>	'&&'
				);

				// Do not show regular field mapping selector for user (Auto populate)
				$meta_keys['form_populate_field_mapping']['condition'][] = array(

					'logic'				=>	'!=',
					'meta_key'			=>	'form_populate_action_id',
					'meta_value'		=>	'user',
					'logic_previous'	=>	'&&'
				);

				// Add user roles
				$all_roles = wp_roles()->roles;
				$user = wp_get_current_user();
				$next_level = 'level_' . ($user->user_level + 1);
				foreach ($all_roles as $name => $role) {
					if(!isset($role['capabilities'][$next_level])) {
						$config_meta_keys['action_' . $this->id . '_role']['options'][] = array('value' => $name, 'text' => $role['name']);
					}
				}

				// WooCommerce
				if($this->woocommerce_activated) {

					$woocommerce_fields = self::get_woocommerce_fields();

					foreach($woocommerce_fields as $meta_key => $woocommerce_field) {

						$config_meta_keys['action_' . $this->id . '_form_populate_field']['options'][] = array('value' => $meta_key, 'text' => $woocommerce_field['label']);
					}
				}

				// ACF
				$options_acf = array();
				if($this->acf_activated) {

					$acf_field_groups = acf_get_field_groups();

					foreach($acf_field_groups as $acf_field_group) {

						$acf_fields = acf_get_fields($acf_field_group);
						
						$acf_field_group_name = $acf_field_group['title'];

						WS_Form_ACF::acf_get_fields($options_acf, $acf_field_group_name, $acf_fields);
					}
				}

				if($this->acf_activated) {

					// ACF - Fields
					$config_meta_keys['action_' . $this->id . '_acf_key'] = array(

						'label'							=>	__('ACF Field', 'ws-form-post'),
						'type'							=>	'select',
						'options'						=>	$options_acf,
						'options_blank'					=>	__('Select...', 'ws-form-post')
					);

					// ACF - Field mapping
					$config_meta_keys['action_' . $this->id . '_field_mapping_acf'] = array(

						'label'						=>	__('ACF Field Mapping', 'ws-form-post'),
						'type'						=>	'repeater',
						'help'						=>	__('Map WS Form fields to ACF fields.', 'ws-form-post'),
						'meta_keys'					=>	array(

							'ws_form_field',
							'action_' . $this->id . '_acf_key'
						),
						'meta_keys_unique'			=>	array(

							'action_' . $this->id . '_acf_key'
						),
						'condition'					=>	array(

							array(

								'logic'			=>	'!=',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	''
							)
						)
					);

					// Populate - ACF - Field mapping
					$config_meta_keys['action_' . $this->id . '_form_populate_field_mapping_acf'] = array(

						'label'						=>	__('ACF Field Mapping', 'ws-form-post'),
						'type'						=>	'repeater',
						'help'						=>	__('Map ACF field values to WS Form fields.', 'ws-form-post'),
						'meta_keys'					=>	array(

							'action_' . $this->id . '_acf_key',
							'ws_form_field'
						),
						'condition'	=>	array(

							array(

								'logic'			=>	'==',
								'meta_key'		=>	'form_populate_action_id',
								'meta_value'	=>	'user'
							),

							array(

								'logic'			=>	'==',
								'meta_key'		=>	'form_populate_enabled',
								'meta_value'	=>	'on'
							)
						)
					);
				}

				// Merge
				$meta_keys = array_merge($meta_keys, $config_meta_keys);

				return $meta_keys;
			}

			// Plug-in options for this action
			public function config_options($options) {

				$options['action_' . $this->id] = array(

					'label'		=>	$this->label,
					'fields'	=>	array(

						'action_' . $this->id . '_license_version'	=>	array(

							'label'		=>	__('Add-on Version', 'ws-form-user'),
							'type'		=>	'static'
						),

						'action_' . $this->id . '_license_key'	=>	array(

							'label'		=>	__('Add-on License Key', 'ws-form-user'),
							'type'		=>	'text',
							'help'		=>	__('Enter your User Management add-on for WS Form PRO license key here.', 'ws-form-user'),
							'button'	=>	'license_action_' . $this->id,
							'action'	=>	$this->id
						),

						'action_' . $this->id . '_license_status'	=>	array(

							'label'		=>	__('Add-on License Status', 'ws-form-user'),
							'type'		=>	'static'
						),
					)
				);

				return $options;
			}

			public function config_settings_form_admin($config_settings_form_admin) {

				if(!isset($config_settings_form_admin['sidebars']['form']['meta']['fieldsets']['action'])) { return $config_settings_form_admin; }

				$meta_keys = $config_settings_form_admin['sidebars']['form']['meta']['fieldsets']['action']['fieldsets'][0]['meta_keys'];

				// Add user field mapping
				self::meta_key_inject($meta_keys, 'action_' . $this->id . '_form_populate_field_mapping', 'form_populate_field_mapping');

				if($this->acf_activated) {

					// Add ACF field mapping
					self::meta_key_inject($meta_keys, 'action_' . $this->id . '_form_populate_field_mapping_acf', 'form_populate_tag_mapping');
				}

				// Add user meta key mapping
				self::meta_key_inject($meta_keys, 'action_' . $this->id . '_form_populate_meta_mapping', 'form_populate_tag_mapping');

				$config_settings_form_admin['sidebars']['form']['meta']['fieldsets']['action']['fieldsets'][0]['meta_keys'] = $meta_keys;

				return $config_settings_form_admin;
			}

			// Inject a meta key
			public function meta_key_inject(&$meta_keys, $insert_this, $insert_before = false) {

				$key = ($insert_before !== false) ? array_search($insert_before, $meta_keys) : false;

				if($key !== false) {

					$meta_keys = 

						array_merge(

							array_values(array_slice($meta_keys, 0, $key, true)),
							array($insert_this),
							array_values(array_slice($meta_keys, $key, count($meta_keys) - 1, true))
						);

				} else {

					$meta_keys = array_merge(array_values($meta_keys), array($insert_this));
				}
			}

			// Process wp_error_process
			public function wp_error_process($user) {

				$error_messages = $user->get_error_messages();
				self::error_js($error_messages);
			}

			// Error
			public function error_js($error_messages) {

				if(!is_array($error_messages)) { $error_messages = array($error_messages); }

				foreach($error_messages as $error_message) {

					// Show the message
					parent::error($error_message, array(

						array(

							'action' => 'message',
							'message' => $error_message,
							'type' => 'danger',
							'method' => $this->message_method,
							'clear' => $this->message_clear,
							'scroll_top' => $this->message_scroll_top,
							'duration' => $this->message_duration,
							'form_hide' => $this->message_form_hide,
							'form_show' => $this->message_form_hide,
							'message_hide' => false
						)
					));
				}
			}

			// Load config for this action
			public function load_config($config = array()) {

				if($this->list_id === false) { $this->list_id = parent::get_config($config, 'action_' . $this->id . '_list_id'); }
				$this->secure_cookie = parent::get_config($config, 'action_' . $this->id . '_secure_cookie', '');
				$this->show_admin_bar_front = parent::get_config($config, 'action_' . $this->id . '_show_admin_bar_front', '');
				$this->rich_editing = parent::get_config($config, 'action_' . $this->id . '_rich_editing', '');
				$this->syntax_highlighting = parent::get_config($config, 'action_' . $this->id . '_syntax_highlighting', '');
				$this->comment_shortcuts = parent::get_config($config, 'action_' . $this->id . '_comment_shortcuts', '');
				$this->admin_color = parent::get_config($config, 'action_' . $this->id . '_admin_color', '');

				// Field mapping
				$this->field_mapping = parent::get_config($config, 'action_' . $this->id . '_field_mapping', array());
				if(!is_array($this->field_mapping)) { $this->field_mapping = array(); }

				// Field mapping - ACF
				if($this->acf_activated) {

					$this->field_mapping_acf = parent::get_config($config, 'action_' . $this->id . '_field_mapping_acf', array());
					if(!is_array($this->field_mapping_acf)) { $this->field_mapping_acf = array(); }
				}

				// Meta mapping
				$this->meta_mapping = parent::get_config($config, 'action_' . $this->id . '_meta_mapping', array());
				if(!is_array($this->meta_mapping)) { $this->meta_mapping = array(); }

				// Custom meta mapping
				$this->meta_mapping_custom = parent::get_config($config, 'action_' . $this->id . '_meta_mapping_custom', array());
				if(!is_array($this->meta_mapping_custom)) { $this->meta_mapping_custom = array(); }

				$this->role = parent::get_config($config, 'action_' . $this->id . '_role', get_option('default_role'));
				$this->password_create = parent::get_config($config, 'action_' . $this->id . '_password_create', '');
				$this->password_length = parent::get_config($config, 'action_' . $this->id . '_password_length', self::WS_FORM_PASSWORD_LENGTH_DEFAULT);
				$this->password_special_characters = parent::get_config($config, 'action_' . $this->id . '_password_special_characters', '');
				$this->role = parent::get_config($config, 'action_' . $this->id . '_role', get_option('default_role'));
				$this->role = parent::get_config($config, 'action_' . $this->id . '_role', get_option('default_role'));

				$this->message_method = parent::get_config($config, 'action_' . $this->id . '_message_method', 'before');
				$this->message_clear = parent::get_config($config, 'action_' . $this->id . '_message_clear', 'on');
				$this->message_scroll_top = parent::get_config($config, 'action_' . $this->id . '_message_scroll_top', '');
				$this->message_duration = parent::get_config($config, 'action_' . $this->id . '_message_duration', 4000);
				$this->message_form_hide = parent::get_config($config, 'action_' . $this->id . '_message_form_hide', '');
			}

			// Load config at plugin level
			public function load_config_plugin() {

				$this->configured = true;
				return $this->configured;
			}

			// Build REST API endpoints
			public function rest_api_init() {

				// API routes - get_* (Use cache)
				register_rest_route(WS_FORM_RESTFUL_NAMESPACE, '/action/' . $this->id . '/lists/', array('methods' => 'GET', 'callback' => array($this, 'api_get_lists'), 'permission_callback' => function () { return WS_Form_Common::can_user('create_form'); }));
				register_rest_route(WS_FORM_RESTFUL_NAMESPACE, '/action/' . $this->id . '/list/(?P<list_id>[a-zA-Z0-9]+)/', array('methods' => 'GET', 'callback' => array($this, 'api_get_list'), 'permission_callback' => function () { return WS_Form_Common::can_user('create_form'); }));
				register_rest_route(WS_FORM_RESTFUL_NAMESPACE, '/action/' . $this->id . '/list/(?P<list_id>[a-zA-Z0-9]+)/fields/', array('methods' => 'GET', 'callback' => array($this, 'api_get_list_fields'), 'permission_callback' => function () { return WS_Form_Common::can_user('create_form'); }));

				// API routes - fetch_* (Pull from API and update cache)
				register_rest_route(WS_FORM_RESTFUL_NAMESPACE, '/action/' . $this->id . '/lists/fetch/', array('methods' => 'GET', 'callback' => array($this, 'api_fetch_lists'), 'permission_callback' => function () { return WS_Form_Common::can_user('create_form'); }));
				register_rest_route(WS_FORM_RESTFUL_NAMESPACE, '/action/' . $this->id . '/list/(?P<list_id>[a-zA-Z0-9]+)/fetch/', array('methods' => 'GET', 'callback' => array($this, 'api_fetch_list'), 'permission_callback' => function () { return WS_Form_Common::can_user('create_form'); }));
				register_rest_route(WS_FORM_RESTFUL_NAMESPACE, '/action/' . $this->id . '/list/(?P<list_id>[a-zA-Z0-9]+)/fields/fetch/', array('methods' => 'GET', 'callback' => array($this, 'api_fetch_list_fields'), 'permission_callback' => function () { return WS_Form_Common::can_user('create_form'); }));
			}

			// API endpoint - Lists
			public function api_get_lists() {

				// Get lists
				$lists = self::get_lists();

				// Process response
				self::api_response($lists);
			}

			// API endpoint - List
			public function api_get_list($parameters) {

				// Get lists
				$this->list_id = WS_Form_Common::get_query_var('list_id', false, $parameters);
				$list = self::get_list();

				// Process response
				self::api_response($list);
			}

			// API endpoint - List fields
			public function api_get_list_fields($parameters) {

				// Get lists
				$this->list_id = WS_Form_Common::get_query_var('list_id', false, $parameters);
				$list_fields = self::get_list_fields(false, false);

				// Process response
				self::api_response($list_fields);
			}

			// API endpoint - Lists with fetch
			public function api_fetch_lists() {

				// Get lists
				$lists = self::get_lists(true);

				// Process response
				self::api_response($lists);
			}

			// API endpoint - List with fetch
			public function api_fetch_list($parameters) {

				// Get lists
				$this->list_id = WS_Form_Common::get_query_var('list_id', false, $parameters);
				$list = self::get_list(true);

				// Process response
				self::api_response($list);
			}

			// API endpoint - List fields with fetch
			public function api_fetch_list_fields($parameters) {

				// Get lists
				$this->list_id = WS_Form_Common::get_query_var('list_id', false, $parameters);
				$list_fields = self::get_list_fields(true, false);

				// Process response
				self::api_response($list_fields);
			}

			// SVG Logo - Color (Used for the 'Add Form' page)
			public function get_svg_logo_color($list_id = false) {

				$svg_logo = '<style>.wsf-wordpress-logo{fill:#595c60}</style><g id="logo" transform="translate(46.000000, 62.000000)"><path class="wsf-wordpress-logo" d="M25 1.5a23.3 23.3 0 0 1 16.62 6.89 23.4 23.4 0 0 1 5.04 7.47 23.45 23.45 0 0 1-2.17 22.29 23.91 23.91 0 0 1-6.35 6.35 23.45 23.45 0 0 1-26.28 0 23.91 23.91 0 0 1-6.35-6.35A23.56 23.56 0 0 1 15.86 3.34 23.4 23.4 0 0 1 25 1.5M25 0a25 25 0 1 0 0 50 25 25 0 0 0 0-50z"/><path class="wsf-wordpress-logo" d="M4.17 25c0 8.25 4.79 15.37 11.74 18.75L5.97 16.52A20.69 20.69 0 0 0 4.17 25zm34.89-1.05a11 11 0 0 0-1.72-5.75c-1.06-1.72-2.05-3.17-2.05-4.89 0-1.91 1.45-3.7 3.5-3.7l.27.02a20.8 20.8 0 0 0-31.47 3.93l1.34.03c2.18 0 5.55-.26 5.55-.26 1.12-.07 1.26 1.58.13 1.72 0 0-1.13.13-2.38.2l7.59 22.57 4.56-13.67-3.25-8.89c-1.12-.07-2.19-.2-2.19-.2-1.12-.07-.99-1.78.13-1.72 0 0 3.44.26 5.49.26 2.18 0 5.55-.26 5.55-.26 1.12-.07 1.26 1.58.13 1.72 0 0-1.13.13-2.38.2l7.53 22.39 2.15-6.81c.96-3 1.52-5.11 1.52-6.89zm-13.69 2.87l-6.25 18.16a20.81 20.81 0 0 0 12.81-.33 1.32 1.32 0 0 1-.15-.29l-6.41-17.54zM43.28 15c.09.66.14 1.38.14 2.14 0 2.11-.4 4.49-1.58 7.46L35.48 43a20.8 20.8 0 0 0 7.8-28z"/></g>';

				return $svg_logo;
			}
		}
	});