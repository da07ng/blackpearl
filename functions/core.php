<?php

/**
 * Time since
 * @param  [type] $older_date   [description]
 * @param  [type] $comment_date [description]
 * @return [type]               [description]
 */
function time_since($older_date,$comment_date = false)
{
  $chunks = array(
    array(86400 , '天前'),
    array(3600 , '小时前'),
    array(60 , '分钟前'),
    array(1 , '秒前'),
 );
  $newer_date = time();
  $since = abs($newer_date - $older_date);
  if ($since < 2592000) {
    for ($i = 0, $j = count($chunks); $i < $j; $i++) {
      $seconds = $chunks[$i][0];
      $name = $chunks[$i][1];
      if (($count = floor($since / $seconds)) != 0) break;
    }
    $output = $count.$name;
  } else {
    $output = !$comment_date ? (date('M d, Y G:i', $older_date)) : (date('M d, Y', $older_date));
  }
  return $output;
}

/**
 * Cache gavatar
 * @param  [type] $avatar      [description]
 * @param  [type] $id_or_email [description]
 * @param  [type] $size        [description]
 * @param  [type] $alt         [description]
 * @return [type]              [description]
 */
function da07ng_cache_avatar($avatar, $id_or_email, $size, $alt)
{
    $avatar = str_replace(array("www.gravatar.com", "0.gravatar.com", "1.gravatar.com", "2.gravatar.com"), "cn.gravatar.com", $avatar);
    $tmp = strpos($avatar, 'http');
    $url = get_avatar_url($id_or_email, $size);
    //$url = str_replace(array("www.gravatar.com", "0.gravatar.com", "1.gravatar.com", "2.gravatar.com"), "cn.gravatar.com", $url);
    $url2x = get_avatar_url($id_or_email, ($size * 2));
    //$url2x = str_replace(array("www.gravatar.com", "0.gravatar.com", "1.gravatar.com", "2.gravatar.com"), "cn.gravatar.com", $url2x);
    $g = substr($avatar, $tmp, strpos($avatar, "'", $tmp) - $tmp);
    $tmp = strpos($g, 'avatar/') + 7;
    $f = substr($g, $tmp, strpos($g, "?", $tmp) - $tmp);
    $w = get_bloginfo('wpurl');
    $e = ABSPATH .'avatar/'. $size . '@'. $f .'.jpg';
    $e2x = ABSPATH .'avatar/'. ($size * 2) . '@'. $f .'.jpg';
    $t = 604800;
    if ((!is_file($e) || (time() - filemtime($e)) > $t) && (!is_file($e2x) || (time() - filemtime($e2x)) > $t)) {
        copy(htmlspecialchars_decode($g), $e);
        copy(htmlspecialchars_decode($url2x), $e2x);
    } else {
        $avatar = $w.'/avatar/'. $size . '@'.$f.'.jpg';
        $avatar2x = $w.'/avatar/'. ($size * 2) . '@'.$f.'.jpg';
        if (filesize($e) < 1000) copy($w.'/avatar/default.png', $e);
        if (filesize($e2x) < 1000) copy($w.'/avatar/default.png', $e2x);
        $avatar = "<img alt='{$alt}' src='{$avatar}' srcset='{$avatar2x}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
    }
    return $avatar;
}
add_filter('get_avatar', 'da07ng_cache_avatar', 1, 5);

/**
 * thumbnail
 * @param  integer $width  [description]
 * @param  integer $height [description]
 * @return [type]          [description]
 */
function da07ng_thumbnail($width=620, $height=180)
{
  global $post;
  $title = $post->post_title;
  if (has_post_thumbnail()) {
    $timthumb_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
    //$post_timthumb = '<img src="'.get_bloginfo("template_url").'/timthumb.php?src='.$timthumb_src[0].'&amp;h='.$height.'&amp;w='.$width.'&amp;zc=1" alt="'.$post->post_title.'" class="thumb" />';
    //$post_timthumb = '<img src="'.$timthumb_src[0].'?imageMogr2/thumbnail/'.$width.'x'.$height.'!" />'; //qiniu
    $post_timthumb = '<img src="'.$timthumb_src[0].'!thumb" />'; //upyun
    echo $post_timthumb;
  } else {
    $content = $post->post_content;
    preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
    $n = count($strResult[1]);
    if ($n > 0) {
      //echo '<img src="'.$strResult[1][0].'?imageMogr2/thumbnail/'.$width.'x'.$height.'!" />'; //qiniu
      echo '<img src="'.$strResult[1][0].'!thumb" />'; //upyun
    }
  }
}

/**
 * pagenavi
 * @param  integer $p [description]
 * @return [type]     [description]
 */
