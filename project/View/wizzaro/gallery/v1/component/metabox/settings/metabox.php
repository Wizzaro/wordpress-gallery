<?php
wp_nonce_field( 'wizzaro_gallery_save_post_settings_nonce', 'wizzaro_gallery_save_post_settings' );
?>
<div class="panel-wrap">

    <div class="panel">
        <ul>
            <li>
                <strong><?php _e( 'Columns', $view_data['languages_domain'] ); ?>:</strong>
                <select name="wizzaro_gallery_settings[columns_count]">
                    <?php
                    for( $i = 1; $i <= 9; $i++ ) {
                        $selected = $i == $view_data['settings']['columns_count'] ? ' selected="selected"' : '';
                        ?>
                        <option value="<?php echo $i; ?>"<?php echo $selected; ?>><?php echo $i; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </li>
            <li>
                <strong><?php _e( 'Display no image information', $view_data['languages_domain'] ); ?>:</strong>
                <select name="wizzaro_gallery_settings[display_empty_info]">
                    <option value="1"<?php echo $view_data['settings']['display_empty_info'] == 1 ? ' selected="selected"' : ''; ?>><?php _e( 'Yes' ); ?></option>
                    <option value="0"<?php echo $view_data['settings']['display_empty_info'] == 0 ? ' selected="selected"' : ''; ?>><?php _e( 'No' ); ?></option>
                </select>
            </li>
        </ul>
    </div>
</div>
