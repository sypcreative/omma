<?php

/**
 * Registro de bloques ACF, categorías Gutenberg y rutas de acf-json.
 *
 * @package omma
 */

// ── Categorías de bloques en el editor ────────────────────────────────────────

function omma_blocks_category( array $categories ): array
{
	$custom = [
		[ 'slug' => 'OM-MA-landing',      'title' => __( 'OM-MA | Landing',          'omma' ) ],
		[ 'slug' => 'OM-MA-home',         'title' => __( 'OM-MA | Home',             'omma' ) ],
		[ 'slug' => 'OM-MA-about',        'title' => __( 'OM-MA | About',            'omma' ) ],
		[ 'slug' => 'OM-MA-services',     'title' => __( 'OM-MA | Services',         'omma' ) ],
		[ 'slug' => 'OM-MA-works',        'title' => __( 'OM-MA | Works',            'omma' ) ],
		[ 'slug' => 'OM-MA-news',         'title' => __( 'OM-MA | News',             'omma' ) ],
		[ 'slug' => 'OM-MA-contact',      'title' => __( 'OM-MA | Contact',          'omma' ) ],
		[ 'slug' => 'OM-MA-single-works', 'title' => __( 'OM-MA | Single Productos', 'omma' ) ],
	];

	return array_merge( $custom, $categories );
}

add_action( 'block_categories_all', 'omma_blocks_category', 10, 2 );


// ── Registro de bloques ───────────────────────────────────────────────────────

