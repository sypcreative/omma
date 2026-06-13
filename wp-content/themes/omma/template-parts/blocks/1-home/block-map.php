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

// Construye los markers para Three.js: solo oficinas con coordenadas
$markers = [];
if ($offices) {
	foreach ($offices as $office) {
		$lat = $office['site_office_lat'] ?? '';
		$lng = $office['site_office_lng'] ?? '';
		if ($lat === '' || $lng === '') continue;
		$markers[] = [
			'location' => [ (float) $lat, (float) $lng ],
		];
	}
}

?>

<section class="block-map bg-charcoal pt-5 pt-lg-6">
	<div class="block-map__globe-wrap">
		<canvas
			class="block-map__globe"
			data-markers="<?php echo esc_attr(wp_json_encode($markers)); ?>"
			data-svg-url="<?php echo esc_url(get_template_directory_uri() . '/assets/dist/images/world-map.svg'); ?>"
		></canvas>
	</div>
</section>
