<select name="<?php echo esc_attr( $view_data['settings_name'] ) . '[' . esc_attr( $view_data['field_name'] ) . ']'; ?>">
    <option value="" selected><?php _e( '----' ); ?></option>
    <?php
    foreach ( $view_data['select_options'] as $page_key => $page_obj ) {
        $selected = ! strcmp( $page_obj->ID, $view_data['value'] ) ? ' selected="selected"' : '';
        echo '<option value="' . $page_obj->ID . '"' . $selected . '>' . $page_obj->post_title . '</option>';
    }
    ?>
</select>