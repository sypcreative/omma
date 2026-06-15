<?php

/**
 * Funciones que mejoran el tema al conectarse a WordPress
 *
 * @package OM-MA
 */

/**
 * SVG de flecha para botones. fill="currentColor" hereda el color del texto.
 */
function omma_btn_arrow(): string {
	return '<svg class="btn-arrow" width="8" height="8" viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M7.15178 7.36288C7.42758 7.35083 7.64154 7.11798 7.62963 6.84221L7.36309 0.744799C7.35177 0.485803 7.14423 0.278264 6.88524 0.266949L0.787824 0.000403651C0.512056 -0.0115064 0.27921 0.202459 0.267161 0.478253C0.255245 0.753992 0.468572 0.987503 0.74432 0.999606L2.26902 1.0659L3.7074 1.13978C4.46445 1.1786 4.81604 2.09755 4.27847 2.63203L4.27709 2.63341L0.145627 6.7773C-0.0491859 6.97288 -0.0485015 7.2895 0.147008 7.48441C0.34258 7.67922 0.659201 7.67854 0.854115 7.48303L4.98489 3.34121C5.53061 2.79911 6.45933 3.15971 6.49578 3.92816L6.56414 5.3624L6.63043 6.88572C6.64253 7.16146 6.87605 7.37479 7.15178 7.36288Z" fill="currentColor"/></svg>';
}

/**
 * Agrega clases personalizadas a la matriz de clases de cuerpo.
 *
 * @param array $classes Clases para el elemento del body.
 *
 * @return array
 */
/**
 * Devuelve el namespace de Barba según el template actual.
 * Usado en header.php y header-landing.php para data-barba-namespace.
 */
function barba_namespace(): string
{
	if (is_page_template('template-landing.php'))              return 'landing';
	if (is_front_page() || is_home())                          return 'home';
	if (is_page_template('template-contacto.php') || is_page('contacto')) return 'contacto';
if (is_single())                                           return 'single';
	if (is_archive())                                          return 'archive';
	if (is_404())                                              return '404';
	if (is_page()) {
		$slug = get_post_field('post_name', get_post());
		return $slug ?: 'page';
	}
	return 'default';
}

function omma_body_classes($classes)
{
	// Agrega una clase de hfeed a páginas no singulares.
	if (!is_singular()) {
		$classes[] = 'hfeed';
	}

	// Agrega una clase de sin barra lateral cuando no hay una barra lateral presente.
	if (!is_active_sidebar('sidebar-1')) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}

add_filter('body_class', 'omma_body_classes');

/**
 * Agregue un encabezado de descubrimiento automático de URL de pingback para publicaciones, páginas o archivos adjuntos individuales.
 */
function omma_pingback_header()
{
	if (is_singular() && pings_open()) {
		printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
	}
}

add_action('wp_head', 'omma_pingback_header');

/**
 * Añadimos que aquellos enlaces del editor de menus que tengan "Nueva ventana"
 * sigan dicho proceso
 *
 * @param $atts
 * @param $item
 * @param $args
 *
 * @return mixed
 */
function abrir_enlaces_menu_nueva_ventana($atts, $item, $args)
{
	if ($item->target) {
		$atts['target'] = '_blank';
		$atts['rel']    = 'noopener noreferrer';
	}

	return $atts;
}

add_filter('nav_menu_link_attributes', 'abrir_enlaces_menu_nueva_ventana', 10, 3);


/**
 * Establece la imagen destacada de un post desde un campo ACF
 *
 * @param int $post_id ID del post.
 * @param WP_Post $post Post object.
 * @param bool $update Indica si el post se está actualizando o no.
 */
function set_featured_image_from_acf_field($post_id, $post, $update)
{
	// Comprobar si el post ya tiene una imagen destacada
	if (!has_post_thumbnail($post_id) && $post_id && function_exists('get_field')) {
		$hero_general_imagen_escritorio = get_field('hero_general_imagen_escritorio', $post_id);

		// Comprobar si el campo ACF tiene un valor
		if ($hero_general_imagen_escritorio) {
			// Obtener el ID de la imagen
			$image_id = $hero_general_imagen_escritorio['ID'];

			// Establecer la imagen como imagen destacada del post
			set_post_thumbnail($post_id, $image_id);
		}
	}
}

add_action('save_post', 'set_featured_image_from_acf_field', 10, 3);


function tiene_hijos_cpt($post_type)
{
	global $post;

	// Argumentos para get_posts
	$args = array(
		'post_type'      => $post_type,
		'posts_per_page' => -1,
		'post_parent'    => $post->ID,
		'fields'         => 'ids'  // Traer solo los IDs para mejorar el rendimiento
	);

	$hijos = get_posts($args);

	// Si hay posts hijos, retorna verdadero; de lo contrario, falso
	return !empty($hijos);
}

/**
 * Limpia una cadena de texto eliminando tildes, convirtiéndola a minúsculas y eliminando caracteres especiales.
 *
 * Esta función toma una cadena de texto y realiza los siguientes pasos para limpiarla:
 * 1. Elimina las tildes de los caracteres.
 * 2. Convierte la cadena a minúsculas.
 * 3. Elimina todos los caracteres especiales, dejando solo letras, números y espacios.
 *
 * @param string $string La cadena de texto que se desea limpiar.
 *
 * @return string La cadena limpia sin tildes, en minúsculas y sin caracteres especiales.
 */
function remove_special_characters($string)
{
	// Eliminar tildes
	$string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);

	// Convertir la cadena a minúsculas
	$string = mb_strtolower($string, 'UTF-8');

	// Eliminar caracteres especiales y dejar solo letras, números y espacios
	$string = preg_replace('/[^\p{L}\p{N}\s]+/u', '', $string);

	return $string;
}
