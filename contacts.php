<?php
/*
Template Name: Страница контакты
Template Post Type: page
*/
get_header(); ?>
<div class="section-dark">
  <div class="container">
    <?php the_title('<h1 class="page-title">','</h1>',true);?>
    <div class="contacts-wrapper">
      <div class="left">
        <p class="page-text">Через форму обратной связи</p>
        <form action="#" class="contacts-form" method="POST">
          <input name="contact_name" type="text" class="input contacts-input" placeholder="Ваше имя">
          <input name="contact_email" type="email" class="input contacts-input" placeholder="Ваш Email">
          <textarea name="contact_comment" id="" class="textarea contacts-textarea" placeholder="Ваш вопрос"></textarea>
          <!--submit-это событие нажатия кнопки - отправки формы-->
          <button type="submit" class="button more">Отправить</button>
        </form> 

        <!--через Contact Form 7-->
        <?php the_content( );?>
      </div><!--left-->
      <div class="right"></div><!--right-->
    </div><!--/.contacts-wrapper-->
  </div><!--/.container-->
</div><!--/.section-dark-->
<?php get_footer();