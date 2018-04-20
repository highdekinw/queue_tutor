var courses = {};
var userid = -1;
var userData = [];
var n = unum = $("#last_unum").val()-1;
var maxPage;
var page;
var perPage = 20;
setMaxPage();
showAllUser();
$.ajax('backend/ajax_loadusers.php', {
  method: 'GET',
  success: function (res) {
    userData = JSON.parse(JSON.stringify(res));
  }
});
if($("table#history_table").attr("data-userid") != -1){
  userid = $("table#history_table").attr("data-userid");
  $.ajax('backend/ajax_history.php', {
    method: 'POST',
    'Content-type': 'application/json',
    'dataType': 'json',
    data : {
      userid : userid
    },success: function (res) {
      courses = JSON.parse(JSON.stringify(res));
    },
    error: function () {

    },
    complete: function () {

    }
  });
}
$(function () {
  $("input#search_history").on("keyup", function(){
    //alert(1);
    if ($(this).val() == '') {
      $('tr').show();
      return;
    }
    $('tr').hide();
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

      thisvalid = $('tr[data-courseid='+o.courseid+'][data-restime="'+o.res_time+'"][data-seat="'+o.seatName+'"] b').text().match(searchReg) != null;
      console.log($('tr[data-courseid='+o.courseid+'][data-restime="'+o.res_time+'"][data-seat="'+o.seatName+'"] b').text().match(searchReg), o.courseid);
      if (thisvalid) {
        $('tr[data-courseid='+o.courseid+'][data-restime="'+o.res_time+'"][data-seat="'+o.seatName+'"]').show();
      } else {
        $('tr[data-courseid='+o.courseid+'][data-restime="'+o.res_time+'"][data-seat="'+o.seatName+'"]').hide();
      }
    });
  });
  $("#usereditform").on('submit', function () {
    var valid = true;
    var fields = ['firstname', 'lastname', 'username', 'password', 'school', 'class', 'nickname', 'phone', 'email'];
    for (field of fields) {
      if ($('#' + field).val() == '') {
        valid = false;
      }
    }
    if (!valid) {
      alert('กรุณาใส่ข้อมูลให้ครบถ้วน');
    }
    return valid;
  });

  //setup before functions
  var typingTimer;                //timer identifier
  var doneTypingInterval = 350;  //time in ms, 5 second for example
  var $search_user = $('input#search_user');

  //on keyup, start the countdown
  $search_user.on('keyup', function () {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(doneTyping, doneTypingInterval);
  });

  //on keydown, clear the countdown
  $search_user.on('keydown', function () {
    clearTimeout(typingTimer);
  });

  //user is "finished typing," do something
  function doneTyping () {
    //do something
    if ($search_user.val() == '') {
      showAllUser();
      return;
    }
    var searchReg = new RegExp($search_user.val(), 'ig');
    n = 0;
    for (var i = 0; i < userData.length; i++) {
      var match = false;
      for (var info in userData[i]) {
        if (info == 'password') continue;
        if (searchReg.test(userData[i][info])) {
          match = true;
          break;
        }
      }
      if (match) {
        n++;
        //$('tr[data-uid=' + userData[i].id + ']').show();
        $('tr[data-uid=' + userData[i].id + ']').attr("visible","true");
        $('tr[data-uid=' + userData[i].id + ']').attr("unum",n);
      } else {
        //$('tr[data-uid=' + userData[i].id + ']').hide();
        $('tr[data-uid=' + userData[i].id + ']').attr("visible","hide");
        $('tr[data-uid=' + userData[i].id + ']').attr("unum",-1);
      }
    }
    setMaxPage();
    userDisplay();
  }
  // $("input#search_user").on('keyup', function () {
  //   if ($(this).val() == '') {
  //     console.log('true');
  //     //$('tr[data-uid]').show();
  //     showAllUser();
  //     return;
  //   }
  //   var searchReg = new RegExp($(this).val(), 'ig');
  //   n = 0;
  //   for (var i = 0; i < userData.length; i++) {
  //     var match = false;
  //     for (var info in userData[i]) {
  //       if (info == 'password') continue;
  //       if (searchReg.test(userData[i][info])) {
  //         match = true;
  //         break;
  //       }
  //     }
  //     if (match) {
  //       n++;
  //       //$('tr[data-uid=' + userData[i].id + ']').show();
  //       $('tr[data-uid=' + userData[i].id + ']').attr("visible","true");
  //       $('tr[data-uid=' + userData[i].id + ']').attr("unum",n);
  //     } else {
  //       //$('tr[data-uid=' + userData[i].id + ']').hide();
  //       $('tr[data-uid=' + userData[i].id + ']').attr("visible","hide");
  //       $('tr[data-uid=' + userData[i].id + ']').attr("unum",-1);
  //     }
  //   }
  //   setMaxPage();
  //   userDisplay();
  // });
  $("#backbtn div").on('click', function(){
    if(page>1){
      page--;
      userDisplay();
    }
    //alert(page);
  });
  $("#nextbtn div").on('click', function(){
    if(page<maxPage){
      page++;
      userDisplay();
    }
    //alert(page);
  });
});
function userDisplay(){
  $('tr[data-uid]').hide();
  var k = page*perPage;
  for(var i=(k+1)-perPage; i<=k; i++){
    $('tr[unum=' + i + '][visible=false]').hide();
    $('tr[unum=' + i + '][visible=true] td[name=num]').text(i);
    $('tr[unum=' + i + '][visible=true]').show();
  }
  if(page == maxPage){
    $("#nextbtn div").hide();
  }else{
    $("#nextbtn div").show();
  }
  if(page == 1){
    $("#backbtn div").hide();
  }else{
    $("#backbtn div").show();
  }
  $(window).scrollTop(0);
}
function showAllUser(){
  $('tr[data-uid]').attr("visible","true");
  n = unum;
  setMaxPage();
  for(var i=1; i<=n; i++){
    $('tr[data-unum=' + i + ']').attr("unum",i);
  }
  userDisplay();
}
function setMaxPage(){
  page = 1;
  maxPage = Math.ceil(n/perPage);
}

function deleteUser(id, username){
  var comfirmation = confirm("ยืนยันการลบผู้ใช้ "+ username +" !")
  if( comfirmation ){
    // var params = {delete_user=id, page=page};
    window.location = 'manage-user.php?delete_user=' + id + '&page=' + page;
  }
}

$(document).ready(function(){
  var current_page = $('#current_page').val();
  console.log(current_page);
  if( current_page > 0){
    page = Math.min(maxPage, current_page);
    userDisplay();
  }
});
