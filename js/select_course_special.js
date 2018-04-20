var ajaxLoadlock = false;
var ajaxLoadlock2 = false;
var reserveComplete = false;
var seat = undefined;
var courseText = undefined;
var seating = undefined;
var courseDetail = '';
$(document).ready(function(){
  $('#slip-form').hide();
  showSubmit();
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
    seat = $('input[name=seat-select]:checked').val();
    if (seat == undefined ) {
      alert('กรุณาเลือกที่นั่งให้เรียบร้อย');
      return false;
    }
    if (!ajaxLoadlock) {
        $('#reserve_success').find('.modal-content p').append('<h5>'+$('#name-user').text()+'</h5>').append(courseDetail + '<br><br>' );
        $('#reserve_success').modal('open');
    }
  });
  $('#confirm-submit').on('click', function(){
    reserveComplete = true;
    // if (!ajaxLoadlock) {
    //   ajaxLoadlock = true;
    //   reserveComplete = true;
    //   var data = {
    //     seatP1: seat,
    //     existing: 0
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
    if (!ajaxLoadlock) {
      ajaxLoadlock = true;
      var selectCourse = $(this).attr('data-courseid'),
          selectedPrice = $(this).attr('data-price'),
          selectCourseName = $(this).attr('data-course-name');
      var sendData = {
        courseid: selectCourse
      };
      var $newtable = $('<div/>').append('<h5 style="text-align:center;"><b>'+selectCourseName+'</b></h5><hr/>').append('<h5 style="text-align:center; background-color: #263238; padding: 3px; color: #cfd8dc">กระดาน	</h5>').append('<table id="seats">');
      $.ajax('backend/ajax_loadseat.php', {
        method: 'POST',
        data: sendData,
        success: function (res) {
          console.log(JSON.stringify(res));
          //courseDetail = '<b>คอร์สพิเศษ:</b> ' + selectCourseName + ' ' + $('a[data-courseid='+selectCourse+']').parent().next().text() + "ราคา " + selectedPrice + " บาท";

          // courseDetail = '<b>คอร์สเสริม : ' + selectCourseName
          // + "</b><br/><b>ระยะเวลาคอร์ส : </b>" + $('a[data-courseid='+selectCourse+']').attr("course-start")
          // + " - " + $('a[data-courseid='+selectCourse+']').attr("course-end")
          // + '<br/><b>วัน-เวลา เรียน : </b>' + $('a[data-courseid='+selectCourse+']').parent().next().text() + "&nbsp;&nbsp;&nbsp;&nbsp; <b>Room : </b>" + $('a[data-courseid='+selectCourse+']').attr("course-room");
          // + "<br/><b>ราคา : </b>" + selectedPrice + " <b>บาท</b>";
          courseDetail = '<b>คอร์สเสริม: ' + selectCourseName +
          "&nbsp;&nbsp;&nbsp;&nbsp; " +  $('a[data-courseid='+selectCourse+']').attr("course-section")  +
          "</b><br/><b>ระยะเวลาคอร์ส: </b>" + $('a[data-courseid='+selectCourse+']').attr("course-start") +
          " - " + $('a[data-courseid='+selectCourse+']').attr("course-end") +
          '<br/><b>วันที่เรียน : </b>' + $('a[data-courseid='+selectCourse+']').attr('course-date') +
          "&nbsp;&nbsp;&nbsp;&nbsp; " +
          "<b>ห้องเรียน: </b>" + $('a[data-courseid='+selectCourse+']').attr("course-room") +
          "<br/><b>เวลาเรียน: </b>" + $('a[data-courseid='+selectCourse+']').attr("course-time");
          courseText = selectCourseName + ' (รอบปกติ ' +
            $('#term-tag').val() + ')' +
            ' / Semester' +
            ' / เวลา : ' + $('a[data-courseid='+selectCourse+']').parent().next().find('span').text() +
            ' / Room : ' + $('a[data-courseid='+selectCourse+']').attr("course-room");
            // ' / ราคา : ' + selectedPrice + ' บาท';
          var iRes = 0, row;

          if (res[0]['_room'] == 1){
            row = ['A', 'B', 'C', 'D', 'E'];

            var $newrow = $('<div/>').append('<tr>');
            $newrow.append('<td></td><td></td><td></td><td></td>');
            if(res[res.length-1]._student == null ){
              $newrow.append('<td style="text-align: center;"><b class="grey-text text-darken-4">' + 'พิเศษ' + '</b> <input type="radio" class="seatSelector" name="seat-select" value="'+res[res.length-1].id+'"></td>');
            }else{
              $newrow.append('<td style="text-align: center;" class="grey-text">' + 'พิเศษ' + ' <br/><span class="user-badge grey">' + res[res.length-1].username.substr(0, 8) +'</span></td>');
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
              if ( ( (row[i] == name[0] && j + 1 == name[1] ) || (name[0] == 'F' && name[1] == '6') || name[0] == 'X') && res[iRes]._student == null ) {
                if(row[i] == 'X' && name[0] == 'X'){
                  $newrow.append('<td style="text-align: center;"><b class="grey-text text-darken-4">' + 'เสริม' + name[1] + '</b> <input type="radio" class="seatSelector" name="seat-select" value="'+res[iRes].id+'"></td>');
                }else{
                  $newrow.append('<td style="text-align: center;"><b class="grey-text text-darken-4">' + res[iRes].name + '</b> <input type="radio" class="seatSelector" name="seat-select" value="'+res[iRes].id+'"></td>');
                }
                iRes++;
              } else if( (row[i] == name[0] && j + 1 == name[1] ) || (name[0] == 'F' && name[1] == '6') || name[0] == 'X' ){
                if(row[i] == 'X' && name[0] == 'X'){
                  $newrow.append('<td class="grey-text" style="text-align: center;">' + 'เสิรม' + name[1] + ' <br/><span class="user-badge grey">' + res[iRes].username.substr(0, 8) +'</span></td>');
                }else{
                  $newrow.append('<td class="grey-text" style="text-align: center;">' + res[iRes].name + ' <br/><span class="user-badge grey">' + res[iRes].username.substr(0, 8) +'</span></td>');
                }
                iRes++;
              } else {
                $newrow.append('<td></td>');
              }
            }
            $newtable.find('table').append($newrow);
          }
          $('#schedule').html($newtable.html());
          setPaymentSelect();
        },
        error: function () {
          alert('AJAX load seat error');
        },
        complete: function () {
          ajaxLoadlock = false;
          showSubmit();
        }
      });
    }
  });

  $('#schedule').on('click', function(){
    if(seating != $('input[name=seat-select]:checked').val()){
      seating = $('input[name=seat-select]:checked').val();
      setPaymentSelect();
    }
  });

  $('#paymentSelect').on('change', function(){
    $('.select-helper').find('input').remove();
    var text = '';
    $(this).val().forEach(function(o,i){
      text = text + ( o + '-');
    });
    $('.select-helper').append('<input hidden name="helper" value='+ text +'>');

    showSubmit();
  });

  $('#slipUpload').on('change', function(){
    showSubmit();
  });
});


function setPaymentSelect(){

  var array = []
  if( $('input[name=seat-select]:checked').val() != undefined ){
    array.push( $('input[name=seat-select]:checked').val() );
  }
  $('p.courseText').html(courseDetail);
  $('input[name=helper]').val(array.join('-'));
  if( $('input[name=seat-select]:checked').val() != undefined ){
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
