<div class="wrap">
    <h2><?php echo esc_attr($view_data['title']); ?></h2>
    <h2 class="nav-tab-wrapper">
    <?php
        foreach ( $view_data['tabs'] as $slug => $settings ){
            $class = ( $slug == $view_data['current_tab'] ) ? ' nav-tab-active' : '';
            echo '<a class="nav-tab' . $class . '" href="?page=' . $view_data['slug'] . '&tab=' . $slug . '">' . $settings['tab_name'] . '</a>';
        }
    ?>
    </h2>
    <div id="setings_page_content">
        <?php
            if ( is_object( $view_data['current_tab_obj'] ) && method_exists( $view_data['current_tab_obj'], $view_data['current_tab_render_fn'] ) ) {
                call_user_func( array( $view_data['current_tab_obj'], $view_data['current_tab_render_fn'] ) );
            }
        ?>
    </div>
</div>