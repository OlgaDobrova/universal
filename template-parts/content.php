<!--конкретный пост определяется по ID-->
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <!--выводим шапку поста с фото, темой, отрывком, датой и автором-->
  <!--сначала - изображение поста-на всю ширину стр-выходит за пределы контейнера-->
  <header class="entry-header <?php echo get_post_type();?>-header" style="background: linear-gradient(0deg, rgba(38, 45, 51, 0.75), rgba(38, 45, 51, 0.75)), url(
    <?php
    if ( has_post_thumbnail() ) {
      echo get_the_post_thumbnail_url();
    }
    else {
        echo get_template_directory_uri().'/assets/images/img-default.png';
    } ?> 
  );">
    <!--инфа в пределах контейнера-->
    <div class="container">
      <div class="post-header-wrapper">
        <div class="post-header-nav">
          <!--выводим категорию-->
          <?php
          //цикл пока...есть заведенные категории
          foreach (get_the_category() as $category) {
            printf('<a href="%s" class="category-link %s">%s</a>',
              //ecs_url(),esc_html() - обеспечивают безопастность ссылкам в сети
              esc_url(get_category_link( $category ) ),
              //$category -> slug как категория прописана в админке (ярлак категории)
              esc_html( $category -> slug ),
              esc_html( $category -> name ) //название категории
            );
          }
          ?>
          <!--Ссылка на главную страницу-->
          <a class="home-link" href="<?php echo get_home_url(); ?>">
           <svg   width="18" height="17" class="icon home-icon">
              <use xlink:href=" <?php echo get_template_directory_uri() ?>/assets/images/sprite.svg#home"> </use>
            </svg>
            На главную
          </a>
          <?php
          //выводим ссылки на предыдущий и следующий посты
			    the_post_navigation(
			    	array(
			    		'prev_text' => '<span class="post-nav-prev">
                  <svg width="15" height="7" class="icon prev-icon">
                    <use xlink:href="' . get_template_directory_uri() . '/assets/images/sprite.svg#left-arrow"></use>
                  </svg>
              ' . esc_html__( 'Назад', 'universal_theme'   ) . '</span>',

			    		'next_text' => '<span class="post-nav-next"> ' . esc_html__( 'Вперед',  'universal_theme'   ) . '
                  <svg width="15" height="7" class="icon next-icon">
                    <use xlink:href="' . get_template_directory_uri() . '/assets/images/sprite.svg#arrow"></use>
                  </svg>
              </span>',
			    	)
			    );
          ?>
        </div>
        <!--/.post-header-nav-->

        <!--Вставляем флажок - bookmark-->
        <svg width="30" height="30" class="bookmark">
          <use xlink:href="<?php echo get_template_directory_uri() ?>/assets/images/sprite.svg#bookmark"></use>
        </svg>

        <?php
        //вывод заголовка
        //если мы на стрнице поста
		    if ( is_singular() ) :    //то...
          //выводим заголовок в теге h1
		    	the_title( '<h1 class="post-title">', '</h1>' );
		    else :
          //иначе - заголовок в теге h2 и ссылку на пост
		    	the_title( '<h2 class="post-title"><a href="' . esc_url( get_permalink() ) . '"    rel="bookmark">', '</a></h2>' );
		    endif; ?>
        
        <!--Вставляем отрывок статьи-->
        <?php the_excerpt(); ?>

        <div class="post-header-info"> <!--подвал шапки каждого поста-->
            <!--иконка даты-->
            <svg width="13.5" height="13.5" class="icon date-icon">
              <use xlink:href="<?php echo get_template_directory_uri() ?>/assets/images/sprite.svg#clock"></use>
            </svg>
            <!--выводим дату статьи в формате день j,месяц F, год Y, напр. 1 мая 2021-->
            <span class="post-header-info-date">  <?php the_time('j F'); ?>, <?php the_time('G:i');?></span>
            <!--выводим в блоке инф о комментариях-->
            <div class="comments post-header-comments">
              <!--иконку-->
              <svg   widh="19" height="15" class="icon comments-icon">
                <use xlink:href="<?php echo get_template_directory_uri()?>/assets/images/sprite.svg#comment"></use>
              </svg>
              <!--кол-во-->
              <span class="comments-counter"><?php comments_number('0','1','%') ?></span>
            </div>
            <!--/.comments post-header-comments-->
            <div class="likes post-header-likes">
              <!--иконку для лайков-->
              <svg   class="icon post-header-info-likes-icon">
                <use xlink:href="<?php echo get_template_directory_uri()?>/assets/images/sprite.svg#heart"></use>
              </svg>
             
              <!--кол-во-->
              <span class="post-header-info-likes-counter"><?php comments_number('0','1','%') ?></span>
          </div>
          <!--/.likes post-header-likes-->
        </div>
        <!--/.post-header-info-->
        <div class="post-author">
          <div class="post-author-info">
            <?php $author_id = get_the_author_meta('ID');?>
              <img src="<?php echo get_avatar_url($author_id)?>" alt="" class="post-author-avatar">
              <span class="post-author-name"><?php the_author();?></span>
              <!--Выводим должность автора-->
              <span class="post-author-rank"><?php 
                //выведем объект со всеми ролями WordPress, обратимся к роли
                $roles=wp_roles()->roles;
                //текущая роль пользователя
                $current_role = get_the_author_meta('roles',$author_id)[0];
                //перебираем по 1 значению роли из всех ролей
                foreach ($roles as $role=>$value){
                  if($role == $current_role){
                    echo $value['name'];
                  }
                }
              ?></span>
              <span class="post-author-posts">
                <?php plural_form(count_user_posts($author_id),
                  /* варианты написания для количества 1, 2 и 5 */
	                array('статья','статьи','статей') );?> 
              </span>
          </div>
          <!--/.post-author-info-->
          <a href="<?php echo get_author_posts_url($author_id)?>" class="post-author-link">
            Страница автора
          </a>
          <!--/.post-author-link-->
        </div>
        <!--/.post-author-->
      </div>
      <!--/.post-header-wrapper-->

    </div>
    <!--/.container-прервали контейнер с инфой "внутри" фото-->
  </header>  

  <!--вся инфа после фото тоже в пределах контейнера-->
  <div class="container">
    <!-- Содержимое поста -->
    <div class="post-content">
	    <?php
        //выводим содержимое поста
	      the_content(
	      	sprintf(
	      		wp_kses(
	      			/* translators: %s: Name of current post. Only visible to screen readers */
	      			__( 'Continue reading<span class="screen-reader-text"> "%s"</span>',    'universal_theme' ),
	      			array(
	      				'span' => array(
	      					'class' => array(),
	      				),
	      			)
	      		),
	      		wp_kses_post( get_the_title() )
	      	)
	      );
	      wp_link_pages(
	      	array(
	      		'before' => '<div class="page-links">' . esc_html__( 'Страницы:', 'universal_theme' ),
	      		'after'  => '</div>',
	      	)
	      );

        //вывод тегов поста
        $tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator',   'universal_theme' ) );
	  		if ( $tags_list ) {
	  			/* translators: 1: list of tags. */
	  			printf( '<span class="tags-links">' . esc_html__( '%1$s', 'universal_theme' ) . '</ span>', $tags_list ); 
	  		}
	    ?>
	  </div>
    <!-- /.Содержимое поста -->

	  <!--Подвал поста-->
    <footer class="post-footer">
      <?php
        //вывод шеринговых кнопок - Поделиться в соцсетях
        meks_ess_share(); 
        //Подключаем сайдбар доп.постов
        get_sidebar('post');
      ?>
    </footer>
    
  </div>
  <!--/.container-->
</article>