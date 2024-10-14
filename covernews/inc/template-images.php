<?php
/**
 * Custom template images for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package CoverNews
 */


if ( ! function_exists( 'covernews_post_thumbnail' ) ) :
    /**
     * Displays an optional post thumbnail.
     *
     * Wraps the post thumbnail in an anchor element on index views, or a div
     * element when on single views.
     */
    function covernews_post_thumbnail() {
        if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
            return;
        }

        global $post;

        if ( is_singular() ) :

            $theme_class = covernews_get_option('global_image_alignment');
            $post_image_alignment = get_post_meta($post->ID, 'covernews-meta-image-options', true);
            $post_class = !empty($post_image_alignment) ? $post_image_alignment : $theme_class;

            if ( $post_class != 'no-image' ):
                ?>
                <div class="post-thumbnail <?php echo esc_attr($post_class); ?>">
                    <?php the_post_thumbnail('large'); ?>
                </div>
            <?php endif; ?>

        <?php else :
            $archive_layout = covernews_get_option('archive_layout');
            $archive_layout = $archive_layout;
            $archive_class = '';
            if ($archive_layout == 'archive-layout-list') {
                $archive_image_alignment = covernews_get_option('archive_image_alignment');
                $archive_class = $archive_image_alignment;
                $archive_image = 'medium';
            } elseif ($archive_layout == 'archive-layout-full') {
                $archive_image = 'large';
            } else {
                $archive_image = 'post-thumbnail';
            }

            ?>
            <div class="post-thumbnail <?php echo esc_attr($archive_class); ?>">
                <a href="<?php the_permalink(); ?>" aria-hidden="true">
                    <?php
                    the_post_thumbnail( $archive_image, array(
                        'alt' => the_title_attribute( array(
                            'echo' => false,
                        ) ),
                    ) );
                    ?>
                </a>
            </div>

        <?php endif; // End is_singular().
    }
endif;



if (!function_exists('covernews_the_post_thumbnail')) :
    /**
     * Displays an optional post thumbnail.
     *
     * Wraps the post thumbnail in an anchor element on index views, or a div
     * element when on single views.
     */
    function covernews_the_post_thumbnail($covernews_thumbnail_size, $covernews_post_id, $return = false)
    {

       
        if (get_the_post_thumbnail($covernews_post_id) != '') {            
            if ($return) {
                return get_the_post_thumbnail($covernews_post_id, $covernews_thumbnail_size);
            } else {
                the_post_thumbnail($covernews_thumbnail_size);
            }
        } else {            
            $covernews_img_url = '';
            ob_start();
            ob_end_clean();
            $covernews_post_content = get_post_field('post_content', $covernews_post_id);
            $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $covernews_post_content, $matches);
            
            if (isset($matches[1][0])) {                
                $covernews_img_id = covernews_find_post_id_from_path($matches[1][0]);                
                $covernews_img_url = wp_get_attachment_image_src($covernews_img_id, $covernews_thumbnail_size);
                if (isset($covernews_img_url[0])) {                    
                    if ($return) {
                        return wp_get_attachment_image($covernews_img_id, $covernews_thumbnail_size);
                    } else {
                        echo wp_get_attachment_image($covernews_img_id, $covernews_thumbnail_size);
                    }
                } else {
                    if (@getimagesize($matches[1][0])) {
                        if ($return) {
                            ob_start();
?>
                        <img src="<?php echo esc_url($matches[1][0]); ?>" alt="<?php echo esc_attr(get_the_title($covernews_post_id)); ?>" />
<?php                            $covernews_img_html = ob_get_contents();
                                    ob_end_clean();
                                    return $covernews_img_html;

                        }
                        else { ?>
                        <img src="<?php echo esc_url($matches[1][0]); ?>" alt="<?php echo esc_attr(get_the_title($covernews_post_id)); ?>"/>
                       <?php
                        }

                    }
                }
            }
        }
    }
endif;


/**
 * Find the post ID for a file PATH or URL
 *
 * @param string $path
 *
 * @return int
 */
function covernews_find_post_id_from_path($path)
{
    
    
    // Extract the file name from the path
    $file_name = basename($path);

    // Check if the file name matches attachment file naming conventions
    if (preg_match('/^(.+)-\d+x\d+\.(jpg|jpeg|png|gif)$/i', $file_name, $matches)) {
        $attachment_slug = $matches[1];
        
        // Remove year/month folders from the slug
        $attachment_slug = preg_replace('/^\d{4}\/\d{2}\//', '', $attachment_slug);
        
        // Retrieve the attachment ID based on the attachment slug
        global $wpdb;
        $attachment_id = $wpdb->get_var($wpdb->prepare(
            "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type = 'attachment'",
            $attachment_slug
        ));
        
        return $attachment_id;
    }

    return 0;
}