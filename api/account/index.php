<?php
$bypassCoreCheck = true;
require_once('../../config.php');
require_once(ROOT_DIR . '/classes/Api.php');

$api = new Api();

switch($_POST[$api->ACTION_KEY])
{
    case 'Login':

        $captchaKey = $_POST['CaptchaKey'];

        if (empty($captchaKey))
            $api->badRequest("CaptchaKey cannot be empty.");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            "secret=" . CAPTCHA_SERVER_SECRET_ANDROID . "&response=$captchaKey&remoteip=" . $_SERVER['HTTP_CLIENT_IP']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close ($ch);

        $response = json_decode($response, true);

        if($response['success'])
        {
            $account = Accounts::withProperties($_POST);
            setCoreAccount($account);
            $localDb = new LocalDatabase();

            if (empty($account->Username))
                $api->badRequest("Username cannot be empty.");

            if (empty($account->Password))
                $api->badRequest("Password cannot be empty.");

            if ($account->login($coreDb))
            {
                setCoreAccount($account);

                require_once(ROOT_DIR . '/classes/tables/core/Teams.php');
                require_once(ROOT_DIR . '/classes/tables/local/Config.php');

                $configs = Config::getObjects($localDb);

                $team = Teams::withId($coreDb, $account->TeamId);

                $obj = new Config();
                $obj->Key = "TEAM_NUMBER";
                $obj->Value = $team->Id;
                $configs[] = $obj;

                $obj = new Config();
                $obj->Key = "TEAM_NAME";
                $obj->Value = $team->Name;
                $configs[] = $obj;

                $obj = new Config();
                $obj->Key = "ROBOT_MEDIA_DIR";
                $obj->Value = $account->RobotMediaDir;
                $configs[] = $obj;

                $obj = new Config();
                $obj->Key = "API_KEY";
                $obj->Value = $account->ApiKey;
                $configs[] = $obj;

                $api->success($configs);
            }

            $api->unauthorized("Invalid username or password");
        }

        $api->unauthorized("Invalid captcha key. Please try again.");

        break;

    default:
        $api->notImplemented("Action not found.");
        break;
}

?>
