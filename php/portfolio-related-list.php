<?php

function getCategoriesList() {
    $categories = get_project_categories();
    $categoriesList = [];
    foreach ($categories as $category) {
        array_push($categoriesList, $category->id);
    }
    return $categoriesList;
}

function categoriesCondition() {
    $categoriesCondition = ' AND ( ';
    foreach (getCategoriesList() as $index => $category) {
        $categoriesCondition .= "wp_terms.term_id = $category";
        if ($index < count(getCategoriesList()) - 1) {
            $categoriesCondition .= " OR ";
        }
    }
    $categoriesCondition .= " ) ";
    return $categoriesCondition;
}

function get_related_projects_list() {
    $pageId = get_page_id();
    $categoriesCondition = categoriesCondition();
    $query_getrelatedProjects = "SELECT DISTINCT ID, post_title, meta_value AS post_img FROM wp_posts, wp_postmeta, wp_term_relationships, wp_term_taxonomy, wp_terms WHERE post_type='page' AND post_parent=100 AND post_status='publish' AND wp_posts.ID = wp_term_relationships.object_id AND wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id AND taxonomy='category' AND wp_term_taxonomy.term_id = wp_terms.term_id $categoriesCondition AND ID != $pageId AND wp_posts.ID = wp_postmeta.post_id AND wp_postmeta.meta_key = '_thumbnail_id' LIMIT 100";
    return $GLOBALS['wpdb']->get_results($query_getrelatedProjects);
}

function show_related_projects_list () {
    $related = get_related_projects_list();

    $list = '';
    foreach ($related as $project) {
        $post_title = $project->post_title;
        $post_slug = "/?p=".$project->ID;
        $post_img = "/?p=".$project->post_img;
        $list .= "<div class='portfolio__item swiper-slide'>
            <a class='portfolio__item--img' href='$post_slug'>
                <img class='portfolio__item--img-asset' src='$post_img' alt='$post_title'>
            </a>
            <a class='portfolio__item--title' href='$post_slug'>$post_title</a>
        </div>";
    }
    return "<div class='portfolio__list portfolio__list--related swiper-container'>
        <div class='swiper-wrapper'>
            $list
        </div>
        <div class='swiper-button-prev'></div>
        <div class='swiper-button-next'></div>
        <div class='swiper-pagination'></div>
    </div>";
}

add_shortcode('current_related_projects_list', 'show_related_projects_list');

?>