var courses = {}
var userid = $("table[data-userid]").attr("data-userid");
/*$.post('backend/ajax_history.php',{userid:userid},function(res){
	courses = JSON.parse(JSON.stringify(res));
	alert(courses);
});*/
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
$(function(){
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
})