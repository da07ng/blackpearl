<?php

class Popular_articles_widget extends WP_Widget
{
  function Popular_articles_widget()
  {
    $widget_ops = array('description' => 'Blackpearl：热门文章（需要WP-PostViews插件）');
    $this->WP_Widget('Popular_articles_widget', 'Blackpearl：热门文章', $widget_ops);
  }

  function widget($args, $instance)
  {
    extract($args);
    $limit = strip_tags($instance['limit']);
    $limit = $limit ? $limit : 10;
    ?>
    <div class="widget widget-popular">
      <div class="widget-header">
        <h3>Popular Articles</h3>
      </div>
      <ul class="widget-list">
        <?php $args = array(
          'paged' => 1,
          'meta_key' => 'views',
          'orderby' => 'meta_value_num',
          'ignore_sticky_posts' => 1,
          'post_type' => 'post',
          'post_status' => 'publish',
          'showposts' => $limit
       );
        $posts = query_posts($args); ?>
        <?php while(have_posts()) : the_post(); ?>
          <li class="widget-item">
            <p>
              <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>">
                <?php the_title(); ?>
              </a>
              <span>[<?php if(function_exists('the_views')) the_views();?>]</span>
            </p>
          </li>
        <?php endwhile;wp_reset_query();$posts=null;?>
      </ul>
    </div>
  <?php
  }

  function update($new_instance, $old_instance)
  {
    if (!isset($new_instance['submit'])) {
      return false;
    }
    $instance = $old_instance;
    $instance['limit'] = strip_tags($new_instance['limit']);
    return $instance;
  }

  function form($instance)
  {
    global $wpdb;
    $instance = wp_parse_args((array) $instance, array('limit' => ''));
    $limit = strip_tags($instance['limit']);
    ?>
    <p>
      <label for="<?php echo $this->get_field_id('limit'); ?>">文章数量：
        <input id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo $limit; ?>" />
      </label>
    </p>
    <input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
  <?php
  }
}

add_action('widgets_init', 'Popular_articles_widget_init');
function Popular_articles_widget_init()
{
  register_widget('Popular_articles_widget');
}


class Latest_comment_widget extends WP_Widget
{
  function Latest_comment_widget()
  {
    $widget_ops = array('description' => 'Blackpearl：最新评论');
    $this->WP_Widget('Latest_comment_widget', 'Blackpearl：最新评论', $widget_ops);
  }

  function widget($args, $instance)
  {
    extract($args);
    $limit = strip_tags($instance['limit']);
    $limit = $limit ? $limit : 5;
    ?>
    <div class="widget widget-comment">
      <div class="widget-header">
        <h3>Latest Comments</h3>
      </div>
      <ul class="widget-list">
        <?php
        $comments = get_comments("user_id=0&number={$limit}&status=approve&type=comment");
        foreach ($comments as $comment) { ?>
          <li class="widget-item">
            <p>
              <a href="<?php echo get_permalink($comment->comment_post_ID); ?>#comment-<?php echo $comment->comment_ID; ?>">
                <?php echo mb_strimwidth(strip_tags(apply_filters('the_content', $comment->comment_content)), 0, 33,".."); ?>
              </a>
            </p>

            <p>
              <?php echo $comment->comment_author; ?>
              <span><?php echo time_since(strtotime($comment->comment_date_gmt)); ?></span>
            </p>
          </li>
        <?php
        }
        ?>
      </ul>
    </div>
  <?php
  }

  function update($new_instance, $old_instance)
  {
    if (!isset($new_instance['submit'])) {
      return false;
    }
    $instance = $old_instance;
    $instance['limit'] = strip_tags($new_instance['limit']);

    return $instance;
  }

  function form($instance)
  {
    global $wpdb;
    $instance = wp_parse_args((array) $instance, array('limit' => ''));
    $limit = strip_tags($instance['limit']);
    ?>

    <p>
      <label for="<?php echo $this->get_field_id('limit'); ?>">评论数量：<input id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo $limit; ?>"/></label>
    </p>
    <input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1"/>
  <?php
  }
}

add_action('widgets_init', 'Latest_comment_widget_init');
function Latest_comment_widget_init()
{
  register_widget('Latest_comment_widget');
}


class Friend_link_widget extends WP_Widget
{
  function Friend_link_widget()
  {
    $widget_ops = array('description' => 'Blackpearl：友情链接');
    $this->WP_Widget('Friend_link_widget', 'Blackpearl：友情链接', $widget_ops);
  }

  function widget($args, $instance)
  {
    extract($args);
    $limit = strip_tags($instance['limit']);
    $limit = $limit ? $limit : 10;
    ?>
    <div class="widget widget-links">
      <div class="widget-header">
        <h3>Links</h3>
      </div>
      <ul class="widget-list">
        <?php $bookmarks = get_bookmarks('limit=' . $limit);
        if (!empty($bookmarks)) {
          foreach ($bookmarks as $bookmark) {
          ?>
            <li class="widget-item">
              <a class="tooltipped tooltipped-n" href="<?php echo $bookmark->link_url; ?>"
                 aria-label="<?php echo $bookmark->link_description != '' ? $bookmark->link_description : $bookmark->link_name; ?>"><?php echo $bookmark->link_name; ?></a>
            </li>
          <?php
          }
        } ?></ul>
      </ul>
    </div>
  <?php
  }

  function update($new_instance, $old_instance)
  {
    if (!isset($new_instance['submit'])) {
      return false;
    }
    $instance = $old_instance;
    $instance['limit'] = strip_tags($new_instance['limit']);

    return $instance;
  }

  function form($instance)
  {
    $instance = wp_parse_args((array) $instance, array('limit' => ''));
    $limit = strip_tags($instance['limit']);
    ?>

    <p>
      <label for="<?php echo $this->get_field_id('limit'); ?>">链接数量：<input id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo $limit; ?>"/></label>
    </p>
    <input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1"/>
  <?php
  }
}

add_action('widgets_init', 'Friend_link_widget_init');
function Friend_link_widget_init()
{
  register_widget('Friend_link_widget');
}

?>
