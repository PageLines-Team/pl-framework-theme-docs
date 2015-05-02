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

		'header' 								=> 'Welcome to The Docs', 
		'subheader' 						=> 'Getting started guide and creation docs for PageLines Framework 5', 
		'button_primary'				=> 'http://www.pagelines.com',
		'button_primary_text'		=> 'Read the Docs',
		'button_primary_style'	=> 'primary',
		'effects'								=> 'pl-effect-window-height',
		'theme'									=> 'pl-scheme-dark'

	);

$default_boxes = [
		
		'header'	=> 'Tools for Beautiful Sites',

		'ibox_array' 			=> [
			[
				'title'		=> "Simple Editing", 
				'text'		=> 'Everything in F5 is designed to be customized easily as you see it.',
				'icon'		=> 'clock-o'
			],
			[
				'title'		=> "Drag &amp; Drop", 
				'text'		=> 'Control your layouts completely with drag and drop editing.', 
				'icon'		=> 'random'
			],
			
			[
				'title'		=> "For Pros &amp; Clients.", 
				'text'		=> 'Features that both professional AND clients will love',
				'icon'		=> 'user-plus'
			],
			[
				'title'		=> "Simple.", 
				'text'		=> 'A minimal interface but with maximum power. Forget the bloat.',
				'icon'		=> 'circle-o'
			],

			[
				'title'		=> "Responsive", 
				'text'		=> 'More advanced responsive features than any other framework.',
				'icon'		=> 'mobile-phone'
			], 
			[
				'title'		=> "Developer Friendly", 
				'text'		=> 'Everything can be accessed and modified using the robust developer tools.',
				'icon'		=> 'code'
			], 
		]
	];


?>

<?php echo pl_get_section( array('section' => 'elements', 'id' => 'uid2121', 'settings' => $default_banner ) );?>

<?php echo pl_get_section( array( 'section' => 'boxes', 'id' => 'someUniqueID2', 'settings' => $default_boxes ) );?>

<?php echo pl_get_section( array('section' => 'hero', 'id' => '2222223') );?>

<?php echo do_shortcode( '[plsection section="slider"]');?>


<div  class="pl-content">
	<div class="row-flex">
		<div class="col-sm-4">123</div>
		<div class="col-sm-4">123</div>
		<div class="col-sm-4">123</div>
	</div>
</div>


