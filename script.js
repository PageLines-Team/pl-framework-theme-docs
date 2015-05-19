!function ($) {

  
  $( document ).on('ready', function(){

    $.plDocs.init( )

  })

  $.plDocs = {

    init: function( ){

      var that    = this

      that.doDocNav()
      
    }, 

    doDocNav: function(){

      var that        = this, 
          list        = '',
          counter     = 1

      $('.docnav-scan').find('h1, h2, h3, h4').each( function( item ){

        var item  = $(this), 
            tag   = item[0].tagName, 
            id    = 'section-' + counter, 
            level = tag.substr( 1 );


        /** -- Add ID from counter -- */
        item.attr( 'id', id )

        list += sprintf('<li class="level%s"><a href="#%s">%s</a></li>', level, id, item.text() )

        counter++;
      })


      /** Get the list */
      $('.doclist-nav').html( list )

      /** Stick it  */
      $('.doclist-nav').stick_in_parent()

      /** Better anchor scrolling */
      $('.doclist-nav a').on( 'click', function(e){
        e.preventDefault();
        
         var id     = $(this).attr("href");
         var offset = $(id).offset();
      
         $("html, body").animate({
           scrollTop: offset.top - 40
         }, 100);
      })

    }, 

  

  }
  

}(window.jQuery);