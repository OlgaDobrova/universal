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
                <?php //цикл пока...есть заведенные категории
                  foreach (get_the_category() as $category) {
                    printf(
                      '<a href="%s" class="category-link %s">%s</a>',
                      //ecs_url(),esc_html() - обеспечивают безопастность ссылкам в сети
                      esc_url(get_category_link( $category ) ),
                      //$category -> slug как категория прописана в админке (ярлак категории)
                      esc_html( $category -> slug ),
                      esc_html( $category -> name ) //название категории
                    );
                  };?>
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
        'category_not_in' => 24 , //tag_ID=24 - это рубрика расследования
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
  <!--/.article-list/ --> 

  <!--article-grid - Сетка статей-->
  <div class="main-grid">  <!--main-grid - Моя сетка из 7 статей-->
    <ul class="article-grid"> <!--Тег <ul> устанавливает маркированный список-->
                                 <!--<li>элемент маркированного списка</li>-->
      <?php		                 
        global $post;
        //формируем запрос в базу данных    
        $query = new WP_Query( [
          //получаем 7 постов
        	'posts_per_page' => 7,
          'category_not_in' => 24, //tag_ID=24 - это рубрика расследования, ее исключаем
        ] );
        
        //проверяем наличие постов
        if ( $query->have_posts() ) {
          //создаем переменную - счетчик постов
          $cnt=0;
          //выводим посты, пока они есть
        	while ( $query->have_posts() ) {
        		$query->the_post();
            $cnt++;
            switch ($cnt) {
              case '1': // выводим пост 1
                ?>
                <li class="article-grid-item article-grid-item-1">
                  <a href="<?php echo the_permalink()?>" class="article-grid-permalink">
                    <!--выводим категорию-->
                    <span class="category-name"><?php $category = get_the_category();
                      echo $category[0]->name;?></span>
                    <!--выводим наименование статьи-->
                    <h4 class="article-grid-title"> 
                      <?php echo mb_strimwidth(get_the_title(),0,50,'...') ;?>
                    </h4>
                    <!--выводим отрывок статьи-->
                    <p class="article-grid-excerpt">
                      <?php echo mb_strimwidth(get_the_excerpt(),0,130,'...'); ?>
                    </p>
                    <!--создаем блок для автора и комментариев-->
                    <div class="article-grid-info">
                      <!--выводим в блоке инф об авторе-->
                      <div class="author">
                        <!--выясняем ментаданные автора - его ID-->
                        <?php $autor_id = get_the_author_meta('ID');?>
                        <!--выводим по ID аватарку-->
                        <img src="<?php echo get_avatar_url($autor_id)?>" alt=""  class="author-avatar">
                        <span class="autor-name"><strong><?php the_author();?></strong>: <?php  the_author_meta('description')?></span>
                      </div>
                      <!--выводим в блоке инф о комментариях-->
                      <div class="comments">
                        <!--иконку-->
                        <img src="<?php echo get_template_directory_uri().'/assets/images/comment.svg'?>" alt="icon : comment" class="comments-icon">
                        <!--кол-во-->
                        <span class="comments-counter"><?php comments_number('0','1', '%')?></  span>
                      </div>
                    </div>
                  </a>
                </li>
                <!--/.article-grid-item article-grid-item-1/ --> 
                <?php
                break;
              
              case '2': // выводим пост 2
                ?>
                <li class="article-grid-item article-grid-item-2">
                  <img src="<?php echo get_the_post_thumbnail_url()?>" alt=""   class="article-grid-thumb">
                  <a href="<?php echo the_permalink()?>" class="article-grid-permalink">
                    <!--выводим первый тег метаданных статьи - напр. Популярное-->
                    <span class="tag">
                      <!--выводим список тегов метаданных статьи-->
                      <?php $posttags =get_the_tags();
                        //если теги есть
                        if ($posttags) {
                          //то выводим имя первого тега
                          echo $posttags[0]->name.'';
                        }
                      ?>
                    </span>
                    <!--выводим категорию-->
                    <span class="category-name"><?php $category = get_the_category();
                      echo $category[0]->name;?></span>
                    <!--выводим наименование статьи-->
                    <h4 class="article-grid-title"><?php the_title();?></h4>

                      <!--выводим в блоке инф об авторе-->
                    <div class="author">
                      <!--выясняем ментаданные автора - его ID-->
                      <?php $autor_id = get_the_author_meta('ID');?>
                      <!--выводим по ID аватарку-->
                      <img src="<?php echo get_avatar_url($autor_id)?>"   alt=""class="author-avatar">
                      
                      <!--создаем еще блок для автора и даты и комментариев и лайков-->
                      <div class="author-info">
                        <span class="autor-name"><strong><?php the_author();?></strong></span>
                        <!--выводим дату статьи в формате день j,месяц F, год Y, напр. 1  мая2021-->
                        <span class="date"><?php the_time('j F');?></span>
                        <!--выводим в блоке инф о комментариях-->
                        <div class="comments">
                          <!--иконку-->
                          <img src="<?php echo get_template_directory_uri().'/assets/images/comment-white.svg'?>" alt="icon : comment" class="comments-icon">
                          <!--кол-во-->
                          <span class="comments-counter"><?php comments_number('0','1', '%') ?></span>
                        </div>
                        <div class="likes">
                          <!--иконку для лайков-->
                          <img src="<?php echo get_template_directory_uri().'/assets/images/heart.svg'?>" alt="icon: like" class="likes-icon">
                          <!--кол-во-->
                          <span class="likes-counter"><?php comments_number('0','1', '%') ?></  span>
                        </div>
                      </div>
                    </div>
                  </a>
                </li>
                <!--/.article-grid-item article-grid-item-2/ --> 
                <?php
                break;
                      
              case '3': // выводим пост 3
                ?>
                <li class="article-grid-item article-grid-item-3">

                  <a href="<?php echo the_permalink()?>" class="article-grid-permalink">
                    <!--выводим картинку-->
                    <img src="<?php echo get_the_post_thumbnail_url()?>"class="article-grid-thumb">
                    <!--выводим наименование статьи-->
                    <h4 class="article-grid-title"> 
                      <?php the_title();?>
                    </h4>
                  </a>
                </li>
                <!--/.article-grid-item article-grid-item-3/ --> 
                <?php
                break;
              
              default:
                ?>
                <li class="article-grid-item article-grid-item-default">
                  <a href="<?php echo the_permalink()?>" class="article-grid-permalink">
                    <!--выводим наименование статьи-->
                    <h4 class="article-grid-title"> 
                      <?php echo mb_strimwidth(get_the_title(),0,25,'...') ;?>
                    </h4>
                    <!--выводим отрывок статьи-->
                    <p class="article-grid-excerpt">
                      <?php echo mb_strimwidth(get_the_excerpt(),0,86,'...') ;?>
                    </p>
                    <!--выводим дату статьи в формате день j,месяц F, год Y, напр. 1 мая 2021-->
                    <span class="article-date"><?php the_time('j F');?></span>
                  </a>
                </li>
                <!--/.article-grid-item article-grid-item-default/ --> 
                <?php
                break;
            }
        		?>
        		<!-- Вывода постов, функции цикла: the_title() и т.д. -->
        		<?php 
        	}
        } else {
        	// Постов не найдено
        }

        wp_reset_postdata(); // Сбрасываем $post
        ?>
    </ul>
    <!--/.article-grid/ --> 
    <!--/.Подключаем сайдбар/ --> 
    <?php get_sidebar('home-top');?>
  </div>
  <!--/.main-grid/ --> 
