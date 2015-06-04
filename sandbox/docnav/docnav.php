<?php
/*

  Section:      DocNav
  Description:  A hard coded documentation navigation section

  Author:       PageLines
  Author URI:   http://www.pagelines.com
  
  Class Name:   DocNav
  Filter:       advanced

*/

class DocNav extends PageLinesSection {

  function section_styles(){


   // pl_enqueue_script( 'pl-scrollup', $this->base_url.'/scrollupbar.js',    array( 'jquery' ), pl_get_cache_key(), true );
    //pl_enqueue_script( 'pl-docnav',   $this->base_url.'/docnav.js',         array( 'jquery' ), pl_get_cache_key(), true );

  }

  function section_template(){

?>
  <div class="docnav pl-nav">
    <a href="<?php echo site_url();?>/">Welcome</a>
    <a href="<?php echo site_url();?>/pagelines-options-bindings">Options &amp; Bindings</a>
    <a href="<?php echo site_url();?>/tutorials">Tutorials</a>

  </div>
<?php

  }

}


