<?php

/**
 * Header para el template Landing.
 * Cargado via get_header('landing') desde template-landing.php.
 *
 * Campos ACF requeridos en la página (group_landing_header):
 *   landing_nav_items  — repeater: landing_nav_label + landing_nav_anchor
 *   landing_nav_cta    — link
 *
 * @package omma
 */

$has_acf   = function_exists('get_field');
$nav_items = $has_acf ? (get_field('landing_nav_items') ?: []) : [];
$cta_link  = $has_acf ? get_field('landing_nav_cta') : null;
$site_logo = $has_acf ? get_field('site_logo_landing', 'option') : null;
?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="format-detection" content="telephone=no">
	<link rel="icon" type="image/png" href="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/favicon.png" sizes="150x50">
	<?php wp_head(); ?>
</head>

<body <?php body_class('is-landing'); ?> data-barba="wrapper" id="barba-wrapper">
	<?php wp_body_open(); ?>

	<header class="progress-nav" role="banner">
		<div class="progress-nav__inner">

			<!-- Logo -->
			<a href="<?php echo esc_url(home_url('/')); ?>" class="progress-nav__logo" aria-label="<?php echo esc_attr(get_bloginfo('name')); ?>">
				<?php if ($site_logo) : ?>
					<img
						src="<?php echo esc_url($site_logo['url']); ?>"
						alt="<?php echo esc_attr($site_logo['alt'] ?: get_bloginfo('name')); ?>"
						class="progress-nav__logo-img"
						width="<?php echo (int) $site_logo['width']; ?>"
						height="<?php echo (int) $site_logo['height']; ?>"
						decoding="async">
				<?php else : ?>
					<svg xmlns="http://www.w3.org/2000/svg" width="178" height="40" viewBox="0 0 178 40" fill="none" class="progress-nav__logo-svg" aria-hidden="true" focusable="false">
						<path d="M161.77 13.4645C161.142 14.0944 160.069 13.6483 160.069 12.7574V0H156.084V15C156.084 16.6569 154.746 18 153.096 18H138.154V22H150.862C151.749 22 152.194 23.0771 151.566 23.7071L142.722 32.5858L145.539 35.4142L154.384 26.5356C155.01 25.9075 156.078 26.3491 156.084 27.2347V40L160.069 40L160.069 25C160.069 23.3431 161.407 22 163.058 22H178V18H165.284C164.404 17.9936 163.964 16.9273 164.582 16.2985L164.587 16.2929L173.432 7.41421L170.614 4.58582L161.77 13.4645Z" fill="currentColor" />
						<path d="M16.084 37.178C6.27782 37.178 0 29.956 0 20.066C0 10.176 6.27782 3 16.084 3C25.8903 3 32.1681 10.176 32.1681 20.066C32.1681 29.956 25.8903 37.178 16.084 37.178ZM5.2697 20.066C5.2697 26.828 8.33987 32.808 16.084 32.808C23.8282 32.808 26.8984 26.828 26.8984 20.066C26.8984 13.304 23.8282 7.37 16.084 7.37C8.33987 7.37 5.2697 13.304 5.2697 20.066Z" fill="currentColor" />
						<path d="M45.478 37.178C38.3754 37.178 34.847 33.498 34.7095 28.714H39.246C39.4293 31.428 41.0789 33.544 45.4322 33.544C49.373 33.544 50.4269 31.796 50.4269 30.094C50.4269 27.15 47.3109 26.828 44.2866 26.184C40.2083 25.218 35.5343 24.022 35.5343 19.146C35.5343 15.098 38.7878 12.384 44.4241 12.384C50.8393 12.384 53.9095 15.834 54.2303 19.882H49.6938C49.373 18.088 48.4107 16.018 44.5157 16.018C41.4914 16.018 40.2083 17.214 40.2083 18.962C40.2083 21.4 42.8202 21.63 46.1195 22.366C50.4269 23.378 55.1009 24.62 55.1009 29.864C55.1009 34.418 51.6183 37.178 45.478 37.178Z" fill="currentColor" />
						<path d="M72.6642 21.492C72.6642 18.364 72.0227 16.248 68.5859 16.248C65.2408 16.248 63.1329 18.594 63.1329 22.136V36.534H58.6422V13.074H63.1329V16.018H63.2246C64.4618 14.224 66.6155 12.384 70.1439 12.384C73.3974 12.384 75.4136 13.856 76.3301 16.478H76.4217C78.1172 14.224 80.5 12.384 84.0742 12.384C88.7941 12.384 91.1769 15.236 91.1769 20.25V36.534H86.6862V21.492C86.6862 18.364 86.0447 16.248 82.6079 16.248C79.2628 16.248 77.1549 18.594 77.1549 22.136V36.534H72.6642V21.492Z" fill="currentColor" />
						<path d="M106.545 37.224C99.2594 37.224 94.8603 32.164 94.8603 24.804C94.8603 17.49 99.2594 12.338 106.591 12.338C113.831 12.338 118.23 17.444 118.23 24.758C118.23 32.118 113.831 37.224 106.545 37.224ZM99.5343 24.804C99.5343 29.68 101.734 33.498 106.591 33.498C111.357 33.498 113.556 29.68 113.556 24.804C113.556 19.882 111.357 16.11 106.591 16.11C101.734 16.11 99.5343 19.882 99.5343 24.804Z" fill="currentColor" />
					</svg>
				<?php endif; ?>
			</a>

			<!-- Nav con indicador de progreso -->
			<?php if ($nav_items) : ?>
				<div class="progress-nav__wrapper">
					<div data-progress-nav-list="" class="progress-nav__list">
						<div class="progress-nav__indicator" aria-hidden="true"></div>
						<div data-progress-nav-target="#top" class="progress-nav__btn is--before" aria-hidden="true"></div>

						<?php foreach ($nav_items as $i => $item) :
							$label  = esc_html($item['landing_nav_label']);
							$anchor = esc_attr(ltrim($item['landing_nav_anchor'], '#'));
						?>
							<a
								data-progress-nav-target="#<?php echo $anchor; ?>"
								href="#<?php echo $anchor; ?>"
								class="progress-nav__btn">
								<span class="progress-nav__btn-text"><?php echo ($i + 1); ?>. <?php echo $label; ?></span>
								<span class="progress-nav__btn-text is--duplicate" aria-hidden="true"><?php echo ($i + 1); ?>. <?php echo $label; ?></span>
							</a>
						<?php endforeach; ?>

						<div data-progress-nav-target="#bottom" class="progress-nav__btn is--after" aria-hidden="true"></div>
					</div>
				</div>
			<?php endif; ?>

			<!-- CTA -->
			<?php if ($cta_link) : ?>
				<a
					href="<?php echo esc_url($cta_link['url']); ?>"
					target="<?php echo esc_attr($cta_link['target'] ?: '_self'); ?>"
					class="progress-nav__contact-btn">
					<span class="progress-nav__btn-text"><?php echo esc_html($cta_link['title']); ?></span>
					<span class="progress-nav__btn-text is--duplicate" aria-hidden="true"><?php echo esc_html($cta_link['title']); ?></span>
				</a>
			<?php endif; ?>

		</div>
	</header>

	<main
		class="site-main"
		style="background: #001019"
		data-barba="container"
		data-barba-namespace="<?php echo esc_attr(barba_namespace()); ?>">