<?php
if ( ! class_exists( 'WP_Customize_Control' ) )
    return NULL;
/**
 * Class to create a custom tags control
 */
class Text_Editor_Custom_Control extends WP_Customize_Control
{
      /**
       * Render the content on the theme customizer page
       */
      public function render_content()
       {
            $geopccb_content = $this->value();
            ?>
                <label>
                  <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                  <!-- updated per https://github.com/paulund/wordpress-theme-customizer-custom-controls/issues/28 -->
                  <input class="wp-editor-area" type="hidden" <?php echo $this->get_link(); ?> value="<?php echo esc_textarea( $geopccb_content ); ?>">
                  <?php
                    $geopccb_settings = array(
                        'textarea_name' => $this->id,
                        'media_buttons' => false,
                        'drag_drop_upload' => false,
                        'teeny' => false
                    );
                    wp_editor($geopccb_content, $this->id, $geopccb_settings );

                    do_action('admin_footer');
                    do_action('admin_print_footer_scripts');

                  ?>
                </label>
            <?php
       }
}
