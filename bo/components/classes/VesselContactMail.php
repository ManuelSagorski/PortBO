<?php
namespace bo\components\classes;

class VesselContactMail
{
    private $id;
    private $contact_id;
    private $ts_erf;
    private $subject;
    private $message;
    private $attachment;
    
    public function __construct() {      
    }
    
    /*
     * Getter und Setter
     */
    public function getID() {
        return $this->id;
    }
    public function getContactID() {
        return $this->contact_id;
    }
    public function getTSErf() {
        return $this->ts_erf;
    }
    public function getSubject() {
        return $this->subject;
    }
    public function getMessage() {
        return $this->message;
    }
    public function getAttachment() {
        return $this->attachment;
    }
}

