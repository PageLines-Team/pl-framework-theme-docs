<?php 


$default_banner = array(

    'header'                => 'Welcome to The Docs', 
    'subheader'             => 'Getting started guide and creation docs for PageLines Framework 5', 
    'button_primary'        => 'http://www.pagelines.com',
    'button_primary_text'   => 'Read the Docs',
    'button_primary_theme'  => 'ol-white',
    'effects'               => 'pl-effect-window-height',
    'theme'                 => 'pl-scheme-dark'

  );
  ?>

<div class="sticky-nav">
<?php echo pl_get_section( array('section' => 'navigation') );?>
</div>