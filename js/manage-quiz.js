var max_page;
var curr_page;

$(document).ready(function(){
  max_page = Math.ceil( $("tr[data-number]").length / 20.0 );
  curr_page = 1;
  filter_pagination();  
});

$(function () {
  $('ul.tabs').tabs();
  var qAmnt = { v: 0, g: 0, r: 0, l: 0 };
  const section = {
    v: '#vocab_questions',
    g: '#grammar_questions',
    r: '#reading_questions',
    l: '#listening_questions'
  }

  var $edit = $('#editidhidden');
  if ($edit.length > 0) { // There is edit, fetch data !
    var editid = $edit.val();
    var file = null;
    $.ajax('backend/ajax_loadquiz.php', {
      'method': 'POST',
      'data': {setid: editid},
      'dataType': 'json',
      success: function (res) {
        var qType;
        for (var i = 0; i < res.length; i++) {
          switch (res[i].type) {
            case '1': qType = 'v'; break;
            case '2': qType = 'g'; break;
            case '3': qType = 'r'; break;
            case '4': qType = 'l'; break;
          }
          if (res[i].type == 4 && file == null) {
            file = res[i].file;
          }
          addQuestion(qType, res[i]);
          if(file !== '' && file !== null) { // there is old sound
            $('#oldSound').html('<audio controls><source src="' + file + '">Your browser does not support the audio element.</audio><br/>มีไฟล์เสียงอยู่แล้ว เลือกไฟล์ใหม่เพื่อเปลี่ยน หรือ ปล่อยไว้ถ้าไม่ต้องการ');
          } else {
            $('#oldSound').html('ไม่มีไฟล์เสียงอยู่')
          }
        }
      },
      error: function () {

      },
      complete: function () {

      }
    });
  }

  $('.addQuestionFloatBtn').on('click', function () {
    var qType = $(this).data('addtype');
    addQuestion(qType);
  });

  $('form').on('click', '.deleteQ', function () {
    var qtype = $(this).data('deleteqtype'),
        qno = $(this).data('deleteqno');
    $('form').find('.row, hr').remove('[data-qno='+qno+'][data-qtype='+qtype+']');
  });

  function addQuestion(qType, data) {
    var qid = qType+'-q-'+qAmnt[qType];
    var choiceid = qType+'-c-'+qAmnt[qType];
    var ansid = qType+'-ans-'+qAmnt[qType];

    var $template = $('#questionTemplate');
    $template.find('.row')
      .attr('data-qno', qAmnt[qType])
      .attr('data-qtype', qType);
    $template.find('#questionTitle').attr('name', qid);
    $template.find('input[type=radio]').each(function () {
      var val = $(this).attr('value');
      $(this).attr('name', choiceid).attr('id', choiceid+'-'+val);
      $template.find('label[value='+val+']').attr('for', choiceid+'-'+val);
    });
    $template.find('.ansField').each(function () {
      var val = $(this).data('forchoice');
      $(this).attr('name', ansid+'-'+val);
    });
    $template.find('.deleteQ')
      .attr('data-deleteqno', qAmnt[qType])
      .attr('data-deleteqtype', qType);

    if(data) {
      $template.find('#questionTitle').attr('value', data.question);
      $template.find('#choice1').attr('value', data.choice1);
      $template.find('#choice2').attr('value', data.choice2);
      $template.find('#choice3').attr('value', data.choice3);
      $template.find('#choice4').attr('value', data.choice4);
      $template.find('input:radio[value='+data.correctchoice+']').attr('checked', data.correctchoice);
    }

    $(section[qType]).append($template.html());
    $(section[qType]).append($('<div/>').append($('<hr>').attr('data-qno', qAmnt[qType]).attr('data-qtype', qType).clone()).html());

    $template.find('#questionTitle, .ansField').removeAttr('value').val('');
    $template.find('input:radio').removeAttr('checked');

    window.scrollTo(0,document.body.scrollHeight);

    qAmnt[qType]++;

    $('#maxV').val(qAmnt.v);
    $('#maxG').val(qAmnt.g);
    $('#maxR').val(qAmnt.r);
    $('#maxL').val(qAmnt.l);
  }
  $('#search_quiz').on("keyup paste",function(){
    var searchReg = new RegExp($(this).val(), 'igm');
    i = 1;
    $("tbody").children("tr").each(function(){
      var thisValid = false;
      thisValid = $(this).find("td.name-desc").text().match(searchReg) != null;
      if(thisValid) {
        $(this).attr('data-number', i);
        $(this).attr('show', 'true');
        i++;
        // $(this).show();
      }else {
        $(this).attr('data-number', -1);
        $(this).attr('show', 'false');
        // $(this).hide();
      }
    });
    max_page = Math.ceil($("tr[data-number][show=true]").length / 20.0)
    curr_page = 1;
    filter_pagination();
  });

  $("#back_btn").on('click', function(){
    curr_page -= 1;
    filter_pagination();
  });

  $("#next_btn").on('click', function(){
    curr_page += 1;
    filter_pagination();
  });

});

function filter_pagination(){
  show_limit = curr_page * 20;
  if (curr_page == 1) {
    $("#back_btn").hide();
  } else {
    $("#back_btn").show();
  }

  if (curr_page >= max_page) {
    $("#next_btn").hide();
  } else {
    $("#next_btn").show();
  }

  $("tr[data-number]").each(function () {
    var this_number = parseInt($(this).attr('data-number'));
    if (this_number <= show_limit && this_number > show_limit - 20) {
      $(this).show();
    } else {
      $(this).hide();
    }
  });
}
// function () {
//   var qType = $(this).data('addtype');
//   var qid = qType+'-q-'+qAmnt[qType];
//   var choiceid = qType+'-c-'+qAmnt[qType];
//   var ansid = qType+'-ans-'+qAmnt[qType];
//
//   var $template = $('#questionTemplate');
//   $template.find('.row')
//     .attr('data-qno', qAmnt[qType])
//     .attr('data-qtype', qType);
//   $template.find('#questionTitle').attr('name', qid);
//   $template.find('input[type=radio]').each(function () {
//     var val = $(this).attr('value');
//     $(this).attr('name', choiceid).attr('id', choiceid+'-'+val);
//     $template.find('label[value='+val+']').attr('for', choiceid+'-'+val);
//   });
//   $template.find('.ansField').each(function () {
//     var val = $(this).data('forchoice');
//     $(this).attr('name', ansid+'-'+val);
//   });
//   $template.find('.deleteQ')
//     .attr('data-deleteqno', qAmnt[qType])
//     .attr('data-deleteqtype', qType);
//
//   $(section[qType]).append($template.html());
//   $(section[qType]).append($('<div/>').append($('<hr>').attr('data-qno', qAmnt[qType]).attr('data-qtype', qType).clone()).html());
//
//   window.scrollTo(0,document.body.scrollHeight);
//
//   qAmnt[qType]++;
//
//   $('#maxV').val(qAmnt.v);
//   $('#maxG').val(qAmnt.g);
//   $('#maxR').val(qAmnt.r);
//   $('#maxL').val(qAmnt.l);
// }
