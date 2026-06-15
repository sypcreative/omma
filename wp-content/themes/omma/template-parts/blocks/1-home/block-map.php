<?php

/**
 * Block: Map
 * Sin campos propios. Lee site_offices desde ACF Options.
 *
 * Campos consumidos:
 *   get_field('site_offices', 'option') → repeater
 *     site_office_name     (texto)
 *     site_office_lat      (número)
 *     site_office_lng      (número)
 */

$offices = function_exists('get_field') ? get_field('site_offices', 'option') : [];
$is_2d   = function_exists('get_field') ? (bool) get_field('block_map_mode') : false;

// Construye los markers para Three.js: solo oficinas con coordenadas
$markers = [];
if ($offices) {
	foreach ($offices as $office) {
		$lat = $office['site_office_lat'] ?? '';
		$lng = $office['site_office_lng'] ?? '';
		if ($lat === '' || $lng === '') continue;
		$markers[] = [
			'location' => [ (float) $lat, (float) $lng ],
			'name'     => $office['site_office_name'] ?? '',
		];
	}
}

?>

<section class="block-map bg-charcoal pt-5 pt-lg-6<?php echo $is_2d ? ' block-map--2d' : ''; ?>">
	<div class="block-map__globe-wrap">
		<?php if ( $is_2d ) : ?>
			<canvas class="block-map__map-2d"
				data-markers="<?php echo esc_attr(wp_json_encode($markers)); ?>"
				role="img"
				aria-label="<?php esc_attr_e('Mapa mundial con la localización de nuestras oficinas', 'omma'); ?>"
			></canvas>
		<?php else : ?>
			<canvas
				class="block-map__globe"
				data-markers="<?php echo esc_attr(wp_json_encode($markers)); ?>"
				data-svg-url="<?php echo esc_url(get_template_directory_uri() . '/assets/dist/images/world-map.svg'); ?>"
				role="img"
				aria-label="<?php esc_attr_e('Globo terráqueo interactivo con la localización de nuestras oficinas', 'omma'); ?>"
			></canvas>
			<div class="block-map__tooltip" role="tooltip" aria-hidden="true"></div>
		<?php endif; ?>
	</div>
</section>
