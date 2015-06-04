// PageLines Tools Initializer

!function ($) {
   

  $.plTemplates = {

    /**
     * Gets the list of sections available of a certain type.
     * @param  {string} type    type of area, full width area or content
     * @param  {object} section the section where the 'add' as clicked
     * @return {string} html output for placement in panel
     */
    init: function( clicked ){

      var that  = this
        

      var config = {
            name:     'Saved Layouts', 
            panels:   that.thePanels(), 
            key:      'templates',
            call: function(){
              that.bindListActions( clicked )
            }
          }

      $.plEditing.sidebarEngine( config )


    }, 

    bindListActions: function( clicked ){

      var that = this


      /**
       * Create new template and add to list.
       */
      $(".pl-create-tpl").on("click", function(e) {

        e.preventDefault()

        var btn         = $(this),
            form        = btn.closest('.create-tpl-form'),
            nameInput   = form.find('.tpl-name'),
            descInput   = form.find('.tpl-desc'), 
            name        = nameInput.val(), 
            desc        = descInput.val()

          if( name != '' ){

            nameInput.removeClass('invalid')

            that.doTplAction({
              run:  'create', 
              name:   name, 
              desc:   desc,
              beforeSend: function( ){

                btn.html("Saving...")

                $("body").css("cursor", "progress");
              },
              postSuccess: function( rsp, config ){
              
                var cloned = $('.pl-tools-list').children().first().clone()


                cloned.find( '.tpl-name').html( name )
                cloned.find( '.desc').html( desc )
                cloned.attr( 'data-key', rsp.key)

                $.plFrame.reloadFrame( { tplSet: rsp.key } )
                $.plEditing.setNeedsSave()

                /** Add to page info for redraws... */
                PLWorkarea.templates[rsp.key] = {
                  name: name, 
                  desc: desc
                }

                cloned
                  .prependTo('.pl-tools-list')
                  .hide()
                  .slideDown()


                
                $("body").css("cursor", "default");
                
                btn.html("Create Layout")



                $.plEditing.showNotification( sprintf('Created "%s" Layout!', name ) )

              }
            })

          } else {

            nameInput.addClass('invalid').focus()

          }



      })

      

      $('.add-new-item').on( 'click', function(){

        var theItem = $(this)

        if( theItem.hasClass('selected') ){

          theItem.removeClass('selected')
        
        } 

        else {
          theItem.addClass('selected')

        }

      })

      $('.pl-tools-list').on( 'click', '.tpl-action', function(){

        var theItem   = $(this),
          theAction   = theItem.data('action')

        plConfirm( theItem, that.getTplAction( theItem, theAction ) )

      })

      $('.context-buttons').on( 'click', '.btn-context:not(.active):not(.btn-none)', function(e){

        e.preventDefault()

        var theItem   = $(this),
          theAction   = theItem.data('action'), 
          container   = theItem.closest('.context-buttons')

        plConfirm( theItem, that.getHandlingAction( theItem, theAction ) )

      })

      that.setPageInfo()
      that.setLinkedState()
      that.setModeState()


    }, 

    getHandlingAction: function( clicked, action ){

      var that  = this, 
        actions = {

          scope: {
            header: 'Change Scope', 
            subhead: sprintf( 'Change page editing to %s scope', clicked.data('scope') ),
            callback: function(){

              var theItem   = $(this),
                scope     = theItem.data('scope')

              that.setModeState( scope )

              /** If moving from local scope to type, then we need to page refresh  */
              if( scope == 'type' )
                $.plFrame.reloadFrame( { tplScope: scope } )

              $.plEditing.setNeedsSave()

            }
          }, 
          unlink: {
            header: 'Unlink from Layout', 
            subhead: 'Disconnect this template from the active layout.',
            callback: function(){

              that.setLinkedState('unlinked')

              $i('.custom-template').removeClass('custom-template')

              $.plEditing.setNeedsSave()

              $.plEditing.showNotification( 'Unlinked Layout' )

              that.setTplActive('')

            }
          }, 


        }

      return actions[ action ]

    }, 

    setPageInfo: function(){

      var that = this

      $('.context-page-type').html( $pl().config.pageTypeName )
      $('.context-page-id').html( $pl().config.pageID )
      $('.context-active-tpl').html( that.getLayoutName() )

      $('.context-page-slug').html( $pl().config.currentPageSlug )


    },

    setModeState: function( state ){

      var that      = this,
        defaultState  = $pl().config.tplMode,
        state       = state || defaultState


      if( $pl().config.pageID == $pl().config.typeID ){

        $('[data-action="scope"]').hide()
        $('.context-page-type-only').show()

      } else{

        $('[data-action="scope"]')
          .removeClass('active')


        $( sprintf('[data-scope="%s"]', state) )
          .addClass('active')



        that.setTplMode( state )

      }
    }, 



    setLinkedState: function( state ){

      var defaultState  = ( $pl().config.tplActive != '' ) ? 'linked' : 'unlinked',
        state       = state || defaultState

      $('[data-action="unlink"]')
        .removeClass('active context-linked context-unlinked')

      if( state == 'linked' ){

      
        $('[data-action="unlink"]')
          .addClass('context-linked')

      } 

      else {

        $('[data-action="unlink"]')
          .addClass('context-unlinked active')

        $pl().config.tplActive = ''

      }



    },

    doTplAction: function( config ){

      var defaults = {
        saveCheck:  true, 
        run:    'save',
        name:     '',
        desc:     '',
        key:    ''
      }

      config = $.extend( defaults, config )

      /** User needs to save changes first. */
      if( config.saveCheck && $.plEditing.needsSave() ){

        plAlert({
          header: 'Please Save Changes', 
          subhead: 'Before saving or updating a layout, save latest changes.'
        })
      } 

      /** All changes already saved, we can create the template */
      else {

        $.plEditing.savePage({
          hook:   'tpl_action',
          run:  config.run,
          name:   config.name, 
          desc:   config.desc,
          key:  config.key,
          postSuccess: function( rsp ){
            
            if ( $.isFunction( config.postSuccess ) )
                    config.postSuccess.call( this, rsp, config )

          },
          beforeSend: function( ){

            if ( $.isFunction( config.beforeSend ) )
                    config.beforeSend.call( this, config )

          }
        })

      }

    }, 

    getTplAction: function( item, action ){

      var that      = this, 
        config      = {}, 
        tpl       = item.closest('.tools-list-item'),
        key       = tpl.data('key'), 
        name      = tpl.find('.tpl-name').html(), 
        desc      = tpl.find('.desc').html()


      config.header     = sprintf( '%s: "%s"', item.html(), name )
      config.subhead    = 'Are you sure?'
 

      var actions = {

        apply: {
          details: '<p>This will apply the selected layout to the current page or type depending on the active scope.</p> <p>Once the layout is applied and saved, the page configuration will be overwritten.</p>',
          callback: function(){

            var theItem   = $(this).closest('.tools-list-item'),
              key     = theItem.data('key'),
              name    = theItem.find('.tpl-name').text()


            that.setLinkedState('linked')

            $.plFrame.reloadFrame( { tplSet: key } )

            $.plEditing.showNotification( sprintf('Applied "%s" Layout!', name ) )

            $.plEditing.setNeedsSave()

          }
        }, 

        update: {
          details: '<p>This action will update the selected layout with the layout and settings from the current page.</p> <p>All pages using this layout will be affected.</p>',
          callback: function(){

            var theItem   = $(this).closest('.tools-list-item'),
              key     = theItem.data('key'),
              name    = theItem.find('.tpl-name').text()

            that.doTplAction({
              run:  'update', 
              key:  key, 
              postSuccess: function( rsp, config ){


              
                $.plEditing.showNotification( sprintf('Updated "%s" Layout!', name ) )
              }
            })

            

          }
        }, 

        info: {
          details:  '<p>This will update the selected layout name and description with the current values (which may be edited inline).</p>',
          dontConfirm: true, 
          callback: function(){

            var theItem   = $(this).closest('.tools-list-item'),
              key     = theItem.data('key'),
              name    = theItem.find('.tpl-name').text()
              desc    = theItem.find('.desc').text()

            that.doTplAction({
              run:    'update_info', 
              key:    key, 
              name:     name, 
              desc:     desc,
              saveCheck:  false,
              postSuccess: function( rsp, config ){
              
                /** Add to page info for redraws... */
                PLWorkarea.templates[rsp.key] = {
                  name: name, 
                  desc: desc
                }
                
                $.plEditing.showNotification( sprintf('Updated "%s" Info', name ) )
              }
            })
          }
        }, 

        dlt: {
          details: '<p>This will permanently delete this layout.</p> <p>All pages with this template applied will revert to their default configuration.</p>',
          callback: function(){

            var theItem   = $(this).closest('.tools-list-item'),
              key     = theItem.data('key'),
              name    = theItem.find('.tpl-name').text()

            that.doTplAction({
              run:    'delete', 
              key:    key, 
              saveCheck:  false,
              postSuccess: function( rsp, config ){
                
                theItem.slideUp( 300, function(){
                  $(this).remove()
                } )

                delete PLWorkarea.templates[ key ];

                $.plEditing.showNotification( sprintf('Deleted "%s"', name ) )
              }
            })

          }
        }

      }

      config = $.extend( config, actions[ action ] )
      
      return config

    }, 

    

    opt_type_tpl_handling: function(){

    

      var scopeOptions = {} 

      scopeOptions.type = sprintf('All of Type: "%s"', $pl().config.typename)

      if( $pl().config.pageID != $pl().config.typeID )
        scopeOptions.local = sprintf('Current Page Only: "%s"', $pl().config.currentPageName)

      if( $pl().config.termID != $pl().config.pageID )
        scopeOptions.term = sprintf( 'Taxonomy Archive: "%s"', $pl().config.currentTaxonomy )



      var config = {
          title:    'Scope', 
          message:  'Use this to control the scope of your changes on this page. Either this specific page, its general type, or category', 
          option:   $.engineOpts.selectOption( scopeOptions, $pl().config.tplMode, 'tpl_scope'), 
          valLabel:   'Scope:',
          val:    $pl().config.tplMode
        }

      scope = $.engineOpts.specialOption( config )

      // var config = {
      //    title:    'Template Connection', 
      //    message:  'If you are using a template on this page, it\'s configuration will stay "linked" to it until you unlink it here.', 
      //    option:   '<a href="#" class="btn btn-primary btn-sm" data-action="unlink"><i class="icon icon-unlock"></i> Unlink</a>'
      //  }

      // lock = $.engineOpts.specialOption( config )


      var out = sprintf( '<div class="create-tpl-form fix">%s</div>', scope)

      return out
    }, 

    // opt_type_tpl_info: function(){

    //   var contexts = ''

    //   var contextInfo = {
    //     name:     $pl().config.currentPageName, 
    //     typeID:   $pl().config.typeID,
    //     pageID:   $pl().config.pageID,
    //     termID:   $pl().config.termID,
    //     slug:     $pl().config.currentPageSlug,
    //     scope:    $pl().config.tplMode,
    //     static:   ( $pl().config.tplRender == 0) ? 'Yes' : 'No',
    //     capture:  ( $pl().config.tplCapture == 1) ? 'Yes' : 'No',
    //   }


    //   $.each( contextInfo, function( name, value){
        
    //     contexts += sprintf('<div class="context-item" ><div class="context-head">%s:</div><div class="context-value">%s</div></div>', name, value)

    //   })

    //   var config = {
    //       title:    'Page Info', 
    //       message:  'This is the information that the system uses to handle and control edits to this page and tie it to other pages in the system.', 
    //       option:   sprintf('<div class="context-list">%s</div>', contexts)
    //     }


    //   var info = $.engineOpts.specialOption( config )

    //   var out = sprintf( '<div class="create-tpl-form fix">%s</div>', info )

    //   return out
    // }, 

    opt_type_tpl_create: function(){

      var out = '<div class="create-tpl-form"><div class="form-group"><label for="tpl-name">Layout Name</label><input id="tpl-name" class="form-control tpl-name" type="text" placeholder="Name" /></div><div class="form-group"><label for="tpl-desc">Layout Description</label><input id="tpl-desc" class="form-control tpl-desc" type="text" placeholder="Description" /></div><div class="form-group"><button class="form-save btn btn-sm btn-primary pl-create-tpl">Create Layout</button></div></div>'

      return out
    }, 

    opt_type_tpl_list: function(){

      var that  = this, 
        out   = ''

      $.each( PLWorkarea.templates, function( tag, info ){

        var desc    = ( ! _.isEmpty( info.desc ) ) ? info.desc : '', 
          actions   = '', 
          bar     = ''

        bar   += sprintf('<div class="tools-bar"><i class="icon icon-file-o"></i> <div class="tpl-name" contentEditable="true">%s</div><div class="actions"><i class="icon icon-angle-down"></i></div></div>', info.name)

        actions += sprintf( '<li class="tpl-action" data-action="apply"><i class="icon icon-download"></i> Apply to Page...</li>' )
        actions += sprintf( '<li class="tpl-action" data-action="update"><i class="icon icon-upload"></i> Update Layout...</li>' )
        actions += sprintf( '<li class="tpl-action" data-action="info"><i class="icon icon-info"></i> Update Name &amp; Description</li>' )
        actions += sprintf( '<li class="tpl-action" data-action="dlt"><i class="icon icon-remove"></i> Delete...</li>' )

        out += sprintf( '<li class="tools-list-item item-closed" data-key="%s">%s<div class="tools-panel"><div class="tools-description tpl-desc" ><strong>Layout Description:</strong> <span class="desc" contentEditable="true">%s&nbsp;</span></div><ul class="list-unstyled">%s</ul></div></li>', tag, bar, desc, actions)

         
      
      })


      return sprintf('<ul class="pl-tools-list list-unstyled list-media">%s</ul>', out)

    }, 


    thePanels: function(){

      var that  = this,
        panels = {

          
          listed:  {
            title:    'Saved Layouts', 
            format:   'full', 
            opts:   [
              {
                type:     'tpl_list',
                callback:   that
              }
            ]
          },

          create:  {
            title:    'Save Page as New Layout', 
            opts:   [
              {
                type:     'tpl_create',
                callback:   that, 
              }
            ]
          },

        }

      return panels

    },

    

    setTplActive: function( tpl ){

      var that = this

      $pl().config.tplActive = tpl

      that.setTplDetails()

    }, 

    setTplMode: function( mode ){

      var that = this

      $pl().config.tplMode = mode

      that.setTplDetails()
      
    }, 

    getLayoutName: function( tpl ){

      var tpl = tpl || $pl().config.tplActive

      return ( plIsset(PLWorkarea.templates[ tpl ]) ) ? PLWorkarea.templates[ tpl ].name : tpl;

    },

    setTplDetails: function( ){

      var that  = this,
        el    = $('.tpl-details'), 
        tpl   = $pl().config.tplActive, 
        mode  = $pl().config.tplMode


      if( that.getLayoutName() != ''){
        el.find('.active-template')
          .html( that.getLayoutName() )
          .fadeIn()
      }
      



    }

  }


}(window.jQuery);