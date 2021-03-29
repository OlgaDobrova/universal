<?php get_header('post');?>
  <main class="site-main">
    <?php
    //запускаем цикл WordPress и проверяем наличие постов
    //если пост есть
		while ( have_posts() ) :
      //выводим все его содержимое
			the_post();

      //находим шаблон для вывода поста в папке template-parts, файл content.php
			get_template_part( 'template-parts/content', get_post_type() );

      //Подключаем сайдбар доп.постов
      get_sidebar('post');

			// Если комментарии к записи открыты, выводим эти комментарии
			if ( comments_open() || get_comments_number() ) :
        //находит файл comments.php и выводит его
				comments_template();
			endif;

		endwhile; // Конец цикла Wordpress
		?>
  </main>
<?php get_footer();?>