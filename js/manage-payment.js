var search_by;
var filter_name;
var filter_term;
var filter_period;
$(document).ready(function(){
	set_period_on_change();
	filter_name = $("select#select_course_name").val();
	filter_term = $("select#select_course_term").val();
	filter_period = $("input[name='period']").val();
	filter_course_show();
});
$(function(){
	// $("input[name='search_radio']").on('change', function(){
	// 	search_by = $(this).val();
	// 	switch(search_by){
	// 		case "0":
	// 			$('#search_by_course').hide();
	// 			$('#search_by_name').hide();
	// 			break;
	// 		case "1":
	// 			$('#search_by_course').fadeIn();
	// 			$('#search_by_name').hide();
	// 			break;
	// 		case "2":
	// 			$('#search_by_course').hide();
	// 			$('#search_by_name').fadeIn();
	// 			break;
	// 	}
	// 	search_filter();
	// });
	//
	// $("#name_search").on('change keyup press paste', function(){
	// 	search_filter();
	// });
	//
	// $("#course_select_1").on('change', function(){
	// 	search_filter();
	// });
	//
	// $("#course_select_2").on('change', function(){
	// 	search_filter();
	// });
	//
	// function search_filter(){
	// 	switch(search_by){
	// 		case "0": $('tr').show(); break;
	// 		case "1":
	// 			var course1 = $('#course_select_1').val().toLowerCase();
	// 			var course2 = $('#course_select_2').val().toLowerCase();
	// 			$('table#slips').find('tbody').find('tr').each(function(){
	// 				var semester = $(this).find('td.semester').attr('data-value').toLowerCase();
	// 				var vacation = $(this).find('td.vacation').attr('data-value').toLowerCase();
	// 				var valid1 = course1 == semester || course1 == 'all';
	// 				var valid2 = course2 == vacation || course2 == 'all';
	// 				if(valid1 && valid2) $(this).show();
	// 				else $(this).hide();
	// 			});
	// 			break;
	// 		case "2":
	// 			var searchReg = new RegExp($("input[name='search_by_name']").val(), 'igm');
	// 			$('table#slips').find('tbody').find('tr').each(function(){
	// 				var valid = false;
	// 				$(this).find('td').each (function() {
	// 					if($(this).hasClass('username') || $(this).hasClass('std_name'))
	// 						valid = valid || $(this).text().match(searchReg) != null;
	// 				});
	// 				if(valid) $(this).show();
	// 				else $(this).hide();
	// 			});
	// 			break;
	// 	}
	// }

	$("input#input_search_all").on('keyup', function(){
		var searchReg = new RegExp($("input[name='input_search_all']").val(), 'igm');
		$('table#slips').find('tbody').find('tr').each(function(){
			var valid = false;
			$(this).find('td').each (function() {
				if($(this).hasClass('no_search')) return;
					valid = valid || $(this).text().match(searchReg) != null;
			});
			if(valid) $(this).show();
			else $(this).hide();
		});
	});

	$("input[name='search_radio']").on('change', function(){
    switch($(this).val()){
			case "0":
				window.location.href = "./manage-payment.php";
				break;
      case "1":
        window.location.href = "?search_course";
        break;
      case "2":
        window.location.href = "?search_name";
        break;
    };
  });

	$('#name_search').on('keyup change', function(){
    var searchReg = new RegExp($(this).val(), 'igm');
    $('table#users').find('tbody').find('tr').each(function(){
      var valid = false;
      $(this).find('td').each (function() {
        if($(this).hasClass('skip_search')) return;
        valid = valid || $(this).text().match(searchReg) != null;
      });
      if(valid) $(this).show();
      else $(this).hide();
    });
  });

	$("select#select_course_name").on('change', function(){
		filter_name = $(this).val();
		filter_course_show();
	});

	$("select#select_course_term").on('change', function(){
		filter_term = $(this).val();
		set_period_on_change();
		filter_course_show();
	});
	$("input[name='period']").on('change', function(){
		filter_period = $(this).val();
		filter_course_show();
	});
	
});

function set_period_on_change(){
	if ($("select#select_course_term").val()[0] == '1') {
		$("#radio_period_1").val(1);
		$("#radio_period_2").val(2);
		$("label[for='radio_period_1']").text('กุมภาพันธ์ - มีนาคม');
		$("label[for='radio_period_2']").text('เทอม 1');
		val = [0, 1, 2, 1, 2];
		filter_period = val[filter_period];
	} else if ($("select#select_course_term").val()[0] == '2') {
		$("#radio_period_1").val(3);
		$("#radio_period_2").val(4);
		$("label[for='radio_period_1']").text('กันยายน - ตุลาคม');
		$("label[for='radio_period_2']").text('เทอม 2');
		val = [0, 3, 4, 3, 4];
		filter_period = val[filter_period];
	}
}
function filter_course_show(){
  $('table#courses').find('tbody').find('tr').each(function(){
    var name = $(this).attr('data-name');
    var term = $(this).attr('data-term');
    var period = $(this).attr('data-period');

    var name_valid = name == filter_name || filter_name == '0';
    var term_valid = term == filter_term || filter_term == '0';
    var period_valid = period == filter_period || filter_period == '0';

    if( name_valid && term_valid && period_valid ) $(this).fadeIn();
    else $(this).hide();
  })
}
