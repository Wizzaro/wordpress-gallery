Wizzaro.namespace('Plugins.Gallery.v1');
Wizzaro.Plugins.Gallery.v1.ImageEditor = function( ajax_data ) {

    if(!(this instanceof Wizzaro.Plugins.Gallery.v1.ImageEditor)) {
        return new Wizzaro.Plugins.Gallery.v1.ImageEditor( ajax_data );
    }

    var instance;
    var _ajax_data = ajax_data;
    var parent = Wizzaro.Plugins.Gallery.v1.ImageEditor;

    Wizzaro.Plugins.Gallery.v1.ImageEditor = function parent() {
        return instance;
    };

    Wizzaro.Plugins.Gallery.v1.ImageEditor.prototype = this;

    instance = new Wizzaro.Plugins.Gallery.v1.ImageEditor( ajax_data );

    instance.constructor = Wizzaro.Plugins.Gallery.v1.ImageEditor;

    var _current_image_data;
    var _editor_view;

    var _editor_view_loader;

    var _editor_view_image;
    var _editor_view_data;

    jQuery( document ).on( 'keydown', keydown );

    //----------------------------------------------------------------------------------------------------
    // API functions
    this.open = function( image_data ) {
        if( ! _editor_view ) {
            create_editor_view();
        }

        if ( ! _current_image_data || _current_image_data.image.id !== image_data.image.id ) {
            _current_image_data = image_data;
            parse_image( image_data.image );
            parse_data( image_data.data );
        }

        _editor_view_loader.hide();
        show();
    }

    function parse_image( data ) {
        _editor_view_image.attr( 'src', data.src + '?t=' + new Date().getTime() );
    }

    function parse_data( data ) {
        jQuery.each( data, function( data_key, value ) {
            var field = _editor_view_data.find( 'input[type="text"][name="' + data_key + '"]' );
            if( field.length > 0 ) {
                field.val( value );
            }
        } );
    }

    function create_editor_view() {
        _editor_view = jQuery( '.wizzaro-gal-editor' );

        if ( _editor_view.length <= 0 ) {
            _editor_view = jQuery( document.createElement( 'div' ) );
            _editor_view.addClass( 'wizzaro-gal-editor' );
            _editor_view.html('' +
                '<div class="wge-wrapper">' +
                    '<div class="wge-content">' +
                        '<div class="spinner wge-loader"></div>' +
                        '<div class="wge-col wge-col-50">' +
                            '<p>' + _ajax_data.editor.l10n.headers.image + ':</p>' +
                            '<p><img class="wge-image" src="" /></p>' +
                        '</div>' +
                        '<div class="wge-col wge-col-50">' +
                            '<p>' + _ajax_data.editor.l10n.headers.data + ':</p>' +
                            '<ul class="wge-data">' +
                                '<li><strong>' + _ajax_data.editor.l10n.data_labels.alt_text + '</strong></li>' +
                                '<li><input class="large-text" name="alt_text" type="text"></li>' +
                            '</ul>' +
                        '</div>' +
                        '<div class="wge-col wge-action-buttons">' +
                            '<p>' +
                                '<button type="button" class="button button-large wge-close">' + _ajax_data.editor.l10n.buttons_titles.close + '</button> ' +
                                '<button type="button" class="button button-primary button-large wge-save">' + _ajax_data.editor.l10n.buttons_titles.save + '</button>' +
                            '</p>' +
                        '</div>' +
                    '</div>' +
                '</div>' );
            }

            _editor_view_image = _editor_view.find( '.wge-image' );
            _editor_view_data = _editor_view.find( '.wge-data' );

            _editor_view_loader = _editor_view.find( '.wge-loader' );

            _editor_view.find( '.wge-close' ).on( 'click', hide );
            _editor_view.find( '.wge-save' ).on( 'click', save );

            jQuery( 'body' ).append( _editor_view );
    }

    function show() {
        jQuery( 'body' ).css( 'overflow', 'hidden' );
        _editor_view.show();
    }

    function hide() {
        jQuery( 'body' ).css( 'overflow', '' );
        _editor_view.hide();
    }

    function keydown( event ) {
        if ( event.keyCode == 27 && _editor_view.is(':visible') ) {
           event.preventDefault();
           event.stopPropagation();
           hide();
        }
    }

    function save() {
        _editor_view_loader.show();

        jQuery.each( _current_image_data.data, function( data_key, value ) {
            var field = _editor_view_data.find( 'input[type="text"][name="' + data_key + '"]' );
            if( field.length > 0 ) {
                _current_image_data.data[data_key] = field.val();
            }
        } );

        console.log(_current_image_data);

        jQuery.ajax({
            url: ajaxurl,
            type: 'post',
            data: {
                action: _ajax_data.editor.action,
                edit_nonce: _ajax_data.editor.nonce,
                post_id: _ajax_data.post_id,
                img_id: _current_image_data.image.id,
                img_data: _current_image_data.data
            },
            dataType: 'json',
            success: function( data ) {
                if ( jQuery.type( data ) === 'object' ) {
                    if ( data.status == '1' ) {
                        jQuery.each( data.data, function( data_key, value ) {
                            if ( _current_image_data[data_key] ) {
                                _current_image_data[data_key] = value;
                            }
                        } );
                        parse_data( data.data );
                        jQuery( instance ).trigger( 'success_edit', _current_image_data );
                    } else {
                        alert( data.message );
                    }
                } else {
                    alert( _ajax_data.editor.l10n.error );
                }
            },
            error: function() {
                alert( _ajax_data.editor.l10n.error );
            },
            complete: function() {
                _editor_view_loader.hide();
            }
        });
    }

    return instance;
};
