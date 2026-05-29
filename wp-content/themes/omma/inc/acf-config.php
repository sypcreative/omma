<?php

/** @noinspection PhpUnused */

/**
 * Esta función crea categorías para los bloques de Gutenberg y los coloca en primera posición
 */
function omma_blocks_category($categories): array
{

	$custom_block = array(
		[
			'slug'  => 'OM-MA-landing',
			'title' => __('OM-MA | Landing', 'OM-MA'),
		],
		[
			'slug'  => 'OM-MA-home',
			'title' => __('OM-MA | Home', 'OM-MA'),
		],
		[
			'slug'  => 'OM-MA-about',
			'title' => __('OM-MA | About', 'OM-MA'),
		],
		[
			'slug'  => 'OM-MA-services',
			'title' => __('OM-MA | Services', 'OM-MA'),
		],
		[
			'slug'  => 'OM-MA-works',
			'title' => __('OM-MA | Works', 'OM-MA'),
		],
		[
			'slug'  => 'OM-MA-contact',
			'title' => __('OM-MA | Contact', 'OM-MA'),
		],
		[
			'slug'  => 'OM-MA-single-works',
			'title' => __('OM-MA | Single Productos', 'OM-MA'),
		],
	);

	$categories_sorted = array();
	foreach ($custom_block as $category) {
		$categories_sorted[] = $category;
	}
	foreach ($categories as $category) {
		$categories_sorted[] = $category;
	}
	return $categories_sorted;
}

add_action('block_categories_all', 'omma_blocks_category', 10, 2);
//https://stackoverflow.com/questions/65886937/show-preview-image-for-custom-gutenberg-blocks
function omma_blocks(): void
{

	if (function_exists('acf_register_block')) {
		/**
		 * Hero Landing
		 */
		acf_register_block(
			array(
				'name'            => 'hero-landing',
				'title'           => __('Hero Landing', 'OMMA'),
				'post_types' 		=> ['page'],
				'description'     => __('Bloque que contiene la cabecera de la landing de I+D+i.', 'OMMA'),
				'render_template' => 'template-parts/blocks/0-landing/block-hero-landing.php',
				'category'        => 'OM-MA-landing',
				'mode'            => 'edit',
				'icon'            => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bookmarks" viewBox="0 0 16 16"><path d="M2 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v11.5a.5.5 0 0 1-.777.416L7 13.101l-4.223 2.815A.5.5 0 0 1 2 15.5V4zm2-1a1 1 0 0 0-1 1v10.566l3.723-2.482a.5.5 0 0 1 .554 0L11 14.566V4a1 1 0 0 0-1-1H4z"/><path d="M4.268 1H12a1 1 0 0 1 1 1v11.768l.223.148A.5.5 0 0 0 14 13.5V2a2 2 0 0 0-2-2H6a2 2 0 0 0-1.732 1z"/> </svg>',
				'keywords'        => array('categorias', 'OM-MA'),
				'render_callback' => 'render_preview',
				'example'         => array(
					'attributes' => array(
						'mode' => 'preview', // Important!
						'data' => array(
							'image' => '<img src="' . get_template_directory_uri() . '/assets/dist/img/blocks/hero-landing.png' . '" style="display: block; margin: 0 auto;  max-width:100%;">'
						),
					),
				),
			)
		);

		/**
		 * Block Sectors
		 */
		acf_register_block(
			array(
				'name'            => 'block-sectors',
				'title'           => __('Block Sectors', 'OMMA'),
				'post_types'      => ['page'],
				'description'     => __('Bloque con título y listado de sectores con imagen.', 'OMMA'),
				'render_template' => 'template-parts/blocks/0-landing/block-sectors.php',
				'category'        => 'OM-MA-landing',
				'mode'            => 'edit',
				'icon'            => 'grid-view',
				'keywords'        => array('sectors', 'OM-MA'),
				'render_callback' => 'render_preview',
			)
		);

		/**
		 * Block Types
		 */
		acf_register_block(
			array(
				'name'            => 'block-types',
				'title'           => __('Block Types', 'OMMA'),
				'post_types'      => ['page'],
				'description'     => __('Bloque con título, enlace, subtítulo y lista de textos.', 'OMMA'),
				'render_template' => 'template-parts/blocks/0-landing/block-types.php',
				'category'        => 'OM-MA-landing',
				'mode'            => 'edit',
				'icon'            => 'list-view',
				'keywords'        => array('types', 'lista', 'OM-MA'),
				'render_callback' => 'render_preview',
			)
		);

		/**
		 * Block What Is
		 */
		acf_register_block(
			array(
				'name'            => 'block-what-is',
				'title'           => __('Block What Is', 'OMMA'),
				'post_types'      => ['page'],
				'description'     => __('Bloque con título, texto, diagrama de nodos y tarjetas apiladas.', 'OMMA'),
				'render_template' => 'template-parts/blocks/0-landing/block-what-is.php',
				'category'        => 'OM-MA-landing',
				'mode'            => 'edit',
				'icon'            => 'networking',
				'keywords'        => array('what is', 'diagram', 'types', 'OM-MA'),
				'render_callback' => 'render_preview',
			)
		);

		/**
		 * Block About
		 */
		acf_register_block(
			array(
				'name'            => 'block-about',
				'title'           => __('Block About', 'OMMA'),
				'post_types'      => ['page'],
				'description'     => __('Imagen fija + bloques de texto apilados.', 'OMMA'),
				'render_template' => 'template-parts/blocks/0-landing/block-about.php',
				'category'        => 'OM-MA-landing',
				'mode'            => 'edit',
				'icon'            => 'admin-users',
				'keywords'        => array('about', 'imagen', 'texto', 'OM-MA'),
				'render_callback' => 'render_preview',
			)
		);

		/**
		 * Block Graph
		 */
		acf_register_block(
			array(
				'name'            => 'block-graph',
				'title'           => __('Block Graph', 'OMMA'),
				'post_types'      => ['page'],
				'description'     => __('Diagrama de nodos conectados con flechas animadas.', 'OMMA'),
				'render_template' => 'template-parts/blocks/0-landing/block-graph.php',
				'category'        => 'OM-MA-landing',
				'mode'            => 'edit',
				'icon'            => 'networking',
				'keywords'        => array('graph', 'diagram', 'nodes', 'OM-MA'),
				'render_callback' => 'render_preview',
			)
		);

		/**
		 * Block Intro
		 */
		acf_register_block(
			array(
				'name'            => 'block-intro',
				'title'           => __('Block Intro', 'OMMA'),
				'post_types'      => ['page'],
				'description'     => __('Bloque de texto introductorio.', 'OMMA'),
				'render_template' => 'template-parts/blocks/0-landing/block-intro.php',
				'category'        => 'OM-MA-landing',
				'mode'            => 'edit',
				'icon'            => 'editor-paragraph',
				'keywords'        => array('intro', 'texto', 'OM-MA'),
				'render_callback' => 'render_preview',
			)
		);
	}
}

