<?php

/**
 * Lista blanca de bloques Gutenberg permitidos.
 *
 * - pages / posts: bloques core + bloques omma genéricos
 * - case-studies:  bloques core + bloques exclusivos de CS
 *
 * @package omma
 */

add_filter( 'allowed_block_types_all', function ( $allowed_blocks, $editor_context ) {

	if ( empty( $editor_context->post ) ) {
		return $allowed_blocks;
	}

	$post_type = $editor_context->post->post_type;

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

	// ── Bloques para pages / posts ────────────────────────────────────────────

	if ( in_array( $post_type, [ 'page', 'post' ], true ) ) {
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
			// About
			'acf/block-about-intro',
			'acf/block-about-ceo',
			'acf/block-about-team',
			// Services
			'acf/block-services-intro',
			'acf/block-services-list',
			// Case Studies — página listado
			'acf/block-cs-page-header',
			'acf/block-cs-grid',
			// News — página listado
			'acf/block-news-page-header',
			'acf/block-news-grid',
		];

		return array_merge( $core, $omma );
	}

	// ── Bloques exclusivos para news ─────────────────────────────────────────

	if ( $post_type === 'news' ) {
		return $core;
	}

	// ── Bloques exclusivos para case-studies ──────────────────────────────────

	if ( $post_type === 'case-studies' ) {
		$cs = [
			'acf/block-cs-header',
			'acf/block-cs-details',
			'acf/block-cs-images',
			'acf/block-cs-extra-info',
			'acf/block-cs-data',
			'acf/block-cs-more-projects',
		];

		return array_merge( $core, $cs );
	}

	return $allowed_blocks;

}, 10, 2 );
