<?php

namespace Bricksforge\ProForms\Actions;

use Bricksforge\Api\FormsHelper as FormsHelper;

class Update_Post_Meta
{
    public $name = "update_post_meta";

    public function run($form)
    {
        $forms_helper = new FormsHelper();

        $form_settings = $form->get_settings();
        $form_fields   = $form->get_fields();
        $form_files = $form->get_uploaded_files();
        $post_id = $form->get_post_id();
        $dynamic_post_id = $form->get_dynamic_post_id();
        $form_id = $form->get_form_id();

        if (isset($dynamic_post_id) && $dynamic_post_id) {
            $dynamic_post_id = $form->get_form_field_by_id($dynamic_post_id);
            $dynamic_post_id = absint($dynamic_post_id);
        }

        $post_meta_data = $form_settings['pro_forms_post_action_update_post_meta_data'];

        $post_meta_data = array_map(function ($item) use ($post_id, $dynamic_post_id, $form) {

            if ($form->is_live_id($item['post_id'])) {
                $post_id = $form->get_live_post_id();
            } else {
                $post_id = isset($item['post_id']) && $item['post_id'] ? intval($form->get_form_field_by_id($item['post_id'])) : intval($post_id);

                $post_id = $dynamic_post_id ? $dynamic_post_id : $post_id;
            }

            return array(
                'id' => isset($item['id']) ? $item['id'] : null,
                'post_id'      => $post_id,
                'name'         => isset($item['name']) ? bricks_render_dynamic_data($item['name'], $post_id) : null,
                'value'        => isset($item['value']) ? bricks_render_dynamic_data($item['value'], $post_id) : null,
                'type'         => isset($item['type']) ? $item['type'] : null,
                'ignore_empty' => isset($item['ignore_empty']) ? $item['ignore_empty'] : null,
                'selector'     => isset($item['selector']) ? bricks_render_dynamic_data($item['selector'], $post_id) : null,
                'number_field' => isset($item['number_field']) ? bricks_render_dynamic_data($item['number_field'], $post_id) : null,
                'is_repeater' => isset($item['is_repeater']) ? $item['is_repeater'] : false,
                'repeater_action' => isset($item['repeater_action']) ? $item['repeater_action'] : null,
                'repeater_row_number' => isset($item['repeater_row_number']) ? $item['repeater_row_number'] : null,
                'repeater_row_fields' => isset($item['repeater_row_fields']) ? $item['repeater_row_fields'] : null,
                'sub_row_name' => isset($item['sub_row_name']) ? $item['sub_row_name'] : null,
                'sub_row_number' => isset($item['sub_row_number']) ? $item['sub_row_number'] : null,
                'repeater_box_name' => isset($item['repeater_box_name']) ? $item['repeater_box_name'] : null,
            );
        }, $post_meta_data);

        $updated_values = array();

        // Update Post Meta for each $post_meta_data
        foreach ($post_meta_data as $post_meta) {
            $post_id = $post_meta['post_id'];
            $post_meta_name = $post_meta['name'];
            $post_meta_value = $post_meta['value'];
            $post_meta_type = $post_meta['type'];
            $post_meta_ignore_empty = $post_meta['ignore_empty'];
            $post_meta_selector = $post_meta['selector'];
            $post_meta_number_field = $post_meta['number_field'];
            $is_repeater = $post_meta['is_repeater'];
            $repeater_action = $post_meta['repeater_action'];
            $repeater_row_number = $post_meta['repeater_row_number'];
            $repeater_row_fields = $post_meta['repeater_row_fields'];
            $sub_row_name = $post_meta['sub_row_name'];
            $sub_row_number = $post_meta['sub_row_number'];
            $repeater_box_name = $post_meta['repeater_box_name'];

            if (!isset($post_meta_name) || !isset($post_meta_value)) {
                continue;
            }

            $post_meta_value = $form->get_form_field_by_id($post_meta_value);

            if (empty($post_meta_value) && $post_meta_ignore_empty) {
                continue;
            }

            $new_post_meta_value;
            $current_value = get_post_meta($post_id, $post_meta_name, true);

            switch ($post_meta_type) {
                case 'replace':
                    $new_post_meta_value = $post_meta_value;
                    break;
                case 'increment':
                    $new_post_meta_value = intval($current_value) + 1;
                    break;
                case 'decrement':
                    $new_post_meta_value = intval($current_value) - 1;
                    break;
                case 'increment_by_number':
                    $post_meta_number_field = $form->get_form_field_by_id($post_meta_number_field);
                    $new_post_meta_value = intval($current_value) + intval($post_meta_number_field);
                    break;
                case 'decrement_by_number':
                    $post_meta_number_field = $form->get_form_field_by_id($post_meta_number_field);
                    $new_post_meta_value = intval($current_value) - intval($post_meta_number_field);
                    break;
                case 'add_to_array':
                    // Add the new value to the array
                    if (!is_array($current_value)) {
                        if (!empty(trim($current_value))) {
                            $new_post_meta_value = array($current_value, $post_meta_value);
                        } else {
                            $new_post_meta_value = array($post_meta_value);
                        }
                    } else {
                        $new_post_meta_value = array_merge($current_value, array($post_meta_value));
                    }

                    break;
                case 'remove_from_array':
                    // If the current value is not an array, make it one and remove the new value
                    if (is_array($current_value)) {
                        $new_post_meta_value = array_diff($current_value, array($post_meta_value));
                    }

                    break;
                default:
                    $new_post_meta_value = $post_meta_value;
                    break;
            }

            $new_post_meta_value = $forms_helper->sanitize_value($new_post_meta_value);

            if ($is_repeater && isset($repeater_action) && isset($repeater_row_fields) && !empty($repeater_row_fields)) {

                $repeater_row_fields = array_reduce($repeater_row_fields, function ($carry, $item) use ($form, $forms_helper) {
                    $field_name = isset($item['name']) ? $form->get_form_field_by_id($item['name']) : null;
                    $field_value = isset($item['value']) ? $form->get_form_field_by_id($item['value']) : null;

                    $is_repeater = isset($item['is_repeater']) && $item['is_repeater'] && isset($item['repeater_values']) && !empty($item['repeater_values']) ? true : false;

                    if ($is_repeater) {
                        $repeater_values = $item['repeater_values'];

                        $repeater_values = array_map(function ($item) use ($form, $forms_helper) {
                            return $forms_helper->process_repeater_values($item, $form);
                        }, $repeater_values);

                        $field_value = array_reduce($repeater_values, function ($carry, $item) use ($form) {
                            $field_name = $item['name'];
                            $field_value = $item['value'];

                            $carry[$field_name] = $field_value;

                            return $carry;
                        }, []);

                        $field_value = array($field_value);
                    }

                    $carry[$field_name] = $field_value;

                    return $carry;
                }, []);

                $forms_helper->update_repeater_field($post_meta_name, $new_post_meta_value, $post_id, $repeater_row_number, $repeater_row_fields, $repeater_action, $sub_row_name, $sub_row_number, $repeater_box_name);
            } else {

                if (class_exists('ACF') && !class_exists('RWMB_Core')) {
                    $forms_helper->update_acf_field($post_meta_name, $new_post_meta_value, $post_id);
                } elseif (class_exists('RWMB_Core')) {
                    $forms_helper->update_metabox_field($post_meta_name, $new_post_meta_value, $post_id);
                } else {
                    update_post_meta($post_id, $post_meta_name, $new_post_meta_value);
                }
            }

            // Action: bricksforge/pro_forms/post_meta_updated
            do_action('bricksforge/pro_forms/post_meta_updated', $post_id, $post_meta_name, $new_post_meta_value);

            // Allow Live Update if post_meta_type is not array related
            $allow_live_update = $post_meta_type === 'add_to_array' || $post_meta_type === 'remove_from_array' ? false : true;

            array_push($updated_values, [
                'selector' => $post_meta_selector,
                'value'    => $new_post_meta_value,
                'live'     => $allow_live_update,
                'data' => $post_meta
            ]);
        }

        $form->set_result(
            [
                'action' => $this->name,
                'type'   => 'success',
            ]
        );

        return $updated_values;
    }
}
