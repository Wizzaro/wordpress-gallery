var topWin = window.dialogArguments || opener || parent || top;

Wizzaro.namespace('Plugins.Gallery.v1');
Wizzaro.Plugins.Gallery.v1.Uploader = function( config ) {
    var _that = this;
    
    var _uploader = new plupload.Uploader( config.uploader_config );
    
    var _config = config.config,
        _media_item_view = new config.media_item_view_class( _config );
        
    //----------------------------------------------------------------------------------------------------
    // Init layout actions
    
    //open hide uploader
    jQuery( '#' + _config.layout.open_uploader_bt_id ).on( 'click', _toogle_uploader_visible );
    jQuery( '#' + _config.uploader.plupload_container_id + ' ' +  _config.uploader.switch_uploaders_bt_elem ).on( 'click', _shitch_uploaders );
    jQuery( '#' + _config.uploader.html_container_id + ' ' +  _config.uploader.switch_uploaders_bt_elem ).on( 'click', _shitch_uploaders );
    
    //----------------------------------------------------------------------------------------------------
    // Init plupload uploader
    
    _uploader.bind( 'Init', function( up ) {
        var uploaddiv = jQuery( '#' + _config.uploader.plupload_container_id );

        if ( up.features.dragdrop && ! jQuery( document.body ).hasClass( 'mobile' ) ) {
            uploaddiv.addClass( 'drag-drop' );
            
            jQuery( '#' + _config.drag_drop_area.area_id ).bind( 'dragover.wp-uploader', function() { // dragenter doesn't fire right :(
                uploaddiv.addClass( 'drag-over' );
            } ).bind( 'dragleave.wp-uploader, drop.wp-uploader', function() {
                uploaddiv.removeClass( 'drag-over' );
            });
        } else {
            uploaddiv.removeClass( 'drag-drop' );
            jQuery( '#' + _config.drag_drop_area.area_id ).unbind( '.wp-uploader' );
        }
    } );

    _uploader.init();

    _uploader.bind('FilesAdded', _event_files_added );

    _uploader.bind('UploadFile', _event_upload_file );

    _uploader.bind('UploadProgress', _event_upload_progress );

    _uploader.bind('Error', _event_error );

    _uploader.bind('FileUploaded', _event_file_uploaded );

    _uploader.bind('UploadComplete', _event_upload_complete );
    
    //----------------------------------------------------------------------------------------------------
    // Functions for layout
    
    function _toogle_uploader_visible( event ) {
        event.preventDefault();
        event.stopPropagation();
        
        jQuery( this ).toggleClass( 'open' );
        var uploader_container = jQuery( '#' + _config.layout.container_id );
        
        if ( uploader_container.is( ':visible' ) ) {
            uploader_container.slideUp( _config.layout.uploader_toogle_time );
        } else {
            uploader_container.slideDown( _config.layout.uploader_toogle_time );
        }
        
        return false;
    }
    
    function _shitch_uploaders( event ) {
        event.preventDefault();
        event.stopPropagation();
        
        var container = jQuery( '#' + _config.layout.container_id ).toggleClass( 'html-uploader' );

        if ( jQuery.type( _uploader ) == 'object' ) {
            _uploader.refresh();
        }
    }
    
    //----------------------------------------------------------------------------------------------------
    // Functions for events
    
    // For "FilesAdded" event
    function _event_files_added( up, files ) {
        jQuery( '#' + _config.errors.container_id ).empty();
        
        try {
            if ( typeof topWin.tb_remove != 'undefined' )
                topWin.jQuery( '#TB_overlay' ).unbind( 'click', topWin.tb_remove );
        } catch(e){}

        plupload.each( files, function( file_obj ) {
            _file_queued( file_obj );
        });

        up.refresh();
        up.start();
    }
    
    // For "UploadFile" event
    function _event_upload_file( up, file_obj ) {
        var hundredmb = 100 * 1024 * 1024,
        max = parseInt( up.settings.max_file_size, 10 );

        if ( max > hundredmb && file_obj.size > hundredmb ) {
            setTimeout( function() {
                if ( file_obj.status < 3 && file_obj.loaded === 0 ) { // not uploading
                    _media_item_view.set_error( file_obj, pluploadL10n.big_upload_failed.replace( '%1$s', '' ).replace( '%2$s', '' ) );
                    up.stop(); // stops the whole queue
                    up.removeFile( file_obj );
                    up.start(); // restart the queue
                }
            }, 10000 ); // wait for 10 sec. for the file to start uploading
        }
    }
    
    // For "UploadProgress" event
    function _event_upload_progress( up, file_obj ) {
        _media_item_view.set_upload_percent( file_obj.id, ( (200 * file_obj.loaded) / file_obj.size ), file_obj.percent );
    }
    
    // For "Error" event
    function _event_error( up, err ) {
        var file_obj = err.file;
        var hundredmb = 100 * 1024 * 1024, max;

        switch ( err.code ) {
            case plupload.FAILED:
                _media_item_view.set_error( file_obj, pluploadL10n.upload_failed );
                break;
            case plupload.FILE_EXTENSION_ERROR:
                _media_item_view.set_error( file_obj, pluploadL10n.invalid_filetype );
                break;
            case plupload.FILE_SIZE_ERROR:
                _media_item_view.set_error( file_obj, pluploadL10n.file_exceeds_size_limit.replace('%s', file_obj.name) );
                up.removeFile( file_obj );
                break;
            case plupload.IMAGE_FORMAT_ERROR:
                _media_item_view.set_error( file_obj, pluploadL10n.not_an_image );
                break;
            case plupload.IMAGE_MEMORY_ERROR:
                _media_item_view.set_error( file_obj, pluploadL10n.image_memory_exceeded );
                break;
            case plupload.IMAGE_DIMENSIONS_ERROR:
                _media_item_view.set_error( file_obj, pluploadL10n.image_dimensions_exceeded );
                break;
            case plupload.GENERIC_ERROR:
                _set_error( pluploadL10n.upload_failed );
                break;
            case plupload.IO_ERROR:
                max = parseInt( up.settings.filters.max_file_size, 10 );
    
                if ( max > hundredmb && file_obj.size > hundredmb ) {
                    _media_item_view.set_error( file_obj, pluploadL10n.big_upload_failed.replace('%1$s', '').replace('%2$s', '') );
                } else {
                    _set_error(pluploadL10n.io_error);
                }
                
                break;
            case plupload.HTTP_ERROR:
                _set_error( pluploadL10n.http_error );
                break;
            case plupload.INIT_ERROR:
                jQuery( '#' + _config.layout.container_id ).addClass( 'html-uploader' );
                break;
            case plupload.SECURITY_ERROR:
                _set_error( pluploadL10n.security_error );
                break;
    /*      case plupload.UPLOAD_ERROR.UPLOAD_STOPPED:
            case plupload.UPLOAD_ERROR.FILE_CANCELLED:
                jQuery('#media-item-' + fileObj.id).remove();
                break;*/
            default:
                _media_item_view.set_error( file_obj, pluploadL10n.default_error );
        }
        
        up.refresh();
    }
    
    // For "FileUploaded" event
    function _event_file_uploaded( up, file_obj, response ) {
        var result;

        //if ( jQuery.type( JSON.parse ) === "function" ) {
            result = JSON.parse(response.response);
        /*} else {
            //for old browser
            try {
                result = eval( response.response );
            } catch(err) {
                result = eval('(' + response.response + ')');
            }
        }*/

        if ( result.status ) {
            try {
                if ( typeof topWin.tb_remove != 'undefined' ) {
                    topWin.jQuery('#TB_overlay').click(topWin.tb_remove);
                }
            } catch(e){}
            
            _media_item_view.success_upload( file_obj, result.data );
            
            jQuery( _that ).trigger( 'success_file_upload', result.data );
        } else {
            _media_item_view.set_error( file_obj, result.data.error );
        }
    }
    
    // For "UploadComplete" event
    function _event_upload_complete() {
        
    }
    
    //----------------------------------------------------------------------------------------------------
    // Functions for errors
    
    function _set_error( message ) {
        jQuery('#' + _config.errors.container_id ).show().html( '<div class="error"><p>' + message + '</p></div>' );
    }
    
    //----------------------------------------------------------------------------------------------------
    // Functions for help
    
    function _file_queued( file_obj ) {
        var items = jQuery( '#' + _config.media_items.container_id ).children();

        // Collapse a single item
        if ( items.length == 1 ) {
            items.removeClass( 'open' ).find( '.slidetoggle' ).slideUp( 200 );
        }
        
        _media_item_view.add_media_item( file_obj );
    }
};