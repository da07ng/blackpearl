<?php

function da07ng_menu()
{
  add_theme_page('主题设置', '主题设置', 'edit_themes', basename(__FILE__), 'da07ng_settings_page');
  add_action('admin_init', 'da07ng_settings');
}
add_action('admin_menu', 'da07ng_menu');

function da07ng_settings()
{
  register_setting('sf-settings-group', DA07NG_THEME_NAME.'_options');
}

function da07ng_settings_page()
{
  if (isset($_REQUEST['settings-updated'])) {
    echo '<div id="message" class="updated fade"><p><strong>主题设置已保存。</strong></p></div>';
  }

  if ('reset' == isset($_REQUEST['reset'])) {
    delete_option(DA07NG_THEME_NAME.'da07ng_options');
    echo '<div id="message" class="updated fade"><p><strong>主题设置已重置。</strong></p></div>';
  }
?>

<div class="wrap">
  <div id="icon-options-general" class="icon32"><br></div><h2>主题设置</h2><br>
  <form method="post" action="options.php">
    <?php settings_fields('sf-settings-group'); ?>
    <?php $options = get_option(DA07NG_THEME_NAME.'da07ng_options');?>
    <table class="form-table">
      <tbody>
        <tr valign="top">
          <th scope="row"><label>网站描述</label></th>
          <td>
            <p>用简洁凝练的话对你的网站进行描述</p>
            <p><textarea type="textarea" class="large-text" name="da07ng_options[description]"><?php echo $options['description']; ?></textarea></p>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row"><label>网站关键词</label></th>
          <td>
            <p>多个关键词请用英文逗号隔开</p>
            <p><textarea type="textarea" class="large-text" name="da07ng_options[keywords]"><?php echo $options['keywords']; ?></textarea></p>
          </td>
        </tr>
      </tbody>
    </table>
    <div class="da07ng_submit_form">
      <input type="submit" class="button-primary da07ng_submit_form_btn" name="save" value="<?php _e('Save Changes') ?>"/>
    </div>
  </form>
  <form method="post">
    <div class="da07ng_reset_form">
      <input type="submit" name="reset" value="<?php _e('Reset') ?>" class="button-secondary da07ng_reset_form_btn"/> 重置有风险，操作需谨慎！
      <input type="hidden" name="reset" value="reset" />
    </div>
  </form>
</div>

<?php
}
?>
