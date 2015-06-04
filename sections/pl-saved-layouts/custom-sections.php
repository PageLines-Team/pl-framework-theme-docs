<?php 

class PLCustomSections extends PLCustomObjects{

  function __construct(  ){

    $this->slug = 'pl-user-sections';

    $this->objects = $this->get_all();
  }


  function render_user_sections(){

    $rendered = array();

    foreach( $this->objects as $key => $i){

      $name = ( isset($i['name']) ) ? $i['name'] : __( 'No Name', 'pagelines' );
      $desc = ( isset($i['desc']) ) ? $i['desc'] : __( 'No Description Entered.', 'pagelines' );

      $rendered[ $key ] = array(
        'id'      => $key,
        'name'      => $name,
        'object'    => 'PL_Container',
        'description' => $desc,
        'filter'    => 'custom-section, full-width',
        'ctemplate'   => $key,
        'screenshot'  =>  PL_IMAGES . '/section-user.png',
        'thumb'     =>  PL_IMAGES . '/section-user.png',
      );

    }


    return pl_array_to_object( $rendered, pl_section_config_default() );
  }

}