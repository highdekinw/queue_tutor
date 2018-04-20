var ajaxLoadlock = false;
var ajaxCourse1 = false;
var ajaxCourse2 = false;
var reserveComplete = false;
var seatP1 = undefined;
var seatP2 = undefined;
var existing = undefined;
var discount = 0;
var seating1 = undefined;
var seating2 = undefined;
var course_detail = [null];
var current_course_detail;
var selectedPeriod;
var confirm_current = false;
var course_seeing = [undefined, undefined];

$(document).ready(function(){
  seating1 = $('input[name=seat-select-period-1]:checked').val();
  seating2 = $('input[name=seat-select-period-2]:checked').val();
  $('#slip-form').hide();
  reserveComplete = false;
  confirm_current = false;

  setInterval(function(){
    updateCourseStudent();
  }, 1500);
});
$(function () {
  $('.modal').modal({
      dismissible: false, // Modal can be dismissed by clicking outside of the modal
      opacity: .5, // Opacity of modal background
      in_duration: 300, // Transition in duration
      out_duration: 200, // Transition out duration
      starting_top: '4%', // Starting top style attribute
      ending_top: '10%', // Ending top style attribute
      complete: function() {
        if(reserveComplete) {
          $('#slip-form').submit();
        }else if(confirm_current){
          confirm_current = false;
          period = ((selectedPeriod%2) + 1) % 2;
          course_detail[0] = current_course_detail
          course_detail.forEach(function(o, i){
            $.each(o, function(key, val){
              index = i + 1;
              $('#' + key + '_' + index).html(val)
            })
          });

          seating1 = undefined;
          seating2 = undefined;
          $('p.courseText1:hidden').fadeIn();
          setPaymentSelect();
        }
      }
  });

  $('#submit-course').on('click', function () {
    reserveComplete = false;
    if($('#slipUpload').get(0).files.length <= 0) return;
    seatP1 = $('input[name=seat-select-period-1]:checked').val();
    seatP2 = $('input[name=seat-select-period-2]:checked').val();
    existing = $('input[name=existing]:checked').val();
    if (course_detail[0] == undefined) {
      alert('กรุณาเลือกทั้ง 2 คอร์สให้เรียบร้อย');
      return false;
    }
    if (!ajaxLoadlock) {
      $('#courseP1').html($('p.courseText1').html());
      $('#courseP2').html($('p.courseText2').html());
      $('#reserve_success').modal('open');
    }
  });

  $('#confirm-submit').on('click', function(){
    reserveComplete = true
  });

  $('.course-button').on('click', function () {
    if($('#cant_select').val() == 'true'){
      $('#already_reserved').modal();
      $('#already_reserved').modal('open');
    }else{
      std_amount = parseInt( $(this).find('span[student-amount]').text() );
      max_std = parseInt( $(this).find('span[max-std-amount]').text() );
      if (std_amount >= max_std){
        $('#course_full').modal('open');
        return
      }

      selectedPeriod = $(this).attr('data-period');
      var selectCourse = $(this).attr('data-courseid'),
          selectCourseName = $(this).attr('data-course-name'),
          selectedTime = $(this).attr("course-time"),
          selectedRoom = $(this).attr("course-room");

      var period_name = [ '',
        'กุมภาพันธ์ - มีนาคม',
        'เทอม 1',
        'กันยายน - ตุลาคม',
        'เทอม 2' ];

      current_course_detail = {
        id: selectCourse,
        course_period: period_name[selectedPeriod],
        course_name: selectCourseName,
        course_section: $('a[data-courseid='+selectCourse+']').attr("course-section"),
        course_start: $('a[data-courseid='+selectCourse+']').attr("course-start"),
        course_end : $('a[data-courseid='+selectCourse+']').attr("course-end"),
        course_date: $('a[data-courseid='+selectCourse+']').attr('course-date'),
        course_room: selectedRoom,
        course_time: selectedTime
      }

      $.each(current_course_detail, function(key, val){
        $('#' + key + '_confirm').html(val)
      });
      $('#confirm_current').modal('open');
    }
  });

  $('#current-submit').on('click', function(){
    confirm_current = true;
  });

  $('input[name=existing]').on('click ',function(){
    if($(this).val() == 0) discount = 0;
    else if($(this).val() == 1) discount = 200;
    setPaymentSelect();
  });

  $('#slipUpload').on('change', function(){
    showSubmit();
  });

  $('div[subject-box]').on('click', function(){
    subject_box = $(this).attr('subject-box') - 1
    if(course_seeing[subject_box] == $(this).attr('subject-id')){
      course_seeing[subject_box] = undefined;
    }else{
      course_seeing[subject_box] = $(this).attr('subject-id')
      force_ajax = [false];
      force_ajax[subject_box] = true;
      updateCourseStudent(force_ajax);
    }
  });

});

function setPaymentSelect(){
  var array = [];
  if(course_detail[0] != undefined){
    array.push(course_detail[0]['id'])
  }
  $('input[name=helper]').val(array.join('-'));
  console.log(course_detail);
  if( course_detail[0] != undefined){
    $('#slip-form').fadeIn();
  }else{
    $('#slip-form').hide();
  }
}

function showSubmit(){
   if($('#slipUpload').get(0).files.length != 0){
        $('#pre-submit-text').hide();
        $('#submit-select-course').fadeIn();
    }else{
      $('#submit-select-course').hide();
      $('#pre-submit-text').show();
    }
}

function updateCourseStudent(force_ajax = [false]){
  if(force_ajax[0]) ajaxCourse1 = false;

  if(course_seeing[0] != undefined && !ajaxCourse1){
    ajaxCourse1 = true;
    $.ajax('backend/ajax_course_std_count.php', {
      method: 'POST',
      data: { subjectid: course_seeing[0] },
      success: function ( res ){
        res = JSON.parse(res);
        res.forEach(function(o){
          $('a[data-courseid='+ o['courseid'] +']').find('span[student-amount]').text(o['count']);
        });
      }
    });
    ajaxCourse1 = false;
  }
}
