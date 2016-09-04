<select name="<?php echo esc_attr( $view_data['settings_name'] ) . '[' . esc_attr( $view_data['field_name'] ) . ']'; ?>">
    <?php
    foreach ( $view_data['select_options'] as $opt_val => $opt_text ) {
        $selected = ! strcmp( $opt_val, $view_data['value'] ) ? ' selected="selected"' : '';
        echo '<option value="' . $opt_val . '"' . $selected . '>' . $opt_text . '</option>';
    }
    ?>
</select>