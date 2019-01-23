<?php

$title = get_the_title();
$user_info = wp_get_current_user();
$display_name = $user_info->display_name;
?>
  <div class="one-half first entry-title">
    <p>
      <?php
          echo $title;
      ?>
    </p>
  </div>
  <div class="one-half user-name">
    <p>
      <?php
        echo $display_name;
      ?>
    </p>
  </div>
