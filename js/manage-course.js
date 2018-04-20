var courses = {};
var selectedTerm = 0;
$.ajax('backend/ajax_loadcourse.php', {
  'method': 'GET',
  'Content-type': 'application/json',
  'dataType': 'json',
  success: function (res) {
    courses = JSON.parse(JSON.stringify(res));
  },
  error: function () {

  },
  complete: function () {

  }
});



$(function () {
  $('input#search_course').on('keyup', function () {
    if ($(this).val() == '') {
      $('tr').show();
      return;
    }
    var searchReg = new RegExp($(this).val(), 'igm');
    courses.forEach(function (o, i) {
      var thisvalid = false;
      /*for (var k in o) {
        if (o.hasOwnProperty(k)) {
          if (searchReg.test(o[k])) {
            thisvalid = true;
            break;
          }
        }
      }*/
      thisvalid = $('tr[data-courseid='+o.id+'] b').text().match(searchReg) != null;
      console.log($('tr[data-courseid='+o.id+'] b').text().match(searchReg), o.id);
      if (thisvalid) {
        if(selectedTerm == 0) $('tr[data-courseid='+o.id+']').show();
        else $('tr[data-courseid='+o.id+'][data-term="'+selectedTerm+'"]').show();
      } else {
        $('tr[data-courseid='+o.id+']').hide();
      }
    });
  })

  $('.timepicker').wickedpicker({
    now: "09:00",
    twentyFour: true,
    hoverState: 'hover-state', //The hover state class to use, for custom CSS
    title: 'เลือกเวลา',
    minutesInterval: 15
  });

  $('a.editCourseBtn').on('click', function () {
    var editid = $(this).data('editcourseid');
    var $editmodal = $('#editCourseModal');
    const days = ['sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat'];
    courses.forEach(function (o, i) {
      if(o.id == editid) {
        $editmodal.find('input[name=editid]').val(editid);
        $editmodal.find('input#courseName').val(o.name);
        $editmodal.find('input#coursePrice').val(o.price);
        // $editmodal.find('select[name=courseType] option[value='+o.type+']').attr('selected', '');
        $editmodal.find('input[type=radio][name=courseType][value='+o.type+']').attr('checked', '');
        $editmodal.find('select[name=course_section] option[value='+o.section+']').attr('selected', '');
        $editmodal.find('select[name=room] option[value='+o._room+']').attr('selected', '');
        $editmodal.find('input#startTime').val(o.time_start);
        $editmodal.find('input#endTime').val(o.time_end);
        $editmodal.find('input#startDate').pickadate('picker').set('select', new Date(o.date_start));
        $editmodal.find('input#endDate').pickadate('picker').set('select', new Date(o.date_end));
        $editmodal.find('input#course_desc').val(o.description);
        for (var i = 0; i < days.length; i++) {
          if(o[days[i]] == 1) $editmodal.find('option[value='+days[i]+']').attr('selected', '');
        }
        $editmodal.find('select[name=term] option[value='+o._term+'-'+o._year+']').attr('selected', '');

        $('select[name="term"]').change()
        $editmodal.find('select[name=coursePeriod] option[value='+o.period+']').attr('selected', '');
        // $editmodal.find('select[name=coursePeriod]').change();
        // console.log($editmodal.find('select[name=coursePeriod]').val());
        $('select[name="day[]"]').change();
        $('select').material_select();
        return;
      }
    });
    $editmodal.modal('open');
  });
  $('#addYear a').on("click", function(){
    var year = Number($("select[name='year'] option:last").text())+1;
    $.ajax({
      url: "backend/ajax_addyear.php",
      method: "POST",
      data: {
        year: year
      },
      success: function(data){
        $("select[name='year']").append("<option value="+year+" selected>"+year+"</option>");
        $("select").material_select();
        $("#term_1").click();
      }
    })
  });
  $("select[name='search_term']").on('change', function(){
    selectedTerm = $(this).find('option:selected').val();
    $('input#search_course').val(null).keyup();
    if(selectedTerm != 0){
      $('tbody tr').hide();
      $('tr[data-term="'+selectedTerm+'"]').show();
    }
  });

  $('select[name="day[]"]').on('change', function(){
    $('input[name="mon"]').attr('value',false);
    $('input[name="tue"]').attr('value',false);
    $('input[name="wed"]').attr('value',false);
    $('input[name="thu"]').attr('value',false);
    $('input[name="fri"]').attr('value',false);
    $('input[name="sat"]').attr('value',false);
    $('input[name="sun"]').attr('value',false);
    $(this).val().forEach(function(o,i){
      $('input[type="text"][name="'+ o +'"]').attr('value',true);
    });
  });
});

$('#course_select').on('change', function(){
  var course_name = [
    '',
    'Eng Basic',
    'Eng 1',
    'Eng 2',
    'Eng 3',
    'Eng 4',
    'Eng Top',
    'Eng Reading A',
    'Eng Reading B',
    'Eng Ent'
  ]
  var current_course = course_name[$(this).val()];
  $('#courseName').val(current_course);
});


$(document).ready(function(){
  // day input bug
  $('input[name="mon"]').attr('value',false);
  $('input[name="tue"]').attr('value',false);
  $('input[name="wed"]').attr('value',false);
  $('input[name="thu"]').attr('value',false);
  $('input[name="fri"]').attr('value',false);
  $('input[name="sat"]').attr('value',false);
  $('input[name="sun"]').attr('value',false);
  $('select[name="day[]"]').val().forEach(function(o,i){
    $('input[type="text"][name="'+ o +'"]').attr('value',true);
  });
  $('select[name="term"]').change();
});

$("input[name='courseType']").on('change', function(){
  var select = $('#course_select');
  select.find('option').remove();
  if( $(this).val() == 1){
    select.append($('<option>', {value: 1, text: 'Eng Basic', selected: true}));
    select.append($('<option>', {value: 2, text: 'Eng 1'}));
    select.append($('<option>', {value: 3, text: 'Eng 2'}));
    select.append($('<option>', {value: 4, text: 'Eng 3'}));
    select.append($('<option>', {value: 5, text: 'Eng 4'}));
    select.append($('<option>', {value: 6, text: 'Eng Top'}));
  }else if( $(this).val() == 2){
    select.append($('<option>', {value: 7, text: 'Eng Reading A', selected: true}));
    select.append($('<option>', {value: 8, text: 'Eng Reading B'}));
    select.append($('<option>', {value: 9, text: 'Eng Ent'}));
  }


  $(select).material_select();
  $('#course_select').change();
});

$('select[name="term"]').on('change', function(){
  term_select = $(this)
  this_val = term_select.val()[0];
  select = $('select[name="coursePeriod"]')
  select.find('option')
    .remove()
    .end()
    .append('<option value="" disabled selected>คลิกเพื่อเลือก</option>')
    .append( function(){
      if (this_val == '1'){

        // console.log(1);
        return '<option value="1">กุมภาพันธ์ - มีนาคม</option><option value="2">เทอม 1</option>';}
      else{
        // console.log(2);
        return '<option value="3">กันยายน - ตุลาคม</option><option value="4">เทอม 2</option>'}
    })
    .val($('select[name="term"]').val()[0] == '1' ? 1 : 3);

    select.material_select();
})