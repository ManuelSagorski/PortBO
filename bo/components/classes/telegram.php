<?php
namespace bo\components\classes;

class telegram
{
    private $message;
    private $chatID;
    private $firstNameSender;
    private $type;
    private $messageID;
    
    private $tmpl;
    
    const TEMPLATE_PATH = PATH . "/views/templates/";
    const TELEGRAM_CODE_REGEX = "/[a-zA-Z0-9]{3}-[a-zA-Z0-9]{3}/";
    
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
            $user = dbConnect::fetchSingle("select * from port_bo_user where telegram_code = ?", user::class, array($this->message));
            if(empty($user)) {
                $this->sendMessage(false, 'Der eingegebene Code ist leider verkehrt. ' .
                    'Schicke bitte einer einer separaten Nachricht ausschließlich den erhaltenen Code.');
            }
            else {
                $sqlstrg = "update port_bo_user set telegram_id = ? where id = ?";
                dbConnect::execute($sqlstrg, array($this->chatID, $user->getId()));
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
            logger::writeLogError('telegramBot', 'Nachricht konnte nicht gesendet werden. Meldung: ' . $result['description']);
        }
        else {
            $userRecipient = dbConnect::fetchSingle("select * from port_bo_user where telegram_id = ?", user::class, array($this->chatID));
            if(!empty($userRecipient)) {
                self::logMessage('send', $userRecipient->getId(), $this->chatID, $_SESSION['user'], $this->tmpl);
            }
            logger::writeLogInfo('telegramBot', 'Nachricht verschickt. Empfänger: ' . $this->chatID . ' Nachricht: ' . $this->tmpl);
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
        dbConnect::execute("insert into port_bo_telegram (direction, user_id, telegram_id, user_id_bo, text) values (?, ?, ?, ?, ?)",
            array($direction, $userID, $telegramID, $userBoID, $text));
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