</div>
<!--/.container/ --> 


<!--/открытие секции - расследование недели.../ --> 
<?php		
global $post;

$query = new WP_Query( [
	'posts_per_page' => 1,
	'category_name'  => 'investigation',
] );

if ( $query->have_posts() ) {
	while ( $query->have_posts() ) {
		$query->the_post();
		?>
		  <section class="investigation" style="background-image: linear-gradient(0deg, rgba(64, 48, 61, 0.45), rgba(64, 48, 61, 0.45)), url(benjamin-lambert-KxdO8elL5_c-unsplash.jpg), url(<?php echo get_the_post_thumbnail_url()?>)">
        <div class="container">
          <h2 class="investigation-title"><?php the_title();?></h2>
          <a href="<?php echo get_the_permalink()?>" class="more">Читать статью</a>
        </div>
        <!--/.container/ -->
      </section>
      <!--/.section/ --> 
		<?php 
	}
} else {
	// Постов не найдено
}

wp_reset_postdata(); // Сбрасываем $post
?>

<div class="container">  <!--/контейнер будет состоять из дайджеста слева и сайдбара справа/ --> 
  <!--/открытие левой секции контейнера - ДАЙДЖЕСТ - подборка статей.../ --> 
  <div class="digest-wrapper">
    <ul class="digest"> <!--маркированный список --> 
      <?php
        //Объявляем глобальную переменную post
        global $post;

        $myposts = get_posts([ 
        	'numberposts' => 6,
        ]);
        //проверка на наличие постов
        //если есть
        if( $myposts ){
          //то запускаем цикл
        	foreach( $myposts as $post ){
        		setup_postdata( $post );
      ?>
      <li class="digest-item"> <!--элемент списка -->
        <a class="digest-item-permalink" href="<?php echo get_the_permalink()?>">
          <img src="<?php echo get_the_post_thumbnail_url(null,  'homepage-thumb')?>" alt="" class="digest-item-permalink-thumb">
          <!--всю информацию отделю от картинки-->
          <div class="digest-item-permalink-info">
            <!--выводим категорию-->
            <span class="digest-item-permalink-info-category">
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
            <h4 class="digest-item-permalink-info-title"> 
              <?php echo mb_strimwidth(get_the_title(),0,50,'...') ;?>
            </h4>
            <!--выводим отрывок статьи-->
            <p class="digest-item-permalink-info-excerpt">
              <?php echo mb_strimwidth(get_the_excerpt(),0,130,'...'); ?>
            </p>
            <div class="digest-item-permalink-info-footer"> <!--подвал каждого поста-->
              <!--выводим дату статьи в формате день j,месяц F, год Y, напр. 1 мая 2021-->
              <span class="digest-date"><?php the_time('j F');?></span>
              <!--выводим в блоке инф о комментариях-->
              <div class="comments digest-item-permalink-info-footer-comments">
                <!--иконку-->
                <img width="19" height="15" src="<?php echo get_template_directory_uri().'/assets/images/comment.svg' ?>"    alt="icon : comment" class="icon digest-item-permalink-info-footer-comments-icon">
                <!--кол-во-->
                <span class="digest-item-permalink-info-footer-comments-counter"><?php comments_number('0','1', '%') ?></span>
              </div>
              <div class="likes digest-item-permalink-info-footer-likes">
                <!--иконку для лайков-->
                <img width="19" height="15" src="<?php echo get_template_directory_uri().'/assets/images/heart-color.svg' ?>"   alt="icon: like" class="digest-item-permalink-info-footer-likes-icon">
                <!--кол-во-->
                <span class="digest-item-permalink-info-footer-likes-counter"><?php comments_number('0','1', '%') ?></span>
              </div>
            </div>
          </div>
        </a>
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
    <!--/.Подключаем нижний сайдбар на главной/ --> 
    <?php get_sidebar('home-bottom');?>
  </div>
  <!--/.digest-wrapper/ -->
</div>
<!--/.container/ -->
