
</main>

<?php do_action( 'tailpress_content_end' ); ?>

</div>

<?php do_action( 'tailpress_content_after' );
global $no_header;
if (!isset($no_header)) : ?>
<footer class="mt-6">
    <div class="footer-inner">
        <div class="flex flex-wrap">
            <nav class="footer-menu">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'footer',
                        'container'      => false,
                        'menu_class'     => 'mt-4 md:flex md:flex-wrap md:mt-0',
                        'fallback_cb'    => false,
                    )
                );
                ?>
            </nav>
        </div>
    </div>
</footer>
<div class="copyright">
    Copyright &copy; <?php echo date_i18n( 'Y' );?> - <?php echo get_bloginfo( 'name' );?> all rights reserved
</div>

</div>
<?php endif; ?>

<?php wp_footer(); ?>

</body>
</html>
