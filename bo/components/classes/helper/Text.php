<?php
namespace bo\components\classes\helper;

class Text
{
    private $languageText = [];
    private $languageDefaultText = [];
    
    private const LANGUAGE_FOLDER = MAIN_DOCUMENT_PATH . "resources/language/";
    
    public function __construct(string $language = null) {
        $this->languageDefaultText = $this->getTextFromJson('de');
        if(!empty($language)) {
            $this->languageText = $this->getTextFromJson($language);
        }
        else {
            if(!empty($_SESSION['language'])) {
                $this->languageText = $this->getTextFromJson($_SESSION['language']);
            }
        }
    }
    
    public function _($index) {
        if(!empty($this->languageText[$index])) {
            echo $this->languageText[$index];
            return $this->languageText[$index];
        }
        else {
            echo $this->languageDefaultText[$index];
            return $this->languageDefaultText[$index];
        }
    }
    
    public function _get($index) {
        if(!empty($this->languageText[$index])) {
            return $this->languageText[$index];
        }
        else {
            return $this->languageDefaultText[$index];
        }
    }
    
    public static function getTextFromJson($languageCode) {
        if(file_exists(self::LANGUAGE_FOLDER . $languageCode . ".json")) {
            $langJson = file_get_contents(self::LANGUAGE_FOLDER . $languageCode . ".json");
            return json_decode($langJson, true);
        }
        else {
            return null;
        }
    }
    
    public function getLanguageText() {
        return $this->languageText;
    }
    public function getLanguageDefaultText() {
        return $this->languageDefaultText;
    }
}

?>