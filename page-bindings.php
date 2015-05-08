<?php
/*
 * Template Name: PageLines Bindings
 * Description: Overview of the PageLines binding APIs
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

 ?>

<div  class="pl-content">
	<div class="doc-item">

		<h1>Bindings</h1>

			<h2>Introduction</h2>
			<p>Bindings are an incredibly easy and powerful way of creating real time editing features for your website. They are completely driven by HTML and tied automatically to options you can create a variety of ways. </p>

		<h2>The Basics</h2>
			<p>PageLines bindings all operate on the <code>data-bind</code> attribute which can be attached to any HTML element within the framework.</p>
			<p>The <code>data-bind</code> attribute can have one or more arguments that tie the content of the elmement to an option.</p>
			<p>As an example, if you create a text option with a key of "my_option_key", a binding to tie this to the inner HTML of an element woiuld look like this:</p>
			<pre>
				<code>
					<div data-bind="pltext: my_option_key">&gt;?php echo $this->opt('my_option_key');?&lt;</div>
				</code>
			</pre>

		<h2>Helpers and Fallbacks</h2>

			<h3>PHP Option Fallbacks</h3>
				<p>PageLines' bindings are driven by javascript (JS). This means they are rendered by the user's browser for display. </p>
				<p>While we certainly could have gotten away with only JS and HTML, above you'll notice that we provided a PHP option within the div as well. There are two reasons for this: 
					<ol>
						<li><strong>Shortcodes:</strong> All text may take some preprocessing. For example, shortcodes will need to be processed by WordPress. As opposed to "lazy-load" this content via AJAX, we just output it at first from PHP.</li>
						<li><strong>SEO</strong> Although in 2014 Google announced they would be compiling JS for their results, it still may be a concern for people dedicated to amazing SEO. Providing a "static" output on page load ensures that all spiders are seeing the content.</li>
					</ol>
				</p>

			<h3>Loading Helper CSS Classes</h3>
			<p>To provide the designer some control as to how PageLines bindings behave on page load. There are two helper classes that allow you to control, specifically, how the binding will behave.</p>
			<ul>
				<li><strong>pl-load-lazy</strong> This class, if added to the element, will render the binding on initial page load. Use this if you would like the binding to render on page load (no PHP fallback) and if you're element potentially includes user defined shortcodes or other information requiring the server's help. The framework will "lazy-load" the content automagically.</li>
				<li><strong>pl-load-html</strong> This class, if added to the element, will render the binding on initial page load but will not send an ajax request looking for shortcodes, etc.. this will load faster.</li>
				<li><strong>pl-load-wait</strong> This is the default for most bindings. If added to your element, the wait class will wait until an option is changed in order to render. Use this when you have provided a PHP fallback for the option.</li>
			</ul>

			<h3>Trigger Helper CSS Classes</h3>
			<p>Many of the bindings in PageLines are known as "configuration" bindings, which mean they control the behavior or appearance of a section.</p>

			<p>As such, when a user changes an option tied to your binding, many times you would like to trigger an "event" that Javascript can use to then rerender or change the element itself or one of its containing elements.</p>

			<p>So as an elegant way of dealing with this problem, PageLines uses the trigger helper classes.</p>

			<ul>
				<li><strong>pl-trigger</strong> </li>
				<li><strong>pl-trigger-container</strong> </li>
				
			</ul>

		<h2>Types of Bindings</h2>
	
		<h4>Knockout JS Binding Library</h4>
		<p>The PageLines binding system is based on the popular Knockout JS library and supports all of it's standard bindings. You can reference and use all the bindings documented on their website.</p>
		<p>Before you do, however, it is important to note that Knockout bindings don't take into account SEO and related JS events while PageLines bindings (documented below) were created and optimized for website and presentation.</p>

		<h4>CSS Class Binding: plclass</h4>

		<h4>Background Image Binding: plbg</h4>

		<h4>Image Source Binding: plimg</h4>

		<h4>Text &amp; HTML Binding: pltext</h4>

		<h4>AJAX Callback Binding: plcallback</h4>

		<h4>Foreach Loop Binding: plforeach</h4>

		<h4>Template Loop Binding: pltemplate</h4>






	</div>
</div>

