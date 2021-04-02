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
        <p class="contacts-title">Через форму обратной связи</p>
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
      <div class="right">
        <p class="contacts-title">Или по этим контактам</p>
        <?php 
          //проверяем наличие заполненного доп.поля Email
          $email = get_post_meta( get_the_ID(), 'email', true );
          if ($email) {
            echo '<a href="mailto: '.$email.'" class="">'.$email.'</a>';
          }
          //проверяем наличие заполненного доп.поля Адрес
          $adress = get_post_meta( get_the_ID(), 'address', true );
          if ($adress) {
            echo '<adress class="">'.$adress.'</adress>';
          }
          //проверяем наличие заполненного доп.поля Тел.
          $phone = get_post_meta( get_the_ID(), 'phone', true );
          if ($phone) {
            echo '<a href="tel: '.$phone.'" class="">'.$phone.'</a>';
          }
          
          the_field('date');
        ?>
      </div><!--right-->
    </div><!--/.contacts-wrapper-->
  </div><!--/.container-->
</div><!--/.section-dark-->
<?php get_footer();