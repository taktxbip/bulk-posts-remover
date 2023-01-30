<?php
/*
* Plugin Name: Bulk Posts Remover
* Description: A bulk posts remover tool. Easily remove thousands of posts with few clicks.
* Version: 1.0
* Author: Evgenii Savelev
* Author URI: https://evdesign.ru/
* License: GPLv2 or later
* Text Domain: bpr
*/

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

define('BPR__DIR', untrailingslashit(dirname(__FILE__)));
define('BPR__URL', plugin_dir_url(__FILE__));

class Bulk_Posts_Remover
{
    public $plugin_domain;
    public $plugin_lower_domain;
    public $version;
    private static $instance = null;

    public function __construct()
    {
        $this->plugin_domain = 'bpr';
        $this->version = '1.0';
        $this->plugin_lower_domain = str_replace('-', '_', $this->plugin_domain);

        $this->require();

        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
    }

    private function require()
    {
        require_once __DIR__ . '/inc/posts-remover-helpers.php';
        require_once __DIR__ . '/inc/class-bpr.php';
    }

    public function admin_enqueue_scripts()
    {
        $current_page = get_current_screen()->base;

        if ($current_page === 'tools_page_bulk-posts-remover') {
            wp_enqueue_style($this->plugin_domain . '-styles', plugin_dir_url(__FILE__) . 'assets/admin/' . $this->plugin_domain . '-admin.min.css', [], $this->version);
            wp_enqueue_script($this->plugin_domain . '-scripts', plugin_dir_url(__FILE__) . 'assets/admin/' . $this->plugin_domain . '-admin.min.js', ['jquery'], $this->version, true);

            wp_localize_script($this->plugin_domain . '-scripts', $this->plugin_lower_domain . '_ajax', [
                'url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('myajax-nonce')
            ]);
        }
    }

    /**
     * Locate template.
     *
     * Locate the called template.
     * Search Order:
     * 1. /themes/theme/bulk-posts-remover/$template_name
     * 2. /themes/theme/$template_name
     * 3. /plugins/bulk-posts-remover/views/$template_name.
     *
     * @since 1.0.0
     *
     * @param 	string 	$template_name			Template to load.
     * @param 	string 	$string $template_path	Path to templates.
     * @param 	string	$default_path			Default path to template files.
     * @return 	string 							Path to the template file.
     */
    public static function plugin_locate_template($template_name, $template_path = '', $default_path = '')
    {

        // Set variable to search in woocommerce-plugin-templates folder of theme.
        if (!$template_path) :
            $template_path = 'bulk-posts-remover/views/';
        endif;

        // Set default plugin templates path.
        if (!$default_path) :
            $default_path = __DIR__ . '/views/'; // Path to the template folder
        endif;

        // Search template file in theme folder.
        $template = locate_template([
            $template_path . $template_name,
            $template_name
        ]);

        // Get plugins template file.
        if (!$template) :
            $template = $default_path . $template_name;
        endif;

        return apply_filters('plugin_locate_template', $template, $template_name, $template_path, $default_path);
    }

    /**
     * Get template.
     *
     * Search for the template and include the file.
     *
     * @since 1.0.0
     *
     * @see plugin_locate_template()
     *
     * @param string 	$template_name			Template to load.
     * @param array 	$args					Args passed for the template file.
     * @param string 	$string $template_path	Path to templates.
     * @param string	$default_path			Default path to template files.
     */
    public static function get_template($template_name, $args = array(), $tempate_path = '', $default_path = '')
    {

        if (is_array($args) && isset($args)) :
            extract($args);
        endif;

        $template_file = Bulk_Posts_Remover::plugin_locate_template($template_name, $tempate_path, $default_path);

        if (!file_exists($template_file)) :
            _doing_it_wrong(__FUNCTION__, sprintf('<code>%s</code> does not exist.', $template_file), '1.0.0');
            return;
        endif;

        include $template_file;
    }


    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new Bulk_Posts_Remover();
        }

        return self::$instance;
    }
}


add_action('init', function () {
    if (current_user_can('administrator') && is_admin()) {
        Bulk_Posts_Remover::getInstance();
    }
});