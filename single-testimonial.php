<?php get_header(); ?>

<div class="container mx-auto my-8">
    <?php if ( have_posts() ) : ?>
        <?php while ( have_posts() ) : the_post();  // Ensure that the_post() is correctly called ?>
            <div class="container px-4 py-8 mx-auto">
                <h3><?php the_title(); ?></h3>
                <div class="testimonial blockquote">
                    <?php the_content(); ?>
                </div>
                <?php
                $testimonial_source = get_post_meta( get_the_ID(), 'testimonial_source', true );
                if ( ! empty( $testimonial_source ) ) : ?>
                    <p class="text-sm text-gray-500">Source: <?php echo esc_html( $testimonial_source ); ?></p>
                <?php endif; ?>
				<?php
				// output the post date
				$post_date = get_the_date( 'F j, Y' );
				?>
				<p class="text-sm text-gray-500">On: <?php echo esc_html( $post_date ); ?></p>
            </div>
            <?php
            ?>
        <?php endwhile; ?>
    <?php else : ?>
        <p>No testimonials found.</p>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
