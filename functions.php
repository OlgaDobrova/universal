<?php 
// если ф-ция создания темы не задана
if ( ! function_exists( 'universal_theme_setup' ) ) :
  // то создадим ф-цию добавления 
    function universal_theme_setup() {
      //добавление заголовка сайта
      add_theme_support( 'title-tag' );
      //Добавление миниатюр
      add_theme_support( 'post-thumbnails', array( 'post' ) );
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

/**
 * Регистрация области виджета (подключение сайдбара).
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
//функция инициализации виджетов в нашей теме
function universal_theme_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Сайдбар на главной', 'universal_theme' ),
			'id'            => 'main-sidebar',
			'description'   => esc_html__( 'Добавьте виджеты сюда', 'universal_theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Сайдбар с последними постами', 'universal_theme' ),
			'id'            => 'post-sidebar',
			'description'   => esc_html__( 'Добавьте виджеты сюда', 'universal_theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'universal_theme_widgets_init' );

/**
 * Добавление нового виджета Downloader_Widget (Виджет загрузчика).
 */
class Downloader_Widget extends WP_Widget {

	// Регистрация виджета используя основной класс
	function __construct() {
		// вызов конструктора выглядит так:
		// __construct( $id_base, $name, $widget_options = array(), $control_options = array() )
		parent::__construct(
			'downloader_widget', // ID виджета, если не указать (оставить ''), то ID будет равен названию класса в нижнем регистре: downloader_widget
			'Полезные файлы',
			array( 'description' => 'Файлы для скачивания', 'classname' => 'widget-downloader', )
		);

		// скрипты/стили виджета, только если он активен
		if ( is_active_widget( false, false, $this->id_base ) || is_customize_preview() ) {
			add_action('wp_enqueue_scripts', array( $this, 'add_downloader_widget_scripts' ));
			add_action('wp_head', array( $this, 'add_downloader_widget_style' ) );
		}
	}

	/**
	 * Вывод виджета во Фронт-энде (на сайте)
	 *
	 * @param array $args     аргументы виджета.
	 * @param array $instance сохраненные данные из настроек
	 */
	function widget( $args, $instance ) {
		$title = $instance['title'] ;
    $description = $instance['description'] ;
    $link = $instance['link'] ;
    //вывод всего, что перед виджетом
		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];	}

    if ( ! empty( $description ) ) {
			echo '<p>'. $description . '</p>';	}
    
    if ( ! empty( $link ) ) {
			echo '<a target="black" class="widget-link" href="' . $link .'">
			<img class="widget-link-icon" src=" ' . get_template_directory_uri().'/assets/images/download.svg">
			Скачать</a>';	}

    //вывод всего, что после виджета
		echo $args['after_widget'];
	}

	/**
	 * Админ-часть виджета
	 *
	 * @param array $instance сохраненные данные из настроек
	 */
	function form( $instance ) {
		$title = @ $instance['title'] ?: 'Тема для скачивания';
    $description = @ $instance['description'] ?: 'Файлы для скачивания';
    $link = @ $instance['link'] ?: 'http://';

		?>
    <!--Заголовок-->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Заголовок:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
    <!--Описание-->
    <p>
			<label for="<?php echo $this->get_field_id( 'description' ); ?>"><?php _e( 'Описание:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>" type="text" value="<?php echo esc_attr( $description ); ?>">
		</p>
    <!--Ссылка на файл для скачивания-->
    <p>
			<label for="<?php echo $this->get_field_id( 'link' ); ?>"><?php _e( 'Ссылка на файл:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" type="text" value="<?php echo esc_attr( $link ); ?>">
		</p>
		<?php 
	}

	/**
	 * Сохранение настроек виджета. Здесь данные должны быть очищены и возвращены для сохранения их в базу данных.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance новые настройки
	 * @param array $old_instance предыдущие настройки
	 *
	 * @return array данные которые будут сохранены
	 */
	function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
    $instance['description'] = ( ! empty( $new_instance['description'] ) ) ? strip_tags( $new_instance['description'] ) : '';
    $instance['link'] = ( ! empty( $new_instance['link'] ) ) ? strip_tags( $new_instance['link'] ) : '';

		return $instance;
	}

	// скрипт виджета
	function add_downloader_widget_scripts() {
		// фильтр чтобы можно было отключить скрипты
		if( ! apply_filters( 'show_my_widget_script', true, $this->id_base ) )
			return;

		$theme_url = get_stylesheet_directory_uri();

		wp_enqueue_script('my_widget_script', $theme_url .'/my_widget_script.js' );
	}

	// стили виджета
	function add_downloader_widget_style() {
		// фильтр чтобы можно было отключить стили
		if( ! apply_filters( 'show_my_widget_style', true, $this->id_base ) )
			return;
		?>
		<style type="text/css">
			.my_widget a{ display:inline; }
		</style>
		<?php
	}

} 
// конец класса Downloader_Widget

// регистрация Downloader_Widget в WordPress
function register_downloader_widget() {
	register_widget( 'Downloader_Widget' );
}
add_action( 'widgets_init', 'register_downloader_widget' );


/**
 * Добавление нового виджета Social_Widget.
 */
class Social_Widget extends WP_Widget {

	// Регистрация виджета используя основной класс
	function __construct() {
		// вызов конструктора выглядит так:
		// __construct( $id_base, $name, $widget_options = array(), $control_options = array() )
		parent::__construct(
			'social_widget', // ID виджета, если не указать (оставить ''), то ID будет равен названию класса в нижнем регистре: social_widget
			'Ссылки на соц сети',
			array( 'description' => 'Популярные соц сети', 'classname' => 'social_widget', )
		);

		// скрипты/стили виджета, только если он активен
		if ( is_active_widget( false, false, $this->id_base ) || is_customize_preview() ) {
			add_action('wp_enqueue_scripts', array( $this, 'add_social_widget_scripts' ));
			add_action('wp_head', array( $this, 'add_social_widget_style' ) );
		}
	}

	/**
	 * Вывод виджета во Фронт-энде
	 *
	 * @param array $args     аргументы виджета.
	 * @param array $instance сохраненные данные из настроек
	 */
	function widget( $args, $instance ) {
		$title = $instance['title'] ;
		$social1 = $instance['social1'] ;
		$social2 = $instance['social2'] ;
		$social3 = $instance['social3'] ;
		$social4 = $instance['social4'] ;

		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		if ( ! empty( $social1 ) ) {
			echo '<a target="black" class="widget-social1" href="' . $social1 .'">
			<img class="widget-social1-icon" src=" ' . get_template_directory_uri().'/assets/images/facebook.svg">
			facebook</a>';	}
		if ( ! empty( $social2 ) ) {
			echo '<a href="' . $social2 .'">instagram</a>';	}
		if ( ! empty( $social3 ) ) {
			echo '<a href="' . $social3 .'">vk</a>';	}
		if ( ! empty( $social4 ) ) {
			echo '<a href="' . $social4 .'">twitter</a>';	}

		echo $args['after_widget'];
	}

	/**
	 * Админ-часть виджета
	 *
	 * @param array $instance сохраненные данные из настроек
	 */
	function form( $instance ) { //объявление переменных
		$title = @ $instance['title'] ?: 'Популярные соц сети'; //Заголовок по умолчанию
		$social1 = @ $instance['social1'] ?: ''; //Заголовка по умолчанию нет
		$social2 = @ $instance['social2'] ?: ''; //Заголовка по умолчанию нет
		$social3 = @ $instance['social3'] ?: ''; //Заголовка по умолчанию нет
		$social4 = @ $instance['social4'] ?: ''; //Заголовка по умолчанию нет

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Заголовок:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'social1' ); ?>"><?php _e( 'facebook:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'social1' ); ?>" name="<?php echo $this->get_field_name( 'social1' ); ?>" type="text" value="<?php echo esc_attr( $social1 ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'social2' ); ?>"><?php _e( 'instagram:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'social2' ); ?>" name="<?php echo $this->get_field_name( 'social2' ); ?>" type="text" value="<?php echo esc_attr( $social2 ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'social3' ); ?>"><?php _e( 'vk:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'social3' ); ?>" name="<?php echo $this->get_field_name( 'social3' ); ?>" type="text" value="<?php echo esc_attr( $social3 ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'social4' ); ?>"><?php _e( 'twitter:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'social4' ); ?>" name="<?php echo $this->get_field_name( 'social4' ); ?>" type="text" value="<?php echo esc_attr( $social4 ); ?>">
		</p>
		<?php 
	}

	/**
	 * Сохранение настроек виджета. Здесь данные должны быть очищены и возвращены для сохранения их в базу данных.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance новые настройки
	 * @param array $old_instance предыдущие настройки
	 *
	 * @return array данные которые будут сохранены
	 */
	function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['social1'] = ( ! empty( $new_instance['social1'] ) ) ? strip_tags( $new_instance['social1'] ) : '';
		$instance['social2'] = ( ! empty( $new_instance['social2'] ) ) ? strip_tags( $new_instance['social2'] ) : '';
		$instance['social3'] = ( ! empty( $new_instance['social3'] ) ) ? strip_tags( $new_instance['social3'] ) : '';
		$instance['social4'] = ( ! empty( $new_instance['social4'] ) ) ? strip_tags( $new_instance['social4'] ) : '';

		return $instance;
	}

	// скрипт виджета
	function add_social_widget_scripts() {
		// фильтр чтобы можно было отключить скрипты
		if( ! apply_filters( 'show_social_widget_script', true, $this->id_base ) )
			return;

		$theme_url = get_stylesheet_directory_uri();

		wp_enqueue_script('social_widget_script', $theme_url .'/social_widget_script.js' );
	}

	// стили виджета
	function add_social_widget_style() {
		// фильтр чтобы можно было отключить стили
		if( ! apply_filters( 'show_social_widget_style', true, $this->id_base ) )
			return;
		?>
		<style type="text/css">
			.social_widget a{ display:inline; }
		</style>
		<?php
	}

} 
// конец класса Social_Widget