add_action('acf/init', 'omma_blocks');


/**
 * Poder ver la preview de los acf
 * @link https://support.advancedcustomfields.com/forums/topic/custom-fields-on-post-preview/
 */
function fix_acf_field_post_id_on_preview($post_id, $original_post_id)
{
	// Don't do anything to options
	if (is_string($post_id) && str_contains($post_id, 'option')) {
		return $post_id;
	}
	// Don't do anything to blocks
	if (is_string($original_post_id) && str_contains($original_post_id, 'block')) {
		return $post_id;
	}

	// This should only affect on post meta fields
	if (is_preview()) {
		return get_the_ID();
	}

	return $post_id;
}

add_filter('acf/validate_post_id', __NAMESPACE__ . '\fix_acf_field_post_id_on_preview', 10, 2);


// ********** Ruta del archivo JSON Guardar campos ACF (¡esto funciona bien!)) **********

function my_acf_json_save_point(): string
{

	// update path HAVING ISSUES!!!
	// return
	return plugin_dir_path(__FILE__) . 'json';
}

add_filter('acf/settings/save_json', 'my_acf_json_save_point');

// ********** Ruta del archivo JSON Cargar campos ACF (¿esto no funciona?) **********
/**
 * @param $paths
 */
function my_acf_json_load_point($paths)
{
	// Remove original path
	unset($paths[0]); // Append our new path
	$paths[] = plugin_dir_path(__FILE__) . 'json';

	return $paths;
}

add_filter('acf/settings/load_json', 'my_acf_json_load_point');

/**
 * Callback block render,
 * return preview image
 * @link //https://stackoverflow.com/questions/65886937/show-preview-image-for-custom-gutenberg-blocks
 * @link https://www.grbav.com/acf-custom-block-shows-preview-image-in-gutenberg/
 * @param $block
 */
function render_preview($block): void
{
	/**
	 * Back-end preview
	 */

	/**
	 * Back-end preview
	 */
	if (!empty($block['data']['image'])) {
		echo $block['data']['image'];
	} else {
		if ($block) :
			$template = $block['render_template'];
			$template = str_replace('.php', '', $template);
			get_template_part('/' . $template);
		endif;
	}
}
