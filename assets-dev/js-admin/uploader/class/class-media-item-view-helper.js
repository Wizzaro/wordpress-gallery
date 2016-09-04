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