<?php
/*
 * Template Name: PL Options and Bindings
 * Description: Overview of the PageLines binding APIs
 */

 ?>

<div class="doclist-container">

  <div class="doclist row">
  
    <sidebar class="doclist-sidebar pl-col-sm-3">
      <ul class="doclist-nav">
      
      </ul>
    </sidebar>

    <div class="doclist-content pl-col-sm-8">    
      <div class="doclist-content-pad docnav-scan">
      
      <div class="heading">
        <h1>Syncing Options with Bindings</h1>
      </div>

      <div class="section">
      
        <h2>Introduction</h2>
          <p>Bindings are a way of syncing your options with what visitors see when they visit your site.</p>

          <p>Bindings are an incredibly easy and powerful way of creating robust editing features; and they are super simple. In fact, they are primarily driven by HTML and tied automatically to options with very little, if any, server side code. </p>

        <h2>The Basics</h2>

        <h3><code>data-bind</code></h3>
        <p>PageLines bindings operate on the <code>data-bind</code> attribute which can be attached to any HTML element within the framework.</p>

        <p>The <code>data-bind</code> attribute can have one or more arguments and it ties the element to an option value.</p>

        <p>As an example, if you create a text option with a key of "my_option_key", a binding to tie this to the inner HTML of an element would look like this:</p>

<?php
$code = <<<'EOT'
/**
*  Example: "Basic data-bind"
*  The example below sets the text of an html element to the value of a option.
*/
<div data-bind="pltext: my_option_key"></div>
EOT;

echo pl_create_code( $code ); ?>

          <h3>When To Use Front End Options</h3>
          <p>Front end options and bindings are best used for content that should be changed on a page-by-page, or type-by-type basis. For example, the text of a masthead or a background image. Don't use the front end for options that should be handled globally for a section or plugin, like social icons or meta content formatting.</p>
          <p><strong>A good rule of thumb:</strong> if the section changes from page to page, use front end options (binding).</p>
          
          <h3>Binding JS and Using Parenthesis ()</h3>
          <p>The value associated with <code>data-bind</code> is first evaluated as Javascript and then set up as a binding variable. </p>
          <p>Because of this, if you have one value that is equal to the option key, you only use the key with no '()' however, if you would like to evaluate a basic logical expression, or do more complicated bindings, you have to use the variable with () after it. Here is an example:</p>

<?php

$code = <<<'EOT'
/**
 *  Example: "Using logic and ()"
 *  The example below shows how to use logical expressions with () in a data-bind.
 */
<div class="pl-col-sm-12" data-bind="class: (cols()) ? 'pl-col-sm-' + cols() : 'pl-col-sm-12'">
  ...some content... 
</div>

/**
 *  If there is no logic, don't use the ()
 */
<div class="" data-bind="class: cols">
  ...some content... 
</div>

EOT;

echo pl_create_code( $code ); ?>

          <h3>Knockout JS Binding Library</h3>
            <p>The PageLines binding system is based on the popular Knockout JS library and supports all of it's standard bindings. You can reference and use all the bindings documented on their website.</p>
            <p>Before you do, however, it is important to note that Knockout bindings don't take into account SEO and related JS events while PageLines bindings (documented below) were created and optimized for website and presentation.</p>


            <p><a class="pl-btn pl-btn-primary pl-btn-lg" href="http://knockoutjs.com/documentation/introduction.html" target="_blank">View Knockout JS Docs</a></p>
   

      <h2>Types of Bindings</h2>

      <h3>Basic Bindings</h3>

      <h4>Background Image Binding: <code>plbg</code></h4>

      <p>In order to help you create options for setting background images, we've created the simple <code>plbg</code> binding.</p>

      

<?php
               
$code = <<<'EOT'
<!-- Example "Setting Background Image" -->
<div class="element pl-bg-cover" data-bind="plbg: option_key">

  <div class="some-content">I love dogs.</div>

</div>
EOT;
echo pl_create_code( $code ); ?>

      <p><strong>Note:</strong> This binding will set the <code>background-image</code> CSS rule on the element. Any other styling will need to be handled via CSS. Typically items are set to <code>background-size: cover;</code> which you can apply using the <code>pl-bg-cover</code> helper function..</p>

      <h4>Image Source Binding: <code>plimg</code></h4>

      <p>To set the src value for images use the <code>plimg</code> binding.</p> 
      

<?php
               
$code = <<<'EOT'
<!-- Example "Setting Image src" -->
<img src="" data-bind="plimg: option_key" >
EOT;
echo pl_create_code( $code ); ?>

      <p>This binding will set the image src on load if the value of the option is not blank. If the value is blank, it will apply a 'js-unset' class which hides the img from view. This gives the user the ability to hide the image by setting it to blank.</p>

      <h4>Text &amp; HTML Binding: <code>pltext</code></h4>

      <p>The basic binding for syncing text and html to the template is the simple <code>pltext</code> binding.</p> 

