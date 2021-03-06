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
            'default'  => 'pl-btn-lg',
            'opts'    => array(
              'pl-btn-lg'     => array( 'name' => 'Large' ),
              'pl-btn-sm'     => array( 'name' => 'Small' ),
              'pl-btn-xs'     => array( 'name' => 'Extra Small' ),
            ),
          ), 
          array(
            'key'       => 'button_color',
            'type'      => 'select',
            'default'   => 'pl-btn-default',
            'label'     => __( 'Button Color', 'pagelines' ),
            'opts'      => array(
              'pl-btn-default'     => array( 'name' => 'Default' ),
              'pl-btn-primary'     => array( 'name' => 'Blue' ),
              'pl-btn-warning'     => array( 'name' => 'Red' ),
            ),
          ), 
      ),
  ), 
  array(
      'type'  => 'multi', 
      'title' => 'Callback Binding',
      'opts'  => array(
          array(
            'key'     => 'list_taxonomy',
            'type'    => 'select_wp_tax',
            'label'   => __( 'Select Taxonomy', 'pagelines' ),
            'default' => 'category'
          ), 

      ),
  )

);

pl_add_static_settings( $opts );


?>

<div class="pl-content-area">


  <div class="docs-example">

    <h3>Classname Binding</h3>

    <p><span class="pl-btn pl-btn-default" data-bind="plclassname: button_size">Button With One Class: Size</span></p>

    <p><span  class="pl-btn" data-bind="plclassname: [ button_size(), button_color() ]">Button With Two Classes: Size &amp; Color</span></p>

  </div>

  <div class="docs-example">

    <h3>Callback Binding</h3>

    <p>Changing the option will get all taxonomies of a certain type and list them here.</p>

    <h4>Default</h4>
    <div class="docs-container" data-bind="plcallback: list_taxonomy" data-callback="taxlist">
      None Selected
    </div>

    <h4>Lazy Loaded (added pl-load-lazy class)</h4>
    <div class="docs-container pl-load-lazy" data-bind="plcallback: list_taxonomy" data-callback="taxlist">
      None Selected
    </div>

  </div>



</div>

