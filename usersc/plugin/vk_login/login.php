<?php
if (!isset($user) || !$user->isLoggedIn())
	require("assets/vk_oauth.php");
else
	Redirect::to($us_url_root."users/account.php");

if (isset($vk_userinfo->id)) {
	$lookupQ = $db->query("SELECT id,logins FROM users WHERE vk_id = ?",[$vk_userinfo->id]);
	$lookupC = $lookupQ->count();
	
	if ($lookupC > 0) {
		$lookup           = $lookupQ->first();
		$_SESSION['user'] = $lookup->id;

		$db->update('users',$lookup->id, [
			'logins'    => $lookup->logins+1, 
			'vk_avatar' => $vk_userinfo->img,
			'vk_un'     => $vk_userinfo->un
		]);
		
		if (file_exists($abs_us_root.$us_url_root.'usersc/scripts/custom_login_script_no_redir'))
			include($abs_us_root.$us_url_root.'usersc/scripts/custom_login_script_no_redir');
		
		if (file_exists($abs_us_root.$us_url_root.'usersc/scripts/custom_login_script'))
			include($abs_us_root.$us_url_root.'usersc/scripts/custom_login_script');
		
		Redirect::to($us_url_root.$settings->redirect_uri_after_login);
	} else {
		$checkUn  = $db->query("SELECT id FROM users WHERE username = ?",[$vk_userinfo->un])->count();
		$username = $vk_userinfo->un;
		
		if ($checkUn >= 1)
			$username = $vk_userinfo->un.randomstring(6); //close enough
		
		$fields = array(
			'username'        => $username,
			'vk_id'           => $vk_userinfo->id,
			'vk_avatar'       => $vk_userinfo->img,
			'vk_un'           => $vk_userinfo->un,
			'username'        => $username,
			'fname'           => $vk_userinfo->first_name,
			'lname'           => $vk_userinfo->last_name,
			'email'           => $settings->vk_email ? $vk_userinfo->email : "{$vk_userinfo->un}@noreply.com",
			'password'        => password_hash(randomstring(20), PASSWORD_BCRYPT, array('cost' => 12)),
			'permissions'     => 1,
			'account_owner'   => 1,
			'join_date'       => date("Y-m-d H:i:s"),
			'email_verified'  => 1,
			'active'          => 1,
			'vericode'        => randomstring(12),
			'vericode_expiry' => "2016-01-01 00:00:00"
		);
		
		if ($db->insert('users',$fields)) {
			$theNewId         = $db->lastId();
			$_SESSION['user'] = $theNewId;
			
			$fields = array(
				'user_id'       => $theNewId,
				'permission_id' => 1,
			);
		
			$db->insert('user_permission_matches',$fields);
			include($abs_us_root.$us_url_root.'usersc/scripts/during_user_creation.php');
			Redirect::to($us_url_root.$settings->redirect_uri_after_login);
		} else {
			$_SESSION = [];
			Redirect::to($us_url_root.'users/login.php?msg=There+was+a+problem+with+your+VK+login2');
		}
	}
}