<?php
/**
 * Template Name: 归档
 */
get_header();?>

<div id="main">
  <div id="content">
    <div class="template-thorough">
      <div class="container grid-container">
        <?php if(have_posts()):while (have_posts()) : the_post();?>
          <div class="archive grid-70 prefix-15 suffix-15 mobile-grid-100" id="post-<?php the_ID();?>">
            <div class="archive-header">
              <h2 class="archive-title"><a href="<?php the_permalink(); ?>" title="<?php the_title();?>"><?php the_title();?></a></h2>
            </div>
            <div class="archive-body">
              <?php echo template_archives_list(); ?>
            </div>
          </div>
        <?php endwhile; endif;?>
      </div>
      <div id="pagenavi"><?php pagenavi();?></div>
    </div>
  </div>
</div>

<?php get_footer(); ?>
