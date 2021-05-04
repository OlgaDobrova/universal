<?php
get_header(); ?>
<div class="container">
  <?php if (function_exists('the_breadcrumbs')) the_breadcrumbs();?>
  <h1 class="category-title">
    <?php single_cat_title();?>
  </h1>
  <div class="post-list">
    <?php while ( have_posts() ){ the_post(); ?>
      <div class="post-card">
        <img src="<?php 
          if ( has_post_thumbnail() ) {
              echo get_the_post_thumbnail_url();
          }
          else {
              echo get_template_directory_uri().'/assets/images/img-default.png';
          }
        ?>" alt=""   class="post-card-thumb">
        <div class="post-card-text">
          <!--выводим наименование статьи-->
          <h2 class="post-card-title"><?php the_title();?></h2>
          <!--выводим отрывок статьи-обрезка по кол-ву слов-->
          <p class="post-card-excerpt">
            <?php echo wp_trim_words(get_the_excerpt(), 12, ' ...' ); ?>
          </p>
            <!--выводим в блоке инф об авторе-->
          <div class="author">
            <!--выясняем ментаданные автора - его ID-->
            <?php $autor_id = get_the_author_meta('ID');?>
            <!--выводим по ID аватарку-->
            <img src="<?php echo get_avatar_url($autor_id)?>"   alt=""class="author-avatar">

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