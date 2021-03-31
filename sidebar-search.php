<?php

if ( ! is_active_sidebar( 'sidebar-search' ) ) {
	return;
}
?>

<aside id="secondary" class="sidebar-search">
	<?php dynamic_sidebar( 'sidebar-search' ); ?>
</aside><!-- #secondary -->