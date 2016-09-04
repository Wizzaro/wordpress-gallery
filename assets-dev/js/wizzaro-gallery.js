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

Wizzaro.namespace('Plugins.Gallery.v1.Loader');

Wizzaro.Plugins.Gallery.v1.Loader = function( config, $ ) {
    
    var _instance = $(this);
    
    var _loaded_images = 0;
    
    var _images_length = 0;
    
    var _images_container = $( config.container ),
        _loader = _images_container.find( config.loader ),
        _loader_progressbar_value = _loader.find( config.loader_progressbar_value ),
        _loader_images_number_info = _loader.find( config.loader_images_number_info ),
        _images_container = _images_container.find( config.images_container );
        _images = _images_container.find( config.image_item );

    _images_length = _images.length;
    
    if ( _images_length > 0 ) {
        
        _instance.on( 'load_image', image_load );
        _instance.on( 'error_load_image', image_load );
        
        if ( _loader_images_number_info.length > 0 ) {
            update_loaded_images_number_info();
            _instance.on( 'load_image', update_loaded_images_number_info );
            _instance.on( 'error_load_image', update_loaded_images_number_info );
        }
        
        if ( _loader_progressbar_value.length > 0 ) {
            _instance.on( 'load_image', update_progress_value );
            _instance.on( 'error_load_image', update_progress_value );  
        }
        
        $.each( _images, function( index, value ) {
            
            var img = $( new Image() );
            
            img.on( 'load', function() {
                _instance.trigger( 'load_image', [$( value )] ); 
            } );
            
            img.on( 'error', function() {
                _instance.trigger( 'error_load_image', [$( value )] ); 
            } );
            
            img.attr( 'src', $( value ).find( 'img' ).attr( 'src' ) );
        } );
    }
    
    function image_load( event, img_container ){
        _loaded_images++;
        
        if ( event.type == 'error_load_image' ) {
            img_container.remove();
        }
        
        check_loaded();
    }
    
    function update_progress_value(){
        _loader_progressbar_value.css( 'width', ( ( _loaded_images / _images_length ) * 100 ) + '%');
    }
    
    function update_loaded_images_number_info() {
        _loader_images_number_info.html( _loaded_images + ' / ' + _images_length );
    }
    
    function check_loaded() {
        if ( _loaded_images == _images_length ) {
            _loader.slideUp( 0 );
            setTimeout( show_gallery, 0 );
        }
    }
    
    function show_gallery() {
        _images_container.show();
        
        setTimeout( function() {    
            _images_container.masonry( {
                itemSelector: config.image_item,
            } );
        }, 0);
    }
};

jQuery( document ).ready( function( $ ) {
    new Wizzaro.Plugins.Gallery.v1.Loader( {
        container: '.wizzaro-gallery-images',
        loader: '.wgi-loader',
        loader_progressbar_value: '.progressbar .progressbar-value',
        loader_images_number_info: '.number-of-images',
        images_container: '.wgi-items',
        image_item: '.wgi-item' 
    }, $ ); 
});