function pagenavi($p = 5)
{
  if (is_singular()) return;
  global $wp_query, $paged;
  $max_page = $wp_query->max_num_pages;
  if ($max_page == 1) return;
  if (empty($paged)) $paged = 1;
  if ($paged > 1) p_link($paged - 1, '« Previous', '«');
  if ($paged > $p + 2) echo '<span class="page-numbers">...</span>';
  for ($i = $paged - $p; $i <= $paged + $p; $i++) {
    if ($i > 0 && $i <= $max_page) $i == $paged ? print "<span class='page-numbers current'>{$i}</span> " : p_link($i);
  }
  if ($paged < $max_page - $p - 1) echo '<span class="page-numbers">...</span>';
  if ($paged < $max_page) p_link($paged + 1,'Next »', '»');
}

function p_link($i, $title = '', $linktype = '')
{
  if ($title == '') $title = "第 {$i} 页";
  if ($linktype == '') { $linktext = $i; } else { $linktext = $linktype; }
  echo "<a class='page-numbers' href='", esc_html(get_pagenum_link($i)), "' title='{$title}'>{$linktext}</a> ";
}

/**
 * Comment mail notify
 * @param  [type] $comment_id [description]
 * @return [type]             [description]
 */
function comment_mail_notify($comment_id) {
  $comment = get_comment($comment_id);
  $parent_id = $comment->comment_parent ? $comment->comment_parent : '';
  $spam_confirmed = $comment->comment_approved;

  $wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME']));
  $from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
  $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";

  if (($parent_id != '') && ($spam_confirmed != 'spam')) {
    $to = trim(get_comment($parent_id)->comment_author_email);
    $subject = '你在 [' . get_option("blogname") . '] 的留言有了新回复';
    $message = '
      <div style="background-color:#eef2fa; border:1px solid #d8e3e8; color:#111; padding:0 15px; -moz-border-radius:5px; -webkit-border-radius:5px; -khtml-border-radius:5px; border-radius:5px;">
      <p><strong>' . trim(get_comment($parent_id)->comment_author) . ', 你好!</strong></p>
      <p><strong>您曾在《' . get_the_title($comment->comment_post_ID) . '》的留言为:</strong><br />'
      . trim(get_comment($parent_id)->comment_content) . '</p>
      <p><strong>' . trim($comment->comment_author) . ' 给你的回复是:</strong><br />'
      . trim($comment->comment_content) . '<br /></p>
      <p>你可以点击此链接 <a href="' . htmlspecialchars(get_comment_link($parent_id)) . '">查看完整内容</a></p><br />
      <p>欢迎再次来访<a href="' . get_option('home') . '">' . get_option('blogname') . '</a></p>
      <p>(此邮件为系统自动发送，请勿直接回复.)</p>
      </div>';

    wp_mail( $to, $subject, $message, $headers );
    // echo 'mail to ', $to, '<br/> ' , $subject, $message; // for testing
  }
}
// add_action('comment_post', 'comment_mail_notify');

/**
 * Coment
 * @param  [type] $comment [description]
 * @param  [type] $args    [description]
 * @param  [type] $depth   [description]
 * @return [type]          [description]
 */
function da07ng_comment($comment, $args, $depth)
{
  $GLOBALS['comment'] = $comment;
  global $commentcount;

  if (!$commentcount) {
    $page = (!empty($in_comment_loop)) ? get_query_var('cpage') - 1 : get_page_of_comment($comment->comment_ID, $args) - 1;
    $cpp = get_option('comments_per_page');
    $commentcount = $cpp * $page;
  }
  if (!$comment->comment_parent) {
  ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
      <div id="comment-<?php comment_ID(); ?>" class="comment-body">
        <div class="comment-info">
          <div class="comment-avatar">
            <?php echo get_avatar($comment, $size = '40'); ?>
          </div>
          <div class="comment-meta">
            <span class="comment-span"><?php printf(__('%s'), get_comment_author_link()) ?> /</span>
            <?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => __('reply')))) ?>
            <span class="comment-span comment-date"><?php echo time_since(abs(strtotime($comment->comment_date_gmt . "GMT")), true);?></span>
          </div>
        </div>
        <div class="comment-content">
          <?php comment_text() ?>
        </div>
      </div>
  <?php } else {?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
      <div id="comment-<?php comment_ID(); ?>" class="comment-body comment-children-body">
        <div class="comment-info">
          <div class="comment-avatar">
            <?php echo get_avatar($comment, $size = '30'); ?>
          </div>
          <div class="comment-meta">
            <span class="comment-span"><?php $parent_id = $comment->comment_parent; $comment_parent = get_comment($parent_id); printf(__('%s'), get_comment_author_link()) ?> /</span>
            <?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => __('reply')))) ?>
            <span class="comment-span comment-date"><?php echo time_since(abs(strtotime($comment->comment_date_gmt . "GMT")), true);?></span>
          </div>
        </div>
        <div class="comment-content">
          <span class="comment-to"><a href="<?php echo "#comment-".$parent_id;?>" title="<?php echo mb_strimwidth(strip_tags(apply_filters('the_content', $comment_parent->comment_content)), 0, 100,"..."); ?>">@<?php echo $comment_parent->comment_author;?></a>：</span>
          <?php echo get_comment_text(); ?>
        </div>
      </div>
  <?php }
}

