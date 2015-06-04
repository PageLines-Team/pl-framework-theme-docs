<?php
/*
  
  Plugin Name:    PL Framework Custom LESS
  Description:    Add custom LESS code globally from the front end.

  Author:         PageLines
  Author URI:     http://www.pagelines.com

  Version:        1.0.0
  PageLines:      true


*/

/**
 * Hybrid section/plugin - only run this first class on initial plugin load
 */
if( ! class_exists( 'PL_Custom_Less' )){


  /** Wait until everything is loaded for this. */
  add_action('wp', 'PL_Custom_Less'); 
  function PL_Custom_Less(){
    new PL_Custom_Less(); 
  }
  
  /**
   * PLUGIN
   * Do this if the plugin is activated, whether or not there is a framework
   */
  class PL_Custom_Less {

    function __construct(){


      $this->id = 'pl-custom-less';
      $this->url = get_stylesheet_directory_uri() . '/sections/'.$this->id;

      pl_add_ab_menu( array(
        'id'      => 'pl-ab-code',
        'title'   => '<i class="icon icon-code"></i> Custom CSS',
        'rel'     => 'plCode',
        'href'    => add_query_arg('pl_tool', 'plCode', PL()->urls->editor )
      ));

      add_action('pl_workarea_scripts', array($this, 'scripts'));

      add_action( 'pl_header_css', array($this, 'css_head'));
      add_action( 'wp_footer',  array($this, 'custom_less'),    99 );

      add_filter( 'pl_standard_save',  array($this, 'save_styles'), 10, 2 );
    }

    function scripts(){

      wp_enqueue_script( $this->id, $this->url . '/script.js', array('jquery'), pl_get_cache_key(), true );
    }

    function css_head(){
      ?>
      <style id="pl-custom-css">
      <?php echo pl_setting('custom_css');?>
      </style>
      <?php 
    }

    function custom_less(){

      printf('<script type="text/plain" id="pl-custom-less">%s</script>', pl_setting('custom_less') );

    }

    function save_styles( $response, $data ){

      if( isset( $data['styles'] ) ){

        $styles = $data['styles'];

        pl_setting_update( 'custom_css',  $styles['css'] );
        pl_setting_update( 'custom_less',   $styles['less'] );

      }

      return $response;

    }


  }


}



