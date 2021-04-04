<?php
/*
Template Name: Страница таксономии
Template Post Type: page
*/
get_header(); ?>
<div class="container">
taksonomy.php
  <h1 class="category-title">
    <?php single_cat_title();?>
  </h1>
  <div class="post-list">
    <?php while ( have_posts() ){ the_post(); ?>
      <div class="post-card">
        <div class="post-card-text">
          <!--выводим наименование статьи-->
          <h2 class="post-card-title"><?php the_title();?></h2>
          <div class="video">
            <?php 
              $tmp1 = explode( '//',get_field('video_link'));
              $tmp2 = substr(end ($tmp1),0,8);
              if ($tmp2 === 'youtu.be') {
               ?>
                <iframe width="100%" height="200" src="https://www.youtube.com/embed/<?php
                  $tmp = explode( '/',get_field('video_link'));
                  echo end ($tmp);
                  ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
               <?php
              }elseif ($tmp2 === 'vimeo.co'){
                ?>
                <iframe src="https://player.vimeo.com/video/<?php
                  $tmp = explode( '/',get_field('video_link'));
                  echo end ($tmp);
                  ?>" width="100%" height="200" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                <?php
              }
            ?> 
          </div><!--/.video-->
          
          

            <!--выводим в блоке инф об авторе-->
          <div class="author">
            <!--выясняем метаданные автора - его ID-->
            <?php $author_id = get_the_author_meta('ID');?>
            <!--выводим по ID аватарку-->
            <img src="<?php echo get_avatar_url($author_id)?>"  alt="" class="author-avatar">

            <!--создаем еще блок для автора и даты и комментариев и лайков-->
            <div class="author-info">
              <span class="author-name"><strong><?php the_author();?></strong></span>
              <!--выводим дату статьи в формате день j,месяц F, год Y, напр. 1  мая2021-->
              <span class="date"><?php the_time('j F');?></span>
              <!--выводим в блоке инф о комментариях-->
              <div class="comments">
                <!--иконку-->
                <svg  class="icon comments-icon">
                  <use width=13.5px height=13.5px xlink:href="<?php echo get_template_directory_uri()?>/assets/images/sprite.svg#comment"></use>
                </svg>
                <!--кол-во-->
                <span class="comments-counter"><?php comments_number('0','1', '%') ?></span>
              </div>
              <div class="likes">
                <!--иконку для лайков-->
                <svg   class="icon likes-icon">
                  <use width=12.75px height=11.7px xlink:href="<?php echo get_template_directory_uri()?>/assets/images/sprite.svg#heart"></use>
                </svg>
                <!--кол-во-->
                <span class="likes-counter"><?php comments_number('0','1', '%') ?></span>
              </div>
            </div><!--/.author-info-->
          </div><!--/.author-->
        </div><!--/.post-card-text-->
      </div><!--/.post-card-->
      <?php } ?>
      <?php if ( ! have_posts() ){ ?>
      	Записей нет.
      <?php } ?>
    <!--/while-->
  </div><!--/.post-list-->
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
</div>
<?php get_footer();