<?php
if (!class_exists('CoverNews_author_info')) :
  /**
   * Adds CoverNews_author_info widget.
   */
  class CoverNews_author_info extends AFthemes_Widget_Base
  {
    /**
     * Sets up a new widget instance.
     *
     * @since 1.0.0
     */
    function __construct()
    {
      $this->text_fields = array('covernews-author-info-title', 'covernews-author-info-image', 'covernews-author-info-name', 'covernews-author-info-desc', 'covernews-author-info-phone', 'covernews-author-info-email');
      $this->url_fields = array('covernews-author-info-facebook', 'covernews-author-info-twitter', 'covernews-author-info-linkedin', 'covernews-author-info-instagram', 'covernews-author-info-vk', 'covernews-author-info-googleplus');

      $widget_ops = array(
        'classname' => 'covernews_author_info_widget',
        'description' => __('Displays author info.', 'covernews'),
        'customize_selective_refresh' => false,
      );

      parent::__construct('covernews_author_info', __('CoverNews Author Info', 'covernews'), $widget_ops);
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args Widget arguments.
     * @param array $instance Saved values from database.
     */

    public function widget($args, $instance)
    {


      echo $args['before_widget'];
      $title = apply_filters('widget_title', $instance['covernews-author-info-title'], $instance, $this->id_base);
      $profile_image = !empty($instance['covernews-author-info-image']) ? ($instance['covernews-author-info-image']) : '';
      $name = !empty($instance['covernews-author-info-name']) ? ($instance['covernews-author-info-name']) : '';

      $desc = !empty($instance['covernews-author-info-desc']) ? ($instance['covernews-author-info-desc']) : '';
      $facebook = !empty($instance['covernews-author-info-facebook']) ? ($instance['covernews-author-info-facebook']) : '';
      $twitter = !empty($instance['covernews-author-info-twitter']) ? ($instance['covernews-author-info-twitter']) : '';
      $linkedin = !empty($instance['covernews-author-info-linkedin']) ? ($instance['covernews-author-info-linkedin']) : '';




?>
      <?php if (!empty($title)): ?>
        <div class="em-title-subtitle-wrap">
          <?php if (!empty($title)): covernews_render_section_title($title); endif; ?>
        </div>
      <?php endif; ?>
      <div class="posts-author-wrapper">

        <?php if (!empty($profile_image)) : ?>
          <figure class="em-author-img bg-image">
            <img src="<?php echo esc_attr($profile_image); ?>" alt="" />
          </figure>
        <?php endif; ?>
        <div class="em-author-details">
          <?php if (!empty($name)) : ?>
            <h3 class="em-author-display-name"><?php echo esc_html($name); ?></h3>
          <?php endif; ?>
          <?php if (!empty($phone)) : ?>
            <a href="tel:<?php echo esc_attr($phone); ?>" class="em-author-display-phone" aria-label="Telephone"><?php echo esc_html($phone); ?></a>
          <?php endif; ?>
          <?php if (!empty($email)) : ?>
            <a href="mailto:<?php echo esc_attr($email); ?>" class="em-author-display-email" aria-label="Email"><?php echo esc_html($email); ?></a>
          <?php endif; ?>
          <?php if (!empty($desc)) : ?>
            <p class="em-author-display-name"><?php echo esc_html($desc); ?></p>
          <?php endif; ?>

          <?php if (!empty($facebook) || !empty($twitter) || !empty($linkedin)) : ?>
            <ul>
              <?php if (!empty($facebook)) : ?>
                <li>
                  <a href="<?php echo esc_url($facebook); ?>" target="_blank" accesskey="f" aria-label="Facebook"></a>
                </li>
              <?php endif; ?>
              <?php if (!empty($twitter)) : ?>
                <li>
                  <a href="<?php echo esc_url($twitter); ?>" target="_blank" accesskey="t" aria-label="Twitter"></a>
                </li>
              <?php endif; ?>
              <?php if (!empty($linkedin)) : ?>
                <li>
                  <a href="<?php echo esc_url($linkedin); ?>" target="_blank" accesskey="l" aria-label="LinkedIn"></a>
                </li>
              <?php endif; ?>

              <?php if (!empty($instagram)) : ?>
                <li>
                  <a href="<?php echo esc_url($instagram); ?>" target="_blank" aria-label="Instagram"></a>
                </li>
              <?php endif; ?>
              <?php if (!empty($vk)) : ?>
                <li>
                  <a href="<?php echo esc_url($vk); ?>" target="_blank" aria-label="VK"></a>
                </li>
              <?php endif; ?>
              <?php if (!empty($googleplus)) : ?>
                <li>
                  <a href="<?php echo esc_url($googleplus); ?>" target="_blank" aria-label="Google Plus"></a>
                </li>
              <?php endif; ?>
            </ul>

          <?php endif; ?>
        </div>
      </div>
<?php
      //print_pre($all_posts);
      // close the widget container
      echo $args['after_widget'];

      //$instance = parent::covernews_sanitize_data( $instance, $instance );


    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance)
    {
      $this->form_instance = $instance;
      $categories = covernews_get_terms();
      if (isset($categories) && !empty($categories)) {
        // generate the text input for the title of the widget. Note that the first parameter matches text_fields array entry
        echo parent::covernews_generate_text_input('covernews-author-info-title', __('Title', 'covernews'), __('Title', 'covernews'));
        echo parent::covernews_generate_image_upload('covernews-author-info-image', __('Profile image', 'covernews'), __('Profile image', 'covernews'));
        echo parent::covernews_generate_text_input('covernews-author-info-name', __('Name', 'covernews'), __('Name', 'covernews'));
        echo parent::covernews_generate_text_input('covernews-author-info-desc', __('Descriptions', 'covernews'), '');
        echo parent::covernews_generate_text_input('covernews-author-info-facebook', __('Facebook', 'covernews'), '');
        echo parent::covernews_generate_text_input('covernews-author-info-twitter', __('Twitter', 'covernews'), '');
        echo parent::covernews_generate_text_input('covernews-author-info-linkedin', __('LinkedIn', 'covernews'), '');
      }
    }
  }
endif;
