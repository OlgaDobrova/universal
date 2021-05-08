<!DOCTYPE html>

<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php wp_head();?>
</head>

<body <?php body_class(); ?>>
 <?php wp_body_open(); ?> 
<header class="header">
  <div class="container">
    <div class="header-wrapper">
      <?php
        //если логотип есть и страница главная
        if( has_custom_logo() && is_front_page() ) {
          //тогда выводим логотип из админки и назв сайта без ссылки на главную
          echo '<div class="logo">'. get_custom_logo() .
          '<span class="logo-name">' . get_bloginfo( 'name' ) . '</span></div>';
        //если логотипа нет, страница - главная
        } elseif ( ! has_custom_logo() && is_front_page() ) {
          //тогда выводим свой логотип (svg) и назв сайта без ссылки на главную
          echo '<div class="logo">'. 
            '<svg class="logo-icon">
                          <use xlink:href="'; echo get_template_directory_uri().'/assets/images/logo.png"></use>
                        </svg> '.
          '<span  class="logo-name" >'.
              get_bloginfo( 'name' ) .
          '</span></div>';
        //если логотип есть, страница - не главная
        } elseif ( has_custom_logo() && ! is_front_page() ) {
          //тогда выводим логотип из админки и назв сайта с ссылкой на главную
          echo '<div class="logo">'. get_custom_logo() ?><span class="logo-name"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></span></div>
          <?php
        //если логотипа нет и страница - не главная
        } else {
          //тогда выводим логотип из админки и назв сайта с ссылкой на главную
          ?>
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
            <svg class="logo-icon">
              <use xlink:href="<?php echo get_template_directory_uri()?>/assets/images/logo.png"></use>
            </svg>
          <?php echo '<span class="logo-name">' . get_bloginfo( 'name' ) . '</span></a>';
        }
      ?>
      <?php
        wp_nav_menu( [
	        'theme_location'  => 'header_menu',
	        'container'       => 'nav', 
	        'container_class' => 'header-nav', 
	        'menu_class'      => 'header-menu', 
	        'echo'            => true,
        ] );
      ?>
      <?php echo get_search_form(); ?>
      <a href="#" class="header-menu-toggle">
        <span></span>
        <span></span>
        <span></span>
      </a>
    </div>
    <!--/.header-wrapper-->
  </div>
</header>