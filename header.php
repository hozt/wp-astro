<?php
    $mobileLogo = get_theme_mod('mobile_logo');
    $logoId = get_theme_mod('custom_logo');
    $logoAttachment = wp_get_attachment_image_src($logoId, 'full');
    $logoUrl = (isset($logoAttachment[0]) ? $logoAttachment[0] : '');
    $postId = get_the_ID();
    $bannerImageUrl = get_post_meta($postId, 'banner_image', true);
    $subtitle = get_post_meta($postId, 'subtitle', true);
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

    <?php wp_head(); ?>
</head>
<?php global $no_header; ?>

<body <?php body_class( 'bg-white text-gray-900 antialiased' ); ?>>

<?php do_action( 'tailpress_site_before' ); ?>

<div id="page" class="<?php echo (!isset($no_header) && $no_header) ? 'flex flex-col' : '';?>">

    <?php do_action( 'tailpress_header' ); ?>

    <?php if (!isset($no_header)) : ?>
    <header class="mx-auto top-header">
        <div class="flex items-center justify-center">
            <?php if ( has_custom_logo() ) : ?>
            <a href="/">
                <img src="<?php echo esc_url( $logoUrl ); ?>" alt="<?php echo get_bloginfo( 'name' ); ?>" class="hidden logo-desktop lg:block" />
                <img src="<?php echo esc_url( $mobileLogo ); ?>" alt="<?php echo get_bloginfo( 'name' ); ?>" class="lg:hidden logo-mobile" />
            </a>
            <?php endif; ?>
        </div>
        <nav id="primary-menu" class="mt-6">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'primary',
                    'container'      => false,
                    'menu_class'     => 'flex-wrap p-0 mx-auto list-none lg:flex main-menu md:justify-center',
                    'fallback_cb'    => false,
                )
            );
            ?>
        </nav>
    </header>
    <div id="content" class="flex-grow site-content">
    <?php else : ?>
    <div>
    <?php endif; ?>

        <?php do_action( 'tailpress_content_start' ); ?>
        <div class="<?php echo ($bannerImageUrl ? 'banner-image' : 'no-banner-image'); ?> banner relative">
            <?php if ($bannerImageUrl) : ?>
                <img src="<?php echo esc_url($bannerImageUrl); ?>" alt="Banner Image" class="absolute inset-0 object-cover w-full h-full" />
            <?php endif; ?>
            <div class="banner-text">
                <div>
                    <h1><?php the_title(); ?></h1>
                    <?php if ($subtitle) : ?>
                        <h2><?php echo $subtitle; ?></h2>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <main class="main-body content">