// регистрация Social_Widget в WordPress
function register_social_widget() {
	register_widget( 'Social_Widget' );
}
add_action( 'widgets_init', 'register_social_widget' );



// правильный способ подключить стили и скрипты

function enqueue_universal_style() {
	wp_enqueue_style( 'style', get_stylesheet_uri() );
  wp_enqueue_style( 'universal_theme-style', get_template_directory_uri( ).'/assets/css/universal_theme.css','style',time());
  wp_enqueue_style('Roboto-Slab','https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@700&display=swap');
}
add_action( 'wp_enqueue_scripts', 'enqueue_universal_style' );

//изменяем настройки для облака тегов
//        к какому событию цепляемся,   называем ф-цию для исполнения
add_filter('widget_tag_cloud_args','edit_widget_tag_cloud_args');
//объявляем ф-цию
function edit_widget_tag_cloud_args( $args ){
	$args['unit']='px';   //ед.измерения меняем на пиксели
	$args['smallest']=14; //фильтруем (переназначаем) Размер текста для меток с min кол-вом записей
	$args['largest']=14;  //Размер текста для меток с max количеством записей
	$args['number']=11;	  //Максимально количество меток, которое будет показано в списке
	$args['orderby']='count'; //сортировка по кол-ву повторений
	return $args;         //вернуть рез изменений
}

## отключаем создание миниатюр файлов для указанных размеров
add_filter( 'intermediate_image_sizes', 'delete_intermediate_image_sizes' );
function delete_intermediate_image_sizes( $sizes ){
	// размеры которые нужно удалить
	return array_diff( $sizes, [
		'medium_large',
		'large',
		'1536x1536',
		'2048x2048',
	] );
}

if ( function_exists( 'add_image_size' ) ) {
	add_image_size( 'homepage-thumb', 65, 65, true ); // Кадрирование изображения
}