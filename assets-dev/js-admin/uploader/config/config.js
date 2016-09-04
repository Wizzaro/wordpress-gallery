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