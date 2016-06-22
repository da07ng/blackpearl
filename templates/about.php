<?php
/**
 * Template Name: 关于
 */
get_header();?>

<div id="main">
  <div id="content">
    <div class="template-thorough">
      <div class="container grid-container">
        <?php if(have_posts()):while (have_posts()) : the_post();?>
          <div class="about grid-70 prefix-15 suffix-15 mobile-grid-100" id="post-<?php the_ID();?>">
            <div class="about-header">
              <h2 class="about-title"><a href="<?php the_permalink(); ?>" title="<?php the_title();?>"><?php the_title();?></a></h2>
            </div>
            <div class="about-body">
              <?php the_content('');?>
            </div>
          </div>
        <?php endwhile; endif;?>
      </div>
    </div>
    <?php comments_template(); ?>
  </div>
</div>

<?php get_footer(); ?>
