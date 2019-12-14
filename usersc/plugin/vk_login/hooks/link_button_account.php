<?php
global $user;
if($user->data()->vk_id == ''){ ?>
  <p><button type="button" onclick="window.location.href = '<?=$us_url_root?>usersc/plugins/vk_login/link_account.php';" name="button" class="btn btn-primary">Link VK Account</button></p>
<?php }else{ ?>
  <p><button type="button" onclick="window.location.href = '#';" name="button" class="btn btn-default">VK Account Linked</button></p>
<?php } ?>
