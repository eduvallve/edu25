<?php

function get_project_tags(){
    $page_id = get_page_id();
    $query_getTags = "SELECT wp_terms.name as name, wp_terms.slug as slug FROM wp_terms, wp_term_taxonomy, wp_term_relationships WHERE wp_terms.term_id = wp_term_taxonomy.term_id AND wp_term_taxonomy.taxonomy = 'post_tag' AND wp_term_taxonomy.term_taxonomy_id = wp_term_relationships.term_taxonomy_id AND wp_term_relationships.object_id = $page_id";
    return $GLOBALS['wpdb']->get_results($query_getTags);
}

function show_project_tags () {
    $tagsList = get_project_tags();
    $content = '';
    foreach ($tagsList as $tag) {
        $content .= "<a href='/tag/$tag->slug' target='_self' class='custom-button'>$tag->name</a>";
    }

    return "<div class='project__tags'>Tags: $content</div>";
}

add_shortcode('current_project_tags', 'show_project_tags');

?>