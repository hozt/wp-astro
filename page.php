
<div class="">
<?php
get_header();

while ( have_posts() ) : the_post();
    $banner_image = get_post_meta(get_the_ID(), 'banner_image', true);
?>
    <div class="container px-4 mx-auto">
        <div class="page-content">
            <?php the_content(); ?>
        </div>
    </div>
<?php
endwhile;

get_footer();

?>
</div>