<?php

function template_archives_list()
{
  $the_query = new WP_Query('posts_per_page=-1&ignore_sticky_posts=1');
  $year=0; $mon=0; $i=0; $j=0;
  $all = array();
  $output = '';

  while ($the_query->have_posts()) : $the_query->the_post();
    $year_tmp = get_the_time('Y');
    $mon_tmp = get_the_time('n');
    $y=$year; $m=$mon;
    if ($mon != $mon_tmp && $mon > 0) $output .= '</div></div>';
    if ($year != $year_tmp) { // 输出年份
      $year = $year_tmp;
      $all[$year] = array();
    }
    if ($mon != $mon_tmp) { // 输出月份
      $mon = $mon_tmp;
      array_push($all[$year], $mon);
      $output .= "<div class='archive-list' id='arti-$year-$mon'><h3 class='month-title'>$year-$mon</h3><div class='archives archives-$mon' data-date='$year-$mon'>";
    }
    $output .= '<div class="archive-item"><a href="'.get_permalink() .'">'.get_the_title() .' - '. get_comments_number('0', '1', '%') .' responses</a></div>';
  endwhile;

  wp_reset_postdata();
  $output .= '</div></div>';
  return $output;
}

?>
