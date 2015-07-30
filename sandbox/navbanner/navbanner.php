<?php
/*

  Section:      NavBanner
  Description:  A masthead banner that includes navigation

  Author:       PageLines
  Author URI:   http://www.pagelines.com
  
  Class Name:   NavBanner
  Filter:       advanced

*/

class NavBanner extends PL_Section {

  /**
   *  Standard method for setting defaults for standard options.
   */
  function section_defaults(){
    return array( 'effects' => 'pl-effect-window-height', 'theme' => 'pl-scheme-light', 'navi_logo' => '[pl_image_url]/leaf-white.png' );
  }

  function section_template(){


    $default_banner = array(

        'header'                => 'Welcome to The Docs', 
        'subheader'             => 'Getting started guide and creation docs for PageLines Framework 5', 
        'button_primary'        => 'http://www.pagelines.com',
        'button_primary_text'   => 'Read the Docs',
        'button_primary_style'  => 'primary',

      );

    echo pl_get_section( array( 'section' => 'docnav' ) );

    echo pl_get_section( array( 'section' => 'elements', 'id' => '123221221', 'settings' => $default_banner ) );


  }

}


