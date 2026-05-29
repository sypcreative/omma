<?php

/**
 * Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 * @package omma
 */

// ── Constantes ────────────────────────────────────────────────────────────────

if (! defined('OMMA_PATH')) {
	define('OMMA_PATH', get_template_directory());
}
if (! defined('OMMA_URI')) {
	define('OMMA_URI', get_template_directory_uri());
}
if (! defined('OMMA_VERSION')) {
	$theme = wp_get_theme();
	define('OMMA_VERSION', $theme->get('Version') ?: '1.0.0');
}

// ── Setup ─────────────────────────────────────────────────────────────────────

function omma_setup(): void
{
	load_theme_textdomain('omma', OMMA_PATH . '/languages');

	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
	add_theme_support('align-wide');

	register_nav_menus([
		'menu-izquierda' => esc_html__('Menú izquierdo', 'omma'),
		'menu-derecha'   => esc_html__('Menú derecho', 'omma'),
		'menu-footer'    => esc_html__('Menú footer', 'omma'),
		'menu-mobile'    => esc_html__('Menú Mobile', 'omma'),
	]);

	add_image_size('description-block', 700, 875, true);
}
add_action('after_setup_theme', 'omma_setup');

function omma_content_width(): void
{
	$GLOBALS['content_width'] = apply_filters('omma_content_width', 1200);
}
add_action('after_setup_theme', 'omma_content_width', 0);

// ── Includes ──────────────────────────────────────────────────────────────────

function omma_require(string $relative_path): void
{
	$path = OMMA_PATH . $relative_path;
	if (file_exists($path)) {
		require_once $path;
	}
}

omma_require('/inc/template-enqueued.php');      // Assets
omma_require('/inc/template-functions.php');     // Helpers de template
omma_require('/inc/template-tags.php');          // Tags de template

omma_require('/inc/custom-post-taxonomy.php');   // CPTs y taxonomías
omma_require('/inc/custom-config.php');          // ACF Options page + config global

omma_require('/inc/acf-config.php');             // Registro de bloques ACF (después de CPTs)
omma_require('/inc/blocks/allowed-blocks.php');  // Lista blanca de bloques Gutenberg

omma_require('/inc/navs/custom-nav-walker.php');
omma_require('/inc/navs/custom-nav-menu.php');

omma_require('/inc/gtm-functions.php');          // DataLayer GTM

omma_require('/inc/seo.php');                    // Dynamic meta tags, OG, Twitter Cards
omma_require('/inc/ajax/ajax-forms.php');        // Handler formulario de contacto

omma_require('/inc/admin/admin-columns.php');    // Columnas admin CPTs

if (defined('JETPACK__VERSION')) {
	omma_require('/inc/jetpack.php');
}

// ── Utilidades de depuración (solo en entorno local) ──────────────────────────

if (defined('WP_DEBUG') && WP_DEBUG && !function_exists('dump')) {
	function dump(mixed $data): void
	{
		echo '<pre style="white-space:pre-wrap;background:#000;color:#fff;padding:1rem;">';
		var_dump($data);
		echo '</pre>';
	}
}
