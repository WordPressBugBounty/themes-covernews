<?php
if (!class_exists('CoverNews_Tabbed_Posts')) :
  /**
   * Adds CoverNews_Tabbed_Posts widget.
   */
  class CoverNews_Tabbed_Posts extends AFthemes_Widget_Base
  {
    /**
     * Sets up a new widget instance.
     *
     * @since 1.0.0
     */
    function __construct()
    {
      $this->text_fields = array('covernews-tabbed-popular-posts-title', 'covernews-tabbed-latest-posts-title', 'covernews-tabbed-categorised-posts-title');

      $this->select_fields = array('covernews-show-excerpt', 'covernews-enable-categorised-tab', 'covernews-select-category');

      $widget_ops = array(
        'classname' => 'covernews_tabbed_posts_widget',
        'description' => __('Displays tabbed posts lists from selected settings.', 'covernews'),
        'customize_selective_refresh' => false,
      );

      parent::__construct('covernews_tabbed_posts', __('CoverNews Tabbed Posts', 'covernews'), $widget_ops);
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
      $instance = parent::covernews_sanitize_data($instance, $instance);

      $min = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
      $covernews_version =  wp_get_theme()->get('Version');
      wp_enqueue_script('bootstrap', get_template_directory_uri() . '/assets/bootstrap/js/bootstrap' . $min . '.js', array('jquery'), $covernews_version, true);

      $tab_id = 'tabbed-' . $this->number;


      /** This filter is documented in wp-includes/default-widgets.php */

      $show_excerpt = !empty($instance['covernews-show-excerpt']) ? $instance['covernews-show-excerpt'] : 'false';
      $excerpt_length = '25';
      $number_of_posts = '5';

      $popular_title = !empty($instance['covernews-tabbed-popular-posts-title']) ? $instance['covernews-tabbed-popular-posts-title'] : __('Popular', 'covernews');
      $latest_title = !empty($instance['covernews-tabbed-latest-posts-title']) ? $instance['covernews-tabbed-latest-posts-title'] : __('Latest', 'covernews');

      $enable_categorised_tab = !empty($instance['covernews-enable-categorised-tab']) ? $instance['covernews-enable-categorised-tab'] : 'true';
      $categorised_title = !empty($instance['covernews-tabbed-categorised-posts-title']) ? $instance['covernews-tabbed-categorised-posts-title'] : __('Trending', 'covernews');
      $category = !empty($instance['covernews-select-category']) ? $instance['covernews-select-category'] : '0';

      // open the widget container
      echo $args['before_widget'];
      $is_recent_active = true;
?>
      <div class="tabbed-container">
        <div class="tabbed-head">
          <ul class="nav nav-tabs af-tabs tab-warpper" role="tablist">
            <li class="tab tab-recent" role="presentation">
              <a href="#<?php echo esc_attr($tab_id); ?>-recent"
                aria-label="<?php esc_attr_e('Recent', 'covernews'); ?>"
                role="tab"
                id="<?php echo esc_attr($tab_id); ?>-recent-tab"
                aria-controls="<?php echo esc_attr($tab_id); ?>-recent"
                aria-selected="<?php echo $is_recent_active ? 'true' : 'false'; ?>"
                data-toggle="tab"
                class="font-family-1 widget-title <?php echo $is_recent_active ? 'active' : ''; ?>">
                <?php echo esc_html($latest_title); ?>
              </a>
            </li>
            <li role="presentation" class="tab tab-popular">
              <a href="#<?php echo esc_attr($tab_id); ?>-popular"
                aria-label="<?php esc_attr_e('Popular', 'covernews'); ?>"
                role="tab"
                id="<?php echo esc_attr($tab_id); ?>-popular-tab"
                aria-controls="<?php echo esc_attr($tab_id); ?>-popular"
                aria-selected="<?php echo $is_recent_active ? 'false' : 'true'; ?>"
                data-toggle="tab"
                class="font-family-1 widget-title">
                <?php echo esc_html($popular_title); ?>
              </a>
            </li>

            <?php if ($enable_categorised_tab == 'true'): ?>
              <li class=" tab tab-categorised" role="presentation">
                <a href="#<?php echo esc_attr($tab_id); ?>-categorised"
                  aria-label="<?php esc_attr_e('Categorised', 'covernews'); ?>"
                  role="tab"
                  id="<?php echo esc_attr($tab_id); ?>-categorised-tab"
                  aria-controls="<?php echo esc_attr($tab_id); ?>-categorised"
                  aria-selected="<?php echo $is_recent_active ? 'false' : 'true'; ?>"
                  data-toggle="tab"
                  class="font-family-1 widget-title">
                  <?php echo esc_html($categorised_title); ?>
                </a>
              </li>
            <?php endif; ?>
          </ul>
        </div>
        <div class="tab-content">
          <div id="<?php echo esc_attr($tab_id); ?>-recent"
            role="tabpanel"
            aria-labelledby="<?php echo esc_attr($tab_id); ?>-recent-tab"
            aria-hidden="<?php echo $is_recent_active ? 'false' : 'true'; ?>"
            class="tab-pane <?php echo $is_recent_active ? 'active' : ''; ?>">

            <?php
            covernews_render_posts('recent', $show_excerpt, $excerpt_length, $number_of_posts);
            ?>
          </div>
          <div id="<?php echo esc_attr($tab_id); ?>-popular"
            role="tabpanel"
            aria-labelledby="<?php echo esc_attr($tab_id); ?>-popular-tab"
            aria-hidden="<?php echo $is_recent_active ? 'true' : 'false'; ?>"
            class="tab-pane <?php echo $is_recent_active ? '' : 'active'; ?>">

            <?php
            covernews_render_posts('popular', $show_excerpt, $excerpt_length, $number_of_posts);
            ?>
          </div>
          <?php if ($enable_categorised_tab == 'true'): ?>
            <div id="<?php echo esc_attr($tab_id); ?>-categorised"
              role="tabpanel"
              aria-labelledby="<?php echo esc_attr($tab_id); ?>-categorised-tab"
              aria-hidden="<?php echo $is_recent_active ? 'true' : 'false'; ?>"
              class="tab-pane <?php echo $is_recent_active ? '' : 'active'; ?>">
              <?php
              covernews_render_posts('categorised', $show_excerpt, $excerpt_length, $number_of_posts, $category);
              ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    <?php
      // close the widget container
      echo $args['after_widget'];
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

      $enable_categorised_tab = array(
        'true' => __('Yes', 'covernews'),
        'false' => __('No', 'covernews')

      );

      $options = array(
        'false' => __('No', 'covernews'),
        'true' => __('Yes', 'covernews')

      );


      // generate the text input for the title of the widget. Note that the first parameter matches text_fields array entry

    ?><h4><?php _e('Latest Posts', 'covernews'); ?></h4><?php
                                                        echo parent::covernews_generate_text_input('covernews-tabbed-latest-posts-title', __('Title', 'covernews'), __('Latest', 'covernews')); ?>

      <h4><?php _e('Popular Posts', 'covernews'); ?></h4><?php
                                                          echo parent::covernews_generate_text_input('covernews-tabbed-popular-posts-title', __('Title', 'covernews'), __('Popular', 'covernews'));



                                                          $categories = covernews_get_terms();
                                                          if (isset($categories) && !empty($categories)) {
                                                          ?><h4><?php _e('Categorised Posts', 'covernews'); ?></h4>
      <?php
                                                            echo parent::covernews_generate_select_options('covernews-enable-categorised-tab', __('Enable Categorised Tab', 'covernews'), $enable_categorised_tab);
                                                            echo parent::covernews_generate_text_input('covernews-tabbed-categorised-posts-title', __('Title', 'covernews'), __('Trending', 'covernews'));
                                                            echo parent::covernews_generate_select_options('covernews-select-category', __('Select category', 'covernews'), $categories);
                                                          }
      ?><h4><?php _e('Settings for all tabs', 'covernews'); ?></h4><?php
                                                                    echo parent::covernews_generate_select_options('covernews-show-excerpt', __('Show excerpt', 'covernews'), $options);
                                                                  }
                                                                }
                                                              endif;
