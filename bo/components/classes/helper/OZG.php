<?php
namespace bo\components\classes\helper;

/**
 * Klasse ozg - ermöglicht Anbingung an die özg Schichtplanung 
 * @author Manuel Sagorski
 *
 */
class OZG
{
    const OZG_CREATE_USER_URL = "https://xn--zg-eka.de/api/createUser.php";
  
    public static function newOzgUser($firstname, $surname, $email, $mobile, $domain) {
        $data = array(
            'token' => OZG_TOKEN,
            'username' => $firstname[0] . $surname, 
            'name' => $firstname . " " . $surname,
            'email' => $email,
            'mobile' => $mobile,
            'domain' => $domain
        );
        
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        
        $context  = stream_context_create($options);
        return file_get_contents(self::OZG_CREATE_USER_URL, false, $context);
    }
}

