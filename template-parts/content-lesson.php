<!--конкретный пост определяется по ID-->
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <!--выводим шапку поста с фото, темой, отрывком, датой и автором-->
  <!--сначала - изображение поста-на всю ширину стр-выходит за пределы контейнера-->
  <header class="entry-header <?php echo get_post_type();?>-header" style="background: linear-gradient(0deg, rgba(38, 45, 51, 0.75), rgba(38, 45, 51, 0.75));">
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
        </div><!--/.post-header-nav-->

        <div class="video">
          <?php 
            $tmp1 = explode( '//',get_field('video_link'));
            $tmp2 = substr(end ($tmp1),0,8);
            if ($tmp2 === 'youtu.be') {
             ?>
              <iframe width="100%" height="550" src="https://www.youtube.com/embed/<?php
                $tmp = explode( '/',get_field('video_link'));
                echo end ($tmp);
                ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
             <?php
            }elseif ($tmp2 === 'vimeo.co'){
              ?>
              <iframe src="https://player.vimeo.com/video/<?php
                $tmp = explode( '/',get_field('video_link'));
                echo end ($tmp);
                ?>" width="100%" height="550" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
              <?php
            }
          ?> 
        </div><!--/.video-->

        <?php
        //вывод заголовка
        //если мы на стрнице поста
		    if ( is_singular() ) :    //то...
          //выводим заголовок в теге h1
		    	the_title( '<h1 class="lesson-title">', '</h1>' );
		    else :
          //иначе - заголовок в теге h2 и ссылку на пост
		    	the_title( '<h2 class="lesson-title"><a href="' . esc_url( get_permalink() ) . '"    rel="bookmark">', '</a></h2>' );
		    endif; ?>

        <div class="post-header-info"> <!--подвал шапки каждого поста-->
            <!--иконка даты-->
            <svg width="13.5" height="13.5" class="icon date-icon">
              <use xlink:href="<?php echo get_template_directory_uri() ?>/assets/images/sprite.svg#clock"></use>
            </svg>
            <!--выводим дату статьи в формате день j,месяц F, год Y, напр. 1 мая 2021-->
            <span class="post-header-info-date">  <?php the_time('j F'); ?>, <?php the_time('G:i');?></span>
        </div><!--/.post-header-info-->
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
        //get_sidebar('post');
      ?>
    </footer>
    
  </div>
  <!--/.container-->
</article>