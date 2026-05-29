<?php

/**
 * Enqueue scripts and styles
 *
 * @package omma
 */

/**
 * CSS crítico inlineado en <head> (priority 1, antes que todo).
 *
 * Contiene solo los estilos mínimos para mostrar el preloader inmediatamente
 * y evitar FOUC mientras el CSS principal carga de forma no bloqueante.
 * Compilado desde assets/sass/critical.scss → assets/dist/css/critical.css
 */
function omma_inline_critical_css()
{
	$critical_file = get_template_directory() . '/assets/dist/css/critical.css';
	if ( ! file_exists( $critical_file ) ) {
		return;
	}
	$fonts_uri = get_template_directory_uri() . '/assets/dist/fonts';
	$css       = str_replace( 'OMMA_FONTS_URI', esc_url( $fonts_uri ), file_get_contents( $critical_file ) );
	echo '<style id="critical-css">' . $css . '</style>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput
}
add_action( 'wp_head', 'omma_inline_critical_css', 1 );


/**
 * Enqueue de assets públicos del tema.
 */
function omma_enqueue_assets()
{
	$theme_uri  = get_template_directory_uri();
	$theme_path = get_template_directory();

	// CSS principal — se encola para que WordPress gestione dependencias y versiones,
	// pero el tag se reemplaza por preload no bloqueante (ver filtro style_loader_tag abajo).
	$css_main = '/assets/dist/css/all.css';
	wp_enqueue_style(
		'omma-all',
		$theme_uri . $css_main,
		array(),
		filemtime( $theme_path . $css_main )
	);

	// Script principal (bundle.js)
	$js_bundle = '/assets/dist/js/bundle.js';
	wp_enqueue_script(
		'omma-js',
		$theme_uri . $js_bundle,
		array(),
		filemtime( $theme_path . $js_bundle ),
		true // carga en footer
	);

	wp_localize_script( 'omma-js', 'ommaAjax', [
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'omma_contact_submit' ),
	] );
}
add_action( 'wp_enqueue_scripts', 'omma_enqueue_assets', 20 );


/**
 * CSS no bloqueante: convierte el <link rel="stylesheet"> del CSS principal
 * en un preload con onload para no bloquear el render.
 *
 * El CSS crítico inlineado arriba evita el FOUC durante la carga asíncrona.
 * El <noscript> garantiza que el CSS carga igualmente sin JavaScript.
 */
add_filter( 'style_loader_tag', function ( $tag, $handle, $href, $media ) {
	if ( 'omma-all' !== $handle ) {
		return $tag;
	}

	$preload  = '<link rel="preload" href="' . esc_url( $href ) . '" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n";
	$noscript = '<noscript>' . $tag . '</noscript>' . "\n";

	return $preload . $noscript;
}, 10, 4 );


/**
 * Añade defer + data-cookieconsent="ignore" al script principal.
 */
add_filter( 'script_loader_tag', function ( $tag, $handle, $src ) {
	if ( 'omma-js' === $handle ) {
		$tag = str_replace(
			'<script ',
			'<script defer data-cookieconsent="ignore" ',
			$tag
		);
	}
	return $tag;
}, 10, 3 );


/**
 * CSS del editor de bloques (Gutenberg / ACF).
 */
function omma_enqueue_editor_assets()
{
	$theme_uri  = get_template_directory_uri();
	$theme_path = get_template_directory();

	$admin_css = '/assets/dist/css/admin.css';
	if ( file_exists( $theme_path . $admin_css ) ) {
		wp_enqueue_style(
			'omma-admin',
			$theme_uri . $admin_css,
			array(),
			filemtime( $theme_path . $admin_css )
		);
	}
}
add_action( 'enqueue_block_editor_assets', 'omma_enqueue_editor_assets' );


/**
 * Script nativo de WP para hilos de comentarios.
 */
function omma_enqueue_comment_reply()
{
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'omma_enqueue_comment_reply' );
