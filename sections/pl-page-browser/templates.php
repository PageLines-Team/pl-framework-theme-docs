<?php
/**
 *
 *
 *  Page Selection Selector in Editing mode
 *
 *
 *  @package  PageLines Framework
 *  @since    3.0.0
 *
 *
 */
class PL_Workarea_Selector {

  function __construct( ) {

    $this->query_limit = 30;

    global $plpg;
    $this->page = $plpg;

    add_action( 'pl_server_get_page_children',  array( $this, 'get_page_children' ),  10, 2 );
    add_action( 'pl_server_search_pages',     array( $this, 'search_pages' ),     10, 2 );

  }


  function template(){

    ob_start();
?>

<div id="layout-selector-select" class="pl-page-selector fix">

  <div id="layout-selector-select-content" class="selector-content">
    <span id="currently-editing">Currently Editing:</span><span id="current-layout"><?php echo $this->page->get_current_page_name();?></span>
    <span class="select-caret"><i class="icon icon-caret-down"></i></span>
  </div>

  <div id="layout-selector" class="the-page-selector">

    <div id="layout-selector-pages-container" class="the-pages-container">

      <script type="text/html" id="layout-page-template">
        <li class="layout-item page-select-item" data-bind="css: {
          'has-children': children().length || ajaxChildren,
          'has-ajax-children': ajaxChildren
        }">

          <span class="the-page layout layout-page" data-bind="attr: {'data-page-id': id, 'data-page-url': url}">
            <span class="children-indicator"><i class="icon icon-caret-down"></i><i class="icon icon-caret-right"></i></span>
            <span class="switch-to-page the-page-title" data-bind="html: name"></span>
          </span>

          <ul class="list-unstyled fix" data-bind="template: {name: 'layout-page-template', foreach: children()}"></ul>

          <span class="load-more-layouts" data-bind="visible: typeof ajaxShowMore != 'undefined' && ajaxShowMore()">Load More...</span>

        </li>
      </script>

      <div id="layout-selector-pages" class="layout-selector-content">
        <ul class="list-unstyled" data-bind="visible: search().length, template: {name: 'layout-page-template', foreach:search()}" id="layout-selector-pages-search-results"></ul>
        <ul class="list-unstyled" data-bind="visible: !search().length, template: {name: 'layout-page-template', foreach:pages()}"></ul>
      </div><!-- div#layout-selector-pages -->


    </div>
    <div id="layout-search-input-container" class="layout-selector-bottom-input page-search-container">
      <form>
        <input type="search" placeholder="Type to Search..." value="" id="layout-search-input" pattern=".{3,}" class="page-search-input search-input allow-enter-key form-control" title="Your search must be 3 characters or longer." data-bind="value: searchQuery, valueUpdate: 'keyup'" />
        <span class="btn btn-primary page-search-submit" id="layout-search-submit">Search</span>
      </form>
    </div>

  </div>
</div>

<?php

