<?php


// Register Custom Post Type
function custom_post_type()
{
	// Check Creative -> ID 1
	/**
	 * Post type Proyectos
	 */
	$labels  = array(
		'name'                  => _x('Proyectos', 'OM-MA'),
		'singular_name'         => _x('Proyecto', 'OM-MA'),
		'menu_name'             => __('Proyectos', 'OM-MA'),
		'name_admin_bar'        => __('Proyectos', 'OM-MA'),
		'archives'              => __('Archivos de proyectos', 'OM-MA'),
		'attributes'            => __('Atributos de proyectos', 'OM-MA'),
		'parent_item_colon'     => __('Proyectos padre', 'OM-MA'),
		'all_items'             => __('Todos los proyectos', 'OM-MA'),
		'add_new_item'          => __('Añadir nuevo proyecto', 'OM-MA'),
		'add_new'               => __('Añadir nuevo', 'OM-MA'),
		'new_item'              => __('Nuevo proyecto', 'OM-MA'),
		'edit_item'             => __('Editar proyecto', 'OM-MA'),
		'update_item'           => __('Actualizar proyecto', 'OM-MA'),
		'view_item'             => __('Ver proyecto', 'OM-MA'),
		'view_items'            => __('Ver proyectos', 'OM-MA'),
		'search_items'          => __('Buscar proyecto', 'OM-MA'),
		'not_found'             => __('No se han encontrado proyectos', 'OM-MA'),
		'not_found_in_trash'    => __('No se han encontrado proyectos en la papelera', 'OM-MA'),
		'featured_image'        => __('Imagen destacada', 'OM-MA'),
		'set_featured_image'    => __('Establecer imagen destacada', 'OM-MA'),
		'remove_featured_image' => __('Eliminar imagen destacada', 'OM-MA'),
		'use_featured_image'    => __('Usar como imagen destacada', 'OM-MA'),
		'insert_into_item'      => __('Insertar en proyecto', 'OM-MA'),
		'uploaded_to_this_item' => __('Subir proyecto', 'OM-MA'),
		'items_list'            => __('Lista de proyectos', 'OM-MA'),
		'items_list_navigation' => __('Navegar en los proyectos', 'OM-MA'),
		'filter_items_list'     => __('Filtrar proyectos', 'OM-MA'),
	);
	$rewrite = array(
		'slug' => 'proyectos', //proyectos
		'with_front' => false,
		'pages'      => true,
		'feeds'      => true,
	);
	$args    = array(
		'label'               => __('Proyecto', 'OM-MA'),
		'description'         => __('Añade y gestiona todos tus proyectos', 'OM-MA'),
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
