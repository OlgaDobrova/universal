<?php 
// если ф-ция создания темы не задана
if ( ! function_exists( 'universal_theme_setup' ) ) :
  // то создадим ф-цию добавления 
    function universal_theme_setup() {

			// Удаляем роль при деактивации нашей темы
			add_action( 'switch_theme', 'deactivate_universal_theme' );
			function deactivate_universal_theme() {
				remove_role( 'developer' );
			}

			// Добавляем роль при активации нашей темы
			add_action( 'after_switch_theme', 'activate_universal_theme' );
			function activate_universal_theme() {
				// Получим объект данных роли "Автор"
				$author = get_role( 'author' );
				add_role( 'developer', 'Разработчик', $author->capabilities );
				add_role( 'admin', 'Фотограф', $author->capabilities );
				add_role( 'User', 'Фрилансер', $author->capabilities );
			}

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

## Шаблон для создания нового типа записи видеоурок
add_action( 'init', 'register_post_types' );
function register_post_types(){
	register_post_type( 'lesson', [
		'label'  => null,
		'labels' => [
			'name'               => 'Уроки', // основное название для типа записи
			'singular_name'      => 'Урок', // название для одной записи этого типа
			'add_new'            => 'Добавить урок', // для добавления новой записи
			'add_new_item'       => 'Добавление урока', // заголовка у вновь создаваемой записи в админ-панели.
			'edit_item'          => 'Редактирование урока', // для редактирования типа записи
			'new_item'           => 'Новый урок', // текст новой записи
			'view_item'          => 'Смотреть урок', // для просмотра записи этого типа.
			'search_items'       => 'Искать урок', // для поиска по этим типам записи
			'not_found'          => 'Не найдено', // если в результате поиска ничего не было найдено
			'not_found_in_trash' => 'Не найдено в корзине', // если не было найдено в корзине
			'parent_item_colon'  => '', // для родителей (у древовидных типов)
			'menu_name'          => 'Уроки', // название меню
		],
		'description'         => 'Разлел с видеоуроками',
		'public'              => true,
		// 'publicly_queryable'  => null, // зависит от public
		// 'exclude_from_search' => null, // зависит от public
		// 'show_ui'             => null, // зависит от public
		// 'show_in_nav_menus'   => null, // зависит от public
		'show_in_menu'        => true, // показывать ли в меню адмнки
		// 'show_in_admin_bar'   => null, // зависит от show_in_menu
		'show_in_rest'        => true, // добавить в REST API. C WP 4.7
		'rest_base'           => null, // $post_type. C WP 4.7
		'menu_position'       => 5, //Позиция где должно расположится меню нового типа записи: 4-9 — под «Записи»
		'menu_icon'           => 'dashicons-welcome-learn-more', //это иконка - студ.шапка
		'capability_type'   => 'post',
		//'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
		'map_meta_cap'      => true, // Ставим true чтобы включить дефолтный обработчик специальных прав
		'hierarchical'        => false,//неиерархический тип записи
		'supports'            => [ 'title', 'editor','thumbnail','custom-fields' ], // 'title','editor','author','excerpt','trackbacks','comments','revisions','page-attributes','post-formats'
		'taxonomies'          => [],
		'has_archive'         => true, //есть архивы этих записей
		'rewrite'             => true,
		'query_var'           => true,
	] );
}

// хук, через который подключается функция
// регистрирующая новые таксономии (create_lesson_taxonomies)
add_action( 'init', 'create_lesson_taxonomies' );

// функция, создающая 2 новые таксономии "genres" (жанр) и "Theacher"(учитель) для постов типа "lesson"
function create_lesson_taxonomies(){

	// Добавляем древовидную таксономию 'genre' (как категории)
	register_taxonomy('genre', array('lesson'), array(
		'hierarchical'  => true, //признак иерархической структуры
		'labels'        => array(  //как именно склонять слово Жанр в разных случаях
			'name'              => _x( 'Genres', 'taxonomy general name' ),
			'singular_name'     => _x( 'Genre', 'taxonomy singular name' ),
			'search_items'      =>  __( 'Search Genres' ),
			'all_items'         => __( 'All Genres' ),
			'parent_item'       => __( 'Parent Genre' ),
			'parent_item_colon' => __( 'Parent Genre:' ),
			'edit_item'         => __( 'Edit Genre' ),
			'update_item'       => __( 'Update Genre' ),
			'add_new_item'      => __( 'Add New Genre' ),
			'new_item_name'     => __( 'New Genre Name' ),
			'menu_name'         => __( 'Genre' ),
		),
		'show_ui'       => true, //показать в меню
		'query_var'     => true,
		'rewrite'       => array( 'slug' => 'the_genre' ), // свой слаг в URL
	));

	// Добавляем НЕ древовидную таксономию 'theacher' (как метки)
	register_taxonomy('theacher', 'lesson',array(
		'hierarchical'  => false,
		'labels'        => array(
			'name'                        => _x( 'Theacher', 'taxonomy general name' ),
			'singular_name'               => _x( 'Theacher', 'taxonomy singular name' ),
			'search_items'                =>  __( 'Search Theacher' ),
			'popular_items'               => __( 'Popular Theacher' ),
			'all_items'                   => __( 'All Theachers' ),
			'parent_item'                 => null,
			'parent_item_colon'           => null,
			'edit_item'                   => __( 'Edit Theacher' ),
			'update_item'                 => __( 'Update Theacher' ),
			'add_new_item'                => __( 'Add New Theacher' ),
			'new_item_name'               => __( 'New Theacher Name' ),
			'separate_items_with_commas'  => __( 'Separate theachers with commas' ),
			'add_or_remove_items'         => __( 'Add or remove theachers' ),
			'choose_from_most_used'       => __( 'Choose from the most used theachers' ),
			'menu_name'                   => __( 'Theachers' ),
		),
		'show_ui'       => true,
		'query_var'     => true,
		'rewrite'       => array( 'slug' => 'the_theacher' ), // свой слаг в URL
	));
}

/**
 * Регистрация области виджета (подключение сайдбара).
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
//функция инициализации виджетов в нашей теме
//регистрация сайдбара
function universal_theme_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Сайдбар на главной сверху', 'universal_theme' ),
			'id'            => 'main-sidebar-top',
			'description'   => esc_html__( 'Добавьте виджеты сюда', 'universal_theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Сайдбар на главной снизу', 'universal_theme' ),
			'id'            => 'main-sidebar-bottom',
			'description'   => esc_html__( 'Добавьте виджеты сюда', 'universal_theme' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Меню в подвале', 'universal_theme' ),
			'id'            => 'sidebar-footer',
			'description'   => esc_html__( 'Добавьте меню сюда', 'universal_theme' ),
			'before_widget' => '<section id="%1$s" class="footer-menu %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="footer-menu-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Текст в подвале', 'universal_theme' ),
			'id'            => 'sidebar-footer-text',
			'description'   => esc_html__( 'Добавьте текст сюда', 'universal_theme' ),
			'before_widget' => '<section id="%1$s" class="footer-text %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '',
			'after_title'   => '',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__( 'Сайдбар на странице поиска', 'universal_theme' ),
			'id'            => 'sidebar-search',
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
			//target="_blank" - открыть ссылку в новом окне
			echo '<a target="_blank" class="widget-link" href="' . $link .'">
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
			array( 'description' => 'Популярные соц сети', 'classname' => 'widget_social', )
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
		$fb = $instance['fb'] ;
		$ig = $instance['ig'] ;
		$vk = $instance['vk'] ;
		$tw = $instance['tw'] ;

		echo $args['before_widget'];
		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		if ( ! empty( $fb ) ) {
			echo '<a target="_blank" class="widget-social" href="' . $fb .'">
			
			<img class="widget-social-icon" src=" ' . get_template_directory_uri() . '/assets/images/facebook.svg"></a>';	}
		if ( ! empty( $ig ) ) {
			echo '<a target="_blank" class="widget-social" href="' . $ig .'">
			<img class="widget-social-icon" src=" ' . get_template_directory_uri().'/assets/images/instagram.svg"></a>';	}
		if ( ! empty( $vk ) ) {
			echo '<a target="_blank" class="widget-social" href="' . $vk .'">
			<img class="widget-social-icon" src=" ' . get_template_directory_uri().'/assets/images/vk.svg"></a>';	}
		if ( ! empty( $tw ) ) {
			echo '<a target="_blank" class="widget-social" href="' . $tw .'">
			<img class="widget-social-icon" src=" ' . get_template_directory_uri().'/assets/images/twitter.svg"></a>';	}

		echo $args['after_widget'];
	}

	/**
	 * Админ-часть виджета
	 *
	 * @param array $instance сохраненные данные из настроек
	 */
	function form( $instance ) { //объявление переменных
		$title = @ $instance['title'] ?: 'Популярные соц сети'; //Заголовок по умолчанию
		$fb = @ $instance['fb'] ?: ''; //Заголовка по умолчанию нет
		$ig = @ $instance['ig'] ?: ''; //Заголовка по умолчанию нет
		$vk = @ $instance['vk'] ?: ''; //Заголовка по умолчанию нет
		$tw = @ $instance['tw'] ?: ''; //Заголовка по умолчанию нет

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Заголовок:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'fb' ); ?>"><?php _e( 'facebook:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'fb' ); ?>" name="<?php echo $this->get_field_name( 'fb' ); ?>" type="text" value="<?php echo esc_attr( $fb ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'ig' ); ?>"><?php _e( 'instagram:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'ig' ); ?>" name="<?php echo $this->get_field_name( 'ig' ); ?>" type="text" value="<?php echo esc_attr( $ig ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'vk' ); ?>"><?php _e( 'vk:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'vk' ); ?>" name="<?php echo $this->get_field_name( 'vk' ); ?>" type="text" value="<?php echo esc_attr( $vk ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'tw' ); ?>"><?php _e( 'twitter:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'tw' ); ?>" name="<?php echo $this->get_field_name( 'tw' ); ?>" type="text" value="<?php echo esc_attr( $tw ); ?>">
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
		$instance['fb'] = ( ! empty( $new_instance['fb'] ) ) ? strip_tags( $new_instance['fb'] ) : '';
		$instance['ig'] = ( ! empty( $new_instance['ig'] ) ) ? strip_tags( $new_instance['ig'] ) : '';
		$instance['vk'] = ( ! empty( $new_instance['vk'] ) ) ? strip_tags( $new_instance['vk'] ) : '';
		$instance['tw'] = ( ! empty( $new_instance['tw'] ) ) ? strip_tags( $new_instance['tw'] ) : '';

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

/**
 * Добавление нового виджета Recent_Posts_Widget (Виджет последних постов).
 */
class Recent_Posts_Widget extends WP_Widget {

	// Регистрация виджета используя основной класс
	function __construct() {
		// вызов конструктора выглядит так:
		// __construct( $id_base, $name, $widget_options = array(), $control_options = array() )
		parent::__construct(
			'recent_posts_widget', // ID виджета, если не указать (оставить ''), то ID будет равен названию класса в нижнем регистре: recent_posts_widget
			'Недавно опубликовано',
			array( 'description' => 'Последние посты', 'classname' => 'widget-recent-posts', )
		);

		// скрипты/стили виджета, только если он активен
		if ( is_active_widget( false, false, $this->id_base ) || is_customize_preview() ) {
			add_action('wp_enqueue_scripts', array( $this, 'add_recent_posts_widget_scripts' ));
			add_action('wp_head', array( $this, 'add_recent_posts_widget_style' ) );
		}
	}

	/**
	 * Вывод виджета во Фронт-энде (на сайте)
	 *
	 * @param array $args     аргументы виджета.
	 * @param array $instance сохраненные данные из настроек
	 */
	function widget( $args, $instance ) {
		$title = $instance['title'];
		$count = $instance['count'];

    //вывод всего, что перед виджетом
		echo $args['before_widget'];

		if ( ! empty( $count ) ) {
			if ( ! empty( $title ) ) {
				echo $args['before_title'] . $title . $args['after_title'];	
			}
			echo '<div class="widget-recent-posts-wrapper">';
				global $post;
				$postslist = get_posts( array( 'posts_per_page' => $count, 'order'=> 'ASC', 'orderby' => 	'title' ) );
				foreach ( $postslist as $post ){
					setup_postdata($post);
					?>
					<a href="<?php the_permalink()?>" class="recent-post-link">
						<img class="recent-posts-thumb" src="<?php 
							if ( has_post_thumbnail() ) {
                echo get_the_post_thumbnail_url();
            	}
            	else {
            	    echo get_template_directory_uri().'/assets/images/img-default.png';
            	}
						?>" alt="">
						<div class="recent-posts-info">
							<h4><?php echo mb_strimwidth(get_the_title(),0,30,'...') ; ?></h4>
							<span class="recent-posts-time">
								<?php 
								$time_diff = human_time_diff( get_post_time('U'), current_time('timestamp') );
								echo "$time_diff назад";
								//> Опубликовано 5 лет назад.
								?>
							</span>
						</div>
					</a>
					<?php
				}
				wp_reset_postdata();
			echo '</div>';
		}
    //вывод всего, что после виджета
		echo $args['after_widget'];
	}

	/**
	 * Админ-часть виджета
	 *
	 * @param array $instance сохраненные данные из настроек
	 */
	function form( $instance ) {
		$title = @ $instance['title'] ?: 'Недавно опубликовано';
    $count = @ $instance['count'] ?: '7';

		?>
    <!--Заголовок-->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Заголовок:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
    <!--Описание-->
    <p>
			<label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php _e( 'Количество постов:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" type="text" value="<?php echo esc_attr( $count ); ?>">
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
		$instance['count'] = ( ! empty( $new_instance['count'] ) ) ? strip_tags( $new_instance['count'] ) : '';
		return $instance;
	}

	// скрипт виджета
	function add_recent_posts_widget_scripts() {
		// фильтр чтобы можно было отключить скрипты
		if( ! apply_filters( 'show_my_widget_script', true, $this->id_base ) )
			return;

		$theme_url = get_stylesheet_directory_uri();

		wp_enqueue_script('my_widget_script', $theme_url .'/my_widget_script.js' );
	}

	// стили виджета
	function add_recent_posts_widget_style() {
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
// конец класса Recent_Posts_Widget

// регистрация Recent_Posts_Widget в WordPress
function register_recent_posts_widget() {
	register_widget( 'Recent_Posts_Widget' );
}
add_action( 'widgets_init', 'register_recent_posts_widget' );
//Завершили всю регистрацию Recent_Posts_Widget (Виджет последних постов)




// правильный способ подключить стили и скрипты

function enqueue_universal_style() {
	wp_enqueue_style( 'style', get_stylesheet_uri() );
  wp_enqueue_style( 'swiper-slider', get_template_directory_uri( ).'/assets/css/swiper-bundle.min.css','style',time());
	wp_enqueue_style( 'universal_theme-style', get_template_directory_uri( ).'/assets/css/universal_theme.css','style',time());
  wp_enqueue_style('Roboto-Slab','https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@700&display=swap');
	wp_deregister_script( 'jquery-core' );
	wp_register_script( 'jquery-core', '//code.jquery.com/jquery-3.6.0.min.js');
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script('swiper', get_template_directory_uri().'/assets/js/swiper-bundle.min.js',null,time(),true);
	wp_enqueue_script('scripts',get_template_directory_uri().'/assets/js/scripts.js','swiper',time(),true);
}
add_action( 'wp_enqueue_scripts', 'enqueue_universal_style' );

## ИЩЕМ МЕСТО, где на хостинге лежит папка WP-ADMIN (в ней admin-ajax.php, нужен к нему доступ)
//    к какому событию цепляемся, называем ф-цию для исполнения, одномерный/двумерный массив
add_action( 'wp_enqueue_scripts', 'adminAjax_data', 99 );
//объявляем ф-цию
function adminAjax_data(){
	//цепляемся к событию - jquery , те название скрипта, перед которым будут добавлены данные
	//adminAjax - это название Javascript объекта, который будет содержать данные
	wp_localize_script( 'jquery', 'adminAjax', 
		//с помощью wp_localize_script создается массив с данными о месте, где на хостинге находится папка wp-admin
		array( 
		'url' => admin_url('admin-ajax.php') 
		)
	);
}

##Обработчик формы обратной связи
add_action( 'wp_ajax_contacts_form', 'ajax_form' );
add_action( 'wp_ajax_nopriv_contacts_form', 'ajax_form' );
function ajax_form() {
	$contact_name = $_POST['contact_name'];
	$contact_email = $_POST['contact_email'];
	$contact_comment = $_POST['contact_comment'];

	$message = 'Уважаемый(ая), '.$contact_name.'! Вы оставили вопрос на сайте Universall: "'.			$contact_comment.'". Ваш Email: "'.$contact_email.'" Ваше сообщение успешно передано на обработку.';
	//от кого письмо
	$headers = 'From: Ольга Доброва <dobrova_o_g@mail.ru>' . "\r\n";

					//кому письмо, Тема, Содержание
	$sent_message = wp_mail($contact_email, 'Новая заявка с сайта', $message, $headers);

	if ($sent_message) {
		echo 'Отправка свершилась!';
	} else {
		echo 'Что-то сломалось :(';
	}

	// выход нужен для того, чтобы в ответе не было ничего лишнего, только то что возвращает функция
	wp_die();
}


##изменяем настройки для облака тегов
//        к какому событию цепляемся,   называем ф-цию для исполнения
add_filter('widget_tag_cloud_args','edit_widget_tag_cloud_args');
//объявляем ф-цию
function edit_widget_tag_cloud_args( $args ){
	$args['unit']='px';   //ед.измерения меняем на пиксели
	$args['smallest']=14; //фильтруем (переназначаем) Размер текста для меток с min кол-вом записей
	$args['largest']=14;  //Размер текста для меток с max количеством записей
	$args['number']=8;	  //Максимально количество меток, которое будет показано в списке
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

## меняем стиль многоточия в отрывках
add_filter( 'excerpt_more', function($more) {
	return '...';
});


//ф-ция склонения слов полсе числительных
function plural_form($number, $after) {
	$cases = array (2, 0, 1, 1, 1, 2);
	echo $number.' '.$after[ ($number%100>4 && $number%100<20)? 2: $cases[min($number%10, 5)] ];
}

/*
 * "Хлебные крошки" для WordPress
 * автор: Dimox
 * версия: 2019.03.03
 * лицензия: MIT
*/
function the_breadcrumbs() {

	/* === ОПЦИИ === */
	$text['home']     = 'Главная'; // текст ссылки "Главная"
	$text['category'] = '%s'; // текст для страницы рубрики
	$text['search']   = 'Результаты поиска по запросу "%s"'; // текст для страницы с результатами поиска
	$text['tag']      = 'Записи с тегом "%s"'; // текст для страницы тега
	$text['author']   = 'Статьи автора %s'; // текст для страницы автора
	$text['404']      = 'Ошибка 404'; // текст для страницы 404
	$text['page']     = 'Страница %s'; // текст 'Страница N'
	$text['cpage']    = 'Страница комментариев %s'; // текст 'Страница комментариев N'

	$wrap_before    = '<div class="breadcrumbs" itemscope itemtype="http://schema.org/BreadcrumbList">'; // открывающий тег обертки
	$wrap_after     = '</div><!-- .breadcrumbs -->'; // закрывающий тег обертки
	$sep            = '<span class="breadcrumbs__separator"> › </span>'; // разделитель между "крошками"
	$before         = '<span class="breadcrumbs__current">'; // тег перед текущей "крошкой"
	$after          = '</span>'; // тег после текущей "крошки"

	$show_on_home   = 0; // 1 - показывать "хлебные крошки" на главной странице, 0 - не показывать
	$show_home_link = 1; // 1 - показывать ссылку "Главная", 0 - не показывать
	$show_current   = 1; // 1 - показывать название текущей страницы, 0 - не показывать
	$show_last_sep  = 1; // 1 - показывать последний разделитель, когда название текущей страницы не отображается, 0 - не показывать
	/* === КОНЕЦ ОПЦИЙ === */

	global $post;
	$home_url       = home_url('/');
	$link           = '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
	$link          .= '<a class="breadcrumbs__link" href="%1$s" itemprop="item"><span itemprop="name">%2$s</span></a>';
	$link          .= '<meta itemprop="position" content="%3$s" />';
	$link          .= '</span>';
	$parent_id      = ( $post ) ? $post->post_parent : '';
	$home_link      = sprintf( $link, $home_url, $text['home'], 1 );

	if ( is_home() || is_front_page() ) {

		if ( $show_on_home ) echo $wrap_before . $home_link . $wrap_after;

	} else {

		$position = 0;

		echo $wrap_before;

		if ( $show_home_link ) {
			$position += 1;
			echo $home_link;
		}

		//если мы на странице категорий
		if ( is_category() ) { echo $sep.'Категории';
			//get_ancestors() - Возвращает массив идентификаторов (ID) родительских элементов, где последняя ячейка массива будет содержать ID самого верхнего элемента цепочки. 
															//это ID выбранной категории, тип - категории
			$parents = get_ancestors( get_query_var('cat'), 'category' );
			//перебираем массив ID категорий, начиная с ID самого верхнего уровня
			foreach ( array_reverse( $parents ) as $cat ) {
				$position += 1;
				//если это еще не последний эл-т, то выводим разделитель (м/д "крошками")
				if ( $position > 1 ) echo $sep;
				//потом ставим имя категории с корректной на него ссылкой
				echo sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ), $position );
			} //конец цикла

			if ( get_query_var( 'paged' ) ) { 
				$position += 1;
				$cat = get_query_var('cat');
				echo $sep . sprintf($link, get_category_link( $cat ), get_cat_name( $cat ), $position );
				echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
			} else {
				//если мы на текущей стр
				if ( $show_current ) { 
					if ( $position >= 1 ) echo $sep;
					echo $before . sprintf($text['category'], single_cat_title( '', false ) ) . $after;
				} elseif ( $show_last_sep ) echo $sep;
			}

			//если мы на стр поиска
		} elseif ( is_search() ) {
			if ( get_query_var( 'paged' ) ) {
				$position += 1;
				if ( $show_home_link ) echo $sep;
				echo sprintf( $link, $home_url . '?s=' . get_search_query(), sprintf( $text['search'], get_search_query() ), $position );
				echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
			} else {
				if ( $show_current ) {
					if ( $position >= 1 ) echo $sep;
					echo $before . sprintf( $text['search'], get_search_query() ) . $after;
				} elseif ( $show_last_sep ) echo $sep;
			}

		} elseif ( is_year() ) {
			if ( $show_home_link && $show_current ) echo $sep;
			if ( $show_current ) echo $before . get_the_time('Y') . $after;
			elseif ( $show_home_link && $show_last_sep ) echo $sep;

		} elseif ( is_month() ) {
			if ( $show_home_link ) echo $sep;
			$position += 1;
			echo sprintf( $link, get_year_link( get_the_time('Y') ), get_the_time('Y'), $position );
			if ( $show_current ) echo $sep . $before . get_the_time('F') . $after;
			elseif ( $show_last_sep ) echo $sep;

		} elseif ( is_day() ) {
			if ( $show_home_link ) echo $sep;
			$position += 1;
			echo sprintf( $link, get_year_link( get_the_time('Y') ), get_the_time('Y'), $position ) . $sep;
			$position += 1;
			echo sprintf( $link, get_month_link( get_the_time('Y'), get_the_time('m') ), get_the_time('F'), $position );
			if ( $show_current ) echo $sep . $before . get_the_time('d') . $after;
			elseif ( $show_last_sep ) echo $sep;

		} elseif ( is_single() && ! is_attachment() ) {
			if ( get_post_type() != 'post' ) {
				$position += 1;
				$post_type = get_post_type_object( get_post_type() );
				if ( $position > 1 ) echo $sep;
				echo sprintf( $link, get_post_type_archive_link( $post_type->name ), $post_type->labels->name, $position );
				if ( $show_current ) echo $sep . $before . get_the_title() . $after;
				elseif ( $show_last_sep ) echo $sep;
			} else {
				$cat = get_the_category(); $catID = $cat[0]->cat_ID;
				$parents = get_ancestors( $catID, 'category' );
				$parents = array_reverse( $parents );
				$parents[] = $catID;
				foreach ( $parents as $cat ) {
					$position += 1;
					if ( $position > 1 ) echo $sep;
					echo sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ), $position );
				}
				if ( get_query_var( 'cpage' ) ) {
					$position += 1;
					echo $sep . sprintf( $link, get_permalink(), get_the_title(), $position );
					echo $sep . $before . sprintf( $text['cpage'], get_query_var( 'cpage' ) ) . $after;
				} else {
					if ( $show_current ) echo $sep . $before . get_the_title() . $after;
					elseif ( $show_last_sep ) echo $sep;
				}
			}

		} elseif ( is_post_type_archive() ) {
			$post_type = get_post_type_object( get_post_type() );
			if ( get_query_var( 'paged' ) ) {
				$position += 1;
				if ( $position > 1 ) echo $sep;
				echo sprintf( $link, get_post_type_archive_link( $post_type->name ), $post_type->label, $position );
				echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
			} else {
				if ( $show_home_link && $show_current ) echo $sep;
				if ( $show_current ) echo $before . $post_type->label . $after;
				elseif ( $show_home_link && $show_last_sep ) echo $sep;
			}

		} elseif ( is_attachment() ) {
			$parent = get_post( $parent_id );
			$cat = get_the_category( $parent->ID ); $catID = $cat[0]->cat_ID;
			$parents = get_ancestors( $catID, 'category' );
			$parents = array_reverse( $parents );
			$parents[] = $catID;
			foreach ( $parents as $cat ) {
				$position += 1;
				if ( $position > 1 ) echo $sep;
				echo sprintf( $link, get_category_link( $cat ), get_cat_name( $cat ), $position );
			}
			$position += 1;
			echo $sep . sprintf( $link, get_permalink( $parent ), $parent->post_title, $position );
			if ( $show_current ) echo $sep . $before . get_the_title() . $after;
			elseif ( $show_last_sep ) echo $sep;

		} elseif ( is_page() && ! $parent_id ) {
			if ( $show_home_link && $show_current ) echo $sep;
			if ( $show_current ) echo $before . get_the_title() . $after;
			elseif ( $show_home_link && $show_last_sep ) echo $sep;

		} elseif ( is_page() && $parent_id ) {
			$parents = get_post_ancestors( get_the_ID() );
			foreach ( array_reverse( $parents ) as $pageID ) {
				$position += 1;
				if ( $position > 1 ) echo $sep;
				echo sprintf( $link, get_page_link( $pageID ), get_the_title( $pageID ), $position );
			}
			if ( $show_current ) echo $sep . $before . get_the_title() . $after;
			elseif ( $show_last_sep ) echo $sep;

		} elseif ( is_tag() ) {
			if ( get_query_var( 'paged' ) ) {
				$position += 1;
				$tagID = get_query_var( 'tag_id' );
				echo $sep . sprintf( $link, get_tag_link( $tagID ), single_tag_title( '', false ), $position );
				echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
			} else {
				if ( $show_home_link && $show_current ) echo $sep;
				if ( $show_current ) echo $before . sprintf( $text['tag'], single_tag_title( '', false ) ) . $after;
				elseif ( $show_home_link && $show_last_sep ) echo $sep;
			}

		} elseif ( is_author() ) {
			$author = get_userdata( get_query_var( 'author' ) );
			if ( get_query_var( 'paged' ) ) {
				$position += 1;
				echo $sep . sprintf( $link, get_author_posts_url( $author->ID ), sprintf( $text['author'], $author->display_name ), $position );
				echo $sep . $before . sprintf( $text['page'], get_query_var( 'paged' ) ) . $after;
			} else {
				if ( $show_home_link && $show_current ) echo $sep;
				if ( $show_current ) echo $before . sprintf( $text['author'], $author->display_name ) . $after;
				elseif ( $show_home_link && $show_last_sep ) echo $sep;
			}

		} elseif ( is_404() ) {
			if ( $show_home_link && $show_current ) echo $sep;
			if ( $show_current ) echo $before . $text['404'] . $after;
			elseif ( $show_last_sep ) echo $sep;

		} elseif ( has_post_format() && ! is_singular() ) {
			if ( $show_home_link && $show_current ) echo $sep;
			echo get_post_format_string( get_post_format() );
		}

		echo $wrap_after;

	}
} // end of the_breadcrumbs()