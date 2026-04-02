<?php
/**
 * The template for displaying all single posts of the "video" post type.
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="container site-main" role="main">

    <?php
    while ( have_posts() ) :
        the_post();

        // Get the custom field value
        $video_url = get_post_meta(get_the_ID(), 'video_url', true);

        if ($video_url) {
            // Check if it's a YouTube or Vimeo URL and format accordingly
            if (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) {
                // Format YouTube embed URL
                $video_url = preg_replace(
                    '/\A.*(youtu\.be\/|youtube\.com\/(watch\?v=|embed\/))([^\?&"\'>]+).*/i',
                    'https://www.youtube.com/embed/$3',
                    $video_url
                );
            } elseif (strpos($video_url, 'vimeo.com') !== false) {
                // Format Vimeo embed URL
                $video_url = preg_replace(
                    '/\A.*(vimeo\.com\/(video\/|channels\/[^\?&"\'>]*\/|groups\/[^\?&"\'>]*\/videos\/)?([^\?&"\'>]+)).*/i',
                    'https://player.vimeo.com/video/$3',
                    $video_url
                );
            }
        }

        // Display the video player
        if ($video_url) {
            echo '<div class="mx-auto" style="max-width: 960px;">';
            echo '<div class="relative" style="padding-bottom: 56.25%; height: 0;">';
            echo '<iframe class="absolute top-0 left-0 w-full h-full" src="' . esc_url($video_url) . '" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
            echo '</div>';
            echo '</div>';
        }

        // Display the post content
        echo '<div class="mx-auto my-8 prose max-w-none">';
        the_content();
        echo '</div>';

        // If comments are open or we have at least one comment, load up the comment template.
        if ( comments_open() || get_comments_number() ) :
            comments_template();
        endif;

    endwhile; // End of the loop.
    ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
