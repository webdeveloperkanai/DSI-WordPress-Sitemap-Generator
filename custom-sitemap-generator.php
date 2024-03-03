<?php
/*
Plugin Name: Custom Sitemap Generator
Description: Generate a custom sitemap with high priority including posts, pages, and author information.
Version: 1.0
Author: DEV SEC IT 
Author uri: https://devsecit.com/
Plugin uri: https://devsecit.com/wordpress/plugin/custom-sitemap
Docs uri: https://devsecit.com/
*/
 
 
add_action('init', 'custom_sitemap_endpoint');
function custom_sitemap_endpoint() {
    add_rewrite_rule('custom-sitemap.xml$', 'index.php?custom_sitemap=true', 'top');
}

// Add the query var for the custom sitemap
add_filter('query_vars', 'custom_sitemap_query_var');
function custom_sitemap_query_var($vars) {
    $vars[] = 'custom_sitemap';
    return $vars;
}

// Handle the custom sitemap request
add_action('template_redirect', 'custom_sitemap_template_redirect');
function custom_sitemap_template_redirect() {
    $custom_sitemap = get_query_var('custom_sitemap', false);
    if ($custom_sitemap) {
        // Clear any previously generated output
        ob_clean();

        // Set header for XML content type
        header('Content-Type: text/xml');

        // Output XML declaration
        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Output posts
        $posts = get_posts(array('post_type' => 'post', 'numberposts' => -1));
        foreach ($posts as $post) {
            echo '<url>';
            echo '<loc>' . get_permalink($post->ID) . '</loc>';
            echo '<priority>1.0</priority>'; // High priority
            echo '</url>';
        }

        // Output pages
        $pages = get_pages(array('number' => -1));
        foreach ($pages as $page) {
            echo '<url>';
            echo '<loc>' . get_permalink($page->ID) . '</loc>';
            echo '<priority>1.0</priority>'; // High priority
            echo '</url>';
        }

        // Output authors
        $authors = get_users(array('who' => 'authors'));
        foreach ($authors as $author) {
            echo '<url>';
            echo '<loc>' . get_author_posts_url($author->ID) . '</loc>';
            echo '<priority>1.0</priority>'; // High priority
            echo '</url>';
        }

        echo '</urlset>';
        exit;
    }
}
