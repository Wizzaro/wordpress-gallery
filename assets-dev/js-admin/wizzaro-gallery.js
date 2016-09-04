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
