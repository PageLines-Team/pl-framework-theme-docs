// PageLines Tools Initializer

!function ($) {
   



  $.plPageBrowser = {

    /**
     * Gets the list of sections available of a certain type.
     * @param  {string} type    type of area, full width area or content
     * @param  {object} section the section where the 'add' as clicked
     * @return {string} html output for placement in panel
     */
    init: function( btn ){

      var that  = this,
        config = {
          name:     'Page Browser', 
          panels:   that.thePanels(), 
          key:      'pagebrowser',
          call: function(){
            that.bindListActions()
          }
        }


      $.plEditing.sidebarEngine( config )

      

    }, 

    bindListActions: function( btn ){

      var that    = this

      that.model()

      $('.selector-content').on('click', function(){
        that.togglePageSelector()
      })
    

      /** Search Page Types Form */
      $('.page-search-submit').on('click', function(e) {
        $(".page-search-container form").trigger('submit');
      });

      $('.page-search-input').on('keyup', function (e) {
        
        if ( $(this).val().length === 0 ) {

          $(".page-search-container form").trigger('submit');

        }

      });

      $(".page-search-container form").on('submit', function(e) {

        var query = $('.page-search-input').val();

        if ( query.length === 0 ) {
          PLWorkarea.models.pageSelector.search([]);
          e.preventDefault();
          return false;
        }
        
        that.searchLayouts(query);

        e.preventDefault();

      });

      /*  Collapsing Stuff */
            $('.the-page-selector').delegate('span.the-page', 'click', function( e ) {

        var layoutData    = ko.dataFor(this),
          layoutContext   = ko.contextFor(this);

        /** No children */
        if ( ! $(this).parent().hasClass('has-children') ) {
          return;
        }

        /** If children, lets toggle */
        $(this).toggleClass('layout-open');

        /** Grab pages via ajax request */
        if ( $(this).parent().hasClass('has-ajax-children') && !layoutContext.$data.ajaxLoaded() ) {

          that.loadLayouts(layoutData, layoutContext);
          
        }


      });

            /** Load More Pages */
            $('.the-page-selector').delegate('span.load-more-layouts', 'click', function (event) {

        var clicked = this;

        var layoutData    = ko.dataFor(this);
        var layoutContext   = ko.contextFor(this);

        $(clicked)
          .text('Load More...')
          .attr('disabled', 'disabled');



        $.when( that.loadLayouts(layoutData, layoutContext, true) ).done(function() {
          $(clicked)
            .text('Load More...')
            .attr('disabled', '');
        });

      });

            $('.the-page-selector').delegate('.switch-to-page', 'click', function(e){

              var thePage = $(this).parents('.the-page'),
                pageURL = thePage.attr('data-page-url');

              if( pageURL != '' ){

                e.preventDefault();
        
          //Switch layouts
          that.switchToPage( thePage );

          /* Hide layout selector */
          that.hidePageSelector();

                  return thePage

              }
              

      });

    }, 

    model: function(){
      
      var that = this

      console.log(PLWorkarea.layouts)


      PLWorkarea.models.pageSelector = {
          search:           ko.observableArray([]),
          pages:            that.makeObservableArray( PLWorkarea.layouts.pages ),
          searchQuery:      ko.observable(),
          currentPageName:  ko.observable( PLWorkarea.currentPageName ),
      }

      ko.applyBindings( PLWorkarea.models.pageSelector, $('.the-page-selector')[0] );


    },

    makeObservableArray: function( array ) {

      var that = this,
        normalizedData = [];

      $.each( array, function (index, data) {
        normalizedData.push( that.layoutModel(data) );
      });


      return ko.observableArray(normalizedData);

    },

    layoutModel: function ( page ) {

      var that = this,
        model = {}

      model.id        = page.id;
      model.name        = page.name;
      model.url         = page.url;
      model.children      = that.makeObservableArray( page.children )

      model.ajaxChildren    = ko.observable(  page.ajaxChildren );

      model.ajaxLoaded    = ko.observable( false );
      model.ajaxShowMore    = ko.observable( false );
      model.ajaxLoadOffset  = ko.observable( 0 );
      

      return model;

    },

    switchToPage: function( thePageSelect, reloadIframe, showSwitchNotification) {

      var that = this 
      if ( typeof  thePageSelect == 'object' && ! thePageSelect.hasClass('layout') )
         thePageSelect =  thePageSelect.find('> span.layout');
        
      if (  thePageSelect.length !== 1 )
        return false;
          
      $('title').text('PageLines Editor Loading');

      //startTitleActivityIndicator();
    
      var layout =  thePageSelect;
      var layoutID = layout.attr('data-page-id');
      var layoutURL = layout.attr('data-page-url');
      var pageName = layout.find('strong').text();

      //Set global variable to tell designEditor.switchLayout that this layout was switched to and not initial load
      PLWorkarea.switchedToLayout = true;

      PLWorkarea.models.pageSelector.currentPageName(pageName);

      /* Push New URL to browser */
      browserURL = layoutURL
      browserURL = updateQueryStringParameter(browserURL, 'pl_action', 'edit_page' );
      browserURL = updateQueryStringParameter(browserURL, 'page_info', layoutID );
      
      window.history.pushState( "", "", browserURL );

      
      //Reload iframe and new layout right away
      if ( typeof reloadIframe == 'undefined' || reloadIframe == true ) {
        
        if ( typeof showSwitchNotification == 'undefined' || showSwitchNotification == true )
          PageLinesIframeLoadNotification = 'Switched to <em>' + pageName + '</em>';
        
        $.plFrame.loadNew( layoutURL);
        
      }
            
      return true;
      
    },

    loadLayouts: function(layoutData, layoutContext, loadingMore) {

      var that = this,
        loadingMore = loadingMore || false;


      var args = {
        hook:     'get_page_children',    
        layout:   layoutData.id,
        offset:   layoutData.ajaxLoadOffset,
        postSuccess: function( rsp ){
          
          console.log(rsp)
          

          var data      = rsp.formatted_layouts, 
              numItems  = Object.keys(data).length

          if ( (!_.isObject(data) || _.isEmpty(data) ) && !loadingMore ) {
            layoutContext.$data.ajaxChildren(false);
            layoutContext.$data.children([]);
            return $(that).removeClass('layout-open');
          }

          if ( !_.isObject(layoutContext.$data.children()) ) {
        
            layoutContext.$data.children( ko.utils.unwrapObservable(that.makeObservableArray(data)) );
          } else {

            

            $.each(ko.utils.unwrapObservable( that.makeObservableArray(data)), function(index, data) {
              layoutContext.$data.children.push(data);
            });

          }

          layoutContext.$data.ajaxLoaded(true);
          layoutContext.$data.ajaxLoadOffset( layoutContext.$data.ajaxLoadOffset() + numItems);

          if ( numItems == 30 ) {
            layoutContext.$data.ajaxShowMore(true);
          } else {
            layoutContext.$data.ajaxShowMore(false);
          }
      
        },
      
      }

      return $plServer().run( args )


    },

    searchLayouts: function( query ) {


      var that = this

      var args = {
        hook  : 'search_pages',
        query   : query,
        postSuccess: function( rsp ){
          
          var data = rsp.formatted_pages, 
            numItems = Object.keys(data).length


          if ( !_.isObject(data) || ! numItems ) {
            data = {
              0: {
                name:     "No Results", 
                id:     'no_results',
                children:   {},
                ajaxChildren: false,
                url:    ''
              }
            }
            
          }

          return PLWorkarea.models.pageSelector.search( ko.utils.unwrapObservable( that.makeObservableArray(data)) );

          
        },
      
      }

      return $plServer().run( args )

    },

    

    togglePageSelector: function() {
      var that = this

      if ( $('div#layout-selector-select').hasClass('page-selector-visible') ) {
        that.hidePageSelector(false);
      } else {
        that.showPageSelector();
      }

    },


    showPageSelector:  function() {
      var that = this

      $('div#layout-selector-select')
        .addClass('page-selector-visible');

      /* Move layout selector into correct position below the layout selector select */
      $('div#layout-selector').css({
        left: $('div#layout-selector-select-content').offset().left
      });

      $(document).bind('mousedown', that.hidePageSelector);

      PLWorkarea.iframe.contents().bind('mousedown', that.hidePageSelector);

      return $('div#layout-selector-select');

    },

    hidePageSelector: function(event) {

      var that = this

      if ( event && ($(event.target).is('#layout-selector-select') || $(event.target).parents('#layout-selector-select').length === 1 ))
        return;

      $('div#layout-selector-select')
        .removeClass('page-selector-visible');

      $(document).unbind('mousedown', that.hidePageSelector);
      PLWorkarea.iframe.contents().unbind('mousedown', that.hidePageSelector);
      
      return $('div#layout-selector-select');

    }, 

    



   
    thePanels: function(){

      var that  = this,
        panels = {
          builder:  {
            title:    'Page Browser', 
            opts:   [
              {
                type:     'page_browser',
                callback:   that
              }
            ]
          },

        }

      return panels

    },

    opt_type_page_browser: function(){

      var that  = this
    
      return $('#pl-page-browser-template').html()
      
    }, 

  }


}(window.jQuery);