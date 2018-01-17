<?php 
$access_token = "EAAcPdw89RjsBAPQ13v6YbPho5ng9XTxanrUGp4w5U7AlqPAW9PhUu1BB7RNF6g9eCa9oW4tbXZCVXlFDNxRGVVscOSwssyXW0s5RkMUPDX0p4n6CWYhLf02WlrhPBmQeP324RB7sGxFIlIfAqZCWiZA7hrZAPxf6xUlu1zamxgZDZD";
$app_id = "1987328868173371";
$app_secret = "6eedef8b7f5770ca11ba0921517d2c62";
// should begin with "act_" (eg: $account_id = 'act_1234567890';)
$account_id = "act_812695502157201";
define('SDK_DIR', __DIR__ . '/..'); // Path to the SDK directory

$loader = include SDK_DIR.'/vendor/autoload.php';
date_default_timezone_set('America/Los_Angeles');


if(is_null($access_token) || is_null($app_id) || is_null($app_secret)) {
  throw new \Exception(
    'You must set your access token, app id and app secret before executing'
  );
}

if (is_null($account_id)) {
  throw new \Exception(
    'You must set your account id before executing');
}
use FacebookAds\Api;

Api::init($app_id, $app_secret, $access_token);


?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="../assets/script.js"></script>
