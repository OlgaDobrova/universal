<?php 
// если ф-ция создания темы не задана
if ( ! function_exists( 'universal_theme_setup' ) ) :
  // то создадим ф-цию добавления 
    function universal_theme_setup() {
      //добавление заголовка сайта
      add_theme_support( 'title-tag' );
      //добавление пользовательского логотипа
      add_theme_support( 'custom-logo', [
	        'width'                  => 163,
	        'flex-height'            => true,
	        'header-text'            => 'Universal',
          'unlink-homepage-logo'   => false, //WP 5.5
      ] );
      //добавление меню
      //register_nav_menus - резервирует места на сайте, где будет расположено меню
      register_nav_menus( [
		    'header_menu' => 'Меню в шапке',
		    'footer_menu' => 'Меню в подвале'
	    ] );

    }
endif;

add_action( 'after_setup_theme', 'universal_theme_setup' );

// правильный способ подключить стили и скрипты

function enqueue_universal_style() {
	wp_enqueue_style( 'style', get_stylesheet_uri() );
  wp_enqueue_style( 'universal_theme-style', get_template_directory_uri( ).'/assets/css/universal_theme.css','style',time());
}
add_action( 'wp_enqueue_scripts', 'enqueue_universal_style' );