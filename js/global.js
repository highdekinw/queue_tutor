$(function () {
  $('.button-collapse').sideNav();
  $('.dropdown-button').dropdown();
  $('.modal').modal();
  $('select').material_select();
  $('.datepicker').pickadate({
    selectMonths: true
  });
  $('#promo_firstpage').carousel({
    full_width: true,
    indicators: true
  });
  timer = setInterval(function() {
    $('#promo_firstpage').carousel('next');
  }, 3250);
  $('#promo_firstpage').hover(function(ev){
    clearInterval(timer);
  }, function(ev){
      timer = setInterval(function() {
      $('#promo_firstpage').carousel('next');
    }, 3250);
  });
  
  $('#gallery_firstpage').carousel({
    indicators: true
  });
  setInterval(function() {
    $('#gallery_firstpage').carousel('next');
  }, 4000);
  $('.modalClose').on('click', function () {
    $('.modal').modal('close');
  });
  setEqualHeightOf('.newscard .card');

  $('.photoviewer')
    .hide()
    .on('click', function () {
      $(this).hide();
    });

  $('.viewable').on('click', function () {
    $('.photoviewer img').attr('src', $(this).data('src'));
    $('.photoviewer').show();
  });

  $('span.date').each(function () {
    var date = new Date($(this).html());
    var options = {
      weekday: "long", year: "numeric", month: "short",
      day: "numeric", hour: "2-digit", minute: "2-digit"
    };

    $(this).html(date.toLocaleTimeString("en-us", options));
  });

  $('#loginModal-trigger').on('click ', function(){
      window.location = './login.php';
      // alert(1);
  });
});

function setEqualHeightOf ($e, callback) {
  var maxHeight = 0;
  for (var i = 0; i < $($e).length; i++) {
    var thisHeight = $($($e)[i]).height();
    maxHeight = thisHeight > maxHeight ? thisHeight : maxHeight;
  }
  $($e).css('height', maxHeight);
  if (typeof callback === 'function') {
    callback();
  }
}
