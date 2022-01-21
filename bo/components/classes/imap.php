<?php
namespace bo\components\classes;

class imap
{
    private $mailBox;
    
    public function __construct() {
        // $this->mailBox = imap_open(IMAP_MAILBOX, IMAP_USER, SMTP_SECRET) or logger::writeLogError('imap', 'Verbindung zur Mailbox konnte nicht hergestellt werden.');
    }
    
    public function getMails() {
        $this->mailBox = imap_open(IMAP_MAILBOX, IMAP_USER, SMTP_SECRET);
        
        $mailCount=imap_num_msg($this->mailBox);
        
        for ($i=1;$i<=$mailCount;$i++){
            $overview = imap_fetch_overview($this->mailBox, $i, 0);
            
            // $teststrg = "to the crew of vessel (eni: 1234567) bla und blub";
        
            $vesselNumber = [];            
            preg_match("/\(((IMO)|(imo)|(Imo)|(ENI)|(eni)|(Eni)): ([0-9]{7})\)/", $overview[0]->subject, $vesselNumber);
            
            echo $overview[0]->subject . "<BR>";
            print_r($vesselNumber);
            
            
            if(isset($vesselNumber[8])) {
                $emailDate = new \DateTime($overview[0]->date);
                $sqlstrg = "select vc.* 
                              from port_bo_vessel v join port_bo_vesselContact vc on v.id = vc.vess_id 
                             where v.{{numberType}} = ?
                               and vc.date = ?";
                
                $vesselContact = dbConnect::fetchSingle(str_replace("{{numberType}}", strtoupper($vesselNumber[1]), $sqlstrg), 
                    vesselContact::class, Array($vesselNumber[8], $emailDate->format('Y-m-d')));
                
                if(!empty($vesselContact)) {
                    $body = $this->get_part($this->mailBox, $i, "TEXT/HTML");
                    if(empty($body)) {
                        $body = $this->get_part($this->mailBox, $i, "TEXT/PLAIN");
                    }
                    
                    $sqlstrg = "insert into port_bo_vesselContactMail (contact_id, subject, message) value (?, ?, ?)";
                    dbConnect::execute($sqlstrg, Array($vesselContact->getID(), $overview[0]->subject, $body));
                }
                else {
                    echo $vesselNumber[8];
                    echo $emailDate->format('Y-m-d');
                    echo "Kein Kontakt gefunden<br>";
                }
            }
           
            echo "<br><br>";
        }
        
        imap_close($this->mailBox);
    }
    
    private function get_part($imap, $uid, $mimetype, $structure = false, $partNumber = false) {
        if (!$structure) {
            $structure = imap_fetchstructure($imap, $uid, FT_UID);
        }
        if ($structure) {
            if ($mimetype == $this->get_mime_type($structure)) {
                if (!$partNumber) {
                    $partNumber = 1;
                }
                $text = imap_fetchbody($imap, $uid, $partNumber, FT_UID);
                switch ($structure->encoding) {
                    case 3: return imap_base64($text);
                    case 4: return imap_qprint($text);
                    default: return $text;
                }
            }
            
            // multipart
            if ($structure->type == 1) {
                foreach ($structure->parts as $index => $subStruct) {
                    $prefix = "";
                    if ($partNumber) {
                        $prefix = $partNumber . ".";
                    }
                    $data = $this->get_part($imap, $uid, $mimetype, $subStruct, $prefix . ($index + 1));
                    if ($data) {
                        return $data;
                    }
                }
            }
        }
        return false;
    }
    
    private function get_mime_type($structure) {
        $primaryMimetype = array("TEXT", "MULTIPART", "MESSAGE", "APPLICATION", "AUDIO", "IMAGE", "VIDEO", "OTHER");
        
        if ($structure->subtype) {
            return $primaryMimetype[(int)$structure->type] . "/" . $structure->subtype;
        }
        return "TEXT/PLAIN";
    }
}

