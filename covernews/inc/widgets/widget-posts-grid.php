<?php
if (!class_exists('CoverNews_Posts_Grid')) :
  /**
   * Adds CoverNews_Posts_Grid widget.
   */
  class CoverNews_Posts_Grid extends AFthemes_Widget_Base
  {
    /**
     * Sets up a new widget instance.
     *
     * @since 1.0.0
     */
    function __construct()
    {
      $this->text_fields = array('covernews-categorised-posts-title');
      $this->select_fields = array('covernews-select-category');

      $widget_ops = array(
        'classname' => 'covernews_double_col_categorised_posts grid-layout',
        'description' => __('Displays posts from selected category in a grid.', 'covernews'),
        'customize_selective_refresh' => false,
      );

      parent::__construct('covernews_double_col_categorised_posts', __('CoverNews Posts Grid', 'covernews'), $widget_ops);
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


      /** This filter is documented in wp-includes/default-widgets.php */
      $title = apply_filters('widget_title', $instance['covernews-categorised-posts-title'], $instance, $this->id_base);

      $category = !empty($instance['covernews-select-category']) ? $instance['covernews-select-category'] : '0';
      $show_excerpt = 'true';
      $excerpt_length = '25';
      $number_of_posts = 6;

      // open the widget container
      echo $args['before_widget'];
?>
      <?php if (!empty($title)): ?>
        <div class="em-title-subtitle-wrap">
          <?php if (!empty($title)): covernews_render_section_title($title); endif; ?>

        </div>
      <?php endif; ?>
      <?php
      $all_posts = covernews_get_posts($number_of_posts, $category);
      ?>
      <div class="widget-wrapper">
        <div class="row">
          <?php
          $count = 1;
          if ($all_posts->have_posts()) :
            while ($all_posts->have_posts()) : $all_posts->the_post();

              global $post;
              $thumbnail_size = 'medium';
              $covernews_post_id = $post->ID;
          ?>
              <div class="col-sm-4 second-wiz" data-mh="em-double-column">
                <div class="spotlight-post">

                  <figure class="categorised-article inside-img">
                    <div class="categorised-article-wrapper">
                      <div class="data-bg-hover data-bg-categorised read-bg-img">
                        <a href="<?php the_permalink(); ?>"
                          aria-label="<?php echo esc_attr(get_the_title($covernews_post_id)); ?>">
                          <?php covernews_the_post_thumbnail($thumbnail_size, $covernews_post_id);
                          ?>
                        </a>

                      </div>
                    </div>
                    <?php echo covernews_post_format($post->ID); ?>
                    <div class="figure-categories figure-categories-bg">

                      <?php covernews_post_categories(); ?>
                    </div>
                  </figure>

                  <figcaption>

                    <h3 class="article-title article-title-1">
                      <a href="<?php the_permalink(); ?>">
                        <?php the_title(); ?>
                      </a>
                    </h3>
                    <div class="grid-item-metadata">
                      <?php covernews_post_item_meta(); ?>
                    </div>
                    <?php if ($show_excerpt != 'false'): ?>
                      <div class="full-item-discription">
                        <div class="post-description">
                          <?php if (absint($excerpt_length) > 0) : ?>
                            <?php
                            $excerpt = covernews_get_excerpt($excerpt_length, get_the_content(), $covernews_post_id);
                            if (!empty($excerpt)) {
                              echo wp_kses_post(wpautop($excerpt));
                            }
                            ?>
                          <?php endif; ?>
                        </div>
                      </div>
                    <?php endif; ?>
                  </figcaption>
                </div>
              </div>

          <?php
              $count++;
            endwhile;
          endif;
          wp_reset_postdata();
          ?>

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

      $categories = covernews_get_terms();

      if (isset($categories) && !empty($categories)) {
        // generate the text input for the title of the widget. Note that the first parameter matches text_fields array entry
        echo parent::covernews_generate_text_input('covernews-categorised-posts-title', __('Title', 'covernews'), __('Posts Grid', 'covernews'));
        echo parent::covernews_generate_select_options('covernews-select-category', __('Select category', 'covernews'), $categories);
      }
    }
  }
endif;
