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
                    */
        image_html += '<a class="wgi-i-b-button ' + _config.image.edit.bt_class + '" href="#" title="' + _ajax_data.l10n.buttons_titles.edit  + '">' +
                        '<span class="dashicons dashicons-edit"></span>' +
                    '</a>' +
                    '<a class="wgi-i-b-button ' + _config.image.delete.bt_class + '" href="#" title="' + _ajax_data.l10n.buttons_titles.delete  + '">' +
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
