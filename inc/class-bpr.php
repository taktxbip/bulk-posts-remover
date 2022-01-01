<?php
final class BPR
{
    private $options_name = 'bpr_settings';
    private $settings_fields = [
        [
            'field_label' => 'Chunk Size',
            'field_name' => 'chunk_size',
            'description' => 'How many posts will be removed at once. Maximum is 800. Use large values for light tasks and small for heavy.',
            'default_value' => 100,
            'type' => 'number'
        ]
    ];

    public function __construct()
    {
        $ajax = [
            'get_posts_ids_ajax',
            'remove_posts_ajax'
        ];

        foreach ($ajax as $name) {
            add_action('wp_ajax_' . $name, [$this, $name]);
            add_action('wp_ajax_nopriv_' . $name, [$this, $name]);
        }

        add_action('admin_init', [$this, 'register_settings']);

        add_action('admin_menu', function () {
            add_submenu_page('tools.php', 'Bulk Posts Remover', 'Bulk Posts Remover', 'administrator', 'bulk-posts-remover', [$this, 'view'], 8);
        });
    }

    public function register_settings()
    {
        // If plugin settings don't exist, then create them
        if (!get_option($this->options_name)) add_option($this->options_name);

        add_settings_section(
            'bpr_settings_section',
            'Settings',
            null,
            'bulk-posts-remover'
        );

        foreach ($this->settings_fields as $field) {
            $args = [
                'field_name'  => $field['field_name'],
                'type' => $field['type']
            ];

            if (isset($field['description']) && $field['description']) {
                $args['description'] = __($field['description'], 'bpr');
            }

            if (isset($field['default_value']) && $field['default_value']) {
                $args['default_value'] = __($field['default_value'], 'bpr');
            }

            add_settings_field($field['field_name'], __($field['field_label'], 'bpr'), [$this, 'settings_fields_callback'], 'bulk-posts-remover', 'bpr_settings_section', $args);
        }

        register_setting($this->options_name, $this->options_name);
    }

    public function settings_fields_callback($args)
    {
        $value = $this->pre_field($args['field_name'], $args['default_value']);
        $name = $this->options_name . '[' . $args['field_name'] . ']';
        switch ($args['type']) {
            case 'text':
            case 'email':
                echo '<input type="' . $args['type'] . '" id="' . $args['field_name'] . '" name="' . $name . '" value="' . $value . '" />';
                break;
            case 'number':
                echo '<input min="1" max="800" type="' . $args['type'] . '" id="' . $args['field_name'] . '" name="' . $name . '" value="' . $value . '" />';
                break;
        }
        if (isset($args['description']) && $args['description']) {
            echo '<p class="description">' . $args['description'] . '</p>';
        }
    }

    private function pre_field($option_key, $default_val = '')
    {
        $options = get_option($this->options_name);

        if (isset($options[$option_key]) && $options[$option_key]) {
            return esc_html($options[$option_key]);
        }

        return $default_val;
    }

    public function view()
    {
        if (!current_user_can('administrator')) return;

        $active_tab = isset($_GET['tab']) && $_GET['tab'] ? sanitize_text_field($_GET['tab']) : null;

        Bulk_Posts_Remover::get_template('posts-remover-tabs.php', ['active_tab' => $active_tab]);
        $localize_args = [];
        switch ($active_tab) {
            case null:
                $options = get_option($this->options_name);
                $chunk_size = isset($options['chunk_size']) && $options['chunk_size'] ? $options['chunk_size'] : 80;
                $localize_args['chunkSize']  = $chunk_size;

                Bulk_Posts_Remover::get_template('posts-remover-main.php');
                break;
            case 'settings':
                Bulk_Posts_Remover::get_template('posts-remover-settings.php');
                break;
            default:
                break;
        }

        wp_localize_script('bpr-scripts', 'bpr', $localize_args);
    }

    public function get_posts_ids_ajax()
    {
        check_ajax_referer('myajax-nonce', 'nonce_code');
        if (!current_user_can('administrator')) wp_die();

        $post_type = isset($_GET['post_type']) ? $_GET['post_type'] : '';

        if (!$post_type) {
            wp_send_json(['status' => 0]);
            wp_die();
        }

        wp_send_json(['status' => 1, 'ids' => $this->get_posts_ids($_GET)]);
        wp_die();
    }

    public function remove_posts_ajax()
    {
        check_ajax_referer('myajax-nonce', 'nonce_code');

        if (!current_user_can('administrator')) wp_die();

        $post_type = isset($_POST['post_type']) ? $_POST['post_type'] : '';
        $ids = json_decode($_POST['ids']);

        $log = [];
        if (!is_array($ids)) {
            wp_send_json(['status' => 0, 'message' => 'Error. Wrong IDs format']);
            wp_die();
        }
        foreach ($ids as $id) {
            $title = get_the_title($id);
            $status = null;
            if ($post_type == 'attachment') {
                $status = wp_delete_attachment($id, true);
            } else {
                $status = wp_delete_post($id, true);
            }
            $status = $status ? 'Removed ' : 'Failed to remove ';
            $log[] = $status . $post_type . ' with id: ' . $id . '. ' . $title;
        }

        wp_send_json(['status' => 1, 'log' => $log]);
        wp_die();
    }


    private function get_posts_ids($args)
    {
        $defaults = array(
            'date_from' => '',
            'date_to' => '',
        );

        $args = wp_parse_args($args, $defaults);

        // Date filter
        $date_query = [];
        if ($args['date_from'] || $args['date_to']) {
            $date_query['inclusive'] = true;
        }

        if ($args['date_from']) {
            $date_query['after'] = $args['date_from'];
        }

        if ($args['date_to']) {
            $date_query['before'] = $args['date_to'];
        }

        // Create posts query
        $posts_args = [
            'post_status' => 'all',
            'post_type'   => $args['post_type'],
            'numberposts' => -1,
            'fields' => 'ids'
        ];

        if (!empty($date_query)) {
            $posts_args['date_query'] = [$date_query];
        }

        if ($posts_args['post_type'] == 'attachment') {
            $posts_args['post_mime_type'] = [
                'image/png',
                'image/jpeg',
                'image/gif'
            ];
        }

        return get_posts($posts_args);
    }
};

new BPR();
