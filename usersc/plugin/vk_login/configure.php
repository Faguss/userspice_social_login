  <?php if(!in_array($user->data()->id,$master_account)){ Redirect::to($us_url_root.'users/admin.php');} //only allow master accounts to manage plugins! ?>

<?php
include "plugin_info.php";
pluginActive($plugin_name);
 if(!empty($_POST['plugin_vk_login'])){
   $token = $_POST['csrf'];
if(!Token::check($token)){
  include($abs_us_root.$us_url_root.'usersc/scripts/token_error.php');
}
   // Redirect::to('admin.php?err=I+agree!!!');
 }
 $token = Token::generate();
 ?>
 <div class="content mt-3">
   <div class="row">
     <div class="col-6 offset-3">
       <h2>VK Login Settings</h2>
	   <br>
	   <p>Go to <a href="https://vk.com/apps?act=manage"><u>VK Developer Portal</u></a> and add a new application. Copy ID number and paste it here. Add two redirects:
	   <ul>
	   <li>https://mydomain.com/userspice/users/login_vk.php</li>
	   <li>https://mydomain.com/userspice/usersc/plugins/vk_login/link_account.php</li>
	   </ul>
	   <br>
	   </p>
	   
     <div class="form-group">
       <label for="vk_appid">Application ID</label>
       <input type="password" autocomplete="off" class="form-control ajxtxt" data-desc="VK APP ID" name="vk_appid" id="vk_appid" value="<?=$settings->vk_appid?>">
     </div>
	 
     <div class="form-group">
       <label for="vk_key">Secure Key</label>
       <input type="password" autocomplete="off" class="form-control ajxtxt" data-desc="VK API Key" name="vk_key" id="vk_key" value="<?=$settings->vk_key?>">
     </div>

     <div class="form-group">
       <label for="vk_domain">Redirect URI (to login_vk.php)</label>
       <input type="text" autocomplete="off" class="form-control ajxtxt" data-desc="VK Domain"  name="vk_domain" id="vk_domain" value="<?=$settings->vk_domain?>">
     </div>
	 
     <div class="form-group">
       <label for="vk_email">Request for E-mail</label>
       <select class="form-control ajxtxt" data-desc="VK Email" name="vk_email" id="vk_email">
       <option value="0" <?=$settings->vk_email ? "selected" : ""?>>Disabled</option>
       <option value="1" <?=$settings->vk_email ? "selected" : ""?>>Enabled</option>
       </select>
     </div>

 </div>
 </div>
