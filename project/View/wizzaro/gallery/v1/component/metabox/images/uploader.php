<div class="wrap">
    <div class="hide-if-no-js">
        <a href="#" id="wizzaro-gal-open-uploader" class="button button-primary button-large">
            <?php _e( 'Add new photos', $view_data['languages_domain'] ); ?>
            <span class="dashicons dashicons-arrow-down-alt2"></span>
            <span class="dashicons dashicons-arrow-up-alt2"></span>
        </a>
    </div>
    <div id="wizzaro-gal-uploader" class="media-upload-form type-form validate hide-if-js">
        <div id="wizzaro-gal-media-upload-error">
        </div>
        <div id="wizzaro-gal-plupload-upload-ui" class="hide-if-no-js">
            <div id="wizzaro-gal-drag-drop-area">
                <div class="drag-drop-inside">
                    <p class="drag-drop-info">
                        <?php _e( 'Drop files here', $view_data['languages_domain'] ); ?>
                    </p>
                    <p>
                        <?php _e( 'or', $view_data['languages_domain'] ); ?>
                    </p>
                    <p class="drag-drop-buttons">
                        <input id="wizzaro-gal-plupload-browse-button" type="button" value="<?php esc_attr_e( 'Select Files', $view_data['languages_domain'] ); ?>" class="button" />
                    </p>
                </div>
            </div>
            <p class="wizzaro-gal-bypass">
            <?php printf( __( 'You are using the multi-file uploader. Problems? Try the <a href="%1$s" target="%2$s">browser uploader</a> instead.', $view_data['languages_domain'] ), '#', '_self' ); ?>
            </p>
        </div>

        <div id="wizzaro-gal-html-upload-ui" class="hide-if-js">
            <p>
                <label class="screen-reader-text" for="async-upload">
                    <?php _e( 'Upload', $view_data['languages_domain'] ); ?>
                </label>
                <input id="wizzaro-async-upload" type="file" name="wizzaro-gal-upload-image" />
            </p>
            <div class="clear"></div>
            <p class="wizzaro-gal-bypass">
               <?php _e( 'You are using the browser&#8217;s built-in file uploader. The WordPress uploader includes multiple file selection and drag and drop capability. <a href="#">Switch to the multi-file uploader</a>.', $view_data['languages_domain'] ); ?>
            </p>
        </div>
        
        <p class="max-upload-size"><?php printf( __( 'Maximum upload file size: %s.', $view_data['languages_domain'] ), esc_html( size_format( $view_data['max_upload_size'] ) ) ); ?></p>
        
        <?php 
            wp_nonce_field( 'wizzaro_gallery_images_add_nounce', 'wizzaro_gallery_images_add' ); 
        ?>
        <div id="wizzaro-gal-media-items" class="hide-if-no-js">
        </div>
    </div>
</div>