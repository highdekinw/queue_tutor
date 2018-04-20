var max_page;
var curr_page;

$(document).ready(function () {
  max_page = Math.ceil($("tr[data-number]").length / 20.0);
  curr_page = 1;
  filter_pagination();
});

$('#search_files').on("keyup paste", function () {
  var searchReg = new RegExp($(this).val(), 'igm');
  i = 1;
  $("tbody").children("tr").each(function () {
    var thisValid = false;
    thisValid = $(this).attr("data-file").match(searchReg) != null;
    if (thisValid) {
      $(this).attr('data-number', i);
      $(this).attr('show', 'true');
      i++;
    } else {
      $(this).attr('data-number', -1);
      $(this).attr('show', 'false');
    }
  });
  max_page = Math.ceil($("tr[data-number][show=true]").length / 20.0)
  curr_page = 1;
  filter_pagination();
});

$("#back_btn").on('click', function () {
  curr_page -= 1;
  filter_pagination();
});

$("#next_btn").on('click', function () {
  curr_page += 1;
  filter_pagination();
});

$("a[data-edit-fileid]").on('click', function(){
  $("#edit_filename").val($(this).attr('data-edit-filename'));
  $("#edit_fileid").val($(this).attr('data-edit-fileid'));
  $("#edit_path").val($(this).attr('data-edit-path'));
});

function filter_pagination() {
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