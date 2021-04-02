const swiper = new Swiper('.swiper-container', {
  // Optional parameters
  loop: true,

   autoplay: {
   delay: 5000,
   },

  // If we need pagination
  pagination: {
    el: '.swiper-pagination',
  },

});
let menuToggle = $('.header-menu-toggle');
menuToggle.on('click',function(event){
  event.preventDefault();
  $('.header-nav').slideToggle(200);
})

let contactsForm = $('.contacts-form');

contactsForm.on('submit', function(event){
  event.preventDefault(); //отменили стандартную отправку формы
  alert();
  var formData = new FormData(this); //здесь данные по всем 3м полям формы обр связи: имя, email и вопрос
  formData.append('action','contacts_form'); //в форму обратной связи добавили атрибут action
  //формируем запрос на сервер
  $.ajax({
    type: "POST",
    url: adminAjax.url,
    data: formData,
    contentType: false,
    processData: false,
    success: function (response) {
      console.log('Ответ сервера:' + response);
    }
  });
});