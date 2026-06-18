<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package OM-MA
 */

$menu_principal = [
	'theme_location' => 'menu-principal',
	'container'      => 'ul',
	'menu_class'     => 'navbar-nav mx-auto py-2 py-md-0',
	'walker'         => new PrimaryMenu_Walker_Nav_Menu(),
	'fallback_cb'    => false,
];

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="format-detection" content="telephone=no">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/assets/img/favicon.png" sizes="150x50">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> data-theme-status="light" data-barba="wrapper" data-cursor="<?php echo (function_exists('get_field') && get_field('opciones_sitio_cursor_basic', 'option')) ? 'basic' : 'none'; ?>" id="barba-wrapper">
	<?php wp_body_open(); ?>
	<?php if (function_exists('get_field') && get_field('opciones_sitio_cursor_basic', 'option')) : ?>
		<div class="cursor"></div>
	<?php endif; ?>
	<div id="page" class="site">
		<!-- Nav Cabecera -->
		<header class="position-fixed w-100 z-100">
			<div class="px-5">
				<nav class="site-nav d-none d-md-flex" id="menuCabecera">
					<?php $logo = function_exists('get_field') ? get_field('site_logo', 'option') : null; ?>
					<a href="<?= get_home_url(); ?>" class="site-nav__brand">
						<?php if ($logo) : ?>
							<img
								src="<?= esc_url($logo['url']); ?>"
								alt="<?= esc_attr($logo['alt'] ?: get_bloginfo('name')); ?>"
								width="<?= (int) $logo['width']; ?>"
								height="<?= (int) $logo['height']; ?>"
								decoding="async">
						<?php endif; ?>
					</a>
					<?php
					wp_nav_menu([
						'theme_location'  => 'menu-header',
						'container'       => false,
						'items_wrap'      => '<ul class="site-nav__list">%3$s</ul>',
						'depth'           => 1,
						'fallback_cb'     => false,
						'list_item_class' => 'site-nav__item',
						'link_class'      => 'site-nav__link',
					]);
					?>
				</nav>
				<!-- Navbar móvil -->
				<nav data-navigation-status="not-active" class="mobile-nav d-md-none" aria-label="Menú principal">

					<!-- Overlay oscuro -->
					<div data-navigation-toggle="close" class="mobile-nav__overlay" aria-hidden="true"></div>

					<!-- Card centrada -->
					<div class="mobile-nav__card">
						<div class="mobile-nav__card-bg"></div>

						<!-- Cabecera (siempre visible) -->
						<div class="mobile-nav__header">
							<a href="<?= get_home_url(); ?>" class="mobile-nav__logo">
								<?php $mob_logo = function_exists('get_field') ? (get_field('site_logo_positive', 'option') ?: get_field('site_logo', 'option')) : null; ?>
								<?php if ($mob_logo) : ?>
									<img
										src="<?= esc_url($mob_logo['url']); ?>"
										alt="<?= esc_attr($mob_logo['alt'] ?: get_bloginfo('name')); ?>"
										height="28"
										decoding="async">
								<?php endif; ?>
							</a>
							<button data-navigation-toggle="toggle" class="mobile-nav__hamburger" aria-label="Abrir menú" aria-expanded="false">
								<svg class="mobile-nav__hamburger-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
									<path d="M4.69995 16.0998C4.69995 14.3325 6.13264 12.8998 7.89995 12.8998C9.66726 12.8998 11.1 14.3325 11.1 16.0998C11.1 17.8671 9.66726 19.2998 7.89995 19.2998C6.13264 19.2998 4.69995 17.8671 4.69995 16.0998Z" fill="currentColor" />
									<rect x="5.69995" y="5.69995" width="4.8" height="4.8" rx="2.4" fill="currentColor" />
									<rect x="13.5" y="13.5" width="4.8" height="4.8" rx="2.4" fill="currentColor" />
									<rect x="12.8999" y="4.69995" width="6.4" height="6.4" rx="3.2" fill="currentColor" />
								</svg>
								<svg class="mobile-nav__hamburger-close" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
									<path d="M1.5 1.5l13 13M14.5 1.5l-13 13" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" />
								</svg>
							</button>
						</div>

						<!-- Contenido expandible -->
						<div class="mobile-nav__content">
							<div class="mobile-nav__inner">

								<?php
								wp_nav_menu([
									'theme_location' => 'menu-header',
									'container'      => false,
									'items_wrap'     => '<ul class="mobile-nav__ul text-center text-uppercase">%3$s</ul>',
									'fallback_cb'    => false,
								]);
								?>

								<!-- Banner CTA Contacto -->
								<div class="mobile-nav__banner-w">
									<a href="<?= esc_url(get_permalink(get_page_by_path('contacto'))); ?>" class="mobile-nav__banner">
										<div class="mobile-nav__banner-row">
											<div data-css-marquee-list class="mobile-nav__banner-track">
												<?php for ($i = 0; $i < 6; $i++) : ?>
													<div class="mobile-nav__banner-item">Contacto</div>
												<?php endfor; ?>
											</div>
											<div data-css-marquee-list class="mobile-nav__banner-track">
												<?php for ($i = 0; $i < 6; $i++) : ?>
													<div class="mobile-nav__banner-item">Contacto</div>
												<?php endfor; ?>
											</div>
										</div>
									</a>
								</div>

							</div>
						</div>
					</div>

				</nav>
			</div>
		</header>
		<main
			class="site-main bg-dark"
			data-barba="container"
			data-barba-namespace="<?= esc_attr(barba_namespace()); ?>">