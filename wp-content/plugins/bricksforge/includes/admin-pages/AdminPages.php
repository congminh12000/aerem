<?php

namespace Bricksforge;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Global Classes Handler
 */
class AdminPages
{
    private $instances = [];

    public function __construct()
    {
        $this->init();
    }

    public function init()
    {
        if ($this->activated() === true) {
            $this->prepare();

            if (is_admin()) {
                $this->instances = get_option('brf_admin_pages') ? get_option('brf_admin_pages') : [];
                add_action('admin_menu', [$this, 'create_admin_pages']);
                add_action('admin_head', [$this, 'enqueue_scripts']);
            }
        }
    }

    public function enqueue_scripts($hook_suffix)
    {
        wp_enqueue_style('bricksforge-admin-pages', BRICKSFORGE_ASSETS . '/css/backend-designer/admin-pages.css');
    }

    public function prepare()
    {
        if (is_user_logged_in() && isset($_GET['backend']) && $_GET['backend'] == 'true') {
            add_filter('body_class', function ($classes) {
                $classes[] = 'brf-backend-view brf-backend-view__admin-page';
                return $classes;
            });

            add_filter('show_admin_bar', function ($show) {
                return false;
            });
        }
    }

    public function create_admin_pages()
    {
        // For each $this->instances (which contains id, name, roles and template), we want to create a wordpress Admin Page
        foreach ($this->instances as $instance) {
            $this->create_admin_page($instance);
        }
    }

    public function create_admin_page($instance)
    {
        $slug = $this->generate_slug($instance->name);
        $type = $instance->type;

        if ($type == "subMenuPage") {
            $parent = $instance->parent;

            if (!isset($parent)) {
                return;
            }

            // Create a wordpress admin submenu page
            add_submenu_page(
                $parent,
                $instance->name,
                $instance->name,
                'manage_options',
                $slug,
                function () use ($instance) {
                    $this->render_admin_page($instance);
                }
            );
        } else {
            // Create a wordpress admin menu page
            add_menu_page(
                $instance->name,
                $instance->name,
                'manage_options',
                $slug,
                function () use ($instance) {
                    $this->render_admin_page($instance);
                },
                $instance->icon,
                $instance->position
            );
        }
    }

    public function render_admin_page($instance)
    {
        if (!isset($instance->template)) {
            return;
        }

        $template = $instance->template;
        $template = intval($template);

        $url = add_query_arg('backend', 'true', get_permalink($template));

        echo '<iframe src="' . $url . '" width="100%" height="100%"></iframe>';
    }

    public function generate_slug($name)
    {
        // My New Page -> my-new-page
        $slug = "brf-ap-" . strtolower($name);
        $slug = str_replace(' ', '-', $slug);

        return $slug;
    }

    public function activated()
    {
        return get_option('brf_activated_tools') && in_array(17, get_option('brf_activated_tools'));
    }
}
