<?php
if ( count( $view_data['images'] ) > 0 ) {
    $html5 = current_theme_supports( 'html5', 'gallery' );
    $atts = array_merge( array(
        'itemtag'    => $html5 ? 'figure'     : 'dl',
        'icontag'    => $html5 ? 'div'        : 'dt',
        'columns_count'    => 4,
    ), $view_data['settings'] );

    $itemtag = tag_escape( $atts['itemtag'] );
    $icontag = tag_escape( $atts['icontag'] );
    $columns = intval( $atts['columns_count'] );

    $output = "<div class=\"gallery gallery-columns-{$columns}\">";

    $i = 0;
    foreach ( $view_data['images'] as $img ) {
        $image_output = "<a href=\"" . esc_url( $view_data['urls']['url'] . $img->name ) ."\">
            <img src=\"" . esc_url( $view_data['urls']['url_tumbnail'] . $img->name ) . "\" alt=\"" . ( mb_strlen( $img->alt_text ) > 0 ? $img->alt_text : $img->name ) . "\">
        </a>";

        $orientation = ( $img->height > $img->width ) ? 'portrait' : 'landscape';

        $output .= "<{$itemtag} class=\"gallery-item\">";
        $output .= "
            <{$icontag} class=\"gallery-icon {$orientation}\">
                $image_output
            </{$icontag}>";
        $output .= "</{$itemtag}>";
        if ( ! $html5 && $columns > 0 && ++$i % $columns == 0 ) {
            $output .= '<br style="clear: both" />';
        }
    }

    if ( ! $html5 && $columns > 0 && $i % $columns !== 0 ) {
        $output .= "
            <br style='clear: both' />";
    }

    $output .= "
        </div>\n";

    echo $output;
} elseif ( $view_data['settings']['display_empty_info'] == 1 ) {
    ?>
    <p class="no-results">
        <?php _e( 'No images in gallery.', $view_data['languages_domain'] ); ?>
    </p>
    <?php
}
