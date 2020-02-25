<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->RestartBuffer();

$user = CUser::GetList($by, $order, array("ID" => $USER->GetID()), array("FIELDS" => array("ID", "PASSWORD", "CHECKWORD", "DIGEST_PASSWORD", "APPLICATION_ID", "LOGIN", "EMAIL")));

if($user = $user->Fetch())
{
    $PASSWORD = "";
    $CHECKWORD = $user["CHECKWORD"];

    if(isUserPassword($user["ID"], $PASSWORD)){
        d("it is user password");
    }
    else{
        d("it is not user password");
    }
}

d($user);


/**
 * The concepts:
 * PASSWORD
 * CheckDBPassword
 *
 * @param $external_user_id
 * @param $appPassword
 * @param bool $passwordOriginal
 */
/**
 * Проверяем, является ли $password текущим паролем пользователя.
 *
 * @param int $userId
 * @param string $password
 *
 * @return bool
 */
function isUserPassword($userId, $password)
{
    $userData = CUser::GetByID($userId)->Fetch();

    $salt = substr($userData['PASSWORD'], 0, (strlen($userData['PASSWORD']) - 32));

    d($salt);

    $realPassword = substr($userData['PASSWORD'], -32);

    d($realPassword);

    $password = md5($salt.$password);



    return ($password == $realPassword);
}

