<?php

function pages_with_category_tag_register(){
  /* add categories and tags to pages */
  register_taxonomy_for_object_type('category', 'page');
  register_taxonomy_for_object_type('post_tag', 'page');
}
add_action( 'init', 'pages_with_category_tag_register');

function pages_with_category_tag_register_pre_get( $query ) {

  if ( is_admin() || ! $query->is_main_query() ) {
    return;
  }
  /* view categories and tags archive pages */
  if($query->is_category && $query->is_main_query()){
    $query->set('post_type', array( 'post', 'page'));
  }
  if($query->is_tag && $query->is_main_query()){
    $query->set('post_type', array( 'post', 'page'));
  }
}
add_action( 'pre_get_posts', 'pages_with_category_tag_register_pre_get' );

?>