<?php
               
$code = <<<'EOT'
<!-- Example "Setting Text" -->
<div class="element" data-bind="pltext: option_key" ></div>
EOT;
echo pl_create_code( $code ); ?>

      <p>This binding will set content on load if the value of the option is set. If it isn't set, it will apply a 'js-unset' class which hides the element from view. This gives the user the ability to hide the element.</p>
      
      <h4>Setting Classes: <code>plclassname</code></h4>

      <p>The class binding is the easiest way to set the class name on an element to the value of an option.</p>

<?php
               
$code = <<<'EOT'
/**
 *  Example 1
 *  The class will be set to the value of 'my_option_key'. 
 *  In this case equal to 'user-value'.
 */
<div class="user-value" data-bind="plclassname: my_option_key">
  ...Some Content...
</div>

/**
 *  Example 2 "If / Else"
 *  If set, the class will be set to the value of 'my_option_key' else it will be set to default.
 */
<div class="my-default" data-bind="plclassname: (my_option_key()) ? my_option_key() : 'my-default'">
  ...Some Content...
</div>

/**
 *  Example 3 "Multiple Classes"
 *  Set multiple classes on the element using an array.
 */
<div class="user-value-1 user-value-2" data-bind="plclassname: [ my_key_1, my_key_2 ]">
  ...Some Content...
</div>
EOT;

echo pl_create_code( $code ); ?>

      <h3>Advanced Bindings</h3>

      <h4>AJAX Callbacks: <code>plcallback</code></h4>

      <p>Often times with bindings you want to do something custom to a template, that requires some work by the server. For this need, we have created the callback binding <code>plcallback</code>.</p>

      <p>The <code>plcallback</code> binding requires some additional PHP to work, but the versatility and power of this binding is unmatched. So let's give it a try...</p>

<?php
$code = <<<'EOT'
/**
 *  Example 1
 *  Creating a typical AJAX callback with an action, callback function and required HTML binding.
 */

/**
 * Step 1 - Create the callback function and hook
 * Note: 
 *  Function name can be the name of any function
 *  The callback name is assigned on the binding element. In the data-callback attribute.
 */
add_filter( 'pl_binding_callback_name', 'my_function_name', 10, 2);

function my_function_name( $response, $data ){

  // ... Do stuff to get your HTML

  // $data['value'] will contain all the input information from the option
  // If the binding has multiple options, $data['value'] will be an array

  $size = $data['value']['size'];
  $color = $data['value']['color'];

  $response[ 'template' ] = sprintf('<button class="pl-btn %s %s">My Button</button>', $size, $color);

  return $response;
}

/**
 * Step 2 - Create the Binding
 * Note: 
 *  You can pass one or multiple values to the callback. 
 *  The binding requires an additional data-callback attribute used to identify the filter.
 */
?>

  <div data-bind="plcallback: { size: size_option_key, color: color_option_key }" data-callback="callback_name"></div>

<?php


EOT;

echo pl_create_code( $code ); ?>

      <h4>Using <code>plcallback</code> in a section</h4>

      <p>A common use case for <code>plcallback</code> will be inside of PageLines sections. To illustrate how that should be formatted, here is the following example:</p>
<?php
               
$code = <<<'EOT'
/**
 *  Example 2
 *  Complete example using plcallback inside of a section. 
 *  Includes setting option, callback and template
 */

// Create Section
class My_Awesome_Section extends PL_Section{

  // Create callback hook
  function section_persistent(){

    add_filter( 'pl_binding_callback_name', array( $this, 'my_function_name'), 10, 2);

  }

  // Create callback function and return template
  function my_function_name( $response, $data ){

    $html = do_something( $data['value'] );

    $response[ 'template' ] = $html;

    return $response;
  
  }
  
  // Create the user option used in the callback
  function section_opts(){
      
    $opts = array(
      array(
        'key'     => 'my_option_key',
        'type'    => 'text',
        'label'   => 'An Option!'
      )
    );
      
    return $opts;
    
  }
  
  // The template shown along with binding html
  function section_template(){ 
?>

    <div data-bind="plcallback: my_option_key" data-callback="callback_name"></div>

<?php  

  }

}

EOT;
echo pl_create_code( $code ); ?>

    <h4>Loop Bindings: <code>plforeach</code> and <code>pltemplate</code></h4>

    <p>Often its ideal to tie a template to an array of items inside the framework. In sections, this is controlled by the 'accordion' option type, which allows users to add/reorder/remove items as they please.</p>

    <p>To show these item arrays, we've created the <code>plforeach</code> and <code>pltemplate</code> callbacks, which will loop through them and bind elements within them, on the fly.</p>

    <p>Functionally, these two loops work very much the same way but require a different syntax. Below we have some documented examples:</p>

