<?php


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



