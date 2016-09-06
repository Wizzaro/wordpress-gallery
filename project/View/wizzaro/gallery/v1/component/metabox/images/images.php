<div class="wrap">
    <div id="wizzaro-gal-images">
        <?php
        if ( count( $view_data['images'] ) <= 0 ) {
            ?>
            <p id="wg-no-images">
                <?php _e( 'In this gallery has no photos. Upload something.', $view_data['languages_domain'] ); ?>    
            </p>
            <?php
        } else {
            ?>
            <div id="wgi-wrapper">
                <?php
                foreach ( $view_data['images'] as $image ) {
                    $thumbnail_class = ( ! strcasecmp( $view_data['thumbnail_id'], $image->ID ) && ! $view_data['support_thumbnail'] ) ? ' gallery-thumb' : '';
                    $visible_class = strcasecmp( $image->visible, '1' ) ? ' invisible' : '';
                    ?>
                    <div class="wgi-image-container<?php echo $thumbnail_class . $visible_class; ?>">
                        <div class="wgi-image-wrapper">
                            <div class="wgi-i-image">
                                <img src="<?php echo esc_url( $view_data['urls']['url_tumbnail'] . $image->name ); ?>" >
                            </div>
                            <div class="wgi-i-butons">
                                <?php
                                if ( ! $view_data['support_thumbnail'] ) {
                                    ?>
                                    <a class="wgi-i-b-button wgi-i-b-set-thumbnail" href="#" title="<?php  echo $view_data['buttons_titles']['set_thumbnail'] ?>">
                                        <span class="dashicons dashicons-star-filled"></span>
                                    </a>
                                <?php
                                } 
                                /* 
                                <a class="wgi-i-b-button wgi-i-b-preview hide-if-no-js" href="#" title="<?php  echo $view_data['buttons_titles']['preview'] ?>">
                                    <span class="dashicons dashicons-search"></span>
                                </a> 
                                <a class="wgi-i-b-button wgi-i-b-edit" href="#" title="<?php  echo $view_data['buttons_titles']['edit'] ?>">
                                    <span class="dashicons dashicons-edit"></span>
                                </a> 
                                 */ ?>
                                <a class="wgi-i-b-button wgi-i-b-del" href="#" title="<?php  echo $view_data['buttons_titles']['set_thumbnail'] ?>">
                                    <span class="dashicons dashicons-trash"></span>
                                </a>
                            </div>
                            <input class="wgi-i-image-id" type="hidden" name="wizzaro-gal-images[]" value="<?php echo $view_data['encrypt_instance']->encryption( $image->ID ); ?>">
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            <?php
        }
        ?>
    </div>
    <div id="wizzaro-gal-legend">
        <p>
            <strong><?php _e( 'Markings on the picture:', $view_data['languages_domain'] ); ?></strong>
            <ul>
                <?php
                if ( ! $view_data['support_thumbnail'] ) {
                    ?>
                    <li>
                        <span class="dashicons dashicons-star-filled"></span> - <?php _e( 'Gallery thumbnail.', $view_data['languages_domain'] ); ?>
                    </li>
                    <?php
                }
                ?>
                <li>
                    <span class="dashicons dashicons-hidden"></span> - <?php _e( 'Image is not visible on gallery page. When you save gallery then will be visible.', $view_data['languages_domain'] ); ?>
                </li>
            </ul>
        </p>
        <p>
            <strong><?php _e( 'Buttons:', $view_data['languages_domain'] ); ?></strong>
            <ul>
                <?php
                if ( ! $view_data['support_thumbnail'] ) {
                    ?>
                    <li>
                        <span class="dashicons dashicons-star-filled"></span> - <?php  echo $view_data['buttons_titles']['set_thumbnail'] ?>.
                    </li>
                    <?php
                }
                /*
                <li>
                    <span class="dashicons dashicons-search"></span> - <?php  echo $view_data['buttons_titles']['preview'] ?>.
                </li>
                <li>
                    <span class="dashicons dashicons-edit"></span> - <?php  echo $view_data['buttons_titles']['edit'] ?>.
                </li> */?>
                <li>
                    <span class="dashicons dashicons-trash"></span> - <?php  echo $view_data['buttons_titles']['delete'] ?>.
                </li>
            </ul>
        </p>
    </div>
</div>