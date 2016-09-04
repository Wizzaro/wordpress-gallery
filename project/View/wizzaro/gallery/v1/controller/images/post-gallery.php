<?php
if ( count( $view_data['images'] ) > 0 ) {
    ?>
    <div class="wizzaro-gallery-images">
        <div class="wgi-loader">
            <div class="loader"></div>
            <div class="progressbar">
                <div class="progressbar-value"></div>
            </div>
            <div class="info">
                <?php _e( 'Loading images', $view_data['languages_domain'] ); ?>
                <br>
                <span class="number-of-images"></span>
            </div>
        </div>
        <div class="wgi-items">
            <?php
            foreach ( $view_data['images'] as $img ) {
                ?>
                <div class="wgi-item">
                    <a href="<?php echo esc_url( $view_data['urls']['url'] . $img->name ); ?>">
                        <img src="<?php echo esc_url( $view_data['urls']['url_tumbnail'] . $img->name ); ?>" alt="<?php  echo mb_strlen( $img->alt_text ) > 0 ? $img->alt_text : $img->name; ?>">
                    </a>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <?php
} else {
    ?>
    <p class="no-results">
        <?php _e( 'No images in gallery.', $view_data['languages_domain'] ); ?>
    </p>
    <?php
}
