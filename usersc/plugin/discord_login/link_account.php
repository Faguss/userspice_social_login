<?php
    require '../../../users/init.php';
    require_once $abs_us_root.$us_url_root.'users/includes/template/prep.php';
    include "plugin_info.php";
    pluginActive($plugin_name);
    if(isset($user) && $user->isLoggedIn()){

?>
    <style>
        .table {
            table-layout: fixed;
            word-wrap: break-word;
        }
    </style>

    <div class="container" style="margin-top: 30px; margin-bottom: 30px; padding-bottom: 10px; background-color: #FFF;">
		<h1 align="center">Link Your Discord Account</h1>
		<hr>
		<?php
		require("assets/discord_oauth.php");
	
		if (isset($discord_userinfo->id)) {
			$check = $db->query("SELECT id FROM users WHERE discord_id = ?",[$discord_userinfo->id])->count();
			
			if ($check < 1) {
				$fields = array(
					'discord_id'     => $discord_userinfo->id,
					'discord_avatar' => $discord_userinfo->img,
					'discord_un'     => $discord_userinfo->un
				);
				
				$db->update('users', $user->data()->id, $fields);

				Redirect::to($us_url_root.'users/account.php');
			} else {
				Redirect::to($us_url_root.'users/account.php?err=This+Discord+id+is+already+linked+to+an+account');
			}
		}
		?>
<?php }else{
  Redirect::to($us_url_root."users/account.php");
} //end of security checks?>
