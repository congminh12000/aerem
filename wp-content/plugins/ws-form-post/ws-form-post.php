<?php

/**
 * @link			https://wsform.com/knowledgebase/post-management/
 * @since			1.0.0
 * @package			WS_Form_Post
 *
 * @wordpress-plugin
 * Plugin Name:		WS Form PRO - Post Management
 * Plugin URI:		https://wsform.com/knowledgebase/post-management/
 * Description:		Post Management add-on for WS Form PRO
 * Version:			1.1.23
 * Author:			Westguard Solutions
 * Author URI:		https://www.westguardsolutions.com/
 * Text Domain:		ws-form-post
 */

	Class WS_Form_Add_On_Post {

		const WS_FORM_PRO_ID 			= 'ws-form-pro/ws-form.php';
		const WS_FORM_PRO_VERSION_MIN 	= '1.7.150';

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

				new WS_Form_Action_Post();

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
			$dependency_notice = sprintf('<tr class="plugin-update-tr"><td colspan="3" class="plugin-update colspanchange"><div class="update-message notice inline notice-error notice-alt"><p>%s</p></div></td></tr>', sprintf(__('This add-on requires %s (version %s or later) to be installed and activated.', 'ws-form-post'), '<a href="https://wsform.com?utm_source=ws_form_pro&utm_medium=plugins" target="_blank">WS Form PRO</a>', self::WS_FORM_PRO_VERSION_MIN));

			// Show notice
			echo $dependency_notice;
		}
	}

	$wsf_add_on_post = new WS_Form_Add_On_Post();

	register_activation_hook(__FILE__, array($wsf_add_on_post, 'activate'));

	// This gets fired by WS Form when it is ready to register add-ons
	add_action('wsf_plugins_loaded', function() {

		class WS_Form_Action_Post extends WS_Form_Action {

			public $id = 'post';
			public $pro_required = true;
			public $label;
			public $label_action;
			public $events;
			public $multiple = true;
			public $configured = false;
			public $priority = 25;
			public $can_repost = true;
			public $form_add = false;

			// Add new features
			public $add_new_reload = false;

			// Licensing
			private $licensing;

			// Config
			public $method;
			public $list_id = false;
			public $status;
			public $field_mapping;
			public $meta_mapping_custom;
			public $field_mapping_acf;
			public $meta_mapping;
			public $tag_mapping;
			public $attachment_mapping;
			public $featured_image;
			public $deduplication_mapping;
			public $author;
			public $author_restrict;
			public $comment_status;
			public $ping_status;
			public $date;
			public $expose;
			public $page_template;
			public $post_id;

			public $form_populate_post_id;
			public $form_populate_meta_mapping;

			public $message_method;
			public $message_clear;
			public $message_scroll_top;
			public $message_duration;
			public $message_form_hide;

			// ACF
			public $acf_activated;

			// Constants
			const WS_FORM_LICENSE_ITEM_ID = 1642;
			const WS_FORM_LICENSE_NAME = 'Post Management add-on for WS Form PRO';
			const WS_FORM_LICENSE_VERSION = '1.1.23';
			const WS_FORM_LICENSE_AUTHOR = 'Westguard Solutions';
			const DEFAULT_POST_STATUS = 'publish';
			const DEFAULT_POST_TYPE = 'post';
			const DEFAULT_COMMENT_STATUS = 'closed';
			const DEFAULT_PING_STATUS = 'closed';

			public function __construct() {

				// Set label
				$this->label = __('Post Management', 'ws-form-post');

				// Set label for actions pull down
				$this->label_action = __('Post Management', 'ws-form-post');

				// Events
				$this->events = array('submit');

				// ACF
				$this->acf_activated = class_exists('ACF');

				// Register config filters
				add_filter('wsf_config_options', array($this, 'config_options'), 10, 1);
				add_filter('wsf_config_meta_keys', array($this, 'config_meta_keys'), 11, 2);
				add_filter('wsf_config_settings_form_admin', array($this, 'config_settings_form_admin'), 20, 1);
				add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'plugin_action_links'), 10, 1);
				add_action('rest_api_init', array($this, 'rest_api_init'));

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
				array_unshift($links, sprintf('<a href="%s">%s</a>', WS_Form_Common::get_admin_url('ws-form-settings', false, 'tab=action_' . $this->id), __('Settings', 'ws-form-post')));

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

							$value = '<input class="wsf-button" type="button" data-action="wsf-mode-submit" data-mode="deactivate" value="' . __('Deactivate', 'ws-form-post') . '" />';

						} else {

							$value = '<input class="wsf-button" type="button" data-action="wsf-mode-submit" data-mode="activate" value="' . __('Activate', 'ws-form-post') . '" />';
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

				global $wpdb;

				// Check action is configured properly
				if(!self::check_configured()) { return false; }

				// Load configuration
				self::load_config($config);

				// Clear hidden meta values?
				$submit_parse = clone $submit;
				if($this->clear_hidden_meta_values) { $submit_parse->clear_hidden_meta_values(); }

				// Check post ID
				if($this->post_id != '') {

					$this->post_id = WS_Form_Common::parse_variables_process($this->post_id, $form, $submit_parse, 'text/plain');
				}

				// Check list ID is configured properly
				if(!self::check_list_id()) { return false; }

				// Get post type name
				$post_type = get_post_type_object($this->list_id);
				if(empty($post_type)) { return array(); }

				// Dedupe mapping
				$deduplication_mapping = array();
				foreach($this->deduplication_mapping as $deduplication_map) {

					$deduplication_mapping[] = $deduplication_map['ws_form_field'];
				}

				// Process field mapping
				$api_fields = array();
				$meta_input = array();

				foreach($this->field_mapping as $field_map) {

					// Get API field
					$api_field = $field_map['action_' . $this->id . '_list_fields'];

					// Get submit value
					$field_id = $field_map['ws_form_field'];
					$submit_value = parent::get_submit_value($submit_parse, WS_FORM_FIELD_PREFIX . $field_id, false, true);
					if($submit_value === false) { continue; }

					// Post cannot accept arrays
					if(is_array($submit_value)) { $submit_value = implode(', ', $submit_value); }

					// Check for duplicate
					if(in_array($field_id, $deduplication_mapping)) {

						$sql = sprintf("SELECT ID FROM %s WHERE post_type = '%s' AND NOT(post_status = 'trash') AND %s = '%s' LIMIT 1", $wpdb->posts, esc_sql($this->list_id), esc_sql($api_field), esc_sql($submit_value));
						$post_id = $wpdb->get_var($sql);
						if(!is_null($post_id)) {

							// Error
							self::error_js(sprintf(__('Duplicate %s submitted.' , 'ws-form-post'), strtolower($post_type->labels->singular_name)));

							// Halt
							return 'halt';
						}
					}

					// Save field
					$api_fields[$api_field] = $submit_value;
				}

				// Process field meta mapping
				foreach($this->meta_mapping as $meta_map) {

					$field_id = $meta_map['ws_form_field'];
					$meta_key = $meta_map['action_' . $this->id . '_meta_key'];
					if(empty($meta_key)) { continue; }

					// Get submit value
					$meta_value = parent::get_submit_value($submit_parse, WS_FORM_FIELD_PREFIX . $field_id, false, true);
					if($meta_value === false) { continue; }

					// Handle arrays
					if(is_array($meta_value)) { $meta_value = implode(',', $meta_value); }

					// Check for duplicate
					if(in_array($field_id, $deduplication_mapping)) {

						$sql = sprintf('SELECT ID FROM %1$s RIGHT JOIN %2$s ON %1$s.post_id = %2$s.ID WHERE %1$s.meta_key = \'%3$s\' AND %1$s.meta_value = \'%4$s\' AND %2$s.post_type = \'%5$s\' AND NOT(%2$s.post_status = \'trash\') LIMIT 1', $wpdb->postmeta, $wpdb->posts, esc_sql($meta_key), esc_sql($meta_value), esc_sql($this->list_id));
						$post_id = $wpdb->get_var($sql);
						if(!is_null($post_id)) {

							// Error
							self::error_js(sprintf(__('Duplicate %s submitted.' , 'ws-form-post'), strtolower($post_type->labels->singular_name)));

							// Halt
							return 'halt';
						}
					}

					$meta_input[$meta_key] = $meta_value;
				}

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
						$get_submit_value_repeatable_return = parent::get_submit_value_repeatable($submit_parse, WS_FORM_FIELD_PREFIX . $field_id, array(), true);
						if(
							!is_array($get_submit_value_repeatable_return) ||
							!is_array($get_submit_value_repeatable_return['value']) ||
							!isset($get_submit_value_repeatable_return['value'][0])
						) { continue; }

						// Run through each value and check for duplication
						foreach($get_submit_value_repeatable_return['value'] as $meta_value) {

							// Deduplication
							if(in_array($field_id, $deduplication_mapping)) {
	 
								$meta_value_dedupe = is_array($meta_value) ? serialize($meta_value) : $meta_value;

								$sql = sprintf('SELECT ID FROM %1$s RIGHT JOIN %2$s ON %1$s.post_id = %2$s.ID WHERE %1$s.meta_key = \'%3$s\' AND %1$s.meta_value = \'%4$s\' AND %2$s.post_type = \'%5$s\' AND NOT(%2$s.post_status = \'trash\') LIMIT 1', $wpdb->postmeta, $wpdb->posts, esc_sql($meta_key), esc_sql($meta_value_dedupe), esc_sql($this->list_id));
								$post_id = $wpdb->get_var($sql);
								if(!is_null($post_id)) {

									// Error
									self::error_js(sprintf(__('Duplicate %s submitted.' , 'ws-form-post'), strtolower($post_type->labels->singular_name)));

									// Halt
									return 'halt';
								}
							}
						}

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
								foreach($this->attachment_mapping as $attachment_map) {

									if($attachment_map['ws_form_field'] == $field_id) {

										$field_already_mapped = true;
										break;
									}
								}
								if(!$field_already_mapped) {

									$this->attachment_mapping[] = array('ws_form_field' => $field_id);
								}

								// Remember which ACF key this field needs to be mapped to
								$acf_file_fields[$field_id] = $acf_key;

								break;
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

								$meta_input[$acf_data_meta_key] = ($acf_parent_field_type == 'repeater') ? $row_count : '';
								$meta_input['_' . $acf_data_meta_key] = $acf_data_acf_key;

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
										$meta_input[$meta_key] = $meta_value;
										$meta_input['_' . $meta_key] = $acf_key;
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
									$meta_input[$meta_key] = $meta_value;
									$meta_input['_' . $meta_key] = $acf_key;
								}
						}
					}
				}

				// Process custom meta mapping
				foreach($this->meta_mapping_custom as $meta_map) {

					$meta_key = $meta_map['action_' . $this->id . '_meta_key'];
					if(empty($meta_key)) { continue; }

					$meta_value = $meta_map['action_' . $this->id . '_meta_value'];

					// If meta value is serialized, unserialize it$meta_value = unserialize($meta_value);
					if(is_serialized($meta_value)) { $meta_value = unserialize($meta_value); }

					// Parse meta value
					$meta_value = WS_Form_Common::parse_variables_process($meta_value, $form, $submit_parse, 'text/plain');

					$meta_input[$meta_key] = $meta_value;
				}

				// Build post
				$postarr = array();

				// Author
				$post_author = isset($this->author) ? $this->author : '';
				if($post_author != '') { $postarr['post_author'] = $this->author; } 

				// Status
				$postarr['post_status'] = (isset($this->status) && ($this->status != '')) ? $this->status : self::DEFAULT_POST_STATUS;

				// Type
				$postarr['post_type'] = (isset($this->list_id) && ($this->list_id != '')) ? $this->list_id : self::DEFAULT_POST_TYPE;

				// Comment status
				$postarr['comment_status'] = (isset($this->comment_status) && ($this->comment_status != '')) ? $this->comment_status : self::DEFAULT_COMMENT_STATUS;

				// Ping status
				$postarr['ping_status'] = (isset($this->ping_status) && ($this->ping_status != '')) ? $this->ping_status : self::DEFAULT_PING_STATUS;

				// Page template
				$page_template = (($this->list_id == 'page') && isset($this->page_template)) ? $this->page_template : '';
				if($page_template != '') { $postarr['page_template'] = $this->page_template; } 

				// Post title
				$post_title = isset($api_fields['post_title']) ? $api_fields['post_title'] : false;
				if($post_title !== false) { $postarr['post_title'] = $post_title; }

				// Post content
				$post_content = isset($api_fields['post_content']) ? $api_fields['post_content'] : false;
				if($post_content !== false) { $postarr['post_content'] = $post_content; }

				// Post excerpt
				$post_excerpt = isset($api_fields['post_excerpt']) ? $api_fields['post_excerpt'] : false;
				if($post_excerpt !== false) { $postarr['post_excerpt'] = $post_excerpt; }

				// Meta input
				if(count($meta_input) > 0) { $postarr['meta_input'] = $meta_input; }

				// Post ID
				$this->post_id = intval($this->post_id);
				if($this->post_id > 0) {

					if(get_post_type($this->post_id) !== $this->list_id) {

						// Error
						self::error_js(__('Invalid post type.', 'ws-form-post'));

						// Halt
						return 'halt';
					}

					$postarr['ID'] = $this->post_id;

					$post_method = __('updated', 'ws-form-post');

				} else {

					$post_method = __('added', 'ws-form-post');
				}

				if(isset($postarr['ID'])) {

					// Check for author restriction
					if($this->author_restrict) {

						$post = get_post($postarr['ID']);
						if(is_null($post)) {

							self::error_js(__('Invalid post ID', 'ws-form'));

							return 'halt';
						}
						$post_author_id = intval($post->post_author);

						if($post_author_id !== WS_Form_Common::get_user_id(false)) {

							self::error_js(__('Insufficient permissions to update', 'ws-form'));

							return 'halt';
						}
					}

					// WordPress update post
					$post_id = wp_update_post($postarr, true);

				} else {

					// WordPress insert post
					$post_id = wp_insert_post($postarr, true);
				}

				// Error management
				if(is_wp_error($post_id)) {

					self::wp_error_process($post_id);
					return 'halt';
				}

				// Save post ID to submission
				$submit->post_id = $post_id;

				// Process tags
				$taxonomy_tags = array();
				foreach($this->tag_mapping as $tag_map) {

					// Get taxonomy
					$taxonomy = $tag_map['action_' . $this->id . '_tag_category_id'];
					if(!isset($taxonomy_tags[$taxonomy])) { $taxonomy_tags[$taxonomy] = array(); }
					$taxonomy_obj = get_taxonomy($taxonomy);
					if (!$taxonomy_obj) { continue; }

					// Get field ID
					$field_id = $tag_map['ws_form_field'];
					if($field_id == '') { continue; }

					// Read submit meta
					$tags = parent::get_submit_value($submit_parse, WS_FORM_FIELD_PREFIX . $field_id, false);
					if($tags !== false) {

						// Turn into array if it isn't already
						if(!is_array($tags)) { $tags = (($tags == '') ? array() : explode(',', $tags)); }

						// Convert tags to integers
						foreach($tags as $index => $tag) {

							if(is_numeric($tag)) { $tags[$index] = intval($tag); }
						}

						$taxonomy_tags[$taxonomy] = array_merge($taxonomy_tags[$taxonomy], $tags);
					}
				}

				foreach($taxonomy_tags as $taxonomy => $tags) {

					// Set object terms
					wp_set_object_terms($post_id, $tags, $taxonomy);
				}

				// Check for a featured image
				$featured_image_field_id = (intval($this->featured_image) == 0) ? false : intval($this->featured_image);

				// Add featured image field ID to attachment map
				$featured_image_field_id_mapped = false;
				foreach($this->attachment_mapping as $attachment_map) {

					$field_id = $attachment_map['ws_form_field'];
					if($field_id == $featured_image_field_id) { $featured_image_field_id_mapped = true; }
				}
				if(!$featured_image_field_id_mapped) {

					$this->attachment_mapping[] = array('ws_form_field' => $featured_image_field_id);
				}

				// Process attachment mapping
				$files = array();
				foreach($this->attachment_mapping as $attachment_map) {

					$field_id = $attachment_map['ws_form_field'];

					// Get submit value
					$get_submit_value_repeatable_return = parent::get_submit_value_repeatable($submit_parse, WS_FORM_FIELD_PREFIX . $field_id, array(), true);

					if(
						!is_array($get_submit_value_repeatable_return) ||
						!is_array($get_submit_value_repeatable_return['value']) ||
						!isset($get_submit_value_repeatable_return['value'][0])
					) { continue; }

					// Add each value
					foreach($get_submit_value_repeatable_return['value'] as $repeater_index => $meta_value) {

						$file_objects = $get_submit_value_repeatable_return['value'][$repeater_index];
						if(!is_array($file_objects)) { continue; }

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

							// set_post_thumbnail?
							$set_post_thumbnail = ($featured_image_field_id !== false) ? ($field_id == $featured_image_field_id) : false;

							if($handler === 'attachment') {

								if(!isset($file_object['attachment_id'])) { continue; }
								$attachment_id = intval($file_object['attachment_id']);
								if(!$attachment_id) { continue; }

								// Build file array
								$file_single = array(

									'attachment_id'			=>	$attachment_id,
									'set_post_thumbnail'	=>	$set_post_thumbnail,
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
									'set_post_thumbnail'	=>	$set_post_thumbnail,
									'field_id'				=>	$field_id,
									'repeater_index'		=>	$repeater_index
								);
								$files[] = $file_single;
							}

							// Reset set_post_thumbnail
							if($set_post_thumbnail) { $featured_image_field_id = false; }
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

							// Assign attachment to this post
							wp_update_post(
	
								array(
	
									'ID' => $attachment_id, 
									'post_parent' => $post_id
								)
							);

						} else {

							// Need to require these files
							if(!function_exists('media_handle_upload')) {

								require_once(ABSPATH . "wp-admin" . '/includes/image.php');
								require_once(ABSPATH . "wp-admin" . '/includes/file.php');
								require_once(ABSPATH . "wp-admin" . '/includes/media.php');
							}

							$attachment_id = media_handle_sideload($file, $post_id);

							// Error management
							if(is_wp_error($attachment_id)) {

								self::wp_error_process($attachment_id);
								return 'halt';
							}
						}

						// set_post_thumbnail
						if($file['set_post_thumbnail']) {

							set_post_thumbnail($post_id, $attachment_id);
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
							update_post_meta($post_id, $meta_key, (count($attachment_array) == 1) ? $attachment_array[0] : $attachment_array);
							update_post_meta($post_id, '_' . $meta_key, $acf_key);
						}
					}
				}

				// Expose?
				if($this->expose) {

					global $post;
					$post = get_post($post_id);
					setup_postdata($post);
					$GLOBALS['ws_form_post_root'] = $post;
				}

				// Success
				parent::success(sprintf(__('Successfully %s new %s (ID: %u)' , 'ws-form-post'), $post_method, $post_type->labels->singular_name, $post_id));

				return true;
			}

			// Get post data
			public function get($form, $user) {

				// Check action is configured properly
				if(!self::check_configured()) { return false; }

				// Check list ID is set
				if(!self::check_list_id()) { return false; }

				// Read form populate data - Post ID
				$post_id = WS_Form_Common::get_object_meta_value($form, 'action_' . $this->id . '_form_populate_post_id', '');
				if($post_id == '') { $post_id = '#post_id'; }
				$post_id = WS_Form_Common::parse_variables_process($post_id, $form);
				$post_id = intval($post_id);
				if($post_id == 0) { return false; }

				// Check post type of get
				if(get_post_type($post_id) != $this->list_id) { return false; }

				// Get the post
				$post = get_post($post_id);
				if(is_null($post)) { return false; }

				// Check for author restriction
				$author_restrict = WS_Form_Common::get_object_meta_value($form, 'action_' . $this->id . '_form_populate_author_restrict', '');
				if($author_restrict) {

					$post_author_id = intval($post->post_author);

					if($post_author_id !== WS_Form_Common::get_user_id(false)) {

						return false;
					}
				}

				$fields_return = array(

					'post_title'	=>	$post->post_title,
					'post_content'	=>	$post->post_content,
					'post_excerpt'	=>	$post->post_excerpt,
				);
				$fields_repeatable_return = array();
				$section_repeatable_return = array();

				// ACF field mapping
				$acf_update_field_array = array();
				if($this->acf_activated) {

					// Get first option value so we can use that to set the value
					$fields = WS_Form_Common::get_fields_from_form($form);

					// Get field types
		 			$field_types = WS_Form_Config::get_field_types_flat();

					// Get ACF field mappings
					$field_mapping_acf = WS_Form_Common::get_object_meta_value($form, 'action_' . $this->id . '_form_populate_field_mapping_acf', '');
					if(is_array($field_mapping_acf)) {

						// Get ACF field values for current post
						$acf_field_data = WS_Form_ACF::acf_get_field_data($post_id);

						// Run through each mapping
						foreach($field_mapping_acf as $field_map_acf) {

							// Get ACF field key
							$acf_key = $field_map_acf->{'action_' . $this->id . '_acf_key'};

							// Get field ID
							$field_id = $field_map_acf->ws_form_field;

							// Get meta value
							if(!isset($acf_field_data[$acf_key])) { continue; }

							// Read ACF field data
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
				if(is_array($meta_mapping)) {

					foreach($meta_mapping as $meta_map) {

						$meta_key = $meta_map->{'action_' . $this->id . '_meta_key'};
						$field_id = $meta_map->ws_form_field;
						$meta_value = get_post_meta($post_id, $meta_key, true);
						$fields_return[$field_id] = $meta_value;
					}
				}

				$tags_return = array();

				// Run through each taxonomy
				$args = array('object_type' => array($this->list_id));
				$taxonomies = get_taxonomies($args, 'objects');
				foreach ($taxonomies as $taxonomy) {

					$terms = wp_get_post_terms($post_id, $taxonomy->name);

					foreach($terms as $term) {

						$tags_return[$term->term_id] = true;
					}
				}

				// Featured image
				$featured_image_field_id = intval(WS_Form_Common::get_object_meta_value($form, 'action_' . $this->id . '_form_populate_featured_image', ''));
				if($featured_image_field_id > 0) {

					$post_thumbnail_id = get_post_thumbnail_id($post);

					if($post_thumbnail_id) {

						$fields_return[$featured_image_field_id] = array(WS_Form_File_Handler::get_file_object_from_attachment_id($post_thumbnail_id));
					}
				}

				$return_array = array('fields' => $fields_return, 'section_repeatable' => $section_repeatable_return, 'fields_repeatable' => $fields_repeatable_return, 'tags' => $tags_return);
				return $return_array;
			}

			// Get lists
			public function get_lists($fetch = false) {

				// Check action is configured properly
				if(!self::check_configured()) { return false; }

				$lists = array();

				$post_types_exclude = array('attachment');
				$post_types = get_post_types(array('show_in_menu' => true), 'objects', 'or');

				foreach($post_types as $post_type) {

					$post_type_name = $post_type->name;

					if(in_array($post_type_name, $post_types_exclude)) { continue; }

					$post_count_object = wp_count_posts($post_type_name);
					$record_count = (isset($post_count_object->publish)) ? $post_count_object->publish : 0;

					$lists[] = array(

						'id' => 			$post_type_name, 
						'label' => 			$post_type->labels->singular_name, 
						'field_count' => 	false,
						'record_count' => 	$record_count
					);
				}

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

				// Read post type
				$post_type = get_post_type_object($this->list_id);
				if(empty($post_type)) { return array(); }

				// Set label
				$label = $post_type->labels->singular_name;

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

				// Check if Visual Editor can be selected
				global $wp_version;
				$post_content_meta = (version_compare($wp_version, '4.8', '>=')) ? array('input_type_textarea' => 'tinymce') : false;

				// Post fields
				$fields = array();
				$sort_index = 1;

				// Check if title is supported
				if(post_type_supports($this->list_id, 'title')) {

					$fields[] = (object) array('id' => 'post_title', 'name' => __('Title', 'ws-form-post'), 'type' => 'text', 'required' => true, 'meta' => false);
				}

				// Check if editor is supported
				if(post_type_supports($this->list_id, 'editor')) {

					$fields[] = (object) array('id' => 'post_content', 'name' => __('Content', 'ws-form-post'), 'type' => 'textarea', 'required' => false, 'meta' => $post_content_meta);
				}

				// Check if excerpt is supported
				if(post_type_supports($this->list_id, 'excerpt')) {

					$fields[] = (object) array('id' => 'post_excerpt', 'name' => __('Excerpt', 'ws-form-post'), 'type' => 'textarea', 'required' => false, 'meta' => false);
				}

				// Check if featured image is supported
				if(post_type_supports($this->list_id, 'thumbnail')) {

					$fields[] = (object) array('id' => 'featured_image', 'name' => __('Featured Image', 'ws-form-post'), 'type' => 'file', 'required' => false, 'meta' => array('accept' => 'image/jpeg,image/gif,image/png', 'sub_type' => 'dropzonejs', 'file_handler' => 'attachment'), 'no_map' => true);
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

				// Get ACF fields
				if($this->acf_activated && $acf) {

					$groups = acf_get_field_groups(array('post_type' => $this->list_id));

					$fields = array();

					foreach($groups as $group) {

						if(!isset($group['key'])) { continue; }
						$fields = array_merge($fields, acf_get_fields($group['key']));
					}

					$acf_fields_to_list_fields_return = WS_Form_ACF::acf_fields_to_list_fields($fields, 0, 0, $sort_index);
					$list_fields = array_merge($list_fields, $acf_fields_to_list_fields_return['list_fields']);
				}

				return $list_fields;
			}

			// Get list fields meta data (Returns group and section data such as label and whether or not a section is repeatable)
			public function get_list_fields_meta_data() {

				$group_meta_data = array();
				$section_meta_data = array();

				// Get ACF fields
				if($this->acf_activated) {

					$groups = acf_get_field_groups(array('post_type' => $this->list_id));

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

				$form_fields = array(

					'submit' => array(

						'type'			=>	'submit',
						'label'			=>	__('Submit', 'ws-form-post')
					)
				);

				return $form_fields;
			}

			// Get form actions
			public function get_actions($form_field_id_lookup_all, $form_field_type_lookup) {

				// Get post type name
				$post_type = get_post_type_object($this->list_id);
				if(empty($post_type)) { return array(); }

				// Set label
				$label = $post_type->labels->singular_name;

				$form_actions = array(

					$this->id => array(

						'meta'	=> array(

							'action_' . $this->id . '_list_id'			=>	$this->list_id,
							'action_' . $this->id . '_field_mapping'	=>	'field_mapping',
							'action_' . $this->id . '_tag_mapping'		=>	'tag_mapping'
						)
					),

					'message' => array(

						'meta'	=> array(

							'action_message_message'	=> sprintf(__('Successfully added %s.', 'ws-form-post'), strtolower($label))
						)
					)
				);

				if(post_type_supports($this->list_id, 'thumbnail')) {

					$form_actions[$this->id]['meta']['action_' . $this->id . '_featured_image'] = '#featured_image';
				}

				if($this->acf_activated) {

					$list_fields = array();

					// Get ACF fields
					$groups = acf_get_field_groups(array('post_type' => $this->list_id));

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

				// Look for file or image fields that we can map as attachments
				$form_actions[$this->id]['meta']['action_' . $this->id . '_attachment_mapping'] = array();
				foreach($form_field_type_lookup as $field_id => $field_type) {

					switch($field_type) {

						case 'file' :
						case 'signature' :

							$form_actions[$this->id]['meta']['action_' . $this->id . '_attachment_mapping'][] = array(

								'ws_form_field' => $field_id
							);
							break;
					}
				}

				return $form_actions;
			}

			// Get form meta
			public function get_meta($form_field_id_lookup_all) {

				if(!$this->acf_activated) { return array(); }

				$list_fields = array();
				$form_meta = array();

				// Get ACF fields
				$groups = acf_get_field_groups(array('post_type' => $this->list_id));

				$fields = array();

				foreach($groups as $group) {

					if(!isset($group['key'])) { continue; }
					$fields = array_merge($fields, acf_get_fields($group['key']));
				}

				$acf_fields_to_list_fields_return = WS_Form_ACF::acf_fields_to_list_fields($fields);
				$list_fields = $acf_fields_to_list_fields_return['list_fields'];

				foreach($list_fields as $list_field) {

					if(!isset($form_field_id_lookup_all[$list_field['id']])) { continue; }

					$form_meta['action_' . $this->id . '_form_populate_field_mapping_acf'][] = array(

						'action_' . $this->id . '_acf_key' => $list_field['id'],
						'ws_form_field' => $form_field_id_lookup_all[$list_field['id']]
					);
				}

				$form_meta['action_' . $this->id . '_form_populate_featured_image'] = $form_field_id_lookup_all['featured_image'];

				return $form_meta;
			}

			// Get tag categories
			public function get_tag_categories($fetch = false) {

				// Check action is configured properly
				if(!self::check_configured()) { return false; }

				// Check list ID is set
				if(!self::check_list_id()) { return false; }

				$tag_categories = WS_Form_Common::option_get('action_' . $this->id . '_tag_categories_' . $this->list_id);

				if($fetch || ($tag_categories === false)) {

					$tag_categories = array();

					// Taxonomy
					$args = array('object_type' => array($this->list_id));
					$taxonomies = get_taxonomies($args, 'objects');
					$sort_index = 1; 
					foreach ($taxonomies as $taxonomy) {

						$tag_categories[] = array(

							'id'			=> $taxonomy->name,
							'label'			=> $taxonomy->labels->singular_name, 
							'sort_index'	=> $sort_index,
							'type'			=> 'checkbox',
							'data_source'	=> array(

								'id'	=> 'term',
								'meta'	=>	array(

									'data_source_term_filter_taxonomies' => array(

										array('data_source_term_taxonomies' => $taxonomy->name)
									),

									'data_source_term_groups' => ''
								)
							)
						);

						$sort_index++;
					}

					// Store to options
					WS_Form_Common::option_set('action_' . $this->id . '_tag_categories_' . $this->list_id, $tag_categories);
				}

				return $tag_categories;
			}

			// Get tags
			public function get_tags($tag_category_id = false, $fetch = false) {

				// Check action is configured properly
				if(!self::check_configured()) { return false; }

				// Check list ID is set
				if(!self::check_list_id()) { return false; }

				// Check tag category ID is set
				if($tag_category_id === false) { self::error(__('Tag category ID is not set', 'ws-form-post')); }

				$tags = WS_Form_Common::option_get('action_' . $this->id . '_tag_categories_' . $this->list_id . '_tags_' . $tag_category_id);

				if($fetch || ($tags === false)) {

					$tags = array();

					// Check if taxonomy is hierachical
					$tag_category_hierachical = is_taxonomy_hierarchical($tag_category_id);

					// Get terms
					$api_tags = get_terms(array('taxonomy' => $tag_category_id, 'hide_empty' => false));

					$sort_index = 1;

					foreach($api_tags as $tag) {

						$tags[] = array(

							'id' => 			$tag->term_id,
//							'id' => 			($tag_category_hierachical ? $tag->term_id : $tag->name),
							'label' => 			$tag->name,
							'record_count' =>	$tag->count,
							'sort_index' =>		$sort_index
						);

						$sort_index++;
					}

					// Store to options
					WS_Form_Common::option_set('action_' . $this->id . '_tag_categories_' . $this->list_id . '_tags_' . $tag_category_id, $tags);
				}

				return $tags;
			}

			// Get settings
			public function get_action_settings() {

				$settings = array(

					'meta_keys'		=> array(

						'action_' . $this->id . '_list_id',
						'action_' . $this->id . '_post_id',
						'action_' . $this->id . '_page_template',
						'action_' . $this->id . '_field_mapping',
						'action_' . $this->id . '_clear_hidden_meta_values',
						'action_' . $this->id . '_meta_mapping',
						'action_' . $this->id . '_meta_mapping_custom',
						'action_' . $this->id . '_attachment_mapping',
						'action_' . $this->id . '_featured_image',
						'action_' . $this->id . '_deduplication_mapping',
						'action_' . $this->id . '_tag_mapping',
						'action_' . $this->id . '_status',
						'action_' . $this->id . '_author',
						'action_' . $this->id . '_author_restrict',
						'action_' . $this->id . '_comment_status',
						'action_' . $this->id . '_ping_status',
						'action_' . $this->id . '_date',
						'action_' . $this->id . '_expose',
						'action_' . $this->id . '_message_method',
						'action_' . $this->id . '_message_clear',
						'action_' . $this->id . '_message_scroll_top',
						'action_' . $this->id . '_message_form_hide',
						'action_' . $this->id . '_message_duration'
					)
				);

				// ACF
				if($this->acf_activated) {

					array_splice( $settings['meta_keys'], 4, 0, 'action_' . $this->id . '_field_mapping_acf');
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

				if(!$this->configured) { self::error(__('Action not configured', 'ws-form-post') . ' (' . $this->label . ''); }

				return $this->configured;
			}

			// Check list ID is set
			public function check_list_id() {

				if($this->list_id === false) { self::error(__('Post type is not set', 'ws-form-post')); }

				return ($this->list_id !== false);
			}

			// Meta keys for this action
			public function config_meta_keys($meta_keys = array(), $form_id = 0) {

				// Build config_meta_keys
				$config_meta_keys = array(

					// Post Type
					'action_' . $this->id . '_list_id'	=> array(

						'label'							=>	__('Post Type', 'ws-form-post'),
						'type'							=>	'select',
						'help'							=>	__('Which post type do you want to add or update?', 'ws-form-post'),
						'options'						=>	'action_api_populate',
						'options_blank'					=>	__('Select...', 'ws-form-post'),
						'options_action_id_meta_key'	=>	'action_id',
						'options_action_api_populate'	=>	'lists',
						'default'						=>	'post'
					),

					// Post ID
					'action_' . $this->id . '_post_id' => array(

						'label'			=>	__('Post ID (For Updates)', 'ws-form-post'),
						'type'			=>	'text',
						'default'		=>	'',
						'select_list'	=>	true,
						'help'			=>	__('If blank, a new post will be added. To update an existing post, enter a post ID or variable. <a href="https://wsform.com/knowledgebase/post-management/" target="_blank">Learn more</a>', 'ws-form-post'),
						'condition'					=>	array(

							array(

								'logic'			=>	'!=',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	''
							)
						)
					),

					// Clear hidden meta values
					'action_' . $this->id . '_clear_hidden_meta_values'	=> array(

						'label'						=>	__('Clear Hidden Fields', 'ws-form'),
						'type'						=>	'checkbox',
						'help'						=>	__('Enabling this will clear fields that were hidden when the form was submitted.', 'ws-form'),
						'default'					=>	'on'
					),

					// Field mapping
					'action_' . $this->id . '_field_mapping'	=> array(

						'label'						=>	__('Field Mapping', 'ws-form-post'),
						'type'						=>	'repeater',
						'help'						=>	__('Map WS Form fields to post fields.', 'ws-form-post'),
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
							)
						)
					),

					// Field meta mapping
					'action_' . $this->id . '_meta_mapping'	=> array(

						'label'						=>	__('Field Meta Mapping', 'ws-form-post'),
						'type'						=>	'repeater',
						'help'						=>	__('Map WS Form fields to post meta keys.', 'ws-form-post'),
						'meta_keys'					=>	array(

							'ws_form_field',
							'action_' . $this->id . '_meta_key'
						),
						'condition'					=>	array(

							array(

								'logic'			=>	'!=',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	''
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

								'logic'			=>	'!=',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	''
							)
						)
					),

					// Meta key
					'action_' . $this->id . '_meta_key'	=> array(

						'label'						=>	__('Meta Key', 'ws-form-post'),
						'type'						=>	'text'
					),

					// Meta value
					'action_' . $this->id . '_meta_value'	=> array(

						'label'						=>	__('Meta Value', 'ws-form-post'),
						'type'						=>	'text'
					),

					// Term mapping
					'action_' . $this->id . '_tag_mapping'	=> array(

						'label'						=>	__('Term Mapping', 'ws-form-post'),
						'type'						=>	'repeater',
						'help'						=>	__('Map WS Form fields to post terms.', 'ws-form-post'),
						'meta_keys'					=>	array(

							'ws_form_field',
							'action_' . $this->id . '_tag_category_id'
						),
						'meta_keys_unique'			=>	array(
							'action_' . $this->id . '_tag_category_id'
						),
						'condition'					=>	array(

							array(

								'logic'			=>	'!=',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	''
							)
						)
					),

					// Taxonomy
					'action_' . $this->id . '_tag_category_id' => array(

						'label'							=>	__('Taxonomy', 'ws-form-post'),
						'type'							=>	'select',
						'options'						=>	array(),
					),

					// Attachment mapping
					'action_' . $this->id . '_attachment_mapping'	=> array(

						'label'						=>	__('Attachment Mapping', 'ws-form-post'),
						'type'						=>	'repeater',
						'help'						=>	__('Map WS Form fields to post attachments.', 'ws-form-post'),
						'meta_keys'					=>	array(

							'ws_form_field_file'
						),
						'meta_keys_unique'			=>	array(

							'ws_form_field_file'
						),
						'condition'					=>	array(

							array(

								'logic'			=>	'!=',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	''
							)
						)
					),

					// Featured image
					'action_' . $this->id . '_featured_image'	=> array(

						'label'							=>	__('Featured Image', 'ws-form-post'),
						'type'							=>	'select',
						'options'						=>	'fields',
						'options_blank'					=>	__('Select...', 'ws-form-post'),
						'fields_filter_type'			=>	array('file', 'signature'),
						'help'							=>	__('Select which file field to use for the featured image.', 'ws-form-post'),
						'condition'					=>	array(

							array(

								'logic'			=>	'!=',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	''
							)
						)
					),

					// Deduplication
					'action_' . $this->id . '_deduplication_mapping'	=> array(

						'label'						=>	__('Deduplicate By Field', 'ws-form-post'),
						'type'						=>	'repeater',
						'help'						=>	__('Select unique WS Form fields.', 'ws-form-post'),
						'meta_keys'					=>	array(

							'ws_form_field'
						),
						'meta_keys_unique'			=>	array(
							'ws_form_field'
						),
						'condition'					=>	array(

							array(

								'logic'			=>	'!=',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	''
							)
						)
					),

					// List fields
					'action_' . $this->id . '_list_fields'	=> array(

						'label'							=>	__('Post Field', 'ws-form-post'),
						'type'							=>	'select',
						'options'						=>	'action_api_populate',
						'options_blank'					=>	__('Select...', 'ws-form-post'),
						'options_action_id'				=>	$this->id,
						'options_list_id_meta_key'		=>	'action_' . $this->id . '_list_id',
						'options_action_api_populate'	=>	'list_fields'
					),

					// Status
					'action_' . $this->id . '_status'	=> array(

						'label'						=>	__('Status', 'ws-form-post'),
						'type'						=>	'select',
						'help'						=>	__('Status post will be added with.', 'ws-form-post'),
						'options'					=>	array(),
						'default'					=>	self::DEFAULT_POST_STATUS,
						'condition'					=>	array(

							array(

								'logic'			=>	'!=',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	''
							)
						)
					),

					// Author
					'action_' . $this->id . '_author'	=> array(

						'label'						=>	__('Author', 'ws-form-post'),
						'type'						=>	'select',
						'help'						=>	__('Author of post.', 'ws-form-post'),
						'options'					=>	array(),
						'default'					=>	'',
						'condition'					=>	array(

							array(

								'logic'			=>	'!=',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	''
							)
						)
					),

					// Author - Restrict
					'action_' . $this->id . '_author_restrict'	=> array(

						'label'						=>	__('Restrict Updates to Author', 'ws-form-post'),
						'type'						=>	'checkbox',
						'help'						=>	__('Only allow posts to be updated by the original author.', 'ws-form-post'),
						'default'					=>	''
					),

					// Comment Status
					'action_' . $this->id . '_comment_status'	=> array(

						'label'						=>	__('Comment Status', 'ws-form-post'),
						'type'						=>	'select',
						'help'						=>	__('Whether the post can accept comments.', 'ws-form-post'),
						'options'					=>	array(

							array('value' => 'closed', 'text' => 'Closed'),
							array('value' => 'open', 'text' => 'Open')
						),
						'default'					=>	'closed',
						'condition'					=>	array(

							array(

								'logic'			=>	'!=',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	''
							)
						)
					),

					// Ping Status
					'action_' . $this->id . '_ping_status'	=> array(

						'label'						=>	__('Ping Status', 'ws-form-post'),
						'type'						=>	'select',
						'help'						=>	__('Whether the post can accept pings.', 'ws-form-post'),
						'options'					=>	array(

							array('value' => 'closed', 'text' => 'Closed'),
							array('value' => 'open', 'text' => 'Open')
						),
						'default'					=>	get_option('default_ping_status', self::DEFAULT_PING_STATUS),
						'condition'					=>	array(

							array(

								'logic'			=>	'!=',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	''
							)
						)
					),

					// Page Template
					'action_' . $this->id . '_page_template'	=> array(

						'label'						=>	__('Page Template', 'ws-form-post'),
						'type'						=>	'select',
						'help'						=>	__('Page template to assign page to.', 'ws-form-post'),
						'options'					=>	array(),
						'default'					=>	'default',
						'condition'					=>	array(

							array(

								'logic'			=>	'==',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	'page'
							)
						)
					),

					// Date
					'action_' . $this->id . '_date'	=> array(

						'label'						=>	__('Date', 'ws-form-post'),
						'type'						=>	'date',
						'help'						=>	__('The date of the post. Leave blank for current time.', 'ws-form-post'),
						'default'					=>	'',
						'condition'					=>	array(

							array(

								'logic'			=>	'!=',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	''
							)
						)
					),

					// Expose
					'action_' . $this->id . '_expose'	=> array(

						'label'						=>	__('Expose Post to Other Actions', 'ws-form-post'),
						'type'						=>	'checkbox',
						'help'						=>	__('If checked the newly created post data will be available in #post variables.', 'ws-form-post'),
						'default'					=>	'on'
					),

					// Message - Method
					'action_' . $this->id . '_message_method'	=> array(

						'label'						=>	__('Error Messages', 'ws-form-post'),
						'type'						=>	'select',
						'help'						=>	__('Where should error messages be added?', 'ws-form-post'),
						'options'					=>	array(

							array('value' => 'before', 'text' => __('Before Form', 'ws-form-post')),
							array('value' => 'after', 'text' => __('After Form', 'ws-form-post'))
						),
						'default'					=>	'before',
						'condition'					=>	array(

							array(

								'logic'			=>	'!=',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	''
							)
						)
					),

					// Message - Duration
					'action_' . $this->id . '_message_duration'	=> array(

						'label'						=>	__('Error Message Duration (ms)', 'ws-form-post'),
						'type'						=>	'number',
						'help'						=>	__('Duration in milliseconds error message shown.', 'ws-form-post'),
						'default'					=>	'4000',
						'condition'					=>	array(

							array(

								'logic'			=>	'!=',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	''
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
							)
						)
					),

					// Message - Form Hide
					'action_' . $this->id . '_message_form_hide'	=> array(

						'label'						=>	__('Hide Form On Error', 'ws-form-post'),
						'type'						=>	'checkbox',
						'help'						=>	__('Hide form when error message shown?', 'ws-form-post'),
						'default'					=>	'',
						'condition'					=>	array(

							array(

								'logic'			=>	'!=',
								'meta_key'		=>	'action_' . $this->id . '_list_id',
								'meta_value'	=>	''
							)
						)
					),

					// Auto Populate

					// Post ID
					'action_' . $this->id . '_form_populate_post_id' => array(

						'label'			=>	__('Post ID', 'ws-form-post'),
						'type'			=>	'text',
						'default'		=>	'',
						'placeholder'	=>	'#post_id',
						'help'			=>	__('If blank, WS Form will use the current post ID. You can also manually enter a post ID or use a variable such as #query_var("post_id").'),
						'select_list'	=>	true,
						'condition'		=>	array(

							array(

								'logic'			=>	'==',
								'meta_key'		=>	'form_populate_action_id',
								'meta_value'	=>	'post'
							),

							array(

								'logic'			=>	'==',
								'meta_key'		=>	'form_populate_enabled',
								'meta_value'	=>	'on'
							)
						)
					),

					// Field meta mapping
					'action_' . $this->id . '_form_populate_meta_mapping'	=> array(

						'label'						=>	__('Meta Mapping', 'ws-form-post'),
						'type'						=>	'repeater',
						'help'						=>	__('Map post meta key values to WS Form fields.', 'ws-form-post'),
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
								'meta_value'	=>	'post'
							),

							array(

								'logic'			=>	'!=',
								'meta_key'		=>	'form_populate_list_id',
								'meta_value'	=>	''
							),

							array(

								'logic'			=>	'==',
								'meta_key'		=>	'form_populate_enabled',
								'meta_value'	=>	'on'
							)
						)
					),

					// Form populate - Author - Restrict
					'action_' . $this->id . '_form_populate_author_restrict'	=> array(

						'label'						=>	__('Restrict Populate to Author', 'ws-form-post'),
						'type'						=>	'checkbox',
						'help'						=>	__('Only allow population of the form from posts authored by the logged in user.', 'ws-form-post'),
						'default'					=>	'',
						'condition'	=>	array(

							array(

								'logic'			=>	'==',
								'meta_key'		=>	'form_populate_action_id',
								'meta_value'	=>	'post'
							),

							array(

								'logic'			=>	'!=',
								'meta_key'		=>	'form_populate_list_id',
								'meta_value'	=>	''
							),

							array(

								'logic'			=>	'==',
								'meta_key'		=>	'form_populate_enabled',
								'meta_value'	=>	'on'
							)
						)
					),

					// Featured image
					'action_' . $this->id . '_form_populate_featured_image'	=> array(

						'label'							=>	__('Featured Image', 'ws-form-post'),
						'type'							=>	'select',
						'options'						=>	'fields',
						'options_blank'					=>	__('Select...', 'ws-form-post'),
						'fields_filter_type'			=>	array('file', 'signature'),
						'help'							=>	__('Select which file field to use for the featured image. Only file fields of type DropzoneJS using the Media Library file handler are compatible with this feature.', 'ws-form-post'),
						'condition'	=>	array(

							array(

								'logic'			=>	'==',
								'meta_key'		=>	'form_populate_action_id',
								'meta_value'	=>	'post'
							),

							array(

								'logic'			=>	'!=',
								'meta_key'		=>	'form_populate_list_id',
								'meta_value'	=>	''
							),

							array(

								'logic'			=>	'==',
								'meta_key'		=>	'form_populate_enabled',
								'meta_value'	=>	'on'
							)
						)
					),
				);

				// Add post status
				$post_statuses = get_post_stati(array('internal' => false), 'objects');
				foreach($post_statuses as $post_status_name => $post_status) {
					$config_meta_keys['action_' . $this->id . '_status']['options'][] = array('value' => $post_status_name, 'text' => $post_status->label);
				}

				// Add authors
				$authors = get_users(array('who' => 'authors'));
				$config_meta_keys['action_' . $this->id . '_author']['options'][] = array('value' => '', 'text' => 'Current User');
				foreach($authors as $author) {
					$config_meta_keys['action_' . $this->id . '_author']['options'][] = array('value' => $author->ID, 'text' => $author->display_name);
				}

				// Add page templates
				$templates = wp_get_theme()->get_page_templates();
				$config_meta_keys['action_' . $this->id . '_page_template']['options'][] = array('value' => 'default', 'text' => __('Default Template'));
				foreach($templates as $template_file => $template_name) {
					$config_meta_keys['action_' . $this->id . '_page_template']['options'][] = array('value' => $template_file, 'text' => $template_name);
				}

				// Taxonomy
				$taxonomies = get_taxonomies(array(), 'objects'); 
				foreach ($taxonomies as $taxonomy) {
					$config_meta_keys['action_' . $this->id . '_tag_category_id']['options'][] = array('value' => $taxonomy->name, 'text' => $taxonomy->labels->singular_name);
				}

				// ACF
				if($this->acf_activated) {

					$options_acf = array();

					$acf_field_groups = acf_get_field_groups();

					foreach($acf_field_groups as $acf_field_group) {

						$acf_fields = acf_get_fields($acf_field_group);

						$acf_field_group_name = $acf_field_group['title'];

						WS_Form_ACF::acf_get_fields($options_acf, $acf_field_group_name, $acf_fields);
					}
				
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
						'meta_keys_unique'			=>	array(

							'ws_form_field'
						),
						'condition'	=>	array(

							array(

								'logic'			=>	'==',
								'meta_key'		=>	'form_populate_action_id',
								'meta_value'	=>	'post'
							),

							array(

								'logic'			=>	'!=',
								'meta_key'		=>	'form_populate_list_id',
								'meta_value'	=>	''
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

							'label'		=>	__('Add-on Version', 'ws-form-post'),
							'type'		=>	'static'
						),

						'action_' . $this->id . '_license_key'	=>	array(

							'label'		=>	__('Add-on License Key', 'ws-form-post'),
							'type'		=>	'text',
							'help'		=>	__('Enter your Post Management add-on for WS Form PRO license key here.', 'ws-form-post'),
							'button'	=>	'license_action_' . $this->id,
							'action'	=>	$this->id
						),

						'action_' . $this->id . '_license_status'	=>	array(

							'label'		=>	__('Add-on License Status', 'ws-form-post'),
							'type'		=>	'static'
						),
					)
				);

				return $options;
			}

			public function config_settings_form_admin($config_settings_form_admin) {

				if(!isset($config_settings_form_admin['sidebars']['form']['meta']['fieldsets']['action'])) { return $config_settings_form_admin; }

				$meta_keys = $config_settings_form_admin['sidebars']['form']['meta']['fieldsets']['action']['fieldsets'][0]['meta_keys'];

				// Add post ID
				self::meta_key_inject($meta_keys, 'action_' . $this->id . '_form_populate_post_id', 'form_populate_field_mapping');

				// Add author restrict
				self::meta_key_inject($meta_keys, 'action_' . $this->id . '_form_populate_author_restrict', 'form_populate_field_mapping');

				if($this->acf_activated) {

					// Add ACF field mapping
					self::meta_key_inject($meta_keys, 'action_' . $this->id . '_form_populate_field_mapping_acf', 'form_populate_tag_mapping');
				}

				// Add meta key mapping
				self::meta_key_inject($meta_keys, 'action_' . $this->id . '_form_populate_meta_mapping', 'form_populate_tag_mapping');

				// Add featured image mapping
				$meta_keys[] = 'action_' . $this->id . '_form_populate_featured_image';

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
			public function wp_error_process($post) {

				$error_messages = $post->get_error_messages();
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
				$this->post_id = parent::get_config($config, 'action_' . $this->id . '_post_id', '');
				$this->clear_hidden_meta_values = 	parent::get_config($config, 'action_' . $this->id . '_clear_hidden_meta_values', 'on');
				$this->status = parent::get_config($config, 'action_' . $this->id . '_status', self::DEFAULT_POST_STATUS);
				$this->author = parent::get_config($config, 'action_' . $this->id . '_author', '');
				$this->author_restrict = parent::get_config($config, 'action_' . $this->id . '_author_restrict', '');
				$this->comment_status = parent::get_config($config, 'action_' . $this->id . '_comment_status', self::DEFAULT_COMMENT_STATUS);
				$this->ping_status = parent::get_config($config, 'action_' . $this->id . '_ping_status', get_option('default_ping_status', self::DEFAULT_PING_STATUS));
				$this->date = parent::get_config($config, 'action_' . $this->id . '_date', '');
				$this->expose = parent::get_config($config, 'action_' . $this->id . '_expose', '');
				$this->page_template = parent::get_config($config, 'action_' . $this->id . '_page_template', '');
				$this->featured_image = parent::get_config($config, 'action_' . $this->id . '_featured_image');

				// Field mapping
				$this->field_mapping = parent::get_config($config, 'action_' . $this->id . '_field_mapping', array());
				if(!is_array($this->field_mapping)) { $this->field_mapping = array(); }

				// Field mapping - ACF
				if($this->acf_activated) {

					$this->field_mapping_acf = parent::get_config($config, 'action_' . $this->id . '_field_mapping_acf', array());
					if(!is_array($this->field_mapping_acf)) { $this->field_mapping_acf = array(); }
				}

				// Field meta mapping
				$this->meta_mapping = parent::get_config($config, 'action_' . $this->id . '_meta_mapping', array());
				if(!is_array($this->meta_mapping)) { $this->meta_mapping = array(); }

				// Custom meta mapping
				$this->meta_mapping_custom = parent::get_config($config, 'action_' . $this->id . '_meta_mapping_custom', array());
				if(!is_array($this->meta_mapping_custom)) { $this->meta_mapping_custom = array(); }

				// Tag mapping
				$this->tag_mapping = parent::get_config($config, 'action_' . $this->id . '_tag_mapping', array());
				if(!is_array($this->tag_mapping)) { $this->tag_mapping = array(); }

				// Deduplication mapping
				$this->deduplication_mapping = parent::get_config($config, 'action_' . $this->id . '_deduplication_mapping', array());
				if(!is_array($this->deduplication_mapping)) { $this->deduplication_mapping = array(); }

				// Attachment mapping
				$this->attachment_mapping = parent::get_config($config, 'action_' . $this->id . '_attachment_mapping', array());
				if(!is_array($this->attachment_mapping)) { $this->attachment_mapping = array(); }

				// Messages
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
				register_rest_route(WS_FORM_RESTFUL_NAMESPACE, '/action/' . $this->id . '/list/(?P<list_id>[a-zA-Z0-9_-]+)/', array('methods' => 'GET', 'callback' => array($this, 'api_get_list'), 'permission_callback' => function () { return WS_Form_Common::can_user('create_form'); }));
				register_rest_route(WS_FORM_RESTFUL_NAMESPACE, '/action/' . $this->id . '/list/(?P<list_id>[a-zA-Z0-9_-]+)/fields/', array('methods' => 'GET', 'callback' => array($this, 'api_get_list_fields'), 'permission_callback' => function () { return WS_Form_Common::can_user('create_form'); }));

				// API routes - fetch_* (Pull from API and update cache)
				register_rest_route(WS_FORM_RESTFUL_NAMESPACE, '/action/' . $this->id . '/lists/fetch/', array('methods' => 'GET', 'callback' => array($this, 'api_fetch_lists'), 'permission_callback' => function () { return WS_Form_Common::can_user('create_form'); }));
				register_rest_route(WS_FORM_RESTFUL_NAMESPACE, '/action/' . $this->id . '/list/(?P<list_id>[a-zA-Z0-9_-]+)/fetch/', array('methods' => 'GET', 'callback' => array($this, 'api_fetch_list'), 'permission_callback' => function () { return WS_Form_Common::can_user('create_form'); }));
				register_rest_route(WS_FORM_RESTFUL_NAMESPACE, '/action/' . $this->id . '/list/(?P<list_id>[a-zA-Z0-9_-]+)/fields/fetch/', array('methods' => 'GET', 'callback' => array($this, 'api_fetch_list_fields'), 'permission_callback' => function () { return WS_Form_Common::can_user('create_form'); }));
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