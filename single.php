<?php get_header(); ?>

<div id="main">
    <div id="content">
      <div class="post-thorough">
        <div class="container grid-container">
        <?php if (have_posts()):while (have_posts()) : the_post();?>
          <div class="post grid-70 prefix-15 suffix-15 mobile-grid-100" id="post-<?php the_ID(); ?>">
            <div class="post-header">
              <h2 class="post-title"><?php the_title();?></h2>
              <div class="post-meta"></div>
            </div>
            <div class="post-body">
              <?php the_content(''); ?>
            </div>
            <div class="post-extra">
              <div class="post-tags">
                <?php if (get_the_tags()) { the_tags("Tags: ", ", "); } else{ echo "";  } ?>
              </div>
            </div>
          </div>
        <?php endwhile; endif;?>
      </div>
    </div>
    <?php comments_template(); ?>
  </div>
</div>

<?php get_footer(); ?>
