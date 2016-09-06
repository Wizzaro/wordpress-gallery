var Wizzaro = Wizzaro || {};

Wizzaro.namespace = Wizzaro.namespace || function( ns_name ) {
    var parts = ns_name.split('.');
    var parent = Wizzaro;
    var i, max;
    
    if( parts[0] == 'Wizzaro' ) {
        parts = parts.slice(1);
    }
    
    for( i = 0, max = parts.length; i < max; i++ ) {
        if( jQuery.type( parent[parts[i]] ) == 'undefined' ) {
            parent[parts[i]] = {};
        }
        
        parent = parent[parts[i]];
    }
    
    return parent;
};

jQuery(document).ready(function($){
    // init uploader
    if ( typeof( wpWizzaroGalleryUploaderInit ) == 'object' ) {
        var isIE = navigator.userAgent.indexOf('Trident/') != -1 || navigator.userAgent.indexOf('MSIE ') != -1;

        // Make sure flash sends cookies (seems in IE it does whitout switching to urlstream mode)
        if ( ! isIE && 'flash' === plupload.predictRuntime( wpWizzaroGalleryUploaderInit ) &&
            ( ! wpWizzaroGalleryUploaderInit.required_features || ! wpWizzaroGalleryUploaderInit.required_features.hasOwnProperty( 'send_binary_string' ) ) ) {

            wpWizzaroGalleryUploaderInit.required_features = wpWizzaroGalleryUploaderInit.required_features || {};
            wpWizzaroGalleryUploaderInit.required_features.send_binary_string = true;
        }
        
        Wizzaro.Plugins.Gallery.v1.UploaderObj = new Wizzaro.Plugins.Gallery.v1.Uploader( {
            uploader_config: wpWizzaroGalleryUploaderInit,
            config: Wizzaro.Plugins.Gallery.v1.UploaderConfig,
            media_item_view_class: Wizzaro.Plugins.Gallery.v1.MediaItemView
        } );
    }
    
    // init images
    if ( typeof( wpWizzaroGalleryImagesInit ) == 'object' ) {
        Wizzaro.Plugins.Gallery.v1.ImagesObj = new Wizzaro.Plugins.Gallery.v1.Images( {
            ajax_data: wpWizzaroGalleryImagesInit,
            config: Wizzaro.Plugins.Gallery.v1.ImagesConfig,
            image_class: Wizzaro.Plugins.Gallery.v1.ImageItem,
            sortable_class: Wizzaro.Plugins.Gallery.v1.ImagesSortable
        } );
        
        if ( typeof( Wizzaro.Plugins.Gallery.v1.UploaderObj ) == 'object' ) {
            jQuery( Wizzaro.Plugins.Gallery.v1.UploaderObj ).on( 'success_file_upload', function( event, data ) {
                Wizzaro.Plugins.Gallery.v1.ImagesObj.add_image( data.id, data.thumbnail_url );
            });
        }
    }
});

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
Wizzaro.namespace('Plugins.Gallery.v1');
Wizzaro.Plugins.Gallery.v1.Images = function( config ) {
    var _ajax_data = config.ajax_data;
    
    var _config = config.config,
        _image_item_class = config.image_class;
        
    var _sortable_obj = new config.sortable_class( _config.sortable );
    
    var _image_items = {};
    
    //----------------------------------------------------------------------------------------------------
    // Init imgages
    
    if ( jQuery( '#' + _config.images.container_id ).length > 0 ) {
        jQuery( '#' + _config.images.container_id + ' .' + _config.image.container_class ).each( function() {
            _create_image_item( this );
        } );
        
        _sortable_obj.create();
    }

    //----------------------------------------------------------------------------------------------------
    // Public interface
    this.add_image = _add_image;
    
    //----------------------------------------------------------------------------------------------------
    // Images functions
    
    function _add_image( id, thumbnail_url ) {
        
        if ( jQuery( '#' + _config.images.container_id ).length <= 0 ) {
            jQuery( '#' + _config.container_id ).html( '<div id="' + _config.images.container_id + '"></div>' );
            
            _sortable_obj.create();
        }
        
        var new_img_obj = jQuery( document.createElement( 'div' ) ).addClass( _config.image.container_class ).addClass( _config.image.invisible_class );
        
        var image_html = '' +
            '<div class="wgi-image-wrapper">' +
                '<div class="wgi-i-image">' +
                    '<img src="' + thumbnail_url + '" >' +
                '</div>' +
                '<div class="wgi-i-butons">';
                
        if ( _ajax_data.support_thumbnail == '0' ) {
            image_html += '<a class="wgi-i-b-button ' + _config.image.thumbnail.bt_class + '" href="#" title="' + _ajax_data.l10n.buttons_titles.set_thumbnail  + '">' +
                '<span class="dashicons dashicons-star-filled"></span>' +
            '</a>';
        }
                    /*
                    '<a class="wgi-i-b-button ' + _config.image.preview.bt_class + '" href="#" title="' + _ajax_data.l10n.buttons_titles.preview  + '">' +
                        '<span class="dashicons dashicons-search"></span>' +
                    '</a>' +
                    '<a class="wgi-i-b-button ' + _config.image.edit.bt_class + '" href="#" title="' + _ajax_data.l10n.buttons_titles.edit  + '">' +
                        '<span class="dashicons dashicons-edit"></span>' +
                    '</a>' +
                    */
        image_html += '<a class="wgi-i-b-button ' + _config.image.delete.bt_class + '" href="#" title="' + _ajax_data.l10n.buttons_titles.delete  + '">' +
                        '<span class="dashicons dashicons-trash"></span>' +
                    '</a>' +
                '</div>' +
                '<input class="' + _config.image.image_id_elem_class + '" type="hidden" name="wizzaro-gal-images[]" value="' + id + '">' +
            '</div>';
        
        new_img_obj.html( image_html );
        
        new_img_obj.hide();
        
        jQuery( '#' + _config.images.container_id ).append( new_img_obj );
        
        _create_image_item( new_img_obj );
        
        new_img_obj.show( _config.image.visible_toogle_time );
    }
    
    function _create_image_item( img_obj ) {
        var img_item = new _image_item_class( img_obj, _config.image, _ajax_data );
        _image_items[img_item.get_img_id] = img_item;
        jQuery( img_item ).on( 'set_as_thumbnail',  _event_image_before_set_as_thumbnail );
        jQuery( img_item ).on( 'delete',  _event_image_delete );
    }
    
    function _event_image_before_set_as_thumbnail( event, img_item ) {
        jQuery( '#' + _config.images.container_id + ' .' + _config.image.container_class ).not( img_item ).removeClass( _config.image.thumbnail_class );
    }
    
    function _event_image_delete( event, img_item ) {
        delete _image_items[img_item.get_img_id];
        
        if ( jQuery( '#' + _config.images.container_id + ' .' + _config.image.container_class ).length <= 0 ) {
            _sortable_obj.destroy();
            jQuery( '#' + _config.container_id ).html( '<p id="' + _config.images.no_image_container_id + '">' + _ajax_data.l10n.no_image + '</p>' );
        }
    }
};