    return ob_get_clean();
  }


  function search_pages( $response, $data ){


    global $wpdb;

    $query = $data['query'];

    if ( empty($query) || strlen($query) <= 2 ) {
      return false;
    }

    $results = array();

    /* Posts */
    $posts_query = $wpdb->prepare("SELECT ID, post_title, post_status, post_type FROM $wpdb->posts WHERE $wpdb->posts.post_title LIKE '%s' ORDER BY $wpdb->posts.post_title", '%' . $query . '%');

    foreach ( $wpdb->get_results( $posts_query ) as $post ) {

      $post_type_page_slug = 'single__' . $post->post_type;
      $page_slug = $post_type_page_slug . '__' . $post->ID;

      if ( !isset($results[ $post_type_page_slug ]) ) {

        $post_type_object = get_post_type_object( $post->post_type );

        $results[ $post_type_page_slug ] = $this->format_pages_array( $post_type_page_slug, array(
          'name' => 'Single &rsaquo; ' . $post_type_object->labels->name,
          'children' => array()
        ));

      }

      $results[ $post_type_page_slug ]['children'][] = $this->format_pages_array($page_slug, array(
        'name' => $post->post_title,
        'url' => trailingslashit(home_url()) . '?p=' . $post->ID
      ));

    }

    /* Archives/Terms */
    foreach ( get_taxonomies( array( 'public' => true, '_builtin' => true ), 'objects' ) as $slug => $taxonomy ) {

      $terms = get_terms( $slug, array(
        'name__like' => $query
      ) );

      if ( !empty($terms ) ) {

        if ( $taxonomy->_builtin ) {
          $taxonomy_page_slug = 'archive__post__' . $slug;
        } else {
          $taxonomy_page_slug = 'archive__taxonomy__' . $slug;
        }

        $taxonomy_object = get_taxonomy( $slug );

        $results[ $taxonomy_page_slug ] = $this->format_pages_array( $taxonomy_page_slug, array(
          'name'     => 'Archive &rsaquo; ' . $taxonomy_object->labels->name,
          'children' => array()
        ) );

        foreach ( $terms as $term ) {

          $page_slug = $taxonomy_page_slug . '__' . $term->term_id;

          $results[ $taxonomy_page_slug ]['children'][] = $this->format_pages_array($page_slug, array(
            'name' => $term->name
          ));

        }

      }

    }

    /* Users */
    $user_query = get_users( array(
      'fields' => array(
        'ID',
        'display_name'
      ),
      'search' => '*' . $query . '*'
    ) );

    if ( !empty($user_query) ) {

      $authors_page_slug = 'archive__post__author';

      $results[ $authors_page_slug ] = $this->format_pages_array( $authors_page_slug, array(
        'name' => 'Archive &rsaquo; Author',
        'children' => array()
      ) );

    }

    foreach ( $user_query as $user ) {

      $page_slug = $authors_page_slug . '__' . $user->ID;

      $results[ $authors_page_slug ]['children'][] = $this->format_pages_array($page_slug, array(
        $user->display_name
      ));

    }

    $response['formatted_pages'] = array_values($results);

    return $response;
  }

  function query_posts( $post_type = 'post', $post_parent = 0, $offset = 0 ) {

    $query = get_posts( array(
      'post_type'   => $post_type,
      'post_parent' => $post_parent,
      'numberposts' => $this->query_limit,
      'post_status' => array( 'publish', 'pending', 'draft', 'future', 'private' ),
      'offset'    => $offset
    ) );

    $posts = array();

    foreach ( $query as $post ) {

      $posts['single__' . $post_type . '__' . $post->ID] = array();

      $has_children_query = get_posts( array(
        'post_type'   => $post_type,
        'post_parent' => $post->ID,
        'numberposts' => 1,
        'post_status' => array( 'publish', 'pending', 'draft', 'future', 'private' )
      ) );

      if ( $has_children_query ) {
        $posts[ sprintf('single__%s__%s', $post_type, $post->ID) ]['ajaxChildren'] = true;
      }

    }

    return $posts;

  }

  function query_terms($taxonomy, $prefix, $parent = 0, $offset = 0) {

    $query = get_terms( $taxonomy, array(
      'parent'    => $parent,
      'number'    => $this->query_limit,
      'offset'    => $offset,
      'hide_empty'  => false
    ) );

    $terms = array();

    foreach ( $query as $term ) {

      $taxonomy_id = ($prefix != '') ? sprintf('%s__%s', $prefix, $taxonomy)  : $taxonomy;


      $the_slug = sprintf( 'archive__%s__%s', $taxonomy_id, $term->term_id );

      $terms[ $the_slug ] = array();

      $has_children_query = get_terms( $taxonomy, array(
        'parent'    => $term->term_id,
        'hide_empty'  => false,
        'number'    => 1
      ) );

      if ( $has_children_query ) {

        $terms[ $the_slug ]['ajaxChildren'] = true;

      }

    }

    return $terms;

  }


  function query_authors($offset = 0, $prefix) {

    $author_query = get_users( array(
      'who'     => 'authors',
      'fields'  => 'ID',
      'offset'  => $offset,
      'orderby'   => 'post_count',
      'number'  => $this->query_limit
    ) );

    $authors = array();

    foreach ( $author_query as $author_id ) {

      $the_slug = sprintf( 'archive__post__author__%s', $author_id );

      $authors[ $the_slug ] = array();

    }

    return $authors;

  }

  function get_page_children( $response, $data ){

    $page_slug  = $data['layout'];
    $offset   = $data['offset'];

    $page_slug_fragments = explode('__', $page_slug);

    $groupID    = $page_slug_fragments[0]; 

    $typeID     = ( isset($page_slug_fragments[1]) ) ? $page_slug_fragments[1] : ''; 

    $metaID     = ( isset($page_slug_fragments[2]) ) ? $page_slug_fragments[2] : '';

    $termID     = ( isset($page_slug_fragments[3]) ) ? $page_slug_fragments[3] : '';

    if ( count( $page_slug_fragments ) === 1 ) {
      return null;
    }

    if( $groupID == 'single' ){

      $post_type = $typeID;
      
      $post_parent = !empty( $metaID ) ? $metaID : 0;

      $pages = $this->query_posts($post_type, $post_parent, $offset);

    }

    else if( $groupID == 'archive' ){

      if( $typeID == 'taxonomy' ){

        /** Top Level Taxonomies */
        if ( empty( $metaID ) ) {

          $taxonomies_query = get_taxonomies( array( 'public' => true, '_builtin' => false ), 'objects' );
          $exclude          = array( 'link_category' );
          $taxonomies       = array();

          $pages = array();

          foreach ( $taxonomies_query as $slug => $taxonomy ) {

            $pages['archive__taxonomy__' . $slug] = array('ajaxChildren' => true);

          }

        } 

        /** A sub taxonomy, lets deal with underscores and next level  */
        else {

          $parent = !empty( $termID ) ? $termID : 0;

          $pages = $this->query_terms( $metaID, 'taxonomy', $parent, $offset );

        //  $response['pages'] = $pages;
        }

      }

      else if ( $typeID == 'post_type' ){

        $post_types = get_post_types( array( 'public' => true ), 'objects' );

        $excluded_post_types = array( 'post', 'page', 'attachment' );

        $pages = array();

        foreach ( $post_types as $post_type ) {

          //If excluded, skip it
          if ( in_array( $post_type->name, $excluded_post_types ) )
            continue;

          $pages[ 'archive__post_type__' . $post_type->name] = array();

        }

      }

      else if ( $typeID == 'post' ){


        if( $metaID == 'category' ){
          $parent = ! empty( $termID ) ? $termID : 0; 
          $pages = $this->query_terms( 'category', 'post', $parent, $offset );
        }

        else if ( $metaID == 'author' ){
          $pages = $this->query_authors( $offset, 'post');
        }

        else if ( $metaID == 'post_tag' ){
          $pages = $this->query_terms( 'post_tag', 'post', 0, $offset );

        }

        else if ( $metaID == 'post_format' ){
          $pages = $this->query_terms( 'post_format', 'post', 0, $offset );
        }

      }

    }



    /* Format the array for Knockout */
    $formatted_pages = array();

    foreach ( $pages as $page_slug => $page_info ) {
      $formatted_pages[] = $this->format_pages_array( $page_slug, $page_info );
    }

    $response['formatted_layouts'] = $formatted_pages;


    return $response;
  }


  function get_pages_array() {


    $pages = array();

    /* Index & Front Page */
    if ( get_option( 'show_on_front' ) == 'page' ) {

      $pages['single__page__' . get_option( 'page_on_front' ) ] = array();

    } 

    
    $pages['archive__post__blog'] = array();
    

    

    /* Single */
    $pages['single'] = array( 'children' => array() );

    foreach ( get_post_types( array( 'public' => true ), 'objects' ) as $post_type ) {

      $pages[ 'single' ][ 'children' ][ 'single__' . $post_type->name] = array( 'ajaxChildren' => true);

    }

    /* Archives */
    $pages['archive'] = array(
      'children' => array(
        'archive__post__category'       => array( 'ajaxChildren' => true),
        'archive__post__search'         => array( ),
        'archive__post__date'           => array( ),
        'archive__post__author'         => array( 'ajaxChildren' => true),
        'archive__post__post_tag'         => array( 'ajaxChildren' => true),
        'archive__post__post_format'      => array( 'ajaxChildren' => true),
        'archive__taxonomy'           => array( 'ajaxChildren' => true),
        'archive__post_type'          => array( 'ajaxChildren' => true),
        
      )
    );

    /* 404 */
    $pages['special__four04'] = array();


    /* Format the array for Knockout */
    $formatted_pages = array();

    foreach ( $pages as $page_slug => $page_info ) {
      $formatted_pages[] = $this->format_pages_array( $page_slug, $page_info );
    }

    return $formatted_pages;

  }

  function format_pages_array( $page_slug, $info ) {

    $post_status   = $this->array_get( 'post_status', $info );

    $page_setup = array(
      'id'          =>  $page_slug,
      'name'        =>   $this->array_get( 'name',    $info ) ? $this->array_get( 'name', $info ) : $this->page->get_page_slug_info( $page_slug, 'name' ),
      'url'         =>   $this->array_get( 'url',     $info ) ? $this->array_get( 'url', $info ) : $this->page->get_page_slug_info( $page_slug, 'url' ),
      'children'    =>   $this->array_get( 'children',  $info ) === false ? false : array(),
      'ajaxChildren'  => ! $this->array_get( 'ajaxChildren',    $info ) ? false : true,
    );

    if ( !empty( $info['children'] ) && is_array( $info['children'] ) ) {

      $page_setup['children'] = array();

      foreach ( $info['children'] as $child_id => $child_info ) {
        $page_setup['children'][ ] = $this->format_pages_array( $child_id, $child_info );
      }

    }

    return $page_setup;

  }


  function array_get( $name, $array = false, $default = null, $fix_data_type = false ) {

    if ( $array === false )
      $array = $_GET;

    if ( (is_string( $name ) || is_numeric( $name )) && !is_float( $name ) ) {

      if ( is_array($array) && isset($array[$name]) )
        $result = $array[$name];
      
      elseif ( is_object($array) && isset($array->$name) )
        $result = $array->$name;

    }

    if ( !isset($result) )
      $result = $default;

    return ! $fix_data_type ? $result : pl_fix_data_type( $result );

  }

  
}
