<?php
/*
 * Template Name: PL Options and Bindings
 * Description: Overview of the PageLines binding APIs
 */


$default_banner = array(

		'header' 								=> 'Options &amp; Bindings', 
		'subheader' 						=> 'A simple interface creating easily customized pages.',
		'theme'									=> 'pl-scheme-dark'

	);




echo pl_get_section( array('section' => 'elements', 'settings' => $default_banner ) );?>

<div class="pl-content">

	<h2>About Options and Bindings</h2>
	<p>PageLines has a simple HTML interface that allows you to create easily editable templates and sections in your theme.</p>

	<pre>
	<code class="language-css">
	p { color: red }</code></pre>


</div>