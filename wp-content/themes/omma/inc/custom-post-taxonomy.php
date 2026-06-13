<?php


// Register Custom Post Type
function custom_post_type()
{
	// Check Creative -> ID 1
	/**
	 * Post type Proyectos
	 */
	$labels  = array(
		'name'                  => _x('Proyectos', 'omma'),
		'singular_name'         => _x('Proyecto', 'omma'),
		'menu_name'             => __('Proyectos', 'omma'),
		'name_admin_bar'        => __('Proyectos', 'omma'),
		'archives'              => __('Archivos de proyectos', 'omma'),
		'attributes'            => __('Atributos de proyectos', 'omma'),
		'parent_item_colon'     => __('Proyectos padre', 'omma'),
		'all_items'             => __('Todos los proyectos', 'omma'),
		'add_new_item'          => __('Añadir nuevo proyecto', 'omma'),
		'add_new'               => __('Añadir nuevo', 'omma'),
		'new_item'              => __('Nuevo proyecto', 'omma'),
		'edit_item'             => __('Editar proyecto', 'omma'),
		'update_item'           => __('Actualizar proyecto', 'omma'),
		'view_item'             => __('Ver proyecto', 'omma'),
		'view_items'            => __('Ver proyectos', 'omma'),
		'search_items'          => __('Buscar proyecto', 'omma'),
		'not_found'             => __('No se han encontrado proyectos', 'omma'),
		'not_found_in_trash'    => __('No se han encontrado proyectos en la papelera', 'omma'),
		'featured_image'        => __('Imagen destacada', 'omma'),
		'set_featured_image'    => __('Establecer imagen destacada', 'omma'),
		'remove_featured_image' => __('Eliminar imagen destacada', 'omma'),
		'use_featured_image'    => __('Usar como imagen destacada', 'omma'),
		'insert_into_item'      => __('Insertar en proyecto', 'omma'),
		'uploaded_to_this_item' => __('Subir proyecto', 'omma'),
		'items_list'            => __('Lista de proyectos', 'omma'),
		'items_list_navigation' => __('Navegar en los proyectos', 'omma'),
		'filter_items_list'     => __('Filtrar proyectos', 'omma'),
	);
	$rewrite = array(
		'slug' => 'proyectos', //proyectos
		'with_front' => false,
		'pages'      => true,
		'feeds'      => true,
	);
	$args    = array(
		'label'               => __('Proyecto', 'omma'),
		'description'         => __('Añade y gestiona todos tus proyectos', 'omma'),
		'labels'              => $labels,
		'supports'            => array('title', 'editor', 'custom-fields', 'page-attributes', 'revisions', 'thumbnail'),
		'taxonomies'          => array(),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 4,
		'menu_icon'           => 'dashicons-tablet',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'show_in_rest'        => true,
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => true,
		'rewrite'             => $rewrite,
		'capability_type'     => 'page',
	);

	register_post_type('proyectos', $args);
}

add_action('init', 'custom_post_type');


// add_action('init', 'omma_taxonomy', 0);
