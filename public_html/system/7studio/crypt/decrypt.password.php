<?
use Bitrix\Main\Authentication\ApplicationPasswordTable;

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->RestartBuffer();

$users = CUser::GetList($by, $order, array("ID" => $USER->GetID()), array("FIELDS" => array("ID", "PASSWORD", "CHECKWORD", "DIGEST_PASSWORD", "APPLICATION_ID", "LOGIN", "EMAIL")));

while ($user = $users->Fetch())
{
    d($user);

    $PASSWORD = $user["PASSWORD"];
    $CHECKWORD = $user["CHECKWORD"];





}


/**
 * PASSWORD
 * @param $external_user_id
 * @param $appPassword
 * @param bool $passwordOriginal
 */
function decryptItbx($external_user_id, $appPassword, $passwordOriginal = true) {


}



