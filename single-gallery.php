<?php
/*
* Template Name: Gallery Post
* Template Post Type: gallery
*/

get_header();

while ( have_posts() ) : the_post();
    $image_ids = get_post_meta(get_the_ID(), '_gallery_image_ids', true);
    $image_ids = explode(',', $image_ids);
?>
    <div class="container px-4 py-8 mx-auto">
        <h1 class="mb-6 text-3xl font-bold"><?php the_title(); ?></h1>
        <div class="page-content">
            <?php the_content(); ?>
        </div>
        <?php if (!empty($image_ids)): ?>
            <div class="grid grid-cols-3 gap-4">
                <?php foreach ($image_ids as $image_id):
                    $image_url = wp_get_attachment_url($image_id);
                    if (!empty($image_url)): ?>
                        <div class="relative">
                            <img src="<?php echo esc_url($image_url); ?>" alt="" class="max-w-44 max-h-44">
                        </div>
                    <?php endif;
                endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
<?php
endwhile;

get_footer();
