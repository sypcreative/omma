<?php

// ── Case Studies ──────────────────────────────────────────────────────────────

function omma_cpt_case_studies(): void
{
	$labels = array(
		'name'                  => _x('Case Studies', 'omma'),
		'singular_name'         => _x('Case Study', 'omma'),
		'menu_name'             => __('Case Studies', 'omma'),
		'name_admin_bar'        => __('Case Study', 'omma'),
		'archives'              => __('Archivos de case studies', 'omma'),
		'attributes'            => __('Atributos de case study', 'omma'),
		'parent_item_colon'     => __('Case Study padre', 'omma'),
		'all_items'             => __('Todos los case studies', 'omma'),
		'add_new_item'          => __('Añadir nuevo case study', 'omma'),
		'add_new'               => __('Añadir nuevo', 'omma'),
		'new_item'              => __('Nuevo case study', 'omma'),
		'edit_item'             => __('Editar case study', 'omma'),
		'update_item'           => __('Actualizar case study', 'omma'),
		'view_item'             => __('Ver case study', 'omma'),
		'view_items'            => __('Ver case studies', 'omma'),
		'search_items'          => __('Buscar case study', 'omma'),
		'not_found'             => __('No se han encontrado case studies', 'omma'),
		'not_found_in_trash'    => __('No se han encontrado case studies en la papelera', 'omma'),
		'featured_image'        => __('Imagen destacada', 'omma'),
		'set_featured_image'    => __('Establecer imagen destacada', 'omma'),
		'remove_featured_image' => __('Eliminar imagen destacada', 'omma'),
		'use_featured_image'    => __('Usar como imagen destacada', 'omma'),
		'insert_into_item'      => __('Insertar en case study', 'omma'),
		'uploaded_to_this_item' => __('Subido a este case study', 'omma'),
		'items_list'            => __('Lista de case studies', 'omma'),
		'items_list_navigation' => __('Navegar en los case studies', 'omma'),
		'filter_items_list'     => __('Filtrar case studies', 'omma'),
	);

	$rewrite = array(
		'slug'       => 'case-studies',
		'with_front' => false,
		'pages'      => true,
		'feeds'      => true,
	);

	$args = array(
		'label'               => __('Case Study', 'omma'),
		'description'         => __('Añade y gestiona todos tus case studies', 'omma'),
		'labels'              => $labels,
		'supports'            => array('title', 'editor', 'custom-fields', 'page-attributes', 'revisions', 'thumbnail'),
		'taxonomies'          => array( 'cliente', 'servicios' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-portfolio',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'show_in_rest'        => true,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'rewrite'             => $rewrite,
		'capability_type'     => 'page',
	);

	register_post_type('case-studies', $args);
}

add_action('init', 'omma_cpt_case_studies');


// ── News ──────────────────────────────────────────────────────────────────────

function omma_cpt_news(): void
{
	$labels = array(
		'name'                  => _x('News', 'omma'),
		'singular_name'         => _x('New', 'omma'),
		'menu_name'             => __('News', 'omma'),
		'name_admin_bar'        => __('New', 'omma'),
		'all_items'             => __('Todas las news', 'omma'),
		'add_new_item'          => __('Añadir nueva news', 'omma'),
		'add_new'               => __('Añadir nueva', 'omma'),
		'new_item'              => __('Nueva news', 'omma'),
		'edit_item'             => __('Editar news', 'omma'),
		'update_item'           => __('Actualizar news', 'omma'),
		'view_item'             => __('Ver news', 'omma'),
		'view_items'            => __('Ver news', 'omma'),
		'search_items'          => __('Buscar news', 'omma'),
		'not_found'             => __('No se han encontrado news', 'omma'),
		'not_found_in_trash'    => __('No se han encontrado news en la papelera', 'omma'),
		'featured_image'        => __('Imagen destacada', 'omma'),
		'set_featured_image'    => __('Establecer imagen destacada', 'omma'),
	);

	$args = array(
		'label'               => __('News', 'omma'),
		'description'         => __('Añade y gestiona todas las news', 'omma'),
		'labels'              => $labels,
		'supports'            => array('title', 'editor', 'custom-fields', 'revisions', 'thumbnail', 'excerpt'),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 6,
		'menu_icon'           => 'dashicons-megaphone',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'show_in_rest'        => true,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'rewrite'             => [ 'slug' => 'news', 'with_front' => false ],
		'capability_type'     => 'page',
	);

	register_post_type('news', $args);
}

add_action('init', 'omma_cpt_news');


// ── Podcast ───────────────────────────────────────────────────────────────────

function omma_cpt_podcast(): void
{
	$labels = array(
		'name'                  => _x('Podcasts', 'omma'),
		'singular_name'         => _x('Podcast', 'omma'),
		'menu_name'             => __('Podcasts', 'omma'),
		'name_admin_bar'        => __('Podcast', 'omma'),
		'all_items'             => __('Todos los podcasts', 'omma'),
		'add_new_item'          => __('Añadir nuevo podcast', 'omma'),
		'add_new'               => __('Añadir nuevo', 'omma'),
		'new_item'              => __('Nuevo podcast', 'omma'),
		'edit_item'             => __('Editar podcast', 'omma'),
		'update_item'           => __('Actualizar podcast', 'omma'),
		'view_item'             => __('Ver podcast', 'omma'),
		'view_items'            => __('Ver podcasts', 'omma'),
		'search_items'          => __('Buscar podcast', 'omma'),
		'not_found'             => __('No se han encontrado podcasts', 'omma'),
		'not_found_in_trash'    => __('No se han encontrado podcasts en la papelera', 'omma'),
		'featured_image'        => __('Imagen destacada', 'omma'),
		'set_featured_image'    => __('Establecer imagen destacada', 'omma'),
	);

	$args = array(
		'label'               => __('Podcast', 'omma'),
		'description'         => __('Añade y gestiona todos los podcasts', 'omma'),
		'labels'              => $labels,
		'supports'            => array('title', 'editor', 'custom-fields', 'revisions', 'thumbnail', 'excerpt'),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 7,
		'menu_icon'           => 'dashicons-microphone',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'show_in_rest'        => true,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'rewrite'             => [ 'slug' => 'podcast', 'with_front' => false ],
		'capability_type'     => 'page',
	);

	register_post_type('podcast', $args);
}

add_action('init', 'omma_cpt_podcast');


// ── Taxonomías de Case Studies ────────────────────────────────────────────────

function omma_taxonomies_case_studies(): void
{
	// Cliente
	register_taxonomy( 'cliente', 'case-studies', [
		'labels' => [
			'name'              => __( 'Clientes', 'omma' ),
			'singular_name'     => __( 'Cliente', 'omma' ),
			'search_items'      => __( 'Buscar clientes', 'omma' ),
			'all_items'         => __( 'Todos los clientes', 'omma' ),
			'edit_item'         => __( 'Editar cliente', 'omma' ),
			'update_item'       => __( 'Actualizar cliente', 'omma' ),
			'add_new_item'      => __( 'Añadir nuevo cliente', 'omma' ),
			'new_item_name'     => __( 'Nuevo cliente', 'omma' ),
			'menu_name'         => __( 'Clientes', 'omma' ),
		],
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'rewrite'           => [ 'slug' => 'cliente' ],
	] );

	// Servicios
	register_taxonomy( 'servicios', 'case-studies', [
		'labels' => [
			'name'              => __( 'Servicios', 'omma' ),
			'singular_name'     => __( 'Servicio', 'omma' ),
			'search_items'      => __( 'Buscar servicios', 'omma' ),
			'all_items'         => __( 'Todos los servicios', 'omma' ),
			'edit_item'         => __( 'Editar servicio', 'omma' ),
			'update_item'       => __( 'Actualizar servicio', 'omma' ),
			'add_new_item'      => __( 'Añadir nuevo servicio', 'omma' ),
			'new_item_name'     => __( 'Nuevo servicio', 'omma' ),
			'menu_name'         => __( 'Servicios', 'omma' ),
		],
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'rewrite'           => [ 'slug' => 'servicios' ],
	] );
}

add_action( 'init', 'omma_taxonomies_case_studies', 0 );
