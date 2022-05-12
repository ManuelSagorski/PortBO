<?php
namespace bo\components\types;

class ContactTypes
{
    public const TRANSLATION_KEYS = [
        'Email' => 'email',
        'Brief' => 'letter',
        'Besuch' => 'visit',
        'Telefon' => 'phone-call',
    ];

    /**
     * $contactTypes
     *
     * Kontakt Arten
     * @var array
     */
    public static $contactTypes = array("Email", "Brief", "Besuch", "Telefon");

    /**
     * $translateContactTypes
     *
     * Uebersetzte Kontakttypen für GB
     * @var array
     */
    public static $translateContactTypes = array("Email" => "Email", "Brief" => "Letter", "Besuch" => "Visit", "Telefon" => "Phone");
}

?>