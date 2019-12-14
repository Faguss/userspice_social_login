<?php
global $user;
if($user->data()->discord_id != ''){ ?>
  <p>Discord <?=lang("MENU_ACCOUNT")?>: <?=$user->data()->discord_un?></p>
<?php }?>
