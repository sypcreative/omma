<?php

/**
 * Columnas personalizadas en el listado de CPTs del admin.
 *
 * @package omma
 */

// ── Limpieza del menú lateral del admin ───────────────────────────────────────

add_action( 'admin_menu', function () {
	remove_menu_page( 'edit.php' ); // Entradas (post type nativo)
} );
