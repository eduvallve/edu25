<?php
function get_project_categories(){
    $page_id = get_page_id();
    $query_getTags = "SELECT wp_terms.name as name, wp_terms.slug as slug, wp_terms.term_id as id FROM wp_terms, wp_term_taxonomy, wp_term_relationships WHERE wp_terms.term_id = wp_term_taxonomy.term_id AND wp_term_taxonomy.taxonomy = 'category' AND wp_term_taxonomy.term_taxonomy_id = wp_term_relationships.term_taxonomy_id AND wp_term_relationships.object_id = $page_id";
    return $GLOBALS['wpdb']->get_results($query_getTags);
}

function show_project_categories() {
    $categoriessList = get_project_categories();
    $content = '';
    foreach ($categoriessList as $category) {
        $content .= "<a href='/category/$category->slug' target='_self' class='custom-button'>$category->name</a>";
    }

    return "<div class='project__categories'>Categories: $content</div>";
}

add_shortcode('current_project_categories', 'show_project_categories');

?>