<?php

$title = get_the_title();
$user_info = wp_get_current_user();
$display_name = $user_info->display_name;
?>
  <div class="top-matter wrap">
    <div class="one-half first entry-title">
      <h3>
        <?php
            echo $title;
        ?>
      </h3>
    </div>
    <div class="one-half user-name">
      <p>
        <?php
          echo $display_name;
        ?>
        <a href='/dashboard'>
          <span class="dashicons dashicons-admin-generic"></span>
        </a>
      </p>

    </div>
  </div>
