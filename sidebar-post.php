<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package universal_theme
 */

if ( ! is_active_sidebar( 'sidebar-post' ) ) {
	return;
}
?>

<aside id="secondary" class="sidebar-content">
	<?php dynamic_sidebar( 'sidebar-post' ); ?>
</aside><!-- #secondary -->