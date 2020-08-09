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

$discord_userinfo = new stdClass();
$redirect_uri     = $settings->discord_domain;
$scope            = "identify";

if ($settings->discord_email)
	$scope .= " email";

if (basename($_SERVER['PHP_SELF']) == "link_account.php")
	$redirect_uri = substr_replace($redirect_uri, "usersc/plugins/discord_login/link_account.php", strpos($redirect_uri,"users/login_discord.php"));

$link_to_discord  = "https://discord.com/api/oauth2/authorize?client_id={$settings->discord_clientid}&redirect_uri={$redirect_uri}&response_type=code&scope={$scope}";

if (isset($_GET["error"]))
	echo "<h1>{$_GET["error"]}</h1>";

if (isset($_GET["error_description"]))
	echo "<h3>{$_GET["error_description"]}</h3>";


// When Discord redirects the user back here, there will be a "code" and "state" parameter in the query string
if (isset($_GET['code'])) {
	// Exchange the auth code for a token
	$token = apiRequest('https://discord.com/api/oauth2/token', array(
		'client_id'     => $settings->discord_clientid,
		'client_secret' => $settings->discord_clientsecret,
		'grant_type'    => "authorization_code",
		'code'          => $_GET['code'],
		'redirect_uri'  => $redirect_uri,
		'scope'         => $scope
	));
	
	$_SESSION['discord_token'] = $token->access_token;
}

if (!isset($_SESSION['discord_token']))
	echo "<a href='{$link_to_discord}'><img class='img-responsive' src='{$us_url_root}usersc/plugins/discord_login/assets/discord.png'></a>";
else {
	$discord_userinfo = apiRequest("https://discord.com/api/users/@me", false, ["Authorization: Bearer {$_SESSION['discord_token']}"]);

	if (isset($discord_userinfo->id)) {
		$discord_userinfo->img = "https://cdn.discordapp.com/avatars/{$discord_userinfo->id}/{$discord_userinfo->avatar}.png";
		$discord_userinfo->un  = $discord_userinfo->username . "#" . $discord_userinfo->discriminator;
	} else {
		$_SESSION = [];
		Redirect::to($us_url_root.'users/login.php?msg=There+was+a+problem+with+your+Discord+login');			
	}
}
?>
