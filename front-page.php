<?php get_header( );?>
<main class="front-page-header">
  <div class='container'>
    <div class="hero">
      <div class="left">
        <?php
          //Объявляем глобальную переменную post
          global $post;

          $myposts = get_posts([ 
            // вывести 1 последних постов
          	'numberposts' => 1,
            'category_name' => 'javascript, css, html, web-design',
          ]);
          //проверка на наличие постов
          //если есть
          if( $myposts ){
            //то запускаем цикл
          	foreach( $myposts as $post ){
          		setup_postdata( $post );
        ?>
          		<!-- Вывода постов, функции цикла: the_title() - заголовок записи и др.-->
              <img src="<?php the_post_thumbnail_url() ?>" alt="" class="post-trumb">
              <?php $autor_id = get_the_author_meta('ID');?>
              <a href="<?php echo get_author_posts_url($autor_id);?>" class="autor">
                <img src="<?php echo get_avatar_url($autor_id)?>" alt="" class="avatar">
                <div class="autor-bio">
                  <span class="autor-name"><?php the_author();?></span>
                  <span class="autor-rank">Должность</span>
                </div>
              </a>
              <div class="post-text">
                <?php the_category( );?>
                <h2 class="post-title"><?php echo mb_strimwidth(get_the_title(),0,60,'...') ;?></h2>
                <a href="<?php echo get_the_permalink()?>" class="more">Читать далее</a>
              </div>
              <?php 
          	}
          } else {
                	// Постов не найдено
              ?><p>Постов не найдено</p><?php
            }
              
            wp_reset_postdata(); // Сбрасываем $post
            ?>
      </div>
      <!--/.left/ -->
      <div class="right">
        <h3 class="recommend">Рекомендуем</h3>
        <ul class="posts-list">
          <?php
            //Объявляем глобальную переменную post
            global $post;

            $myposts = get_posts([ 
              // вывести 5 последних постов
            	'numberposts' => 5,
              'offset'  => 1,
              'category_name' => 'javascript, css, html, web-design',
            ]);
            //проверка на наличие постов
            //если есть
            if( $myposts ){
              //то запускаем цикл
            	foreach( $myposts as $post ){
            		setup_postdata( $post );
                ?>
                <li class="post">
                <?php the_category( );?>
                <a class="post-permalink" href="<?php echo get_the_permalink()?>">
                  <h4 class="post-title"><?php echo wp_trim_words(get_the_title(), 6, ' ...' );?></h4>
                </a>
                </li>
                <?php 
    	        }
            } else {
          	// Постов не найдено
            ?><p>Постов не найдено</p><?php
            }

            wp_reset_postdata(); // Сбрасываем $post
            ?>
        </ul>
      <!--/.right/ -->
    </div>
    <!--/.hero/ -->
  </div>
  <!--/.container/ -->
</main>
<div class="container">
  <ul class="article-list">
    <?php
      //Объявляем глобальную переменную post
      global $post;

      $myposts = get_posts([ 
      	'numberposts' => 4,
        'category_name' => 'articles',
      ]);
      //проверка на наличие постов
      //если есть
      if( $myposts ){
        //то запускаем цикл
      	foreach( $myposts as $post ){
      		setup_postdata( $post );
    ?>
    <li class="article-item">
      <a class="article-permalink" href="<?php echo get_the_permalink()?>">
        <h4 class="article-title">
          <?php echo mb_strimwidth(get_the_title(),0,50,'...') ;?>
        </h4>
      </a>
      <img width="65" height="65" src="<?php echo get_the_post_thumbnail_url(null,'homepage-thumb')?>" alt="" class="">
    </li>
    <?php 
      	}
      } else {
      	// Постов не найдено
        ?>
        <p>Постов не найдено</p>
        <?php
      }
      wp_reset_postdata(); // Сбрасываем $post
    ?>
  </ul>
</div>
<!--/.container/ --> 