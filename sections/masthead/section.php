<?php
/*
	Section: 		Masthead
	Author: 		PageLines
	Author URI: 	http://www.pagelines.com
	Description: 	A responsive full width splash and text area. Great for getting big ideas across quickly.
	Class Name: 	PLMasthead
	Filter: 		component
*/


class PLMasthead extends PageLinesSection {

	
	function section_opts(  ){

		$options = array(
				array(
					'key'	=> 'pagelines_masthead_splash_multi',
					'type' 	=> 'multi',
					'title' => __('Masthead Splash Options','pagelines'),
					'opts'	=> array(
						array(
							'key'			=> 'pagelines_masthead_img',
							'type' 			=> 'image_upload',
							'imagepreview' 	=> '270',
							'has_alt'		=> true,
							'label' 	=> __( 'Upload custom image', 'pagelines' ),
						),
						array(
							'key'		=> 'pagelines_masthead_html',
							'type' 		=> 'textarea',
							'label' 	=> __( 'Masthead Video (optional, to be used instead of image)', 'pagelines' ),
						),
						array(
							'key'		=> 'masthead_html_width',
							'type' 		=> 'select',
							'label' 	=> __( 'Maximum width of splash', 'pagelines' ),
							'default'	=> '600px', 
							'opts'=> array(
								'50%'		=> array( 'name' => "50%" ),
								'75%'	 	=> array( 'name' => "75%" ),
								'100%'	 	=> array( 'name' => "100%" ),
								'400px'		=> array( 'name' => "400px" ),
								'600px'	 	=> array( 'name' => "600px" ), 
								'960px'		=> array( 'name' => "960px" ),

							),
						),
					),
				),
				array(
					'type' 				=> 'multi',
					'label' 			=> __( 'Masthead Text', 'pagelines' ),
					'title' 			=> __( 'Masthead Text', 'pagelines' ),
					'opts'	=> array(
						array(
							'key'		=> 'pagelines_masthead_title',
							'type'		=> 'text',
							'label'		=> __( 'Title', 'pagelines' ), 
						),
						array(
							'key'		=> 'pagelines_masthead_tagline',
							'type'		=> 'text',
							'label'		=>__( 'Tagline', 'pagelines' ), 
						)
					),

				),
		); 


		$options[] = array(
					'type' 	=> 'multi',
					'title' => __('Masthead Buttons','pagelines'),
					'opts'	=> array(
						array(
							'title'			=> __( 'Primary Button', 'pagelines' ),
							'type'			=> 'multi',
							'stylize'		=> 'button-config',
							'opts'			=> pl_button_link_options('button_primary')
						),
						array(
							'title'			=> __( 'Secondary Button', 'pagelines' ),
							'type'			=> 'multi',
							'stylize'		=> 'button-config',
							'opts'			=> pl_button_link_options('button_secondary')
						),
					),
				);
			
		// for($i = 1; $i <= 2; $i++){

		// 	$options[] = array(
		// 		'key'		=> 'masthead_button_multi_'.$i,
		// 		'type'		=> 'multi',
		// 		'col'		=> 3,
		// 		'title'		=> __('Masthead Action Button '.$i, 'pagelines'),
		// 		'opts'	=> array(
		// 			array(
		// 				'key'		=> 'masthead_button_link_'.$i,
		// 				'type' => 'text',
		// 				'label' => __( 'Enter the link destination (URL - Required)', 'pagelines' ),

		// 			),
		// 			array(
		// 				'key'		=> 'masthead_button_text_'.$i,
		// 				'type' 			=> 'text',
		// 				'label' 	=> __( 'Masthead Button Text', 'pagelines' ),
		// 			 ),

		// 			array(
		// 				'key'		=> 'masthead_button_target_'.$i,
		// 				'type'			=> 'check',
		// 				'default'		=> false,
		// 				'label'	=> __( 'Open link in new window.', 'pagelines' ),
		// 			),
		// 			array(
		// 				'key'		=> 'masthead_button_theme_'.$i,
		// 				'type'			=> 'select_button',
		// 				'default'		=> false,
		// 				'label'		=> __( 'Select Button Color', 'pagelines' ),
					
		// 			),
		// 		)
		// 	);

		// }
			
				
		$options[] = array(
					'key'			=> 'masthead_menu',
					'type' 			=> 'select_menu',
					'title'			=> __( 'Masthead Menu', 'pagelines' ),
					'label' 		=> __( 'Select Masthead Menu', 'pagelines' ),
				); 

		$options[] = array(
					'key'		=> 'masthead_meta',
					'type' 			=> 'text',
					'title'			=> __( 'Masthead Meta', 'pagelines' ),
					'label' 		=> __( 'Enter Masthead Meta Text', 'pagelines' ),
				); 

		

		return $options;
	}
	
	

	/**
	* Section template.
	*/
   function section_template() {


		$mast_title = ( ! $this->opt('pagelines_masthead_title') ) ? 'Hello.' : $this->opt('pagelines_masthead_title');
		$mast_tag 	= ( ! $this->opt('pagelines_masthead_tagline') ) 	? 'Masthead section loaded! Now set the options.' : $this->opt('pagelines_masthead_tagline');

	?>

	<div class="masthead">

		<div class="splash" style="max-width: 500px;" data-bind="visible: pagelines_masthead_img() != '', style: { 'max-width': masthead_html_width }">
			<?php echo $this->image( 'pagelines_masthead_img', false, array( 'masthead-img' ) );?>
		</div>


		<div class="splash video-splash" style="max-width:%s;" data-bind="style: { 'max-width': masthead_html_width }, html: pagelines_masthead_html">
			<?php echo $this->opt('pagelines_masthead_html');?>
		</div>


		<div class="inner">

			<h1 class="masthead-title" data-bind="plsync: pagelines_masthead_title"><?php echo $mast_title; ?></h1>

			<p class="masthead-tag" data-bind="plsync: pagelines_masthead_tagline"><?php echo $mast_tag; ?></p>

		    <p class="download-info">
		    	<?php pl_dynamic_button( 'button_primary', 'btn-lg btn-default' ); ?>
				<?php pl_dynamic_button( 'button_secondary', 'btn-lg btn-default' ); ?>
			</p>
			
		</div>

		<div class="mastlinks">
			<div class="mastnav" data-bind="plsync: masthead_menu, sync_mode: 'menu', args: {menu_class: 'quick-links', depth: 1, no_toggle: true}" >
				<?php echo pl_navigation( array('menu' => $this->opt( 'masthead_menu' ), 'menu_class' => 'quick-links', 'depth' => 1, 'no_toggle' => true ) );?>
			</div>
			
			<div class="quick-links mastmeta" data-bind="plsync: masthead_meta"><?php echo do_shortcode( $this->opt('masthead_meta') );?></div>
		</div>

	</div>
		<?php
	}
}