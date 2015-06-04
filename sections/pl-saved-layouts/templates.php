<?php

/**
 * Get custom templates
 */
function set_templates(){
  global $pltemplate;

  $templates = $pltemplate->get_all();

  return stripslashes_deep( $templates );
}


// ------------------------------------------
// Using Custom Templates
// ------------------------------------------

function pl_tpl_classes(){

  global $pl_custom_template; 

  $classes    = array('pl-region'); 
  $attributes   = sprintf( 'data-clone="%s"', pl_edit_id() );
  
  if( ! empty( $pl_custom_template ) ){

    $classes[] = 'custom-template';
    
    $attributes .= sprintf(
      ' data-custom-template="%s" data-template-name="%s"', 
      $pl_custom_template['key'], 
      stripslashes( $pl_custom_template['name'] )
    );
    
  } 

  if( pl_is_static_template() && ! pl_is_captured() ){

    $classes[] = 'static-template';

  }

  return sprintf( 'class="%s" %s', join(' ', $classes), $attributes );

}

function get_tpl_uid(){

  global $plpg; 

  return $plpg->id;

}

function pl_tpl_action( $response, $data ){

  $key = false;

  $custom_tpl = new PLToolsTemplates;

  $action = $data['run'];

  /** Create new template  */
  if( $action == 'create' ){

    /** Just take the template portion */
    $map = $data[ 'map' ][ 'template' ][ 'map' ];

    $key = $custom_tpl->create( array( 'name' => $data['name'], 'desc' => $data['desc'], 'map' => $map ) ); 

  }

  else if ( $action == 'update' ){

    $map = $data[ 'map' ][ 'template' ][ 'map' ];

    $key = $custom_tpl->update( $data['key'], array( 'map' => $map ) ); 

  }

  else if ( $action == 'update_info' ){


    $key = $custom_tpl->update( $data['key'], array( 'name' => $data['name'], 'desc' => $data['desc'] ) ); 
    
  }

  else if ( $action == 'delete' ){


    $key = $custom_tpl->delete( $data['key'] ); 

    
  }
  

  $response['key'] = $key;

  return $response;
  
}


/**
 * Custom Templates class
 *
 * Extends base objects abstraction and sets options
 *
 * @class     PLToolsTemplates
 * @version   3.0.0
 * @package   PageLines/Classes
 * @category  Class
 * @author    PageLines
 */
class PLToolsTemplates extends PLCustomObjects{

  
  function __construct(  ){
    
    $this->slug = 'pl-user-templates';
    
    $this->objects = $this->get_all();
    
  }
  
  function default_objects(){

    $t = array();

    $t[ 'default' ] = array(
        'name'  => __( 'Default', 'pagelines' ),
        'desc'  => __( 'Standard page configuration. (Content and Primary Sidebar.)', 'pagelines' ),
        'map' => array(
          'template' => $this->default_template( true )
        )
      );


    return apply_filters('pl_default_templates', $t);

  }

  /**
   * The default page template, takes into context page type
   */
  function default_template(){
    

    /** Default for POST type pages */
    if( pl_page_type() == 'post' ) {


      $content = array(
            array(
              'object'  => 'PL_Content',
              'settings'  => array(
                'col'     => 8
              ),
            ),
            
            array(
              'object'  => 'PL_Widgets',
              'settings'  => array(
                'col'     => 4,
              ),
            )
          );

    } 
    
    
    /** Default for everything else... */
    else {
      $content = array(
        array(
          'object'  => 'PL_Content',
        )
      );
    }


    return apply_filters( 'pl_default_template_handler', array( 'content' => $content ) );
  }

}


