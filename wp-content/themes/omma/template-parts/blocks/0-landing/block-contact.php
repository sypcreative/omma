<?php

/**
 * Block: Contact
 * Template: template-parts/blocks/0-landing/block-contact.php
 */

$title    = get_field('block_contact_title')    ?: 'Contact Us';
$subtitle = get_field('block_contact_subtitle') ?: 'Start your project with confidence &mdash; request a free consultation today.';
$image    = get_field('block_contact_image');

?>

<section class="block-contact align-items-center" id="contact" data-progress-nav-anchor="">

	<!-- ── Form side ──────────────────────────────────────────────────────────── -->
	<div class="block-contact__form-side">
		<div class="block-contact__form-wrap">

			<h2 class="block-contact__title h-2 h-lg-1 text-uppercase mb-3" data-anim="lines">
				<?php echo esc_html($title); ?>
			</h2>

			<p class="block-contact__subtitle fs-6 mb-5" data-anim="fade-up" data-anim-delay="0.1">
				<?php echo wp_kses_post($subtitle); ?>
			</p>

			<form class="block-contact__form" data-contact-form="" novalidate>

				<div class="block-contact__row">
					<div class="block-contact__field">
						<label class="block-contact__label" for="contact-fname">
							<?php esc_html_e('First Name', 'omma'); ?>
						</label>
						<input type="text" id="contact-fname" name="fname"
							class="block-contact__input" required autocomplete="given-name">
					</div>
					<div class="block-contact__field">
						<label class="block-contact__label" for="contact-lname">
							<?php esc_html_e('Last Name', 'omma'); ?>
						</label>
						<input type="text" id="contact-lname" name="lname"
							class="block-contact__input" required autocomplete="family-name">
					</div>
				</div>

				<div class="block-contact__field">
					<label class="block-contact__label" for="contact-email">
						<?php esc_html_e('Email Address', 'omma'); ?>
					</label>
					<input type="email" id="contact-email" name="email"
						class="block-contact__input" required autocomplete="email">
				</div>

				<div class="block-contact__field">
					<label class="block-contact__label" for="contact-message">
						<?php esc_html_e('Message', 'omma'); ?>
					</label>
					<textarea id="contact-message" name="message"
						class="block-contact__input block-contact__textarea"
						rows="8" required></textarea>
				</div>

				<div class="block-contact__footer">
					<p class="block-contact__feedback" data-contact-feedback="" aria-live="polite" hidden></p>
					<button type="submit" class="btn btn-omma-blue">
						<span class="button-020__inner">
							<span class="button-020__default">
								<span class="button-020__default-bg"></span>
								<span class="button-020__default-text">
									<?php esc_html_e('Send message', 'omma'); ?>
								</span>
							</span>
							<span aria-hidden="true" class="button-020__hover">
								<span class="button-020__hover-bg"></span>
								<span class="button-020__hover-text">
									<?php esc_html_e('Send message', 'omma'); ?>
								</span>
							</span>
						</span>
					</button>
				</div>

			</form>
		</div>
	</div>

	<!-- ── Image side ─────────────────────────────────────────────────────────── -->
	<?php if ($image) : ?>
		<div class="block-contact__image-side d-none d-md-block">
			<?php echo wp_get_attachment_image(
				$image['ID'],
				'large',
				false,
				[
					'class'    => 'block-contact__image',
					'loading'  => 'lazy',
					'decoding' => 'async',
					'sizes'    => '(min-width: 992px) 50vw, 100vw',
				]
			); ?>
		</div>
	<?php endif; ?>

</section>