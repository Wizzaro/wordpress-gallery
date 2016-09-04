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