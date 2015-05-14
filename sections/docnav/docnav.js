!function ($) {

  
  $( '.section-docnav' ).on('template_ready', function(){

    $.plDocNav.create( $(this) )

  })

  $.plDocNav = {

    create: function( section ){

      var that    = this

      console.log('go')

      section.find('.docnav').addClass('gogogogo').scrollupbar();
      
    }
  }
  

}(window.jQuery);