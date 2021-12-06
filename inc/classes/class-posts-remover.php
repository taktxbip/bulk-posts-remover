<?php
final class Post_Remover
{
    public function __construct()
    {
        add_action('wp_ajax_get_posts_ids', array($this, 'get_posts_ids'));
        add_action('wp_ajax_nopriv_get_posts_ids', array($this, 'get_posts_ids'));

        add_action('wp_ajax_remove_posts', array($this, 'remove_posts'));
        add_action('wp_ajax_nopriv_remove_posts', array($this, 'remove_posts'));
    }

    public function get_posts_ids()
    {
        check_ajax_referer('myajax-nonce', 'nonce_code');
        if (!current_user_can('administrator')) die();

        $post_type = isset($_GET['post_type']) ? $_GET['post_type'] : '';

        if (!$post_type) {
            echo json_encode(array('status' => 0));
            die();
        }

        echo json_encode(array('status' => 1, 'post_type' => $post_type, 'ids' => $this->count_posts($post_type)));
        die();
    }

    public function remove_posts()
    {
        check_ajax_referer('myajax-nonce', 'nonce_code');
        if (!current_user_can('administrator')) die();

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
        die();
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

new Post_Remover();
