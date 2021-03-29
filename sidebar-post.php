<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package universal_theme
 */

?>

<!--вся инфа в пределах контейнера-->
<div class="container">
	<aside id="secondary" class="sidebar-сollection">
		<?php
			global $post;

			$query = new WP_Query( [
				//выводим 4 поста
				'posts_per_page' => 4,
				//посты, которые входят в одну из указанных категорий
//$current_cat_id = $category[0]->
				'category__in' => array('cat_ID'),
				'post__not_in' => array($post->ID),
			] );
			
			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					?>
					<a href="<?php the_permalink()?>" class="сollection-link">
	  			  <img class="сollection-thumb" src="<?php 
	  			    if ( has_post_thumbnail() ) {
	  			      echo get_the_post_thumbnail_url();
	  			    }
	  			    else {
	  			        echo get_template_directory_uri().'/assets/images/img-default.png';
	  			    }
	  			  ?>" alt="">

	  			  <h4><?php echo mb_strimwidth(get_the_title(),0,70,'...') ; ?></h4>
						
	  			  <div class="info сollection-info">
	  			    <!--выводим в блоке инф о просмотрах-->
	  			    <div class="viewing post-viewing">
	  			      <!--иконку для просмотров-->
	  			      <svg   class="icon post-info-viewing-icon">
	  			        <use xlink:href="<?php echo get_template_directory_uri()?>/assets/images/sprite.svg#eye"></use>
	  			      </svg>
						
	  			      <!--кол-во просмотров-->
	  			      <span class="counter post-info-viewing-counter"><?php comments_number('0','1',	'%')  ?></span>
	  			    </div>
	  			    <!--/.viewing post-viewing-->
						
	  			    <!--выводим в блоке инф о комментариях-->
	  			    <div class="comments post-comments">
	  			      <!--иконку-->
	  			      <svg   widh="19" height="15" class="icon comments-icon">
	  			        <use xlink:href="<?php echo get_template_directory_uri()?>/assets/images/sprite.svg#comment"></use>
	  			      </svg>
	  			      <!--кол-во-->
	  			      <span class="counter comments-counter"><?php comments_number('0','1','%') ?></span>
	  			    </div>
	  			    <!--/.comments post-comments-->
	  			  </div>
	  			</a>
					<?php
				}
			} else {
			// Постов не найдено
			}

			wp_reset_postdata(); // Сбрасываем $post
		?>
	</aside><!-- #secondary -->
</div>
<!--/.container-->
