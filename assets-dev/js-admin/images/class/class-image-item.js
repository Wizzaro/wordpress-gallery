Wizzaro.namespace('Plugins.Gallery.v1');
Wizzaro.Plugins.Gallery.v1.ImageItem = function( img_obj, config, ajax_data ) {
    
    var _that = this;
    
    var _img_obj = jQuery( img_obj ),
        _config = config,
        _ajax_data = ajax_data;
    
    var _image_id = _img_obj.find( '.' + _config.image_id_elem_class ).val();
    
    //----------------------------------------------------------------------------------------------------
    // Init imgage
    
    //init set as thumbnail button
    _img_obj.find( '.' + _config.thumbnail.bt_class ).on( 'click', _ajax_set_as_thumbnail );
    
    //init delete image button
    _img_obj.find( '.' + _config.delete.bt_class ).on( 'click', _ajax_delete_image );
    
    
    //----------------------------------------------------------------------------------------------------
    // API functions
    
    this.get_img_id = function() {
        var id = _img_obj;
        return id;
    };
    
    //----------------------------------------------------------------------------------------------------
    // Loader functions
    
    function _show_loader() {
        _img_obj.addClass( _config.loader_class );
    }
    
    function _hide_loader() {
        _img_obj.removeClass( _config.loader_class );
    }
    
    //----------------------------------------------------------------------------------------------------
    // Thumbnail functions
    
    function _ajax_set_as_thumbnail( event ) {
        event.preventDefault();
        event.stopPropagation();
        
        if ( ! _img_obj.hasClass( _config.thumbnail_class ) ) {
            _show_loader();
    
            jQuery.ajax({
                url: ajaxurl,
                type: 'post',
                data: {
                    action: _ajax_data.thumbnail.action,
                    thumbnail_nonce: _ajax_data.thumbnail.nonce,
                    post_id: _ajax_data.post_id,
                    img_id: _image_id
                },
                dataType: 'json',
                success: function( data ) {
                    if ( jQuery.type( data ) === 'object' ) {
                        if ( data.status == '1' ) {
                            _set_thumbnai_success();
                        } else {
                            _ajax_error( data.message );
                        }
                    } else {
                        _ajax_error( _ajax_data.thumbnail.l10n.error );
                    }
                },
                error: function() {
                    _ajax_error(  _ajax_data.thumbnail.l10n.error );
                }
            });
        }
        
        return false;
    }
    
    function _set_thumbnai_success() {
        _img_obj.addClass( _config.thumbnail_class );
        _hide_loader();
        jQuery( _that ).trigger( 'set_as_thumbnail', _img_obj );
    }
    
    //----------------------------------------------------------------------------------------------------
    // Deleting functions
    
    function _ajax_delete_image( event ) {
        event.preventDefault();
        event.stopPropagation();
        
        if ( window.confirm( _wpMediaViewsL10n.warnDelete ) ) {
             _show_loader();
            
            jQuery.ajax({
                url: ajaxurl,
                type: 'post',
                data: {
                    action: _ajax_data.delete.action,
                    delete_nonce: _ajax_data.delete.nonce,
                    post_id: _ajax_data.post_id,
                    img_id: _image_id
                },
                dataType: 'json',
                success: function( data ) {
                    if ( jQuery.type( data ) === 'object' ) {
                        if ( data.status == '1' ) {
                            _delete_success();
                        } else {
                            _ajax_error( data.message );
                        }
                    } else {
                        _ajax_error( _ajax_data.delete.l10n.error );
                    }
                },
                error: function() {
                    _ajax_error(  _ajax_data.delete.l10n.error );
                }
            });
        }
        
        return false;
    }
    
    function _delete_success() {
        _img_obj.hide( _config.visible_toogle_time );
        
        setTimeout( function() {
            _img_obj.remove();
            jQuery( _that ).trigger( 'delete', _that );
        }, _config.visible_toogle_time );
    }
    
    //----------------------------------------------------------------------------------------------------
    // Helper functions
    
    function _ajax_error( message ) {
        alert( message );
        _hide_loader();
    }
};