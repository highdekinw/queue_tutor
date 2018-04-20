var ajaxLoadlock = false;
var ajaxLoadlock2 = false;
var reserveComplete = false;
var seatP1 = undefined;
var seatP2 = undefined;
var existing = undefined;
var priceP1;
var priceP2;
var courseText1 = undefined;
var courseText2 = undefined;
var discount = 0;
var seating1 = undefined;
var seating2 = undefined;
var courseP1 = '', courseP2 = '';
$(document).ready(function(){
  seating1 = $('input[name=seat-select-period-1]:checked').val();
  seating2 = $('input[name=seat-select-period-2]:checked').val();
  $('#slip-form').hide();
});
$(function () {
  $('.modal').modal({
      dismissible: false, // Modal can be dismissed by clicking outside of the modal
      opacity: .5, // Opacity of modal background
      in_duration: 300, // Transition in duration
      out_duration: 200, // Transition out duration
      starting_top: '4%', // Starting top style attribute
      ending_top: '10%', // Ending top style attribute
      complete: function() { if(reserveComplete) {
          $('#slip-form').submit();
          // window.location = './index.php?success=4'; 
        }
      } // Callback for Modal close
  });

  $('#submit-course').on('click', function () {
    reserveComplete = false;
    if($('#slipUpload').get(0).files.length <= 0) return;
    seatP1 = $('input[name=seat-select-period-1]:checked').val();
    seatP2 = $('input[name=seat-select-period-2]:checked').val();
    existing = $('input[name=existing]:checked').val();
    if (seatP1 == undefined || seatP2 == undefined) {
      alert('กรุณาเลือกที่นั่งของทั้ง 2 คอร์สให้เรียบร้อย');
      return false;
    }
    if (!ajaxLoadlock) {
      $('#reserve_success').find('.modal-content p').html('<h5>'+$('#name-user').text()+'</h5>').html(courseP1  + '<br><br>' + courseP2 +  '<br><br>');
      $('#reserve_success').modal('open');
    }
  });
  $('#confirm-submit').on('click', function(){
    reserveComplete = true
    // if (!ajaxLoadlock) {
    //   ajaxLoadlock = true;
    //   reserveComplete = true;
    //   var data = {
    //     seatP1: seatP1,
    //     seatP2: seatP2,
    //     existing: existing
    //   };
    //   $.ajax('backend/ajax_reserveseat.php', {
    //     method: 'POST',
    //     data: data,
    //     success: function (res) {
    //       if (res.status == 0) {

    //       } else {
    //         alert('PHP Error !');
    //       }
    //     },
    //     error: function () {
    //       alert('AJAX reserve seat error');
    //     },
    //     complete: function () {
    //       ajaxLoadlock = false;
    //     }
    //   });
    // }
  });
  $('.course-button').on('click', function () {
    if($('#cant_select').val() == 'true'){
      $('#already_reserved').modal();
      $('#already_reserved').modal('open');
    }else{
      if (!ajaxLoadlock) {
        ajaxLoadlock = true;
        var selectedPeriod = $(this).attr('data-period'),
            selectCourse = $(this).attr('data-courseid'),
            selectedPrice = $(this).children('input').attr('data-price'),
            selectCourseName = $(this).attr('data-course-name');
        var sendData = {
          courseid: selectCourse
        };

        var $newtable = $('<div/>')
        .append('<h5 style="text-align:center;"><b>'+selectCourseName+'</b></h5><hr/>')
        .append('<h5 style="text-align:center; background-color: #263238; padding: 3px; color: #cfd8dc">กระดาน</h5>')
        .append('<table id="seat-period-'+$(this).attr('data-period')+'">');

        // Must refactor these
        $.ajax('backend/ajax_loadcoursedata.php', {
          method: 'POST',
          data: sendData,
          success: function ( res ){
            var result = JSON.parse(res);
            if(selectedPeriod == 1){
              courseP1 = '<b>คอร์ส กันยายน - ตุลาคม: ' + result['name'] + 
              "&nbsp;&nbsp;&nbsp;&nbsp; " +  $('a[data-courseid='+selectCourse+']').attr("course-section")  +
              "</b><br/><b>ระยะเวลาคอร์ส: </b>" + $('a[data-courseid='+selectCourse+']').attr("course-start") + 
              " - " + $('a[data-courseid='+selectCourse+']').attr("course-end") + 
              '<br/><b>วันที่เรียน : </b>' + $('a[data-courseid='+selectCourse+']').attr('course-date') + 
              "&nbsp;&nbsp;&nbsp;&nbsp; " + 
              "<b>ห้องเรียน: </b>" + $('a[data-courseid='+selectCourse+']').attr("course-room") +
              "<br/><b>เวลาเรียน: </b>" + $('a[data-courseid='+selectCourse+']').attr("course-time");

              priceP1 = selectedPrice;
              courseText1 = selectCourseName + ' (รอบปกติ ' + 
              $('#term-tag').val() + ')' +
              ' / Semester' +
              ' / เวลา : ' + $('a[data-courseid='+selectCourse+']').attr('course-time') +
              ' / Room : ' + $('a[data-courseid='+selectCourse+']').attr("course-room");
              seating1 = undefined;
            }else {
              courseP2 = '<b>คอร์ส เทอม 2: ' + selectCourseName
              + "&nbsp;&nbsp;&nbsp;&nbsp; " +  $('a[data-courseid='+selectCourse+']').attr("course-section") 
              + "</b><br/><b>ระยะเวลาคอร์ส: </b>" + $('a[data-courseid='+selectCourse+']').attr("course-start")
              + " - " + $('a[data-courseid='+selectCourse+']').attr("course-end")
              + '<br/><b>วันที่เรียน : </b>' + $('a[data-courseid='+selectCourse+']').attr('course-date') + "&nbsp;&nbsp;&nbsp;&nbsp; <b>ห้องเรียน: </b>" + $('a[data-courseid='+selectCourse+']').attr("course-room")
              + "<br/><b>เวลาเรียน: </b>" + $('a[data-courseid='+selectCourse+']').attr("course-time");

              priceP2 = selectedPrice;
              courseText2 = selectCourseName + ' (รอบปกติ ' + 
              $('#term-tag').val() + ')' +
              ' / Vacation' +
              ' / เวลา : ' + $('a[data-courseid='+selectCourse+']').attr('course-time') +
              ' / Room : ' + $('a[data-courseid='+selectCourse+']').attr("course-room");
              seating2 = undefined;
            }
          }
        });
        // =========================================

        $.ajax('backend/ajax_loadseat.php', {
          method: 'POST',
          data: sendData,
          success: function (res) {
            
            var iRes = 0, row;
            if (res[0]['_room'] == 1){
              row = ['A', 'B', 'C', 'D', 'E'];

              var $newrow = $('<div/>').append('<tr>');
              $newrow.append('<td></td><td></td><td></td><td></td>');
              if(res[res.length-1]._student == null ){
                $newrow.append( '<td style="text-align: center;"><b class="grey-text text-darken-4">' + 'พิเศษ' + 
                  '</b> <input type="radio" class="seatSelector" name="seat-select-period-' + 
                  selectedPeriod + '" value="' + res[res.length-1].id + '"></td>');
              }else{
                $newrow.append( '<td style="text-align: center;" class="grey-text">' + 'พิเศษ' + 
                  ' </i><br/><span class="user-badge grey">' + 
                  res[res.length-1].username.substr(0, 8) +'</span></td>');
              }
              $newrow.append('</tr>');
              $newtable.find('table').append($newrow);
            }else{
              row = ['A', 'B', 'C', 'D', 'E', 'F', 'X'];
            }
            
            for (var i = 0; i < row.length; i++) {
              var $newrow = $('<div/>').append('<tr>');
              for (var j = 0; j < 5; j++) {
                try{
                  var name = res[iRes].name.split('');
                }catch(e){
                  var name = [];
                }
                if( isSeat(row[i], j+1, name[0], name[1]) && notReserved(res[iRes]._student) ) {
                  if(row[i] == 'X' && name[0] == 'X'){
                    $newrow.append( '<td style="text-align: center;"><b class="grey-text text-darken-4">' + 
                      'เสริม' + name[1] + '</b> <input type="radio" class="seatSelector" name="seat-select-period-' + 
                      selectedPeriod+'" value="'+res[iRes].id+'"></td>');
                  }else{
                    $newrow.append( '<td style="text-align: center;"><b class="grey-text text-darken-4">' + 
                      res[iRes].name + '</b> <input type="radio" class="seatSelector" name="seat-select-period-' + 
                      selectedPeriod+'" value="'+res[iRes].id+'"></td>');
                  }
                  iRes++;
                }else if( isSeat(row[i], j+1, name[0], name[1]) ){
                  if(row[i] == 'X' && name[0] == 'X'){
                    $newrow.append( '<td style="text-align: center;" class="grey-text">' + 'เสริม' + 
                      name[1] + ' <br/><span class="user-badge grey">' + 
                      res[iRes].username.substr(0, 8) +'</span></td>');
                  }else{
                    $newrow.append( '<td style="text-align: center;" class="grey-text">' + res[iRes].name + 
                    ' <br/><span class="user-badge grey">' + res[iRes].username.substr(0, 8) + '</span></td>');
                  }
                  iRes++;
                } else {
                  $newrow.append('<td></td>');
                }
              }
              $newtable.find('table').append($newrow);
            }
            $('#period-' + selectedPeriod).html($newtable.html());
            setPaymentSelect();

            // if($('#period-1').html() != '' ) submit-button.show();    --reminder--
          },
          error: function (e) {
            alert('AJAX load seat error'+JSON.stringify(e));
          },
          complete: function () {
            ajaxLoadlock = false;
            
          }
        });
      }
    }
  });

  $('input[name=existing]').on('click ',function(){
    if($(this).val() == 0) discount = 0;
    else if($(this).val() == 1) discount = 200;
    setPaymentSelect();
  });

  $('#period-1').on('click', function(){
    if(seating1 != $('input[name=seat-select-period-1]:checked').val()){
      seating1 = $('input[name=seat-select-period-1]:checked').val();
    }
    setPaymentSelect();
  });

  $('#period-2').on('click', function(){
    if(seating2 != $('input[name=seat-select-period-2]:checked').val()){
      seating2 = $('input[name=seat-select-period-1]:checked').val();
    }
    setPaymentSelect();
  });

  $('#slipUpload').on('change', function(){
    showSubmit();
  });

});



function setPaymentSelect(){
  var array = []
  if( $('input[name=seat-select-period-1]:checked').val() != undefined ){
    array.push( $('input[name=seat-select-period-1]:checked').val() );
  }
  if( $('input[name=seat-select-period-2]:checked').val() != undefined ){
    array.push( $('input[name=seat-select-period-2]:checked').val() );
  }
  $('p.courseText1').html(courseP1);
  $('p.courseText2').html(courseP2);
  $('input[name=helper]').val(array.join('-'));
  if( $('input[name=seat-select-period-1]:checked').val() != undefined && 
      $('input[name=seat-select-period-2]:checked').val() != undefined){
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

function isSeat(current_row, current_column, seat_row, seat_column ){
  return  (current_row == seat_row && current_column == seat_column) || 
          (seat_row == 'F' && seat_column == '6') || 
          seat_row == 'X';
}
function notReserved(student){
  return student == null;
}