Wizzaro.namespace('Plugins.Gallery.v1');
Wizzaro.Plugins.Gallery.v1.ImagesSortable = function( config ) {
    
    var _config = config;
    
    //----------------------------------------------------------------------------------------------------
    // Init sortable
    
    //----------------------------------------------------------------------------------------------------
    // API functions
    
    this.create = _create;
    this.recreate = _recreate;
    this.destroy = _destroy;
    this.refresh = _refresh;
    
    
    //----------------------------------------------------------------------------------------------------
    // Functions
    function _create() {
        jQuery( '#' + _config.container_id ).sortable( {
            items: '> .' + _config.items_class,
            placeholder: _config.placeholder_class,
            cursor: "move",
            opacity: _config.sort_opacity,
            update: function(event, ui) {
                //TODO open sortable tools
            }
        } ).disableSelection();
    }
    
    function _destroy() {
        jQuery( '#' + _config.container_id ).sortable( 'destroy' );
    }
    
    function _recreate() {
        _destroy();
        _create();
    }
    
    function _refresh() {
        jQuery( '#' + _config.container_id ).sortable( 'refresh' );
    }
};

Wizzaro.namespace('Plugins.Gallery.v1');
Wizzaro.Plugins.Gallery.v1.ImagesConfig = Wizzaro.Plugins.Gallery.v1.ImagesConfig || {
    container_id: 'wizzaro-gal-images',
    sortable: {
        container_id: 'wgi-wrapper',
        items_class: 'wgi-image-container',
        placeholder_class: 'wgi-image-placeholder',
        sort_opacity: 0.7
    },
    images: {
        container_id: 'wgi-wrapper',
        no_image_container_id: 'wg-no-images'
    },
    image: {
        container_class: 'wgi-image-container',
        image_id_elem_class: 'wgi-i-image-id',
        loader_class: 'loading',
        invisible_class: 'invisible',
        thumbnail_class: 'gallery-thumb',
        visible_toogle_time: 200,
        thumbnail: {
            bt_class: 'wgi-i-b-set-thumbnail'
        },
        preview: {
            bt_class: 'wgi-i-b-preview'
        },
        edit: {
            bt_class: 'wgi-i-b-edit'
        },
        delete: {
            bt_class: 'wgi-i-b-del'
        },
    }
};
Wizzaro.namespace('Plugins.Gallery.v1');
Wizzaro.Plugins.Gallery.v1.MediaItemView = function( config ) {
    
    var _config = config,
        _media_items = {};
    
    this.add_media_item = _add_media_item;
    this.set_error = _set_error;
    this.success_upload = _success_upload;
    this.set_thumbnail = _set_thumbnail;
    this.set_upload_percent = _set_upload_percent;
    
    function _exist_item( file_id ) {
        if ( _media_items.hasOwnProperty( file_id ) ) {
            return true;
        }
        
        return false;
    }
    
    function _add_media_item( file_obj ) {
        if ( _exist_item( file_obj.id ) ) {
            return _media_items[file_obj.id];
        }
        
        var media_item = jQuery( document.createElement( 'div' ) );
        media_item.attr( 'id', _config.media_items.item_id + file_obj.id )
        .addClass( 'media-item' )
        .append('<img class="pinkynail original" src="" alt="" />')
        .append('<div class="progress"><div class="percent">0%</div><div class="bar"></div></div>',
            jQuery('<div class="filename original">').text( ' ' + file_obj.name ))
        .appendTo( jQuery( '#' + _config.media_items.container_id ) );
        
        _media_items[file_obj.id] = media_item;
    
        // Add preview image
        /* IS TO SLOW :(
        if ( window.File && window.FileReader && window.FileList && window.Blob ) {
            var file = file_obj.getNative();
            
            if ( file ) {
                
                if( file.type.match('image.*') ) {
                    var reader = new FileReader();
                    
                    reader.onload = function(e) {
                        _set_thumbnail( file_obj.id, e.target.result, 'original' );
                    };
            
                    reader.readAsDataURL( file );
                }
            }
            
        }
        */
        return media_item;
    }
    
    function _set_error( file_obj, message ) {
        var item = _add_media_item( file_obj );

        // prevent firing an error for the same file twice
        if ( item.data( 'last-err' ) == file_obj.id ) {
            return;
        }
        
        var new_filename_html = '<p><strong>' + pluploadL10n.error_uploading.replace('%s', jQuery.trim( item.find( '.filename' ).text() ) ) + '</strong> ' + message + '</p>';
        
        item.addClass('error').find( '.filename' ).replaceWith( new_filename_html );
        item.find( '.progress' ).remove();
        
        item.data( 'last-err', file_obj.id );
    }
    
    function _success_upload( file_obj, data ) {
        var item = _add_media_item( file_obj );
        item.addClass('success');
        item.find( '.progress' ).remove();
        item.find( '.filename' ).addClass( 'new' ).removeClass( 'original' ).html( data.name );
        item.find( '.pinkynail' ).addClass( 'new' ).removeClass( 'original' ).attr( 'src', data.thumbnail_url ).show();
    }
    
    function _set_thumbnail( file_id, url, type ) {
        if ( _exist_item( file_id ) ) {
            _media_items[file_id].find('.pinkynail.' + type ).attr( 'src', url ).show();
        }
    }
    
    function _set_upload_percent( file_id, bar_width, percent ) {
        if ( _exist_item( file_id ) ) {
            _media_items[file_id].find( '.bar' ).width( bar_width );
            _media_items[file_id].find( '.percent' ).html( percent + '%' );
        }
    }
};
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
Wizzaro.namespace('Plugins.Gallery.v1');
Wizzaro.Plugins.Gallery.v1.UploaderConfig = Wizzaro.Plugins.Gallery.v1.UploaderConfig || {
    layout: {
        container_id: 'wizzaro-gal-uploader',
        open_uploader_bt_id: 'wizzaro-gal-open-uploader',
        uploader_toogle_time: 200
    },
    uploader: {
        plupload_container_id: 'wizzaro-gal-plupload-upload-ui',
        html_container_id: 'wizzaro-gal-html-upload-ui',
        switch_uploaders_bt_elem: '.wizzaro-gal-bypass a'
    },
    drag_drop_area: {
        area_id: 'wizzaro-gal-drag-drop-area'
    },
    errors: {
        container_id: 'wizzaro-gal-media-upload-error'    
    },
    media_items: {
        container_id: 'wizzaro-gal-media-items',
        item_id: 'wizzaro-gal-media-item-'
    }
};