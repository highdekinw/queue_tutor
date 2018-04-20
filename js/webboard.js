$(function () {
  $("#search_board").on("keyup paste",function(){
    var searchReg = new RegExp($(this).val(), 'igm');
    $("#board").children("div.thread").each(function(){
      var thisValid = false;
      thisValid = $(this).attr("data-text").match(searchReg) != null;
      if(thisValid) $(this).show();
      else $(this).hide();
    });
  });
  $("#search_thread").on("keyup press",function(){
    var searchReg = new RegExp($(this).val(), 'igm');
    $("#board").children("div.tpost").each(function(){
      var thisValid = false;
      thisValid = $(this).attr("data-text").match(searchReg) != null;
      if(thisValid) $(this).show();
      else $(this).hide();
    });
  });
  $('.editPost').on('click', function () {
    var postname = $(this).data('pname'),
        postcontent = $(this).data('pcontent'),
        postid = $(this).data('pid');

        $('#posteditid').val(postid);
        $('#postEditName').val(postname);
        $('#postEditContent').val(postcontent);

        $('#editPostModal').modal('open');
  });

  $('.editThread').on('click', function () {
    $('#editThreadModal #threadContent').val($('#editThreadModal #threadContent').attr('value'));
  });

  $('.deleteThread').on('click', function () {
    var deletethid = $(this).data('thid');
    $.ajax('webboard.php', {
      method: "POST",
      data: {
        deleteThread: 1,
        thid: deletethid
      },
      success: function () {},
      error: function () {},
      complete: function () {
        window.location.href = 'webboard.php';
      }
    });
  });

  $('.deletePost').on('click', function () {
    var deletepid = $(this).data('pid');
    $.ajax('webboard.php', {
      method: "POST",
      data: {
        deletePost: 1,
        pid: deletepid
      },
      success: function () {},
      error: function () {},
      complete: function () {
        location.reload();
      }
    });
  });
  
});
