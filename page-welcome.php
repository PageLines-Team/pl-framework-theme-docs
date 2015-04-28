<?php
/*
 * Template Name: Welcome to PageLines
 * Description: Congrats, lets get started.
 */


$opts = array(
	array(
		'key'		=> 'post_content',
		'col'		=> 1,
		'type'		=> 'edit_post',
		'title'		=> __( 'Edit Post Content', 'pagelines' ),
		'label'		=>	__( '<i class="icon icon-pencil"></i> Edit Post Info', 'pagelines' ),
		'help'		=> __( 'This section uses WordPress posts. Edit post information using WordPress admin.', 'pagelines' ),
		'classes'	=> 'btn-primary'
	),
	);

pl_add_template_settings( $opts );



$default_banner = array(

		'header' 								=> 'Built for Perfectionists', 
		'subheader' 						=> 'PageLines and WordPress is the best way to create and maintain client websites.', 
		'button_primary'				=> 'http://www.pagelines.com',
		'button_primary_text'		=> 'Read the Docs',
		'button_primary_theme'	=> 'ol-white',
		'effects'								=> 'pl-effect-window-height',
		'theme'									=> 'pl-scheme-dark'

	);


?>

<div class="banner-board">

	<?php echo pl_get_section( array('section' => 'elements', 'id' => 'can_support_id_up_to_50_chars', 'settings' => $default_banner ) );?>

</div>


<?php echo pl_get_section( array('section' => 'slider', 'id' => '424242') );?>

<?php echo pl_get_section( array('section' => 'hero', 'id' => '2222223') );?>

<?php echo do_shortcode( '[plsection section="slider"]');?>


<div  class="pl-content">
	<div class="row-flex">
		<div class="col-sm-4">123</div>
		<div class="col-sm-4">123</div>
		<div class="col-sm-4">123</div>
	</div>
</div>