function omma_blocks(): void
{
	if ( ! function_exists( 'acf_register_block_type' ) ) {
		return;
	}

	acf_register_block_type( [
		'name'            => 'hero-landing',
		'title'           => __( 'Hero Landing', 'omma' ),
		'description'     => __( 'Cabecera de la landing de I+D+i.', 'omma' ),
		'post_types'      => [ 'page' ],
		'render_template' => 'template-parts/blocks/0-landing/block-hero-landing.php',
		'render_callback' => 'omma_render_preview',
		'category'        => 'OM-MA-landing',
		'icon'            => 'cover-image',
		'keywords'        => [ 'hero', 'landing', 'omma' ],
		'mode'            => 'edit',
		'supports'        => [ 'align' => false, 'mode' => false ],
		'example'         => [
			'attributes' => [
				'mode' => 'preview',
				'data' => [
					'image' => '<img src="' . get_template_directory_uri() . '/assets/dist/img/blocks/hero-landing.png" style="display:block;margin:0 auto;max-width:100%;">',
				],
			],
		],
	] );

	// ── Home ─────────────────────────────────────────────────────────────────

	acf_register_block_type( [
		'name'            => 'block-hero-home',
		'title'           => __( 'Block Hero Home', 'omma' ),
		'description'     => __( 'Hero a pantalla completa con imagen de fondo, título, subtítulo y botones.', 'omma' ),
		'post_types'      => [ 'page' ],
		'render_template' => 'template-parts/blocks/1-home/block-hero-home.php',
		'render_callback' => 'omma_render_preview',
		'category'        => 'OM-MA-home',
		'icon'            => 'cover-image',
		'keywords'        => [ 'hero', 'home', 'omma' ],
		'mode'            => 'edit',
		'supports'        => [ 'align' => false, 'mode' => false ],
	] );

	acf_register_block_type( [
		'name'            => 'block-map',
		'title'           => __( 'Block Map', 'omma' ),
		'description'     => __( 'Listado de oficinas desde Site Options. Sin campos propios.', 'omma' ),
		'post_types'      => [ 'page' ],
		'render_template' => 'template-parts/blocks/1-home/block-map.php',
		'render_callback' => 'omma_render_preview',
		'category'        => 'OM-MA-home',
		'icon'            => 'location',
		'keywords'        => [ 'map', 'oficinas', 'omma' ],
		'mode'            => 'edit',
		'supports'        => [ 'align' => false, 'mode' => false ],
	] );

	acf_register_block_type( [
		'name'            => 'block-capabilities',
		'title'           => __( 'Block Capabilities', 'omma' ),
		'description'     => __( 'Título y listado de capabilities con número, título y descripción.', 'omma' ),
		'post_types'      => [ 'page' ],
		'render_template' => 'template-parts/blocks/1-home/block-capabilities.php',
		'render_callback' => 'omma_render_preview',
		'category'        => 'OM-MA-home',
		'icon'            => 'list-view',
		'keywords'        => [ 'capabilities', 'servicios', 'omma' ],
		'mode'            => 'edit',
		'supports'        => [ 'align' => false, 'mode' => false ],
	] );

	acf_register_block_type( [
		'name'            => 'block-ecosystem',
		'title'           => __( 'Block Ecosystem', 'omma' ),
		'description'     => __( 'Título, subtítulo, botón y grid de cards con icono.', 'omma' ),
		'post_types'      => [ 'page' ],
		'render_template' => 'template-parts/blocks/1-home/block-ecosystem.php',
		'render_callback' => 'omma_render_preview',
		'category'        => 'OM-MA-home',
		'icon'            => 'screenoptions',
		'keywords'        => [ 'ecosystem', 'cards', 'omma' ],
		'mode'            => 'edit',
		'supports'        => [ 'align' => false, 'mode' => false ],
	] );

	acf_register_block_type( [
		'name'            => 'block-logos',
		'title'           => __( 'Block Logos', 'omma' ),
		'description'     => __( 'Banda de logos con botón CTA.', 'omma' ),
		'post_types'      => [ 'page' ],
		'render_template' => 'template-parts/blocks/1-home/block-logos.php',
		'render_callback' => 'omma_render_preview',
		'category'        => 'OM-MA-home',
		'icon'            => 'images-alt2',
		'keywords'        => [ 'logos', 'clientes', 'omma' ],
		'mode'            => 'edit',
		'supports'        => [ 'align' => false, 'mode' => false ],
	] );

	acf_register_block_type( [
		'name'            => 'block-data',
		'title'           => __( 'Block Data', 'omma' ),
		'description'     => __( 'Banda de datos/estadísticas en repeater.', 'omma' ),
		'post_types'      => [ 'page' ],
		'render_template' => 'template-parts/blocks/1-home/block-data.php',
		'render_callback' => 'omma_render_preview',
		'category'        => 'OM-MA-home',
		'icon'            => 'chart-bar',
		'keywords'        => [ 'data', 'stats', 'omma' ],
		'mode'            => 'edit',
		'supports'        => [ 'align' => false, 'mode' => false ],
	] );

	// ── About ────────────────────────────────────────────────────────────────

	acf_register_block_type( [
		'name'            => 'block-about-intro',
		'title'           => __( 'Block About Intro', 'omma' ),
		'description'     => __( 'Introducción de la página About: título, subtítulo, imagen y texto.', 'omma' ),
		'post_types'      => [ 'page' ],
		'render_template' => 'template-parts/blocks/2-about/block-about-intro.php',
		'render_callback' => 'omma_render_preview',
		'category'        => 'OM-MA-about',
		'icon'            => 'editor-paragraph',
		'keywords'        => [ 'about', 'intro', 'omma' ],
		'mode'            => 'edit',
		'supports'        => [ 'align' => false, 'mode' => false ],
	] );

	acf_register_block_type( [
		'name'            => 'block-about-ceo',
		'title'           => __( 'Block About CEO', 'omma' ),
		'description'     => __( 'Bloque del CEO con título, subtítulo, descripción e imagen.', 'omma' ),
		'post_types'      => [ 'page' ],
		'render_template' => 'template-parts/blocks/2-about/block-about-ceo.php',
		'render_callback' => 'omma_render_preview',
		'category'        => 'OM-MA-about',
		'icon'            => 'admin-users',
		'keywords'        => [ 'ceo', 'about', 'omma' ],
		'mode'            => 'edit',
		'supports'        => [ 'align' => false, 'mode' => false ],
	] );

	acf_register_block_type( [
		'name'            => 'block-about-team',
		'title'           => __( 'Block About Team', 'omma' ),
		'description'     => __( 'Grid del equipo con foto, nombre y puesto.', 'omma' ),
		'post_types'      => [ 'page' ],
		'render_template' => 'template-parts/blocks/2-about/block-about-team.php',
		'render_callback' => 'omma_render_preview',
		'category'        => 'OM-MA-about',
		'icon'            => 'groups',
		'keywords'        => [ 'team', 'equipo', 'omma' ],
		'mode'            => 'edit',
		'supports'        => [ 'align' => false, 'mode' => false ],
	] );

	// ── Case Studies ─────────────────────────────────────────────────────────

	acf_register_block_type( [
		'name'            => 'block-cs-header',
		'title'           => __( 'CS Header', 'omma' ),
		'description'     => __( 'Cabecera del case study: título, excerpt, sector y servicios del post.', 'omma' ),
		'post_types'      => [ 'case-studies' ],
		'render_template' => 'template-parts/blocks/4-case-studies/block-cs-header.php',
		'render_callback' => 'omma_render_preview',
		'category'        => 'OM-MA-works',
		'icon'            => 'cover-image',
		'keywords'        => [ 'header', 'hero', 'case study', 'omma' ],
		'mode'            => 'preview',
		'supports'        => [ 'align' => false, 'mode' => false ],
	] );

	acf_register_block_type( [
		'name'            => 'block-cs-details',
		'title'           => __( 'CS Details', 'omma' ),
		'description'     => __( 'Título y listado de detalles con descripción.', 'omma' ),
		'post_types'      => [ 'case-studies' ],
		'render_template' => 'template-parts/blocks/4-case-studies/block-cs-details.php',
		'render_callback' => 'omma_render_preview',
		'category'        => 'OM-MA-works',
		'icon'            => 'list-view',
		'keywords'        => [ 'details', 'case study', 'omma' ],
		'mode'            => 'edit',
		'supports'        => [ 'align' => false, 'mode' => false ],
	] );

	acf_register_block_type( [
		'name'            => 'block-cs-images',
		'title'           => __( 'CS Images', 'omma' ),
		'description'     => __( 'Galería de imágenes del case study.', 'omma' ),
		'post_types'      => [ 'case-studies' ],
		'render_template' => 'template-parts/blocks/4-case-studies/block-cs-images.php',
		'render_callback' => 'omma_render_preview',
		'category'        => 'OM-MA-works',
		'icon'            => 'format-gallery',
		'keywords'        => [ 'images', 'gallery', 'case study', 'omma' ],
		'mode'            => 'edit',
		'supports'        => [ 'align' => false, 'mode' => false ],
	] );

	acf_register_block_type( [
		'name'            => 'block-cs-extra-info',
		'title'           => __( 'CS Extra Info', 'omma' ),
		'description'     => __( 'Bloque de información adicional con título y descripción.', 'omma' ),
		'post_types'      => [ 'case-studies' ],
		'render_template' => 'template-parts/blocks/4-case-studies/block-cs-extra-info.php',
		'render_callback' => 'omma_render_preview',
		'category'        => 'OM-MA-works',
		'icon'            => 'editor-paragraph',
		'keywords'        => [ 'extra', 'info', 'case study', 'omma' ],
		'mode'            => 'edit',
		'supports'        => [ 'align' => false, 'mode' => false ],
	] );

	acf_register_block_type( [
		'name'            => 'block-cs-data',
		'title'           => __( 'CS Data', 'omma' ),
		'description'     => __( 'Banda de datos numéricos del case study.', 'omma' ),
		'post_types'      => [ 'case-studies' ],
		'render_template' => 'template-parts/blocks/4-case-studies/block-cs-data.php',
		'render_callback' => 'omma_render_preview',
		'category'        => 'OM-MA-works',
		'icon'            => 'chart-bar',
		'keywords'        => [ 'data', 'stats', 'case study', 'omma' ],
		'mode'            => 'edit',
		'supports'        => [ 'align' => false, 'mode' => false ],
	] );

	acf_register_block_type( [
		'name'            => 'block-cs-more-projects',
		'title'           => __( 'CS More Projects', 'omma' ),
		'description'     => __( 'Selección de otros case studies relacionados.', 'omma' ),
		'post_types'      => [ 'case-studies' ],
		'render_template' => 'template-parts/blocks/4-case-studies/block-cs-more-projects.php',
		'render_callback' => 'omma_render_preview',
		'category'        => 'OM-MA-works',
		'icon'            => 'portfolio',
		'keywords'        => [ 'more', 'projects', 'related', 'case study', 'omma' ],
		'mode'            => 'edit',
		'supports'        => [ 'align' => false, 'mode' => false ],
	] );

	// ── Case Studies — página listado ────────────────────────────────────────

	acf_register_block_type( [
		'name'            => 'block-cs-page-header',
		'title'           => __( 'CS Page Header', 'omma' ),
		'description'     => __( 'Cabecera de la página de listado de case studies: título y descripción.', 'omma' ),
		'post_types'      => [ 'page' ],
		'render_template' => 'template-parts/blocks/4-case-studies/block-cs-page-header.php',
		'render_callback' => 'omma_render_preview',
		'category'        => 'OM-MA-works',
		'icon'            => 'cover-image',
		'keywords'        => [ 'case studies', 'header', 'intro', 'omma' ],
		'mode'            => 'edit',
		'supports'        => [ 'align' => false, 'mode' => false ],
	] );

	acf_register_block_type( [
		'name'            => 'block-cs-grid',
		'title'           => __( 'CS Grid', 'omma' ),
		'description'     => __( 'Grid automático con todos los case studies publicados.', 'omma' ),
		'post_types'      => [ 'page' ],
		'render_template' => 'template-parts/blocks/4-case-studies/block-cs-grid.php',
		'render_callback' => 'omma_render_preview',
		'category'        => 'OM-MA-works',
		'icon'            => 'grid-view',
		'keywords'        => [ 'case studies', 'grid', 'portfolio', 'omma' ],
		'mode'            => 'preview',
		'supports'        => [ 'align' => false, 'mode' => false ],
	] );

	// ── News — página listado ────────────────────────────────────────────────

	acf_register_block_type( [
		'name'            => 'block-news-page-header',
		'title'           => __( 'News Page Header', 'omma' ),
		'description'     => __( 'Cabecera de la página de listado de news: título y descripción.', 'omma' ),
		'post_types'      => [ 'page' ],
		'render_template' => 'template-parts/blocks/5-news/block-news-page-header.php',
		'render_callback' => 'omma_render_preview',
		'category'        => 'OM-MA-news',
		'icon'            => 'megaphone',
		'keywords'        => [ 'news', 'header', 'intro', 'omma' ],
		'mode'            => 'edit',
		'supports'        => [ 'align' => false, 'mode' => false ],
	] );

	acf_register_block_type( [
		'name'            => 'block-news-grid',
		'title'           => __( 'News Grid', 'omma' ),
		'description'     => __( 'Grid automático con todas las news publicadas.', 'omma' ),
		'post_types'      => [ 'page' ],
		'render_template' => 'template-parts/blocks/5-news/block-news-grid.php',
		'render_callback' => 'omma_render_preview',
		'category'        => 'OM-MA-news',
		'icon'            => 'grid-view',
		'keywords'        => [ 'news', 'grid', 'omma' ],
		'mode'            => 'preview',
		'supports'        => [ 'align' => false, 'mode' => false ],
	] );

	// ── Services ─────────────────────────────────────────────────────────────

	acf_register_block_type( [
		'name'            => 'block-services-intro',
		'title'           => __( 'Block Services Intro', 'omma' ),
		'description'     => __( 'Introducción de la página de servicios: título y descripción.', 'omma' ),
		'post_types'      => [ 'page' ],
		'render_template' => 'template-parts/blocks/3-services/block-services-intro.php',
		'render_callback' => 'omma_render_preview',
		'category'        => 'OM-MA-services',
		'icon'            => 'editor-paragraph',
		'keywords'        => [ 'services', 'intro', 'omma' ],
		'mode'            => 'edit',
		'supports'        => [ 'align' => false, 'mode' => false ],
	] );

	acf_register_block_type( [
		'name'            => 'block-services-list',
		'title'           => __( 'Block Services List', 'omma' ),
		'description'     => __( 'Listado de servicios con icono, título, descripción, botones y características.', 'omma' ),
		'post_types'      => [ 'page' ],
		'render_template' => 'template-parts/blocks/3-services/block-services-list.php',
		'render_callback' => 'omma_render_preview',
		'category'        => 'OM-MA-services',
		'icon'            => 'list-view',
		'keywords'        => [ 'services', 'list', 'omma' ],
		'mode'            => 'edit',
		'supports'        => [ 'align' => false, 'mode' => false ],
	] );

	// ── Landing ───────────────────────────────────────────────────────────────

	acf_register_block_type( [
		'name'            => 'block-sectors',
		'title'           => __( 'Block Sectors', 'omma' ),
		'description'     => __( 'Título y listado de sectores con imagen.', 'omma' ),
		'post_types'      => [ 'page' ],
		'render_template' => 'template-parts/blocks/0-landing/block-sectors.php',
		'render_callback' => 'omma_render_preview',
		'category'        => 'OM-MA-landing',
		'icon'            => 'grid-view',
		'keywords'        => [ 'sectors', 'omma' ],
		'mode'            => 'edit',
		'supports'        => [ 'align' => false, 'mode' => false ],
	] );

	acf_register_block_type( [
		'name'            => 'block-types',
		'title'           => __( 'Block Types', 'omma' ),
		'description'     => __( 'Título, enlace, subtítulo y lista de textos.', 'omma' ),
		'post_types'      => [ 'page' ],
		'render_template' => 'template-parts/blocks/0-landing/block-types.php',
		'render_callback' => 'omma_render_preview',
		'category'        => 'OM-MA-landing',
		'icon'            => 'list-view',
		'keywords'        => [ 'types', 'lista', 'omma' ],
		'mode'            => 'edit',
		'supports'        => [ 'align' => false, 'mode' => false ],
	] );

	acf_register_block_type( [
		'name'            => 'block-what-is',
		'title'           => __( 'Block What Is', 'omma' ),
		'description'     => __( 'Título, texto, diagrama de nodos y tarjetas apiladas.', 'omma' ),
		'post_types'      => [ 'page' ],
		'render_template' => 'template-parts/blocks/0-landing/block-what-is.php',
		'render_callback' => 'omma_render_preview',
		'category'        => 'OM-MA-landing',
		'icon'            => 'networking',
		'keywords'        => [ 'what is', 'diagram', 'omma' ],
		'mode'            => 'edit',
		'supports'        => [ 'align' => false, 'mode' => false ],
	] );

	acf_register_block_type( [
		'name'            => 'block-about',
		'title'           => __( 'Block About', 'omma' ),
		'description'     => __( 'Imagen fija + bloques de texto apilados.', 'omma' ),
		'post_types'      => [ 'page' ],
		'render_template' => 'template-parts/blocks/0-landing/block-about.php',
		'render_callback' => 'omma_render_preview',
		'category'        => 'OM-MA-landing',
		'icon'            => 'admin-users',
		'keywords'        => [ 'about', 'imagen', 'omma' ],
		'mode'            => 'edit',
		'supports'        => [ 'align' => false, 'mode' => false ],
	] );

	acf_register_block_type( [
		'name'            => 'block-graph',
		'title'           => __( 'Block Graph', 'omma' ),
		'description'     => __( 'Diagrama de nodos conectados con flechas animadas.', 'omma' ),
		'post_types'      => [ 'page' ],
		'render_template' => 'template-parts/blocks/0-landing/block-graph.php',
		'render_callback' => 'omma_render_preview',
		'category'        => 'OM-MA-landing',
		'icon'            => 'networking',
		'keywords'        => [ 'graph', 'diagram', 'omma' ],
		'mode'            => 'edit',
		'supports'        => [ 'align' => false, 'mode' => false ],
	] );

	acf_register_block_type( [
		'name'            => 'block-contact',
		'title'           => __( 'Block Contact', 'omma' ),
		'description'     => __( 'Formulario de contacto con imagen decorativa.', 'omma' ),
		'post_types'      => [ 'page' ],
		'render_template' => 'template-parts/blocks/0-landing/block-contact.php',
		'render_callback' => 'omma_render_preview',
		'category'        => 'OM-MA-contact',
		'icon'            => 'email',
		'keywords'        => [ 'contact', 'form', 'email', 'omma' ],
		'mode'            => 'edit',
		'supports'        => [ 'align' => false, 'mode' => false ],
	] );

	acf_register_block_type( [
		'name'            => 'block-intro',
		'title'           => __( 'Block Intro', 'omma' ),
		'description'     => __( 'Bloque de texto introductorio.', 'omma' ),
		'post_types'      => [ 'page' ],
		'render_template' => 'template-parts/blocks/0-landing/block-intro.php',
		'render_callback' => 'omma_render_preview',
		'category'        => 'OM-MA-landing',
		'icon'            => 'editor-paragraph',
		'keywords'        => [ 'intro', 'texto', 'omma' ],
		'mode'            => 'edit',
		'supports'        => [ 'align' => false, 'mode' => false ],
	] );
}