/**
 * Ajax comment
 * @return [type] [description]
 */
function da07ng_ajax_comment()
{
  if ($_POST['action'] == 'da07ng_ajax_comment' && 'POST' == $_SERVER['REQUEST_METHOD']) {
    if (strtolower(trim(strip_tags($_POST['author']))) == 'gaudj' || strtolower(trim(strip_tags($_POST['author']))) == 'gaodaojing' || strtolower(trim($_POST['email'])) == 'gaodaojing@gmail.com') {
      ajax_comment_err('请勿冒充博主发表评论');
    }

    $comment = wp_handle_comment_submission(wp_unslash($_POST));
    if (is_wp_error($comment)) {
      $data = $comment->get_error_data();
      if (!empty($data)) {
        ajax_comment_err($comment->get_error_message());
      } else {
        exit;
      }
    }
    $user = wp_get_current_user();
    do_action('set_comment_cookies', $comment, $user);
    $GLOBALS['comment'] = $comment;

    //以下是評論式樣, 不含 "回覆". 要用你模板的式樣 copy 覆蓋.
    if (!$comment->comment_parent) {
    ?>
      <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
        <div id="comment-<?php comment_ID(); ?>" class="comment-body">
          <div class="comment-info">
            <div class="comment-avatar">
              <?php echo get_avatar( $comment, $size = '40'); ?>
            </div>
            <div class="comment-meta">
              <span class="comment-span"><?php printf(__('%s'), get_comment_author_link()) ?> /</span>
              <span class="comment-span comment-date"><?php echo time_since(abs(strtotime($comment->comment_date_gmt . "GMT")), true);?></span>
            </div>
          </div>
          <div class="comment-content">
            <?php comment_text() ?>
          </div>
        </div>
    <?php } else {?>
      <li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
        <div id="comment-<?php comment_ID(); ?>" class="comment-body comment-children-body">
          <div class="comment-info">
            <div class="comment-avatar"><?php echo get_avatar( $comment, $size = '30'); ?></div>
            <div class="comment-meta">
              <span class="comment-span"><?php $parent_id = $comment->comment_parent; $comment_parent = get_comment($parent_id); printf(__('%s'), get_comment_author_link()) ?> /</span>
              <span class="comment-span comment-date"><?php echo time_since(abs(strtotime($comment->comment_date_gmt . "GMT")), true);?></span>
            </div>
          </div>
          <div class="comment-content">
            <span class="comment-to"><a href="<?php echo "#comment-".$parent_id;?>" title="<?php echo mb_strimwidth(strip_tags(apply_filters('the_content', $comment_parent->comment_content)), 0, 100,"..."); ?>">@<?php echo $comment_parent->comment_author;?></a>：</span>
            <?php echo get_comment_text(); ?>
          </div>
        </div>
    <?php }
    die(); //以上是評論式樣, 不含 "回覆". 要用你模板的式樣 copy 覆蓋.
  } else {return;}
}
add_action('init', 'da07ng_ajax_comment');

/**
 * 增加: 錯誤提示功能
 * @param  [type] $a [description]
 * @return [type]    [description]
 */
function ajax_comment_err($message)
{
  header('HTTP/1.0 500 Internal Server Error');
  header('Content-Type: text/plain;charset=UTF-8');
  echo $message;
  exit;
}

/**
 * Comment anti spam
 */
class anti_spam {
  function anti_spam() {
    if (!current_user_can('level_0')) {
      add_action('template_redirect', array($this, 'w_tb'), 1);
      add_action('init', array($this, 'gate'), 1);
      add_action('preprocess_comment', array($this, 'sink'), 1);
    }
  }

  function w_tb() {
    if (is_singular()) {
      ob_start(create_function('$input','return preg_replace("#textarea(.*?)name=([\"\'])comment([\"\'])(.+)/textarea>#",
                "textarea$1name=$2w$3$4/textarea><textarea name=\"comment\" cols=\"100%\" rows=\"4\" style=\"display:none\"></textarea>",$input);'));
    }
  }

  function gate() {
    if (!empty($_POST['w']) && empty($_POST['comment'])) {
      $_POST['comment'] = $_POST['w'];
    } else {
      $request = $_SERVER['REQUEST_URI'];
      $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '隐瞒';
      $IP = isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] . ' (透过D理)' : $_SERVER["REMOTE_ADDR"];
      $way = isset($_POST['w']) ? '手动操作' : '未经评论表格';
      $spamcom = isset($_POST['comment']) ? $_POST['comment'] : null;
      $_POST['spam_confirmed'] = "请求: ". $request. "\n来路: ". $referer. "\nIP: ". $IP. "\n方式: ". $way. "\n內容: ". $spamcom. "\n -- 记录成功 --";
    }
  }

