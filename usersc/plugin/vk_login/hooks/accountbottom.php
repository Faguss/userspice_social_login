<?php
global $user;
if($user->data()->vk_id != ''){ ?>
  <p>VK <?=lang("MENU_ACCOUNT")?>: <?=$user->data()->vk_un?></p>
<?php }?>
