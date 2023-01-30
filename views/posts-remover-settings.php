<div class="wrap">
  <div id="posts-remover-app" class="bpr-settings">
    <form method="post" action="options.php" id="bpr-settings-form">
      <?php
      settings_fields('bpr_settings');
      do_settings_sections('bulk-posts-remover');
      submit_button();
      ?>
    </form>
  </div>
</div>