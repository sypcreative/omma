<?php

/**
 * Footer template
 * @package omma
 */

$has_acf = function_exists('get_field');
$logo    = $has_acf ? get_field('site_logo',    'option') : null;
$icon    = $has_acf ? get_field('site_icon',    'option') : null;
$offices = $has_acf ? get_field('site_offices', 'option') : [];
?>

</main>

<footer id="site-footer" class="site-footer position-relative overflow-hidden" style="background: #001019">
	<div class="container position-relative z-1">

		<!-- ── Logo ──────────────────────────────────────────────────────────── -->
		<div class="site-footer__logo py-5">
			<?php if ($logo) : ?>
				<img
					src="<?php echo esc_url($logo['url']); ?>"
					alt="<?php echo esc_attr($logo['alt'] ?: get_bloginfo('name')); ?>"
					class="site-footer__logo-img"
					loading="lazy"
					decoding="async"
					width="<?php echo (int) $logo['width']; ?>"
					height="<?php echo (int) $logo['height']; ?>">
			<?php else : ?>
				<span class="h-4 text-vanilla"><?php echo esc_html(get_bloginfo('name')); ?></span>
			<?php endif; ?>
		</div>

		<!-- ── Oficinas ──────────────────────────────────────────────────────── -->
		<?php if ($offices) : ?>
			<div class="row g-4 g-xl-5 site-footer__offices pb-5">
				<?php foreach ($offices as $office) :
					$name    = $office['site_office_name']    ?? '';
					$address = $office['site_office_address'] ?? '';
					$text    = $office['site_office_text']    ?? '';
				?>
					<div class="col-12 col-md-6 col-lg-4">
						<div class="site-footer__office">

							<?php if ($name) : ?>
								<p class="site-footer__office-name h-6 text-vanilla mb-2">
									<?php echo esc_html($name); ?>
								</p>
							<?php endif; ?>

							<?php if ($address) : ?>
								<p class="site-footer__office-address fs-small text-vanilla mb-1">
									<?php echo esc_html($address); ?>
								</p>
							<?php endif; ?>

							<?php if ($text) : ?>
								<p class="site-footer__office-text fs-small text-vanilla mb-0">
									<?php echo esc_html($text); ?>
								</p>
							<?php endif; ?>

						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<!-- ── Barra copyright ───────────────────────────────────────────────── -->
		<div class="site-footer__bar d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 py-4 border-top border-white border-opacity-10">
			<p class="fs-small text-vanilla mb-0">
				Copyright &copy; <?php echo esc_html(date('Y')); ?> OM-MA &mdash; Todos los derechos reservados.
			</p>
			<p class="fs-small text-vanilla mb-0">
				Parte del Grupo Ontier
				<a href="https://www.ontier.net" target="_blank" rel="noopener noreferrer" class="text-vanilla">
					www.ontier.net
				</a>
			</p>
		</div>

	</div>

	<!-- ── Icono decorativo fondo (aria-hidden, no semántico) ────────────────── -->
	<?php if ($icon) : ?>
		<div class="site-footer__deco position-absolute top-0 end-0 h-100 d-none d-lg-block" aria-hidden="true">
			<img
				src="<?php echo esc_url($icon['url']); ?>"
				alt=""
				class="site-footer__deco-img h-100 w-auto"
				loading="lazy"
				decoding="async">
		</div>
	<?php endif; ?>

</footer>

<?php
// ── JSON-LD: Organization + office locations ───────────────────────────────
$schema = [
	'@context' => 'https://schema.org',
	'@type'    => 'Organization',
	'name'     => get_bloginfo('name'),
	'url'      => home_url('/'),
];

if ($logo && !empty($logo['url'])) {
	$schema['logo'] = esc_url($logo['url']);
}

if ($offices) {
	$locations = [];
	foreach ($offices as $office) {
		$name    = $office['site_office_name']    ?? '';
		$address = $office['site_office_address'] ?? '';
		$text    = $office['site_office_text']    ?? '';
		if (!$name) continue;
		$locations[] = [
			'@type'   => 'Place',
			'name'    => $name,
			'address' => array_filter([
				'@type'         => 'PostalAddress',
				'streetAddress' => $address,
				'description'   => $text,
			]),
		];
	}
	if ($locations) {
		$schema['location'] = count($locations) === 1 ? $locations[0] : $locations;
	}
}
?>
<script type="application/ld+json">
<?php echo wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>
</script>

<?php wp_footer(); ?>

</body>

</html>