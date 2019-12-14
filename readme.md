# Social Logins for UserSpice

Steam / Discord / VK plugins for UserSpice 4.4.14

Also includes [Facebook login fixes](https://userspice.com/bugs/usersc/issue_detail.php?id=638)

## Installation:

Because Discord/VK plugin conflicts with UserSpice Google login modification of some files is necessary.

* Duplicate file users\login.php but with name login_discord.php and login_vk.php
* Edit users\includes\template\header1_must_include.php and add there [condition to disable Google login](https://github.com/Faguss/userspice_social_login/blob/7f1b76afe8ab818585e818e9d24fbbe11247208b/users/includes/template/header1_must_include.php#L98)
* Copy steam_login, discord_login, vk_login folders to usersc\plugins
* In the Admin Dashboard go to Permission Levels and add public access to login_discord.php and login_vk.php
* In the Admin Dashboard go to Plugin Manager and configure the plugins

## Version history:

#### 14.12.2019

* updated info.xml
* added option to get user's e-mail in Discord and VK plugins
* updated VK button image (rounded corners)

#### 16.11.2019

* added VK plugin

#### 14.08.2019

* updated Discord button image (smaller font)
* removed unused joinbody.php in Discord plugin
* Discord plugin wasn't updating avatar correctly on subsequent logins - fixed
* Discord login button now leads directly to Discord site (instead of using header() redirection)
* Steam loginbody.php - fixed lack of ending php mark
* Steam steamauthlogin.php - changed query string in the link
* updated Steam button image (smaller font)

#### 08.08.2019

* added Discord plugin
* added Steam plugin

#### 24.07.2019

* modified Dan's Steam plugin so that new users can login with Steam [link](https://pastebin.com/gUzCp9qT)
