<?php
namespace bo\components\classes\helper;

use bo\components\classes\User;

class Telegram
{
    private $message;
    private $chatID;
    private $firstNameSender;
    private $type;
    private $messageID;
    
    private $tmpl;
    
    const TEMPLATE_PATH = MAIN_DOCUMENT_PATH . "views/templates/";
    const TELEGRAM_CODE_REGEX = "/[a-zA-Z0-9]{4}-[a-zA-Z0-9]{4}/";
    
    public function __construct($chatID, $json = null) {
        if(!empty($json)) {
            $this->message = $json['message']['text'];
            $this->chatID = $json['message']['chat']['id'];
            $this->firstNameSender = trim(str_replace('?', '', preg_replace('/[^A-Za-z0-9 ]/', '', $json['message']['chat']['first_name'])));
            $this->type = $json['message']['chat']['type'];
            $this->messageID = $json['message']['message_id'];;
        }
        else {
            if(!empty($chatID)) {
                $this->chatID = $chatID;
            }
        }
    }
    
    public function register() {
        if(preg_match(self::TELEGRAM_CODE_REGEX, $this->message)) {
            $user = User::getSingleObjectByCondition(Array("telegram_code" => $this->message));
            if(empty($user)) {
                $this->sendMessage(false, 'Der eingegebene Code ist leider verkehrt. ' .
                    'Schicke bitte einer einer separaten Nachricht ausschließlich den erhaltenen Code.');
            }
            else {
                $sqlstrg = "update port_bo_user set telegram_id = ? where id = ?";
                DBConnect::execute($sqlstrg, array($this->chatID, $user->getId()));
                $this->sendMessage(false, 'Herzlich Willkommen ' . $user->getFirstName() .
                    '. Dein Zugang zum Telegram-Kanal der Hafengruppe Nord wurde erfolgreich eingetichtet.');
            }
        }
        else {
            $this->sendMessage(false, 'Hi ' . $this->firstNameSender .
                '! Leider kenne ich dich noch nicht. Schick mir bitte den Telegram Code, den du von uns erhalten hast.');
        }
    }
    
    public function sendMessage($disable_notification, $text=null) {
        $ch = curl_init('https://api.telegram.org/bot' . TELEGRAM_TOKEN . '/sendMessage');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        if(!empty($text)) {
            $this->tmpl = $text;
        }
        
        $param = array(
            'chat_id' => $this->chatID,
            'parse_mode' => 'html',
            'disable_notification' => $disable_notification,
            'text' => $this->tmpl
        );
        
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));
        
        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);
        
        if($result['ok'] == false) {
            Logger::writeLogError('telegramBot', 'Nachricht konnte nicht gesendet werden. Meldung: ' . $result['description']);
        }
        else {
            $userRecipient = User::getSingleObjectByCondition(Array("telegram_id" => $this->chatID));
            if(!empty($userRecipient)) {
                $sender = (isset($_SESSION['user']))?$_SESSION['user']:null;
                self::logMessage('send', $userRecipient->getId(), $this->chatID, $sender, $this->tmpl);
            }
            Logger::writeLogInfo('telegramBot', 'Nachricht verschickt. Empfänger: ' . $this->chatID . ' Nachricht: ' . $this->tmpl);
        }
        
        return $result;
    }
    
    public function applyTemplate($templateName, $replace) {
        $this->tmpl = file_get_contents(self::TEMPLATE_PATH . $templateName . ".html");
        
        foreach ($replace as $key=>$value) {
            $this->tmpl = str_replace("{{" . $key . "}}", $value, $this->tmpl);
        }
    }
    
    public static function logMessage($direction, $userID, $telegramID, $userBoID, $text) {
        DBConnect::execute("insert into port_bo_telegram (direction, user_id, telegram_id, user_id_bo, text) values (?, ?, ?, ?, ?)",
            array($direction, $userID, $telegramID, $userBoID, $text));
    }
    
    public static function createCode() {
        $code = substr(md5(bin2hex(random_bytes(10))), 0, 4) . '-' . substr(md5(bin2hex(random_bytes(10))), 0, 4);
        
        while (!empty(User::getSingleObjectByCondition(['telegram_code' => $code]))) {
            $code = substr(md5(bin2hex(random_bytes(10))), 0, 4) . '-' . substr(md5(bin2hex(random_bytes(10))), 0, 4);
        }
        
        (new Query('update'))
            ->table(User::TABLE_NAME)
            ->values(['telegram_code' => $code])
            ->condition(['id' => $_SESSION['user']])
            ->execute();
    }
    
    public function getMessage() {
        return $this->message;
    }
    public function getChatID() {
        return $this->chatID;
    }
    public function getFirstNameSender() {
        return $this->firstNameSender;
    }
    public function getMessageID() {
        return $this->messageID;
    }
    public function getType() {
        return $this->type;
    }
}

?>