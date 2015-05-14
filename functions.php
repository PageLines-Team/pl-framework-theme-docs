<?php

/**
 * Welcome to PageLines Docs child theme.
 *  
 * Here are some notes: 
 * 
 *   Code
 *   PHP Escaping: uses the PHP Nowdoc syntax to help with formatting
 *   https://php.net/manual/en/language.types.string.php#language.types.string.syntax.nowdoc
 *
 *   Syntax Highlighting
 *   Uses the popular Prism JS library
 *   http://prismjs.com/index.html
 *
 * 
 */



/**
 * Add Syntax highlighting plugin
 * http://prismjs.com/index.html
 */

add_action( 'wp_head', 'pl_add_syntax_highlighting' );
function pl_add_syntax_highlighting(){
?>
  <link rel="stylesheet" href="<?php echo PL_CHILD_URL;?>/_plugins/prism/prism.css">
  <script src="<?php echo PL_CHILD_URL;?>/_plugins/prism/prism.js"></script>
<?php 



}




function pl_create_code( $code, $lang = 'php' ){

  $replace_codes = str_replace('[n]', "\n", $code );

  $encoded = htmlentities( $replace_codes );

  $output = sprintf('<div class="code-block" data-bind="stopBinding: true"><pre><code class="language-%s">%s</code></pre></div>', $lang, $encoded); 

  return $output;

}