add_action( 'acf/init', 'omma_blocks' );


// ── Callback de preview en el editor ─────────────────────────────────────────

/**
 * Si el bloque tiene una imagen de preview configurada en `example`, la muestra.
 * En cualquier otro caso renderiza el template real del bloque.
 */
function omma_render_preview( array $block ): void
{
	if ( ! empty( $block['data']['image'] ) ) {
		echo $block['data']['image']; // phpcs:ignore WordPress.Security.EscapeOutput
		return;
	}

	$template = str_replace( '.php', '', $block['render_template'] );
	get_template_part( '/' . $template );
}


// ── Fix: campos ACF en modo preview de página ─────────────────────────────────

function fix_acf_field_post_id_on_preview( $post_id, $original_post_id )
{
	if ( is_string( $post_id ) && str_contains( $post_id, 'option' ) ) {
		return $post_id;
	}
	if ( is_string( $original_post_id ) && str_contains( $original_post_id, 'block' ) ) {
		return $post_id;
	}
	if ( is_preview() ) {
		return get_the_ID();
	}
	return $post_id;
}

add_filter( 'acf/validate_post_id', 'fix_acf_field_post_id_on_preview', 10, 2 );


// ── Rutas acf-json ────────────────────────────────────────────────────────────

function omma_acf_json_save_point(): string
{
	return plugin_dir_path( __FILE__ ) . 'json';
}

add_filter( 'acf/settings/save_json', 'omma_acf_json_save_point' );

function omma_acf_json_load_point( array $paths ): array
{
	unset( $paths[0] );
	$paths[] = plugin_dir_path( __FILE__ ) . 'json';
	return $paths;
}

add_filter( 'acf/settings/load_json', 'omma_acf_json_load_point' );


// ── Campos del post type Podcast ──────────────────────────────────────────────

add_action( 'acf/init', function () {
	acf_add_local_field_group( [
		'key'      => 'group_podcast',
		'title'    => 'Podcast',
		'fields'   => [
			[
				'key'          => 'field_podcast_youtube_url',
				'label'        => 'URL de YouTube',
				'name'         => 'podcast_youtube_url',
				'type'         => 'url',
				'instructions' => 'Pega la URL del vídeo (ej: https://www.youtube.com/watch?v=xxxxx). La miniatura y el enlace se generan automáticamente.',
				'required'     => 1,
				'placeholder'  => 'https://www.youtube.com/watch?v=',
			],
		],
		'location' => [
			[
				[
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'podcast',
				],
			],
		],
	] );
} );
