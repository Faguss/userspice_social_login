<?php
global $user;
if($user->data()->discord_id == ''){ ?>
  <p><button type="button" onclick="window.location.href = '<?=$us_url_root?>usersc/plugins/discord_login/link_account.php';" name="button" class="btn btn-primary">Link Discord Account</button></p>
<?php }else{ ?>
  <p><button type="button" onclick="window.location.href = '#';" name="button" class="btn btn-default">Discord Account Linked</button></p>
<?php } ?>
