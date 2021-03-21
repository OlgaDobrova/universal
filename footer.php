  <footer class="footer">
    <div class="container"> 
      <div class="footer-menu-bar">
      	<?php dynamic_sidebar( 'sidebar-footer' ); ?>
      </div>
      <!--/.footer-menu-bar  -->
      <div class="footer-info">
        <?php
          wp_nav_menu( [
	          'theme_location'  => 'footer_menu',
	          'container'       => 'nav', 
	          'menu_class'      => 'footer-nav', 
	          'echo'            => true,
          ] );

          $instance = array(
            'fb' => 'http://fb.com/',
            'ig' => 'http://instagram.com/',
            'vk' => 'http://vk.com/',
            'tw' => 'http://twitter.com/',
            'title' => '',
          );
          $args = array(
            'before_widget' => '<div class="footer-social">',
            'after_widget' => '</div>',
          );
          //вывод зарегистрированного виджета
          the_widget( 'Social_Widget', $instance, $args);
        ?>
      </div>
      <!--/.footer-info-->
      <?php 
      if ( ! is_active_sidebar( 'sidebar-footer' ) ) {
      	return;
      }
      ?>
      <div class="footer-text-wrapper">
        <?php dynamic_sidebar( 'sidebar-footer-text' ); ?>
        <span class="footer-copyright">
          <?php echo date('Y') . '&copy'. get_bloginfo( 'name' );?>
        </span>
      </div>
      <!--/.footer-text-wrapper-->
    </div>
    <!--/.container-->
  </footer>
  <?php wp_footer();?>  
  </body>
</html>