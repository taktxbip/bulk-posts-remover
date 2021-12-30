<?php
final class BPR
{
    public function __construct()
    {
        add_action('wp_ajax_get_posts_ids', [$this, 'get_posts_ids']);
        add_action('wp_ajax_nopriv_get_posts_ids', [$this, 'get_posts_ids']);

        add_action('wp_ajax_remove_posts', [$this, 'remove_posts']);
        add_action('wp_ajax_nopriv_remove_posts', [$this, 'remove_posts']);

        add_action('admin_menu', function () {
            add_submenu_page('tools.php', 'Bulk Posts Remover', 'Bulk Posts Remover', 'administrator', 'bulk-posts-remover', [$this, 'view'], 8);
        });
    }

    public function view()
    {
        if (!current_user_can('administrator')) return;

        $active_tab = isset($_GET['tab']) && $_GET['tab'] ? sanitize_text_field($_GET['tab']) : null;

        Bulk_Posts_Remover::get_template('posts-remover-tabs.php', ['active_tab' => $active_tab]);

        switch ($active_tab) {
            case null:
                Bulk_Posts_Remover::get_template('posts-remover-main.php');
                break;
            case 'settings':
                Bulk_Posts_Remover::get_template('posts-remover-settings.php');
                break;
            case 'about':
                Bulk_Posts_Remover::get_template('posts-remover-about.php');
                break;
            default:
                break;
        }
    }

    public function get_posts_ids()
    {
        check_ajax_referer('myajax-nonce', 'nonce_code');
        if (!current_user_can('administrator')) wp_die();

        $post_type = isset($_GET['post_type']) ? $_GET['post_type'] : '';

        if (!$post_type) {
            echo json_encode(array('status' => 0));
            wp_die();
        }

        echo json_encode(array('status' => 1, 'post_type' => $post_type, 'ids' => $this->count_posts($post_type)));
        wp_die();
    }

    public function remove_posts()
    {
        check_ajax_referer('myajax-nonce', 'nonce_code');
        if (!current_user_can('administrator')) wp_die();

        $post_type = isset($_POST['post_type']) ? $_POST['post_type'] : '';
        $ids = $_POST['ids'];

        $log = [];
        if ($post_type == 'attachment') {
            foreach ($ids as $id) {
                $log[] = '<strong>Removed ' . $post_type . ' id: ' . $id . '.</strong> ' . get_the_title($id);
                if ($id > 10000) {
                    // wp_delete_attachment($id, true);
                }
            }
        } else {
            foreach ($ids as $id) {
                $log[] = '<strong>Removed ' . $post_type . ' id: ' . $id . '.</strong> ' . get_the_title($id);
                // wp_delete_post($id, true);
            }
        }

        echo json_encode(array('status' => 0, 'log' => $log));
        wp_die();
    }


    private function count_posts($post_type)
    {
        $args = array(
            'post_status' => 'publish',
            'post_type'   => $post_type,
            'numberposts' => -1,
            'fields' => 'ids'
        );
        if ($post_type == 'attachment') $args['post_mime_type'] = 'image';
        $ids = get_posts($args);
        return $ids;
    }
};

new BPR();
