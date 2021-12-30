<?php

if (!function_exists('dbg')) {
    function dbg($var, $log = true)
    {
        if ($log) {
            error_log(print_r($var, true));
        } else {
            echo '<pre>';
            print_r($var);
            echo '</pre>';
        }
    }
}

if (!function_exists('pr_get_post_name')) {
    function pr_get_post_name($slug)
    {
        switch ($slug) {
            case 'revision':
                return 'Revisions';
            case 'post':
                return 'Posts';
            case 'page':
                return 'Pages';
            case 'product_variation':
                return 'WC Product Variations (product_variation)';
            case 'product':
                return 'WC Product (product)';
            case 'attachment':
                return 'Media Images (attachment)';
            default:
                return false;
        }
    }
}
