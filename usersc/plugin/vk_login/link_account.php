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
		<h1 align="center">Link Your VK Account</h1>
		<hr>
		<?php
		require("assets/vk_oauth.php");
	
		if (isset($vk_userinfo->id)) {
			$check = $db->query("SELECT id FROM users WHERE vk_id = ?",[$vk_userinfo->id])->count();
			
			if ($check < 1) {
				$fields = array(
					'vk_id'     => $vk_userinfo->id,
					'vk_avatar' => $vk_userinfo->img,
					'vk_un'     => $vk_userinfo->un,
				);
				
				$db->update('users', $user->data()->id, $fields);

				Redirect::to($us_url_root.'users/account.php');
			} else {
				Redirect::to($us_url_root.'users/account.php?err=This+VK+id+is+already+linked+to+an+account');
			}
		}
		?>
<?php }else{
  Redirect::to($us_url_root."users/account.php");
} //end of security checks?>
