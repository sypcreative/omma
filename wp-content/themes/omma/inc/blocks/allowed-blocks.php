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
		'acf/hero-landing',
		'acf/block-hero-landing',
		'acf/block-clients',
		'acf/block-best-projects',
		'acf/block-services-home',
		'acf/block-testimonies',
		'acf/block-hero-about',
		'acf/block-title-history',
		'acf/block-image',
		'acf/block-images',
		'acf/block-team',
		'acf/block-videos',
		'acf/block-paragraphs',
		'acf/block-mission',
		'acf/block-hero-services',
		'acf/block-timeline-services',
		'acf/block-works',
		'acf/block-description-single',
		'acf/block-objective-single',
		'acf/block-gallery-single',
		'acf/block-related-projects',
		'acf/block-contact',
		'acf/block-gallery',
		'acf/block-sectors',
		'acf/block-intro',
		'acf/block-types',
		'acf/block-what-is',
		'acf/block-graph',
		'acf/block-about',
	];

	return array_merge($core, $omma);
}, 10, 2);