  function sink($comment) {
    if (!empty($_POST['spam_confirmed'])) {
      if (in_array($comment['comment_type'], array('pingback', 'trackback'))) return $comment;
      //方法一: 直接挡掉, 將 die(); 前面两斜线刪除即可.
      //die();
      //方法二: 标记为 spam, 留在资料库检查是否误判.
      add_filter('pre_comment_approved', create_function('', 'return "spam";'));
      $comment['comment_content'] = "[ 判断这是 Spam! ]\n". $_POST['spam_confirmed'];
    }
    return $comment;
  }
}
$anti_spam = new anti_spam();

/**
 * Ajax comments pagenavi in the theme.
 *
 */
function da07ng_ajax_pagenavi()
{ // pagenavi
  if (isset($_GET['action']) && $_GET['action']== 'da07ng_ajax_pagenavi') {
    global $post,$wp_query, $wp_rewrite;
    $postid = isset($_GET['post']) ? $_GET['post'] : null;
    $pageid = isset($_GET['page']) ? $_GET['page'] : null;
    if(!$postid || !$pageid){
      ajax_comment_err(__('Error post id or comment page id.'));
    }
    // get comments
    $comments = get_comments('post_id='.$postid);

    $post = get_post($postid);

    if (!$comments) {
      ajax_comment_err(__('Error! can\'t find the comments'));
    }

    if ('desc' != get_option('comment_order')) {
      $comments = array_reverse($comments);
    }

    // set as singular (is_single || is_page || is_attachment)
    $wp_query->is_singular = true;

    // base url of page links
    $baseLink = '';
    if ($wp_rewrite->using_permalinks()) {
      $baseLink = '&base=' . user_trailingslashit(get_permalink($postid) . 'comment-page-%#%', 'commentpaged');
    }

    echo '<ul class="comment-list">';
    wp_list_comments('callback=da07ng_comment&type=comment&page=' . $pageid . '&per_page=' . get_option('comments_per_page'), $comments);
    echo '</ul><div class="comments-navi" data-postid=' . $postid . '>';
    paginate_comments_links('prev_text=«&next_text=»&current=' . $pageid);
    $totalPage = '&total='.get_comment_pages_count($comments);
    paginate_comments_links('prev_text=«&next_text=»&current=' . $pageid . $totalPage . $baseLink);
    echo '</div>';

    die;
  } else {return;}
}
add_action('init', 'da07ng_ajax_pagenavi');

/**
 * da07ng_esc_html Escape special characters in pre & code into their HTML entities
 * @param  [type] $content [description]
 * @return [type]          [description]
 */
function da07ng_esc_html($content)
{
  $regex = '/(<pre\s+[^>]*?class\s*?=\s*?[",\'].*?language-.*?[",\'].*?>)(.*?)(<\/pre>)/si';
  $content = preg_replace_callback($regex, parse_content_pre, $content);

  $regex = '/(<code\s+[^>]*?class\s*?=\s*?[",\']\s*?language-.*?[",\'].*?>)(.*?)(<\/code>)/si';
  $content = preg_replace_callback($regex, parse_content_code, $content);

  return $content;
}

function parse_content_pre($matches)
{
  $tags_open = $matches[1];
  $code = $matches[2];
  $tags_close = $matches[3];

  $regex = '/(<code.*?>)(.*?)(<\/code>)/si';
  preg_match($regex, $code, $matches);
  if (!empty($matches)) {
    $tags_open .= $matches[1];
    $code = $matches[2];
    $tags_close = $matches[3].$tags_close;
  }

  $parsed_code = htmlspecialchars($code, ENT_NOQUOTES, get_bloginfo('charset'), true);

  $parsed_code = str_replace('&amp;#038;', '&amp;', $parsed_code);
  return $tags_open.$parsed_code.$tags_close;
}

function parse_content_code($matches)
{
  $tags_open = $matches[1];
  $code = $matches[2];
  $tags_close = $matches[3];

  $parsed_code = htmlspecialchars($code, ENT_NOQUOTES, get_bloginfo('charset'), true);
  $parsed_code = str_replace('&amp;#038;', '&amp;', $parsed_code);
  return $tags_open.$parsed_code.$tags_close;
}
add_filter('the_content', 'da07ng_esc_html');
add_filter('comment_text', 'da07ng_esc_html');

?>
