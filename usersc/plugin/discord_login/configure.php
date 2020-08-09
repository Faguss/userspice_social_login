  <?php if(!in_array($user->data()->id,$master_account)){ Redirect::to($us_url_root.'users/admin.php');} //only allow master accounts to manage plugins! ?>

<?php
include "plugin_info.php";
pluginActive($plugin_name);
 if(!empty($_POST['plugin_discord_login'])){
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
       <h2>Discord Login Settings</h2>
	   <br>
	   <p>Go to <a href="https://discord.com/developers/applications"><u>Discord Developer Portal</u></a> and add a new application. Copy ID numbers and paste them here. Add two redirects:
	   <ul>
	   <li>https://mydomain.com/userspice/users/login_discord.php</li>
	   <li>https://mydomain.com/userspice/usersc/plugins/discord_login/link_account.php</li>
	   </ul>
	   <br>
	   </p>
	   
     <div class="form-group">
       <label for="discord_clientid">Client ID</label>
       <input type="password" autocomplete="off" class="form-control ajxtxt" data-desc="Discord API Key" name="discord_clientid" id="discord_clientid" value="<?=$settings->discord_clientid?>">
     </div>
	 
     <div class="form-group">
       <label for="discord_clientsecret">Client Secret</label>
       <input type="password" autocomplete="off" class="form-control ajxtxt" data-desc="Discord API Key" name="discord_clientsecret" id="discord_clientsecret" value="<?=$settings->discord_clientsecret?>">
     </div>

     <div class="form-group">
       <label for="discord_domain">Redirect URI (to login_discord.php)</label>
       <input type="text" autocomplete="off" class="form-control ajxtxt" data-desc="Discord Domain"  name="discord_domain" id="discord_domain" value="<?=$settings->discord_domain?>">
     </div>
	 
     <div class="form-group">
       <label for="discord_email">Request for E-mail</label>
       <select class="form-control ajxtxt" data-desc="Discord Email" name="discord_email" id="discord_email">
       <option value="0" <?=$settings->discord_email ? "selected" : ""?>>Disabled</option>
       <option value="1" <?=$settings->discord_email ? "selected" : ""?>>Enabled</option>
       </select>
     </div>

 </div>
 </div>
