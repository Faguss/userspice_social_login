<?php
// based on https://gist.github.com/Jengas/ad128715cb4f73f5cde9c467edf64b00

if (!function_exists('apiRequest')) {
	function apiRequest($url, $post=FALSE, $headers=array()) {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

		$response = curl_exec($ch);

		if ($post)
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));

		$headers[] = 'Accept: application/json';
		$headers[] = 'Content-Type: application/x-www-form-urlencoded';

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$response = curl_exec($ch);
		return json_decode($response);
	}
}

if (!isset($settings))
	global $settings;

$vk_userinfo      = new stdClass();
$redirect_uri     = $settings->vk_domain;
$scope            = "0";

if ($settings->vk_email)
	$scope .= "4194304";

if (basename($_SERVER['PHP_SELF']) == "link_account.php")
	$redirect_uri = substr_replace($redirect_uri, "usersc/plugins/vk_login/link_account.php", strpos($redirect_uri,"users/login_vk.php"));

$link_to_vk = "https://oauth.vk.com/authorize?client_id={$settings->vk_appid}&scope={$scope}&redirect_uri={$redirect_uri}&response_type=code&v=5.103";

if (isset($_GET["error"]))
	echo "<h1>{$_GET["error"]}</h1>";

if (isset($_GET["error_description"]))
	echo "<h3>{$_GET["error_description"]}</h3>";


// When VK redirects the user back here, there will be a "code" and "state" parameter in the query string
if (isset($_GET['code'])) {
	// Exchange the auth code for a token
	$token = apiRequest('https://oauth.vk.com/access_token', array(
		'client_id'     => $settings->vk_appid,
		'client_secret'  => $settings->vk_key,
		'code'          => $_GET['code'],
		'redirect_uri'  => $redirect_uri
	));
	
	if (isset($token->error))
		echo "<h1>{$token->error}</h1>";
	
	if (isset($token->error_description))
		echo "<h3>{$token->error_description}</h3>";
	
	$_SESSION['vk_token'] = $token;
}

if (!isset($_SESSION['vk_token'])) {
	echo "<a href='{$link_to_vk}'><img class='img-responsive' src='{$us_url_root}usersc/plugins/vk_login/assets/vk.png'></a>";
} else {
	$fields = 'nickname,first_name,last_name,photo_200_orig';
	
	$vk_response = apiRequest("https://api.vk.com/method/users.get", ['access_token'=>$_SESSION['vk_token']->access_token, 'v'=>5.103, 'fields'=>$fields]);
	$vk_userinfo = $vk_response->response[0];
	
	if (isset($vk_userinfo->id)) {
		$vk_userinfo->img   = $vk_userinfo->photo_200_orig;
		$vk_userinfo->un    = !empty($vk_userinfo->nickname) ? $vk_userinfo->nickname : $vk_userinfo->first_name . " " . $vk_userinfo->last_name;
		
		if ($settings->vk_email)
			$vk_userinfo->email = $_SESSION['vk_token']->email;
	} else {
		$_SESSION = [];
		Redirect::to($us_url_root.'users/login.php?msg=There+was+a+problem+with+your+VK+login');			
	}
}
?>
