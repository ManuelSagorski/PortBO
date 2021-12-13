<?php
namespace bo\components\classes\forecast;

use bo\components\classes\logger;

class fleetmon extends scraping
{
    const URL_FLEETMON_SEGELLISTE = "https://www.fleetmon.com/ports/kiel_dekel_5484/#tab-scheduled-arrivals";
    // const URL_FLEETMON_SEGELLISTE = "http://www.port-mission.de/findShip/fleetmon.html";
    
    private $html;
    
    public function getForecast() {
        $vessel['company'] = "";
        $vessel['leavingDateTime'] = "";
        $vessel['agency'] = "";
        $vessel['port'] = 2;
        
        if(!$this->getSegelliste()) {
            return false;
        }
        
        $resultlist = $this->html->find('#arr_sched_table');
        
        foreach($resultlist[0]->find('tr') as $key => $tr) {
            if($key != 0) {
                $tds = $tr->find('td');

                $vessel['name'] = $tds[1]->find('strong')[0]->plaintext;
                $vessel['imo'] = $tds[2]->find('strong')[0]->plaintext;
                if(isset($tds[7]->find('strong')[0]->find('.date')[0])) {
                    $vessel['arrivalDate'] = $tds[7]->find('strong')[0]->find('.date')[0]->plaintext;
                }
                else {
                    $vessel['arrivalDate'] = $tds[7]->find('strong')[0]->plaintext;
                }
                
                $this->expectedVessels[] = $vessel;
            }
        }
        
        $this->updateDB();
    }
    
    private function getSegelliste() {
        $this->html = $this->getHTML(self::URL_FLEETMON_SEGELLISTE);
        
        if(empty($this->html)) {
            logger::writeLogError('getFleetmon', 'Fleetmon Scrapping lieferte keine Daten.');
            return false;
        }
        
        return true;
    }    
}

