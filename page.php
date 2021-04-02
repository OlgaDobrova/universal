<?php
/*
Template Name: Любая страница сайта
Template Post Type: page
*/
get_header(); ?>
<div class="container">
page.php
  <h1 class="partnership"><?php  the_title();?></h1>
  <p class="partnership-content"><?php the_content();?></p>
</div>
<?php get_footer();