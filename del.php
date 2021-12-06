<?php
/*
* Plugin Name: PHP Run
* Plugin URI: https://evdesign.ru/
* Description: PHP Editor in dashboard for administrator. Run PHP scripts.
* Version: 0.3
* Author: evdesign
* Author URI: https://evdesign.ru/
* License: GPLv2 or later
* Text Domain: php-run
*/

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

define('PHP_RUN_ONCE__DIR', untrailingslashit(dirname(__FILE__)));
define('PHP_RUN_ONCE__URL', plugin_dir_url(__FILE__));

class PHP_run
{
    public $plugin_domain;
    public $plugin_lower_domain;
    public $version;
    private static $instance = null;

    public function __construct()
    {
        $this->plugin_domain = 'php-run';
        $this->version = '0.3';
        $this->plugin_lower_domain = str_replace('-', '_', $this->plugin_domain);
        // $this->views_dir = trailingslashit( dirname( __FILE__ ) ) . 'server/views';

        $this->require();
        $this->load();

        add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts']);
        add_action('init', [$this, 'init']);
    }

    private function require()
    {
        require_once __DIR__ . '/inc/helpers.php';
    }

    private function load()
    {
    }

    public function init()
    {
        if (is_admin() && current_user_can('administrator')) {
            require_once __DIR__ . '/inc/class-php-run-once.php';
            PHP_Run_Once::getInstance();
        }
    }

    public function admin_enqueue_scripts()
    {
        wp_enqueue_style($this->plugin_domain . '-styles', plugin_dir_url(__FILE__) . 'assets/admin/' . $this->plugin_domain . '-admin.min.css', [], $this->version);
        wp_enqueue_script($this->plugin_domain . '-scripts', plugin_dir_url(__FILE__) . 'assets/admin/' . $this->plugin_domain . '-admin.min.js', ['jquery'], $this->version, true);

        wp_localize_script($this->plugin_domain . '-scripts', $this->plugin_lower_domain . '_ajax', [
            'url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('myajax-nonce')
        ]);
    }

    /**
     * Locate template.
     *
     * Locate the called template.
     * Search Order:
     * 1. /themes/theme/class-php-run-once/$template_name
     * 2. /themes/theme/$template_name
     * 3. /plugins/class-php-run-once/views/$template_name.
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
            $template_path = 'class-php-run-once/views/';
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

        $template_file = PHP_run::plugin_locate_template($template_name, $tempate_path, $default_path);

        if (!file_exists($template_file)) :
            _doing_it_wrong(__FUNCTION__, sprintf('<code>%s</code> does not exist.', $template_file), '1.0.0');
            return;
        endif;

        include $template_file;
    }


    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new PHP_run();
        }

        return self::$instance;
    }
}

PHP_run::getInstance();
