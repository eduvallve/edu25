<?php

function get_projects_list() {
    $query_getTags = "SELECT ID, post_title, meta_value AS post_img, '' as taxonomy, '' as term_name, '' as term_slug FROM wp_posts, wp_postmeta WHERE post_type='page' AND post_parent=100 AND post_status='publish' AND wp_posts.ID = wp_postmeta.post_id AND wp_postmeta.meta_key = '_thumbnail_id' UNION SELECT ID, '', '', taxonomy, wp_terms.name as term_name, wp_terms.slug as term_slug FROM wp_posts, wp_term_relationships, wp_term_taxonomy, wp_terms WHERE post_type='page' AND post_parent=100 AND post_status='publish' AND wp_posts.ID = wp_term_relationships.object_id AND wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id AND taxonomy='category' AND wp_term_taxonomy.term_id = wp_terms.term_id ORDER BY ID DESC LIMIT 100";
    return $GLOBALS['wpdb']->get_results($query_getTags);
}

function order_projects_info() {
    $projectsList = get_projects_list();
    $projects = [];
    foreach ($projectsList as $item) {
        if (!isset($projects[$item->ID])) {
            $projects[$item->ID]['post_title'] = $item->post_title;
            $projects[$item->ID]['post_slug'] = "/?p=$item->ID";
            $projects[$item->ID]['post_img'] = "/?p=$item->post_img";
        } else {
            if (!isset($projects[$item->ID]['categories'])) {
                $projects[$item->ID]['categories'] = [];
            }
            array_push($projects[$item->ID]['categories'], ["$item->term_name", "/category/$item->term_slug"]);
        }
    }
    return $projects;
}

function show_projects_list ($amount = null) {
    if ($amount) {
        $amount = $amount['amount'];
    }
    $projects = order_projects_info();

    $list = '';
    $counter = 0;
    foreach ($projects as $project) {
        $counter++;
        if ($counter > $amount) {
            break;
        }

        $post_title = $project['post_title'];
        $post_slug = $project['post_slug'];
        $post_img = $project['post_img'];

        $categories = '';
        if (isset($project['categories'])) {
            $categories .= "<div class='portfolio__item--categories'>";
            foreach ($project['categories'] as $category) {
                $category_name = $category[0];
                $category_slug = $category[1];
                $categories .= "<a class='portfolio__item--category' href='$category_slug'>$category_name</a>";
            }
            $categories .= "</div>";
        }

        $list .= "<div class='portfolio__item'>
            <a class='portfolio__item--img' href='$post_slug'>
                <img class='portfolio__item--img-asset' src='$post_img' alt='$post_title'>
            </a>
            <div class='portfolio__item--content'>
                <a class='portfolio__item--title' href='$post_slug'>$post_title</a>
                $categories
            </div>
        </div>";
    }
    return "<div class='portfolio__list'>$list</div>";
}

add_shortcode('current_projects_list', 'show_projects_list');

?>