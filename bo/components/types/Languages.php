<?php
namespace bo\components\types;

class Languages
{
    /**
     * $languages
     *
     * Sprachen der Mitarbeiter im Hafen
     * @var array
     */
    public static $languages = array(
        1 => "german",
        2 => "english",
        3 => "tagalog",
        4 => "russian",
        5 => "indonesian",
        6 => "polish",
        7 => "chinese",
        8 => "turkish",
        9 => "arabic",
        10 => "hindi",
        11 => "rumanian",
        12 => "bulgarian",
        13 => "french",
        14 => "spanish"
    );
    
    /**
     * $frontendLanguages
     *
     * Sprachen in denen das Frontend zur Verfügung steht
     * @var array
     */
    public static $frontendLanguages = array(
        "de" => "Deutsch",
        "en" => "English"
    );
}

