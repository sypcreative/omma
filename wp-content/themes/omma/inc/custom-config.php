<?php

if (function_exists('acf_add_options_page')) {
	acf_add_options_page(
		array(
			'page_title' => 'Opciones del Sitio',
			'menu_title' => 'Opciones del sitio',
			'position'   => 2,
		)
	);
}

add_action('customize_register', 'ocultar_bloques_tema');


function ocultar_bloques_tema($wp_customize)
{
	$wp_customize->remove_section('front_page');
	$wp_customize->remove_section('custom_css');
}

