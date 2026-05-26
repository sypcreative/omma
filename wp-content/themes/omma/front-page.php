<?php

/**
 * Front Page
 *
 * Si la página de inicio tiene el template "Landing" asignado,
 * delega completamente en template-landing.php.
 * En cualquier otro caso, renderiza el home normal.
 */

if ( is_page_template( 'template-landing.php' ) ) {
	include( locate_template( 'template-landing.php' ) );
	exit;
}

$hero = function_exists('get_field') ? get_field('hero_general_selector') : null;

get_header(); ?>


	<?php
	if ($hero == "home") {
		get_template_part('template-parts/blocks/2-home/block-home-hero');
	} elseif ($hero == "nosotros") {
		get_template_part('template-parts/blocks/4-about/block-hero-nosotros');
	} else if ($hero == "blog") {
		get_template_part('template-parts/blocks/3-blog/block-hero-blog');
	} else {
		// echo '<div class="py-5"></div>';
	}
	the_content();
	?>

<?php get_footer(); ?>
