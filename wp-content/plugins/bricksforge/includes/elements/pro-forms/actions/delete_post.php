<?php

namespace Bricksforge\ProForms\Actions;

use Bricksforge\Api\FormsHelper as FormsHelper;

class Delete_Post
{
    public $name = "delete_post";

    public function run($form)
    {
        $forms_helper = new FormsHelper();
        $form_settings = $form->get_settings();
        $form_fields   = $form->get_fields();
        $form_files = $form->get_uploaded_files();
        $post_id = $form->get_post_id();
        $dynamic_post_id = $form->get_dynamic_post_id();
        $form_id = $form->get_form_id();

        $post_to_delete = isset($form_settings['pro_forms_post_action_post_delete_post_id']) ? $form_settings['pro_forms_post_action_post_delete_post_id'] : '';
        $delete_permanently = isset($form_settings['pro_forms_post_action_post_delete_permanently']) ? $form_settings['pro_forms_post_action_post_delete_permanently'] : false;

        if (!$post_to_delete) {
            $form->set_result(
                [
                    'action'  => $this->name,
                    'type'    => 'error',
                    'message' => 'Deletion failed. No post ID provided.',
                ]
            );

            return false;
        }

        $post_to_delete = $form->get_form_field_by_id($post_to_delete);

        try {
            $post_id = wp_delete_post($post_to_delete, $delete_permanently);

            if ($post_id) {
                $form->set_result(
                    [
                        'action'  => $this->name,
                        'type'    => 'success',
                    ]
                );
            } else {
                $form->set_result(
                    [
                        'action'  => $this->name,
                        'type'    => 'error',
                        'message' => 'Deletion failed.',
                    ]
                );

                return false;
            }
        } catch (\Exception $e) {
            $form->set_result(
                [
                    'action'  => $this->name,
                    'type'    => 'error',
                    'message' => $e->getMessage(),
                ]
            );

            return false;
        }

        return $post_id;
    }
}
