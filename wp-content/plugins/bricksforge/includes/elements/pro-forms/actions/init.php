<?php

namespace Bricksforge\ProForms\Actions;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

include_once(BRICKSFORGE_PATH . '/includes/api/FormsHelper.php');

class Init
{
    private $forms_helper;

    public function __construct()
    {
        $this->forms_helper = new \Bricksforge\Api\FormsHelper();
    }

    /**
     * Before Form Submit
     *
     * @param WP_REST_Request $request Full details about the request.
     *
     * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
     */
    public function form_submit(\WP_REST_Request $request)
    {
        $form_data = $request->get_body_params();
        $form_files = $this->handle_files($request->get_file_params());

        $post_id = absint($form_data['postId']);
        $dynamic_post_id = $request->get_param('dynamicPostId');
        $form_id = $request->get_param('formId');
        $field_ids = $request->get_param('fieldIds') ? json_decode($request->get_param('fieldIds')) : null;
        $captcha_result = $request->get_param('captchaResult');
        $turnstile_result = $request->get_param('turnstileResult');

        $form_settings = \Bricks\Helpers::get_element_settings($post_id, $form_id);

        $hcaptcha_enabled = isset($form_settings['enableHCaptcha']) ? $form_settings['enableHCaptcha'] : false;
        $turnstile_enabled = isset($form_settings['enableTurnstile']) ? $form_settings['enableTurnstile'] : false;

        if (!isset($form_settings) || empty($form_settings)) {
            return false;
        }

        if (!isset($form_settings['actions']) || empty($form_settings['actions'])) {
            wp_send_json_error(array(
                'message' => __('No action has been set for this form.', 'bricksforge'),
            ));
        }

        $form_actions = $form_settings['actions'];
        $return_values = array();

        // Honeypot check. If $form_data contains a key form-field-guardian42 and the value is 1, we stop here.
        if (isset($form_data['form-field-guardian42']) && $form_data['form-field-guardian42'] == 1) {
            wp_send_json_error(array(
                'message' => __('You are not allowed to submit this form.', 'bricksforge'),
            ));

            die();
        }

        // First of all, we need to check if the captcha is valid. If not, we need to stop here.
        if ($hcaptcha_enabled == true) {
            if (!$this->forms_helper->handle_hcaptcha($form_settings, $form_data, $captcha_result ? $captcha_result : null)) {
                return false;
            }
        }

        if ($turnstile_enabled == true) {
            if (!$this->forms_helper->handle_turnstile($form_settings, $form_data, $turnstile_result ? $turnstile_result : null)) {
                wp_send_json_error(array(
                    'message' => __(isset($form_settings['turnstileErrorMessage']) ? $form_settings['turnstileErrorMessage'] : 'Your submission is being verified. Please wait a moment before submitting again.', 'bricksforge'),
                ));

                return false;
            }
        }

        // Add File Data to Form Data
        if (isset($form_files) && !empty($form_files)) {
            foreach ($form_files as $field => $files) {
                foreach ($files as $file) {
                    $form_data[$field] = $file;
                }
            }
        }

        // Run initial sanitation
        $form_data = $this->forms_helper->initial_sanitization($form_settings, $form_data, $field_ids, $post_id);

        // Validate Fields
        $hidden_fields = $request->get_param('hiddenFields') ? json_decode($request->get_param('hiddenFields')) : null;
        $fields_to_validate = $request->get_param('fieldsToValidate') ? json_decode($request->get_param('fieldsToValidate')) : null;
        $validation_result = $this->forms_helper->validate($field_ids, $form_data, $post_id, $hidden_fields, $fields_to_validate);

        if ($validation_result !== true) {
            wp_send_json_error(array(
                'validation' => $validation_result
            ));
        }

        // Trigger bricksforge/pro_forms/before_submit action
        do_action('bricksforge/pro_forms/before_submit', $form_data);

        // Form Base
        $base = new \Bricksforge\ProForms\Actions\Base($form_settings, $form_data, $form_files, $post_id, $form_id, $dynamic_post_id);

        // Handle Form Actions
        if (in_array('post_create', $form_actions)) {
            $action = new \Bricksforge\ProForms\Actions\Create_Post();
            $result = $action->run($base);
        }

        if (in_array('post_update', $form_actions)) {
            $action = new \Bricksforge\ProForms\Actions\Update_Post();
            $result = $action->run($base);
        }

        if (in_array('post_delete', $form_actions)) {
            $action = new \Bricksforge\ProForms\Actions\Delete_Post();
            $result = $action->run($base);

            if ($result === false) {
                wp_send_json_error(array(
                    'message' => __('Deletion failed.', 'bricksforge'),
                ));
            }
        }

        if (in_array('add_option', $form_actions)) {
            $action = new \Bricksforge\ProForms\Actions\Add_Option();
            $result = $action->run($base);
        }

        if (in_array('update_option', $form_actions)) {
            $action = new \Bricksforge\ProForms\Actions\Update_Option();
            $result = $action->run($base);

            if ($result) {
                $return_values['update_option'] = $result;
            }
        }

        if (in_array('delete_option', $form_actions)) {
            $action = new \Bricksforge\ProForms\Actions\Delete_Option();
            $result = $action->run($base);
        }

        if (in_array('update_post_meta', $form_actions)) {
            $action = new \Bricksforge\ProForms\Actions\Update_Post_Meta();
            $result = $action->run($base);

            if ($result) {
                $return_values['update_post_meta'] = $result;
            }
        }

        if (in_array('set_storage_item', $form_actions)) {
            $action = new \Bricksforge\ProForms\Actions\Set_Storage_Item();
            $result = $action->run($base);

            if ($result) {
                $return_values['set_storage_item'] = $result;
            }
        }

        if (in_array('create_submission', $form_actions)) {
            $action = new \Bricksforge\ProForms\Actions\Create_Submission();
            $action->run($base);
        }

        if (in_array('wc_add_to_cart', $form_actions)) {
            $action = new \Bricksforge\ProForms\Actions\Wc_Add_To_Cart();
            $result = $action->run($base);

            if ($result) {
                $return_values['wc_add_to_cart'] = $result;
            }
        }

        if (in_array('webhook', $form_actions)) {
            $action = new \Bricksforge\ProForms\Actions\Webhook();
            $action->run($base);
        }

        if (in_array('login', $form_actions)) {
            $action = new \Bricksforge\ProForms\Actions\Login();
            $action->run($base);
        }

        if (in_array('registration', $form_actions)) {
            $action = new \Bricksforge\ProForms\Actions\Registration();
            $action->run($base);
        }

        if (in_array('update_user_meta', $form_actions)) {
            $action = new \Bricksforge\ProForms\Actions\Update_User_Meta();
            $result = $action->run($base);

            if ($result) {
                $return_values['update_user_meta'] = $result;
            }
        }

        if (in_array('reset_user_password', $form_actions)) {
            $action = new \Bricksforge\ProForms\Actions\Reset_User_Password();
            $action->run($base);
        }

        if (in_array('mailchimp', $form_actions)) {
            $action = new \Bricksforge\ProForms\Actions\Mailchimp();
            $action->run($base);
        }

        if (in_array('sendgrid', $form_actions)) {
            $action = new \Bricksforge\ProForms\Actions\Sendgrid();
            $action->run($base);
        }

        if (in_array('email', $form_actions)) {
            $action = new \Bricksforge\ProForms\Actions\Email();
            $action->run($base);
        }

        if (in_array('custom', $form_actions)) {
            $action = new \Bricksforge\ProForms\Actions\Custom();
            $action->run($base);
        }

        if (in_array('redirect', $form_actions)) {
            $action = new \Bricksforge\ProForms\Actions\Redirect();
            $action->run($base);
        }

        $return_values['results'] = $base->results;

        // Trigger bricksforge/pro_forms/before_submit action
        do_action('bricksforge/pro_forms/after_submit', $form_data, $return_values);

        return $return_values;
    }

    public function handle_files($files)
    {
        require_once(ABSPATH . 'wp-admin/includes/file.php');

        if (empty($files)) {
            return;
        }

        $uploaded_files = [];

        foreach ($files as $input_name => $files) {
            if (empty($files['name'])) {
                continue;
            }

            foreach ($files['name'] as $key => $value) {
                if (empty($files['name'][$key]) || $files['error'][$key] !== UPLOAD_ERR_OK) {
                    continue;
                }

                $file = [
                    'name'     => $files['name'][$key],
                    'type'     => $files['type'][$key],
                    'tmp_name' => $files['tmp_name'][$key],
                    'error'    => $files['error'][$key],
                    'size'     => $files['size'][$key],
                ];

                // Temporarily upload file to 'uploads' folder to sent as email attachment, etc. (no sizes are generated)
                $uploaded = \wp_handle_upload($file, ['test_form' => false]);

                // Upload success: Uploaded to 'uploads' folder
                if ($uploaded && !isset($uploaded['error'])) {
                    $uploaded['type'] = $file['type'];
                    $uploaded['name'] = $file['name'];
                    $uploaded['field'] = $input_name;

                    $uploaded_files[$input_name][] = $uploaded;
                }
            }
        }

        return $uploaded_files;
    }
}
