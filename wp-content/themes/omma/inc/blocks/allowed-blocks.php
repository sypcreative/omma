<?php

/**
 * Lista blanca de bloques Gutenberg permitidos.
 *
 * Restringe el editor a los bloques custom del tema + un subconjunto
 * de bloques nativos de uso general. Evita que el cliente rompa el diseño
 * insertando bloques core sin estilos.
 *
 * @package omma
 */

add_filter('allowed_block_types_all', function ($allowed_blocks, $editor_context) {

	if (empty($editor_context->post)) {
		return $allowed_blocks;
	}

	$post_type = $editor_context->post->post_type;

	if (!in_array($post_type, ['page', 'proyectos', 'post'], true)) {
		return $allowed_blocks;
	}

	$core = [
		'core/paragraph',
		'core/heading',
		'core/image',
		'core/list',
		'core/list-item',
		'core/quote',
		'core/html',
		'core/spacer',
		'core/separator',
		'core/embed',
	];

	// Bloques registrados en inc/acf-config.php.
	// El slug de ACF es acf/{name} donde {name} es el 'name' del array de acf_register_block.
	$omma = [
		// Home
		'acf/block-hero-home',
		'acf/block-data',
		'acf/block-map',
		'acf/block-logos',
		'acf/block-ecosystem',
		'acf/block-capabilities',
		// Landing
		'acf/hero-landing',
		'acf/block-sectors',
		'acf/block-types',
		'acf/block-what-is',
		'acf/block-about',
		'acf/block-graph',
		'acf/block-contact',
		'acf/block-intro',
	];

	return array_merge($core, $omma);
}, 10, 2);
