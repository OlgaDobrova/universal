<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <!--выводим шапку поста-->
  <header class="entry-header <?php echo get_post_type();?>-header" style="background: linear-gradient(0deg, rgba(38, 45, 51, 0.75), rgba(38, 45, 51, 0.75)), url(
    <?php
    if ( has_post_thumbnail() ) {
      echo get_the_post_thumbnail_url();
    }
    else {
        echo get_template_directory_uri().'/assets/images/img-default.png';
    } ?> 
  );">
    <div class="container">
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
        //выводим ссылки на предыдущий и следующий посты
			  the_post_navigation(
			  	array(
			  		'prev_text' => '<span class="post-nav-prev">
                <svg   width="15" height="7" class="icon prev-icon">
                  <use xlink:href="' . get_template_directory_uri().'/assets/images/sprite.svg#left-arrow"></use>
                </svg>
            ' . esc_html__( 'Назад', 'universal_theme'   ) . '</span>',
			  		'next_text' => '<span class="post-nav-next">' . esc_html__( 'Вперед', 'universal_theme'  ) . '</span>' ,
			  	)
			  );
        //если мы на стрнице поста
		    if ( is_singular() ) :
          //то выводим заголовок в теге h1
		    	the_title( '<h1 class="entry-title">', '</h1>' );
		    else :
          //иначе - заголовок в теге h2 и ссылку на пост
		    	the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '"    rel="bookmark">', '</a></h2>' );
		    endif; ?>
        
        <div class="post-header-info"> <!--подвал каждого поста-->
            <!--выводим дату статьи в формате день j,месяц F, год Y, напр. 1 мая 2021-->
            <span class="post-header-date"><?php the_time('j F');?></span>
            <!--выводим в блоке инф о комментариях-->
            <div class="comments post-header-comments">
              <!--иконку-->
              <svg   widh="19" height="15" class="icon comments-icon">
                <use xlink:href="<?php echo get_template_directory_uri()?>/assets/images/sprite.svg#comment"></use>
              </svg>
              <!--кол-во-->
              <span class="comments-counter"><?php comments_number('0','1','%') ?></span>
            </div>
            <div class="likes post-header-likes">
              <!--иконку для лайков-->
              <svg   class="icon post-header-info-likes-icon">
                <use xlink:href="<?php echo get_template_directory_uri()?>/assets/images/sprite.svg#heart"></use>
              </svg>
             
              <!--кол-во-->
              <span class="post-header-info-likes-counter"><?php comments_number('0','1','%') ?></span>
            </div>
        </div>
    </div>
  </header> 
  <!-- /.Шапка поста -->

  <!-- Содержимое поста -->
  <div class="entry-content">
	  <?php
      //выводим содержимое поста
	    the_content(
	    	sprintf(
	    		wp_kses(
	    			/* translators: %s: Name of current post. Only visible to screen readers */
	    			__( 'Continue reading<span class="screen-reader-text"> "%s"</span>',   'universal_theme' ),
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
	  ?>
	</div>
  <!-- /.Содержимое поста -->

	<!--Подвал поста-->
  <footer class="entry-footer">
    <?php
      $tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'universal_theme' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( '%1$s', 'universal_theme' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
    ?>
  </footer>
</article>