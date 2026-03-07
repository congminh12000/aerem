<?php

namespace Bricksforge\ProForms\Actions;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Base
{
    private $settings;
    private $fields;
    private $uploaded_files;
    private $post_id;
    private $dynamic_post_id;
    private $form_id;

    // Live Values
    private $live_post_id;
    private $live_user_id;

    public $results;

    public function __construct($form_settings, $form_data, $form_files, $post_id, $form_id, $dynamic_post_id = null)
    {
        $this->settings = $form_settings;
        $this->fields = $form_data;
        $this->uploaded_files = isset($form_files) ? $form_files : [];
        $this->post_id = $post_id;
        $this->form_id = $form_id;
        $this->dynamic_post_id = $dynamic_post_id;
    }

    public function get_settings()
    {
        return $this->settings;
    }

    public function get_fields()
    {
        return $this->fields;
    }

    public function get_post_id()
    {
        return $this->post_id;
    }

    public function get_dynamic_post_id()
    {
        return $this->dynamic_post_id;
    }

    public function get_form_id()
    {
        return $this->form_id;
    }

    public function get_uploaded_files()
    {
        return $this->uploaded_files;
    }

    public function get_live_post_id()
    {
        return $this->live_post_id;
    }

    public function get_live_user_id()
    {
        return $this->live_user_id;
    }

    public function update_live_post_id($post_id)
    {
        $this->live_post_id = $post_id;
    }

    public function update_live_user_id($user_id)
    {
        $this->live_user_id = $user_id;
    }

    public function is_live_id($input)
    {
        if (!isset($input) || !$input) {
            return false;
        }

        $live_values = ["{{live_post_id}}", "{{live_user_id}}"];

        $trimmed_input = trim($input);

        if (in_array($trimmed_input, $live_values)) {
            return true;
        }

        return false;
    }

    /**
     * Set action result
     *
     * type: success OR danger
     *
     * @param array $result
     * @return void
     */
    public function set_result($result)
    {
        $type = isset($result['type']) ? $result['type'] : 'success';

        // If type is success, add the $settings['successMessage'] as message
        if ($type === 'success') {
            $result['message'] = isset($this->settings['successMessage']) ? bricks_render_dynamic_data($this->settings['successMessage'], $this->get_post_id()) : __('Message successfully sent. We will get back to you as soon as possible.', 'bricksforge');
        }

        $this->results[$type][] = $result;
    }

    public function get_form_field_by_id($id, $form_data = null, $current_post_id = null, $form_settings = null, $form_files = null, $implode_array = true, $force_file_url_output = false, $ignore_files = false)
    {
        foreach ($this->fields as $key => $value) {

            // form-field-{id}
            $field_id = explode('form-field-', $key);
            $field_id = isset($field_id[1]) ? $field_id[1] : null;

            if (!$field_id) {
                // Its not a Form Field. Continue.
                continue;
            }

            // If the ID has the format {{id}} or {{ id }}, we replace the variables with the values
            if (isset($id) && strpos($id, '{{') !== false) {

                // If contains :url, we set $force_file_url_output to true
                if (strpos($id, ':url') !== false) {
                    $force_file_url_output = true;

                    // Remove :url from $id
                    $id = str_replace(':url', '', $id);
                }

                preg_match_all('/{{([^}]+)}}/', $id, $matches);

                foreach ($matches[1] as $match) {
                    $value = $this->get_form_field_by_id(trim($match), $form_data, $current_post_id, $form_settings, $form_files, $implode_array, $force_file_url_output, $ignore_files);

                    // If value is array, we have files. In that case, we replace each of them
                    if (!is_array($value)) {
                        // If the value remains the same, this variable seems to not exist. We return an empty string.
                        if ($value === $match) {
                            $id = str_replace('{{' . $match . '}}', "", $id);
                        } else {
                            $id = str_replace('{{' . $match . '}}', $value, $id);
                        }
                    } else {
                        $id = $match;

                        // Join the array with a comma
                        if ($implode_array) {
                            $id = implode(', ', $value);
                        }
                    }
                }
            }

            if ($field_id === $id || $field_id === $id  . '[]') {

                // Check if there are files in the form data
                if (isset($this->uploaded_files) && !empty($this->uploaded_files) && !$ignore_files) {

                    // If there are files, check if the current field is a file field
                    foreach ($this->uploaded_files as $field => $files) {

                        foreach ($files as $file) {
                            // $field is form-field-my_field. Strip the "form-field-" part concretely
                            $field = substr($file['field'], strpos($file['field'], 'form-field-') + strlen('form-field-'));

                            if ($field === $id) {
                                // If it is a file field, handle this file
                                $file_url = $this->handle_file($id, $form_settings, $this->uploaded_files, 'url', $force_file_url_output);

                                if ($file_url) {
                                    return $file_url;
                                }
                            }
                        }
                    }
                }

                // If $value is an empty array, return empty string
                if (is_array($value) && empty($value)) {
                    return '';
                }

                // If $value is an array, return comma separated values
                if (is_array($value) && $implode_array) {
                    return implode(', ', bricks_render_dynamic_data($value, $this->post_id));
                }

                return bricks_render_dynamic_data($value, $this->post_id);
            }
        }

        return bricks_render_dynamic_data($id, $this->post_id);
    }

