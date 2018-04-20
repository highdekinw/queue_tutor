var max_page;
var curr_page;

$(document).ready(function () {
  max_page = Math.ceil($("tr[data-number]").length / 20.0);
  curr_page = 1;
  filter_pagination();
});

$(function () {
  $("input#search_gal").on('keyup', function () {
    if ($(this).val() === '') {
      $('tbody tr').show();
      return;
    }

    var searchReg = new RegExp($(this).val(), 'ig');
    i = 1;
    $('tbody tr').each(function () {
      if (searchReg.test($(this).attr('data-galname'))) {
        // $(this).show();
        $(this).attr('data-number', i);
        $(this).attr('show', 'true');
        i++;
      } else {
        // $(this).hide();
        $(this).attr('data-number', -1);
        $(this).attr('show', 'false');
      }
    });
    max_page = Math.ceil($("tr[data-number][show=true]").length / 20.0)
    curr_page = 1;
    filter_pagination();
  });

  $('.editGal').on('click', function () {
    var gall = {
      name: $(this).closest('tr').data('galname'),
      id: $(this).data('editgalid'),
      path: $(this).data('originalfile')
    };
    console.log(gall);
    $('#editGalModal #galeditname').html(gall.name);
    $('#editGalModal #galname').val(gall.name);
    $('#editGalModal #galeditid').val(gall.id);
    $('#editGalModal #galeditoldfile').attr('src', gall.path);
    $('#editGalModal').modal('open');
  });

  $("#back_btn").on('click', function () {
    curr_page -= 1;
    filter_pagination();
  });

  $("#next_btn").on('click', function () {
    curr_page += 1;
    filter_pagination();
  });
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