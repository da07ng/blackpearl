<?php get_header();?>

<div id="main">
  <div class="container grid-container">
    <div id="content" class="grid-65 mobile-grid-100">
      <div class="post-list">
        <?php if(have_posts()):while (have_posts()) : the_post();?>
          <div class="post" id="post-<?php the_ID();?>">
            <div class="post-meta">
              <div class="avatar">
                <?php echo get_avatar($post->post_author, 36); ?>
              </div>
              <div class="summary">
                <span class="meta-author"><?php the_author() ?></span>
                <span class="meta-timestamp"><?php echo time_since(get_the_time('U'), current_time('timestamp')); ?></span>
              </div>
            </div>
            <div class="post-header">
              <?php da07ng_thumbnail(600, 180); ?>
              <h2 class="post-title">
                <a href="<?php the_permalink(); ?>" title="<?php the_title();?>"><?php the_title();?></a>
              </h2>
            </div>
            <div class="post-body">
              <?php echo "<p>" . mb_strimwidth(strip_tags(apply_filters('the_content', $post->post_content)), 0, 200,"..."). "</p>";?>
            </div>
            <div class="post-footer">
              <div class="read-more">
                <a href="<?php the_permalink(); ?>" title="<?php the_title();?>">Read more…</a>
              </div>
              <div class="info">
                <span><?php if(function_exists('the_views')) the_views();?></span>
                <div class="alignright">
                  <?php comments_popup_link('',' 1 response',' % responses');?>
                </div>
              </div>
            </div>
          </div>
        <?php endwhile; endif;?>
      </div>
      <div class="post-navi"><?php pagenavi();?></div>
    </div>
    <?php get_sidebar(); ?>
  </div>
</div>

<?php get_footer(); ?>
