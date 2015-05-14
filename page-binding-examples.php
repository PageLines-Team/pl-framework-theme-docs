<?php
/*
 * Template Name: Binding Examples
 * Description: Examples of bindings and options
 */

$opts = array(
  array(
      'type'  => 'multi', 
      'title' => 'Classname Binding',
      'opts'  => array(
          array(
            'key'     => 'button_size',
            'type'    => 'select',
            'label'   => __( 'Button Size', 'pagelines' ),
            'default'  => 'btn-lg',
            'opts'    => array(
              'btn-lg'     => array( 'name' => 'Large' ),
              'btn-sm'     => array( 'name' => 'Small' ),
              'btn-xs'     => array( 'name' => 'Extra Small' ),
            ),
          ), 
          array(
            'key'       => 'button_color',
            'type'      => 'select',
            'default'   => 'btn-default',
            'label'     => __( 'Button Color', 'pagelines' ),
            'opts'      => array(
              'btn-default'     => array( 'name' => 'Default' ),
              'btn-primary'     => array( 'name' => 'Blue' ),
              'btn-warning'     => array( 'name' => 'Red' ),
            ),
          ), 
      ),
  )

);

pl_add_static_settings( $opts );

?>

<div class="pl-content">


  <div class="docs-example">

    <h3>Classname Binding</h3>

    <p><span class="btn btn-default" data-bind="plclassname: button_size">Button With One Class: Size</span></p>

    <p><span  class="btn" data-bind="plclassname: [ button_size(), button_color() ]">Button With Two Classes: Size &amp; Color</span></p>

  </div>

  

</div>