    /**
     * Handle Thumbnail for different actions. 
     * Return the attachment ID
     * @param $thumbnail
     * @param $form_settings
     * @param $form_files
     * @return string
     * 
     */
    public function handle_file($file_id, $form_settings, $form_files, $format = 'id', $force_url_output = false)
    {
        $uploaded_files = [];
        $file_array = [];

        // Handle Thumbnail
        if ($file_id && isset($form_files) && count($form_files)) {

            foreach ($form_files as $files) {
                foreach ($files as $file) {
                    $field = substr($file['field'], strpos($file['field'], 'form-field-') + strlen('form-field-'));

                    // $file_id could be {{id}}. In that case, we need to strip the {{ and }} parts
                    if (strpos($file_id, '{{') !== false) {

                        // If contains :url, we set $force_file_url_output to true
                        if (strpos($id, ':url') !== false) {
                            $force_url_output = true;

                            // Remove :url from $file_id
                            $file_id = str_replace(':url', '', $file_id);
                        }

                        $file_id = str_replace('{{', '', $file_id);
                        $file_id = str_replace('}}', '', $file_id);

                        // Remove spaces
                        $file_id = str_replace(' ', '', $file_id);
                    }

                    if ($field === $file_id) {
                        $uploaded_files[] = $file;
                    }
                }
            }

            if ($uploaded_files && count($uploaded_files)) {

                foreach ($uploaded_files as $file) {
                    $file_name = $file['name'];
                    $file_path = $file['file'];

                    if (file_exists($file_path)) {

                        $attachment = array(
                            'guid'           => $file['url'],
                            'post_mime_type' => $file['type'],
                            'post_title'     => preg_replace('/\.[^.]+$/', '', basename($file_path)),
                            'post_content'   => '',
                            'post_status'    => 'inherit',
                        );

                        $attach_id = wp_insert_attachment($attachment, $file_path);

                        require_once ABSPATH . 'wp-admin/includes/image.php';

                        $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
                        wp_update_attachment_metadata($attach_id, $attach_data);

                        if ($format === 'url' && !class_exists('ACF') && !class_exists('RW_Meta_Box') && !class_exists('Jet_Engine')) {
                            $file_array[] = wp_get_attachment_url($attach_id);
                        } elseif ($format === 'url' && class_exists('Jet_Engine')) {
                            $file_array[] = wp_get_attachment_url($attach_id);
                        } elseif ($force_url_output) {
                            $file_array[] = wp_get_attachment_url($attach_id);
                        } else {
                            $file_array[] = $attach_id;
                        }
                    }
                }
            }
        }

        // If file arrays count is 1, return the first item
        if (count($file_array) === 1) {
            return $file_array[0];
        }

        return $file_array;
    }
}