<?php
               
$code = <<<'EOT'
<?php 
/**
 *  Example 1 "Foreach Loop"
 *  Looping through an accordion array with a foreach
 *
 *  Note: To access options outside of the array, use the $root prefix:
 *    $root.my_other_option_key()
 */
?>


<div class="slider-gallery rendered-item trigger" data-bind="plforeach: user_item_array">

  <!-- Iterate this item for all items in user_item_array. Get text option from array, and scheme option from outside of array. -->
  <div class="cell" data-bind="plclassname: $root.scheme()">
    <div class="cell-text trigger" data-bind="pltext: text"></div>
  </div>

</div>

<?php 

/**
 *  Example 2 "Template Loop"
 *  Looping through an accordion array with a template
 *
 *  Similar syntax but put the template code in a script tag and reference it.
 */
?>
<script type="text/html" id="item-template">

<a class="item" data-bind="class: 'pl-col-sm-' + $root.ibox_cols(), plhref: link" >
  <div class="item-text media-right">
     <h3 class="item-title" data-bind="pltext: title"></h3>
     <div class="item-desc" data-bind="pltext: text"></div>
   </div>
</a>
</script>

<!-- Do iteration and reference the script template.. (item-template) -->
<div class="items-container" data-bind="template: {name: 'item-template', foreach:user_item_array() }" > </div>

<?php 

EOT;
echo pl_create_code( $code ); ?>

      <h2>Helpers</h2>

        <h3>Updating Templates With Events</h3>
        
        <p>Many of the bindings in PageLines are known as "<i>configuration</i>" bindings, which mean they control the behavior or appearance of a section.</p>

        <p>As such, when a user changes an option tied to your binding, many times you would like to trigger an "event" that Javascript can use to then rerender or change the element itself or one of its containing elements.</p>

        <p>So as an elegant way of dealing with this problem, PageLines uses the trigger helper classes.</p>

        <ul>
          <li><code>pl-trigger</code><br/> If an element has this class, when it's updated, it will also trigger a '<code>template_ready</code>' event on the containing section.</li>
          <li><code>pl-trigger-el</code> &amp; <code>pl-trigger-container</code><br/> If an element has the <code>.pl-trigger-el</code> class, it will trigger an update event on the <code>.pl-trigger-container</code> element whenever it is updated.</li>
          
        </ul>

        <h3>Controlling Content Loading</h3>
        <p>To provide the designer some control as to how PageLines bindings behave on page load we've added the pl-lazy-load helper class.</p>
        <ul>
          <li><code>pl-load-lazy</code> This class, if added to the element, will render the binding on initial page load. The default behavior of the callback, shortcode and other server related bindings is to wait for an option to change. (Thie is because they assume a fallback output via PHP). Use this class on the element render on page load instead.</li>
        </ul>

<?php
$code = <<<'EOT'
/**
*  Example: "Using pl-load-lazy helper"
*  On page load, use pl-load-lazy class to use an AJAX request to parse the option for shortcodes.
*/
<div class="pl-load-lazy" data-bind="plshortcode: my_option_key"></div>
EOT;

echo pl_create_code( $code ); ?>

        <h3>Dealing with JS Scripts</h3>

        <p>Often times you want to make bindings work with external JS scripts, like those you use in sliders, galleries, carousels, etc.. The problem is that these scripts are inconsistent and often are problematic when also working with binding updates.</p>

        <p>To resolve this, we've created a simple rendering function you can use to clone your binded (and hidden) html, and then attach your stylized JS to the clone.</p>
        <ul>
          <li><code>.pl-render-item</code> Add this class to the item you want to clone.</li>
          <li><code>plRenderItem( wrapper )</code> This javascript function returns a cloned and visible copy of the binded element with (<code>.pl-render-item</code> class). You can then use this new element to attach your fancy JS.</li>
        </ul>

<?php
$code = <<<'EOT'
/**
*  Example: "Using Render Item Helper"
*  On page load, use pl-load-lazy class to use an AJAX request to parse the option for shortcodes.
*/
<div class="wrapper">
  <div class="pl-render-item" data-bind="plshortcode: my_option_key"></div>
</div>

<script>
!function ($) {

  // Do this whenever the binding is updated (triggers the update event)
  $('.wrapper').on('template_updated', function(){

    // Get an clone ready for manipulation
    readyEl = plRenderedItem( $('.wrapper') )

    readyEl.slider()

  })

}(window.jQuery);
</script>
EOT;

echo pl_create_code( $code ); ?>


      
      </div> <!-- .section -->

      </div>
    </div> <!-- .doclist-content -->
  </div> <!-- .doclist -->
</div> <!-- .doclist-container -->