<?php
/*
  
  Plugin Name:    PL Framework Saved Layouts
  Description:    Save page layouts and custom sections and use them throughout your site.

  Author:         PageLines
  Author URI:     http://www.pagelines.com

  Version:        1.0.0
  PageLines:      true


*/


/**
 * Hybrid section/plugin - only run this first class on initial plugin load
 */
if( ! class_exists( 'PL_Saved_Layouts' )){


  /** Wait until everything is loaded for this. */
  add_action('wp', 'PL_Saved_Layouts'); 
  function PL_Saved_Layouts(){
    new PL_Saved_Layouts(); 
  }

  /**
   * PLUGIN
   * Do this if the plugin is activated, whether or not there is a framework
   */
  class PL_Saved_Layouts {

    function __construct(){

      $this->id = 'pl-saved-layouts';

      $this->url = get_stylesheet_directory_uri() . '/sections/'.$this->id;

      pl_add_ab_menu( array(
        'id'      => 'pl-ab-layouts',
        'title'   => '<i class="icon icon-file-image-o"></i> Saved Layouts',
        'rel'     => 'plTemplates',
        'href'    => add_query_arg('pl_tool', 'plTemplates', PL()->urls->editor )
      ));

      add_action('pl_workarea_scripts', array($this, 'scripts'));
    }

    function scripts(){

      wp_enqueue_script( $this->id, $this->url . '/script.js', array('jquery'), pl_get_cache_key(), true );
    }


  }

  

}



