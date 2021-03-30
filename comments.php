<?php
/**
 * Шаблон для отображения комментариев
 *
 * Это шаблон, который отображает область страницы, содержащую как текущие комментарии,
 * так и форму комментариев.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package universal_theme
 */

/*
 * Если текущий пост защищен паролем и
 * посетитель еще не ввел пароль
 * мы вернемся раньше без загрузки комментариев.
 */

//Создаем свою ф-цию вывода каждого коммента (коммент, аргумент, вложенность)
function universal_theme_comments( $comment, $args, $depth ) {
  //проверяем стиль родителя (ol, ul или div)
	if ( 'div' === $args['style'] ) {
		$tag       = 'div';
		$add_below = 'comment';
	} else {
		$tag       = 'li';
		$add_below = 'div-comment';
	}

  //классы, кот весим на каждый коммент
  //Здесь 4 параметра,  через запятую: parent - дочерний коммент, не указываем id конкретного комментария, не указываем ID поста с комментариями, не выводим все это на экран
	$classes = ' ' . comment_class( empty( $args['has_children'] ) ? '' : 'parent', null, null, false );
	?>

	<<?php echo $tag, $classes; ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) { ?>
		<div id="div-comment-<?php comment_ID() ?>" class="comment-body"><?php
	} ?>

  <!--вывод данных об авторе комментария-->
  <div class="comment-author-avatar">
    <?php
      //вывод аватарки, если есть
		  if ( $args['avatar_size'] != 0 ) {
		  	echo get_avatar( $comment, $args['avatar_size'] );
		  }
    ?>
  </div> <!--/.author-avatar-->
	
  <div class="comment-content">
    <div class="comment-author vcard">
		  <?php
  
	  	printf(
	  		__( '<cite class="comment-author-name">%s</cite>' ),
	  		get_comment_author_link()
	  	);
	  	?>
      <span class="comment-meta commentmetadata">
	  	  <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>">
	  	  	<?php
	  	  	printf(
            //вывод дата время
	  	  		__( '%1$s, %2$s' ),
	  	  		get_comment_date('F jS'),
	  	  		get_comment_time()
	  	  	); ?>
	  	  </a>   
	  	  <?php edit_comment_link( __( '(Edit)' ), '  ', '' ); ?>
	    </span>
	  </div>

    <!--условие для одобрения комментария администратором сайта-->
	  <?php if ( $comment->comment_approved == '0' ) { ?>
	  	<em class="comment-awaiting-moderation">
        <!-- _e перед фразой - это возможность перевода фразы на язык, на кот читается тема-->
	  		<?php _e( 'Your comment is awaiting moderation.' ); ?>
	  	</em><br/>
	  <?php } ?>


    <!--текст комментария-->
	  <?php comment_text(); ?>

    <!--ссылка на ответ комменту-->
	  <div class="comment-reply">
      <!--иконку-->
      <svg   widh="14" height="14" class="icon comments-add-icon">
        <use xlink:href="<?php echo get_template_directory_uri()?>/assets/images/sprite.svg#comment"></use>
      </svg>
	  	<?php
	  	comment_reply_link(
	  		array_merge(
	  			$args,
	  			array(
	  				'add_below' => $add_below,
	  				'depth'     => $depth,
	  				'max_depth' => $args['max_depth']
	  			)
	  		)
	  	); ?>
	  </div>
  </div> <!--/.comment-content-->

	<?php if ( 'div' != $args['style'] ) { ?>
		</div>
	<?php }
}


if ( post_password_required() ) {
	return;
}
?>

<div class="container">
  <div id="comments" class="comments-area">

	  <?php
	  //Проверка наличия комментариев
	  if ( have_comments() ) :
	  	?>
	  	<div class="comments-header">
        <h2 class="comments-title">
	  		  <?php	echo 'Комментарии ' . 
            '<span class="comments-count">'.get_comments_number().'</span>';?>
	  	  </h2><!-- /.comments-title -->
        <a href="#" class="comments-add-button">
          <!--иконку-->
          <svg   widh="18" height="18" class="icon comments-add-icon">
            <use xlink:href="<?php echo get_template_directory_uri()?>/assets/images/sprite.svg#pencil"></use>
          </svg>
          Добавить комментарий
        </a>
      </div><!-- /.comments-header -->

	  	<?php the_comments_navigation(); ?>

      <!--Выводим нумерованный список комментариев-->
	  	<ol class="comments-list">
	  		<?php
          //Здесь каждый конкретный коммент
	  		  wp_list_comments(
	  		  	array(
	  		  		'style'      => 'ol',
	  		  		'short_ping' => true,
              //размер аватарки в px
              'avatar_size' => 75 ,
              //вызов нашей ф-ции
              'callback' => 'universal_theme_comments',
              //'login_text' => 'Зарегистрируйтесь для отправки комментария',
	  		  	)
	  		  );
	  		?>
	  	</ol><!-- /.comment-list -->

	  	<?php
	  	the_comments_navigation();

	  	// Если комментарии закрыты и есть комментарии, то оставим заметку
	  	if ( ! comments_open() ) :
	  		?>
	  		<p class="no-comments"><?php esc_html_e( 'Комментарии закрыты', 'universal_theme' ); ?></p>
	  		<?php
	  	endif;

	  endif; // Проверьте наличие have_comments().

    //вывод на экран формы для введения комментария
	  comment_form(array(
      'title_reply'  => '', //Заголовок для формы ввода комментария

      //надпись внутри формы над самим полем для коммента
      //get_avatar() - это вывод аватарки //get_current_user_id() - id пользователя
      'comment_field' => '<div class="comment-form-comment">
          <label class="comment-label" for="comment">' . _x( 'Что вы  думаете на этот счет? ',  'noun' ) . '</label>
          <div class="comment-wrapper">
            '.get_avatar(get_current_user_id(),75).'
            <div class="comment-textarea-wrapper">
              <textarea id="comment" name="comment" aria-required="true" class="comment-textarea"></textarea>
            </div>
          </div>
        </div>',
      //проверка зарегистрирован ли пользователь
      'must_log_in'  => '<p class="must-log-in">' . 
		      sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( ) ) ) ) . '
	       </p>',
	    'logged_in_as'  => '',
	      'comment_notes_before' => '<p class="comment-notes">
	      	<span id="email-notes">' . __( 'Your email address will not be published.' ) . '</span>'. ( $req ? $required_text : '' ) . '
	      </p>',
      //кнопка отправить
      'class_submit' => 'comment-submit more', //класс more уже у нас описан как синяя кнопка со стрелкой
      'label_submit' => 'Отправить',
      'submit_button' => '<button name="%1$s" type="submit" id="%2$s" class="%3$s" / >%4$s</button>',
    ));
	  ?>

  </div><!-- #comments -->
</div>
<!--/.container-->