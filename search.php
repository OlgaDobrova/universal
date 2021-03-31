<?php
get_header(); ?>
<div class="container">
  <h1 class="digest-search-title">Результаты поиска по запросу:</h1>
    <div class="digest-wrapper">
      <div class="digest-wrapper-2">
        <ul class="digest"> <!--маркированный список -->
          <!--   пока посты есть     - выводить пост-->
          <?php while ( have_posts() ){ the_post(); ?>
            	<li class="digest-item"> <!--элемент списка -->
              <a href="<? echo get_the_permalink()?>" class="digest-item-permalink" >       
                <img src="<?php 
                  if ( has_post_thumbnail() ) {
                      echo get_the_post_thumbnail_url();
                  }
                  else {
                      echo get_template_directory_uri().'/assets/images/img-default.png';
                  }
                  ?>" alt="" class="thumb digest-item-permalink-thumb">

              </a>
              <!--всю информацию отделю от картинки-->
              <div class="digest-info">
                <!--выводим категорию-->
                <span class="digest-info-category">
                  <?php 
                    //цикл пока...есть заведенные категории
                    foreach (get_the_category() as $category) {
                      printf(
                        '<a href="%s" class="category-link %s">%s</a>',
                        //ecs_url(),esc_html() - обеспечивают безопастность ссылкам в сети
                        esc_url(get_category_link( $category ) ),
                        //$category -> slug как категория прописана в админке (ярлак категории)
                        esc_html( $category -> slug ),
                        esc_html( $category -> name ) //название категории
                      );
                    }
                  ;?>
                </span>
                <!--выводим наименование статьи-->
                <h4 class="digest-info-title"> 
                  <?php echo mb_strimwidth(get_the_title(),0,50,'...') ;?>
                </h4>
                <!--выводим отрывок статьи-->
                <p class="digest-info-excerpt">
                  <?php echo mb_strimwidth(get_the_excerpt(),0,130,'...'); ?>
                </p>
                <div class="digest-info-footer"> <!--подвал каждого поста-->
                  <!--выводим дату статьи в формате день j,месяц F, год Y, напр. 1 мая 2021-->
                  <span class="digest-date"><?php the_time('j F');?></span>
                  <!--выводим в блоке инф о комментариях-->
                  <div class="comments digest-info-footer-comments">
                    <!--иконку-->
                    <svg   class="icon digest-info-footer-comments-icon">
                      <use xlink:href="<?php echo get_template_directory_uri()?>/assets/images/sprite.svg#comment"></use>
                    </svg>
                    <!--кол-во-->
                    <span class="digest-info-footer-comments-counter"><?php comments_number('0','1', '%') ?></span>
                  </div>

                  <div class="likes digest-info-footer-likes">
                    <!--иконку для лайков-->
                    <svg   class="icon digest-info-footer-likes-icon">
                      <use xlink:href="<?php echo get_template_directory_uri()?>/assets/images/sprite.svg#heart"></use>
                    </svg>
                  
                    <!--кол-во-->
                    <span class="digest-info-footer-likes-counter"><?php comments_number('0','1', '%') ?></span>
                  </div>
                </div>
              </div>
                  
          </li>

            <?php } ?>
            <?php if ( ! have_posts() ){ ?>
            	Записей нет.
            <?php } ?>
        </ul>
        <?php 
        /*  $args=array(
	          'prev_text'    => '
              <svg width="15" height="7" class="icon pagination-prev-icon">
                    <use xlink:href="' . get_template_directory_uri() . '/assets/images/sprite.svg#left-arrow"></use>
                  </svg> Назад',
	          'next_text'    => 'Вперед
              <svg width="15" height="7" class="icon pagination-next-icon">
                    <use xlink:href="' . get_template_directory_uri() . '/assets/images/sprite.svg#arrow"></use>
                  </svg>',
          ); */

            //&larr; - спецсимвол стрелка влево //&rarr; - спецсимвол стрелка вправо
            $args=array(
	          'prev_text'    => '&larr;Назад',
	          'next_text'    => 'Вперед &rarr;',);
          the_posts_pagination($args);
        ?>
      </div><!--/.digest-wrapper-2-->
      
      <!--/.Подключаем нижний сайдбар с главной/ --> 
      <?php get_sidebar('search');?>
    </div><!--/.digest-wrapper-->
</div><!--/.container-->
<?php get_footer('');