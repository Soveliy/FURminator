

// upload width Browser
windowSize = $(window).width(); 
$(window).on('resize', function(event){
windowSize = $(window).width(); 
});


$(".burger").on('click', function(){
    $(this).toggleClass("js-active")
    $(".main-menu").slideToggle();
    $("body").toggleClass("js-hidden");


})


  
  $(".numbox").mask("8 (999) 999-99-99");






function resizeMenu() {
  var windowWidth = $(window).width();
  if (windowWidth <= 1024) {
    //for_body
    if ($(".burger").hasClass("js-active")) {
      //code
      $('.burger').removeClass('js-active');
      $('body').removeClass('js-hidden');
      $(".mobile-menu").hide();
    }
  } else {
    //for_body
   
  }
}
resizeMenu();
$(window).resize(function () {
  resizeMenu();
});
$(function(){
  $('a[href^="#"]').on('click', function(event) {
    // отменяем стандартное действие
    event.preventDefault();
    
    var sc = $(this).attr("href"),
        dn = $(sc).offset().top;
    /*
    * sc - в переменную заносим информацию о том, к какому блоку надо перейти
    * dn - определяем положение блока на странице
    */
    
    $('html, body').animate({scrollTop: dn}, 1000);
    
    /*
    * 1000 скорость перехода в миллисекундах
    */
  });
});


//forms
const publicKey = "6LeU7b0ZAAAAAF7RV7hBwGyMmzxrvuhG9u9W6R8T"; // GOOGLE public key

//audit_form
function check_grecaptcha() {
    grecaptcha.ready(function () {
        grecaptcha.execute(publicKey, {
            action: "ajaxForm"
        }).then(function (token) {
            $("[name='recaptcha-token']").val(token);
        });
    });
}



function falidator(item) {
  check = true;
  $(item).find('input').each(function() {
      if($(this).hasClass('_req') && $(this).val() == '') {
          check = false;
          $(this).parent().addClass("no-validate");
      } else {
        $(this).parent().removeClass("no-validate");
      }
  });
  if(check) {
      return true;
  } else {
      return false;
  }
}

/**************************/
$("#application").submit(function(){
  if(!falidator(this)) return false;
  $.ajax({ 
      type: "POST", 
      url: "sendmail.php",
      data: $("#application").serialize(),
      success: function(html) { 
      
      }
  });
  
 
  $("#spasibo").arcticmodal();
  /*$('.action_block .inputbox').removeClass("not-empty");*/
  $('#application').trigger("reset");
  return false;
});
/**************************/


//cookies
$(function() {
  // Проверяем запись в куках о посещении
  // Если запись есть - ничего не происходит
  if (!$.cookie('hide_cookies')) {
  // если cookie не установлено появится окно
  // с задержкой 5 секунд
  var delay_popup = 1000;
  setTimeout("document.querySelector('.fixed_cookies_block').style.display='grid'", delay_popup);
  }
  $.cookie('hide_cookies', true, {
  // Время хранения cookie в днях
  expires: 30,
  path: '/'
  });
  });
  // Закрытие полосы cookie
  $('.fixed_cookies_block .close_cookies').click(function(){
  $('.fixed_cookies_block').fadeOut();
  });
  