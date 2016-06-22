<div id="sidebar" class="grid-35 hide-on-mobile">
  <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar')) : ?>
  <?php endif; ?>

  <div class="sidebar-footer">
    <?php wp_nav_menu(array('theme_location'=>'foot-menu','container_class' => 'foot-menu')); ?>
  </div>
</div>
