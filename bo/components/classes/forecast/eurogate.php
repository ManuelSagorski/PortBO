<?php
namespace bo\components\classes\forecast;

use bo\components\classes\helper\logger;

class eurogate extends scraping
{
    const URL_EUROGATE_SEGELLISTE = "https://www.eurogate.de/segelliste/state/show?_state=7i1bpj8n1j8g&_unique=1sof6jwexsptz&_transition=start&period=1&internal=false&languageNo=30&locationCode=HAM&order=+0";
    // const URL_EUROGATE_SEGELLISTE = "http://www.port-mission.de/resultlist.html";
    
    private $html;
    
    public function getForecast() {
        $rowsForArrivalDate = 0;
        $rowsForArrivalTime = 0;
        $rowsForLeavingDateTime = 0;
        $rowsForVesselName = 0;
        $rowsForAgency = 0;

        $vessel['company'] = "Eurogate";
        $vessel['port'] = 1;
        $vessel['imo'] = '';
        
        $vesselKomplete = false;
        
        if(!$this->getSegelliste()) {
            return false;
        }
        
        $resultlist = $this->html->find('.resultlist');
        
        foreach($resultlist[0]->find('tr') as $key => $tr) {
            $tds = $tr->find('td');
            
            /* Ankunft Datum */
            if($key == (2 + $rowsForArrivalDate)) {
                $vessel['arrivalDate'] = $tds[0]->plaintext;
                $rowsForArrivalDate = $rowsForArrivalDate + $tds[0]->rowspan;
                $timeTD = 1;
            }
            else { $timeTD = 0; }
            
            /* Ankuft Uhrzeit */
            if($key == (2 + $rowsForArrivalTime)) {
                $vessel['arrivalTime'] = $tds[$timeTD]->plaintext;
                $rowsForArrivalTime = $rowsForArrivalTime + $tds[$timeTD]->rowspan;
                $leavingTD = $timeTD + 1;
            }
            else { $leavingTD = 0; }
            
            /* Abfahrt Datum und Uhrzeit */
            if($key == (2 + $rowsForLeavingDateTime)) {
                $vessel['leavingDateTime'] = $tds[$leavingTD]->plaintext;
                $rowsForLeavingDateTime = $rowsForLeavingDateTime + $tds[$leavingTD]->rowspan;
                $nameTD = $leavingTD + 1;
            }
            else { $nameTD = $leavingTD + 0; }
            
            /* Name */
            if($key == (2 + $rowsForVesselName)) {
                $vessel['name'] = trim($tds[$nameTD]->plaintext);
                $rowsForVesselName = $rowsForVesselName + $tds[$nameTD]->rowspan;
                $agencyTD = $nameTD + 9;
                $vesselKomplete = true;
            }
            else { $agencyTD = 8; }
           
            /* Agentur */
            if($key == (2 + $rowsForAgency)) {
                $vessel['agency'] = trim($tds[$agencyTD]->plaintext);
                $rowsForAgency = $rowsForAgency + $tds[$agencyTD]->rowspan;
            }
            
            if($vesselKomplete) {
                $this->expectedVessels[] = $vessel;
                $vesselKomplete = false;
            }
        }
        
        $this->updateDB();
        
        return true;
    }
    
    private function getSegelliste() {
        $this->html = $this->getHTML(self::URL_EUROGATE_SEGELLISTE);
        
        if(empty($this->html)) {
            logger::writeLogError('getEurogate', 'Eurogate Scrapping lieferte keine Daten.');
            return false;
        }
        
        return true;
    }
}

?>