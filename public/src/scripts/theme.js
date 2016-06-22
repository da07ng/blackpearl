$(function() {
  $("#comment-form").submit(function(event) {
    event.preventDefault();

    $.ajax({
      url: $("#comment-form").attr("action").replace("wp-comments-post.php", ""),
      data: $(this).serialize() + "&action=da07ng_ajax_comment",
      type: $(this).attr('method'),
      beforeSend: function(xhr) {
        xhr.overrideMimeType("text/plain; charset=x-user-defined");
      },
      success: function(result) {
        var $respond = $('#respond');
        var $commentParent = $('#comment_parent');

        if ($commentParent.val() !== '0') {
          $respond.before('<ul class="children">' + result + '</ul>');
        } else if($('.comment-list').length === 0) {
          $('.comments-thorough').append('<ul class="comment-list">' + result + '</ul>');
        } else {
          $('.comment-list').append(result);
        }

        $commentParent.val(0);
        $('#comment').val('');
        $('.comments-thorough').after($respond);

        $('body').append('<div class="flash"><p class="message success">' + '提交成功' + '</p></div>');
  			setTimeout("$('.flash').remove()", 3000);
      },
      error: function(result) {
        $('body').append('<div class="flash"><p class="message warning">' + result.responseText + '</p></div>');
  			setTimeout("$('.flash').remove()", 3000);
      }
    });
  });

  addComment = {
    moveForm: function(commId, parentId, respondId) {
      var $comment = $('#' + commId);
      var $respond = $('#' + respondId);
      var $cancel = $('#cancel-comment-reply-link');
      var $commentParent = $('#comment_parent');

      $commentParent.val(parentId);
      $comment.after($respond)

      $cancel.css('display', '');
      $cancel.on('click', function(event) {
        event.preventDefault();

        $commentParent.val(0);
        $('.comments-thorough').after($respond);
      })

      return false;
    }
  }

  $('.comments-thorough').on("click", ".comments-navi a", function(event) {
    event.preventDefault();

    var baseUrl = $(this).attr("href");
    var postid = $(this).parent().data("postid");
    var pageid = 1;
    /comment-page-/i.test(baseUrl) ? pageid = baseUrl.split(/comment-page-/i)[1].split(/(\/|#|&).*comments/)[0] : /cpage=/i.test(baseUrl) && (pageid = baseUrl.split(/cpage=/)[1].split(/(\/|#|&).*comments/)[0]);

    $.ajax({
      type: "GET",
      url: $(this).attr('href'),
      data: {
        action: "da07ng_ajax_pagenavi",
        post: postid,
        page: pageid
      },
      beforeSend: function(){
        $('.comment_list').remove();
        $('.comments-navi').remove();
        $('.loading-comments').slideDown();
        $('body').animate({scrollTop: $('.comments-title').offset().top - 65}, 800 );
      },
      success: function(result){
        var $commentsThorough = $('.comments-thorough');

        $('.comment-list').remove();
        $('.comments-navi').remove();
        $('.loading-comments').slideUp();

        $commentsThorough.append(result);
      }
    });
  });
});
