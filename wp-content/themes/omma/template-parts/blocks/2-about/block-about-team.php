<?php

/**
 * Block: About Team
 *
 * Campos consumidos:
 *   get_field('block_abt_team_items') → repeater
 *     block_abt_team_image  (image, array)
 *     block_abt_team_name   (text)
 *     block_abt_team_role   (text)
 */

$title = function_exists('get_field') ? get_field('block_abt_team_title') : '';
$items = function_exists('get_field') ? get_field('block_abt_team_items') : [];

if (! $items) {
	return;
}

?>

<section class="block-about-team bg-charcoal py-5 py-lg-6">
	<div class="container-fluid px-4 px-lg-5">

		<?php if ($title) : ?>
			<div class="row mb-4 mb-lg-5">
				<div class="col-12">
					<h2 class="h-2 h-lg-1 text-vanilla">
						<?php echo esc_html($title); ?>
					</h2>
				</div>
			</div>
		<?php endif; ?>

		<div class="row g-3 g-lg-4">
			<?php foreach ($items as $member) :
				$photo = $member['block_abt_team_image'] ?? null;
				$name  = $member['block_abt_team_name']  ?? '';
				$role  = $member['block_abt_team_role']  ?? '';
			?>
				<div class="col-12 col-md-6 col-lg-4">
					<div class="block-about-team__card">

						<?php if ($photo) : ?>
							<figure class="block-about-team__photo-wrap m-0">
								<img
									src="<?php echo esc_url($photo['url']); ?>"
									alt="<?php echo esc_attr($photo['alt'] ?: $name); ?>"
									width="<?php echo (int) $photo['width']; ?>"
									height="<?php echo (int) $photo['height']; ?>"
									loading="lazy"
									decoding="async"
									class="block-about-team__photo"
								>
							</figure>
						<?php endif; ?>

						<div class="block-about-team__info d-flex justify-content-between align-items-start gap-3">
							<?php if ($name) : ?>
								<p class="block-about-team__name h-6 text-vanilla mb-0">
									<?php echo esc_html($name); ?>
								</p>
							<?php endif; ?>
							<?php if ($role) : ?>
								<p class="block-about-team__role fs-small mb-0 text-end">
									<?php echo esc_html($role); ?>
								</p>
							<?php endif; ?>
						</div>

					</div>
				</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>
