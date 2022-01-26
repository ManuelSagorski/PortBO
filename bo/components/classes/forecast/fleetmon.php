<?php
namespace bo\components\classes\forecast;

use bo\components\classes\helper\logger;

class fleetmon extends scraping
{
    const URL_FLEETMON_SEGELLISTE = "https://www.fleetmon.com/ports/{{portName}}/#tab-scheduled-arrivals";
    // const URL_FLEETMON_SEGELLISTE = "http://www.port-mission.de/findShip/fleetmon.html";
    
    private $html;
    private $ports = [2 => 'kiel_dekel_5484', 10 => 'brunsbuttel_debrb_5709', 11 => 'stade_desta_19428', 17 => 'tees_gbtee_5919'];
    
    public function getForecast() {
        foreach ($this->ports as $key => $port) {
            $vessel['company'] = "";
            $vessel['leavingDateTime'] = "";
            $vessel['agency'] = "";
            $vessel['port'] = $key;
            
            if(!$this->getSegelliste(str_replace('{{portName}}', $port, self::URL_FLEETMON_SEGELLISTE))) {
                return false;
            }
            
            $resultlist = $this->html->find('#arr_sched_table');
            
            foreach($resultlist[0]->find('tr') as $key => $tr) {
                if($key != 0) {
                    $tds = $tr->find('td');    

                    if(isset($tds[7]->find('strong')[0])) {
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
            }
            
            $this->updateDB();
        }
    }
    
    private function getSegelliste($url) {
        $this->html = $this->getHTML($url);
        
        if(empty($this->html)) {
            logger::writeLogError('getFleetmon - ' . $url, 'Fleetmon Scrapping lieferte keine Daten.');
            return false;
        }
        
        return true;
    }    
}

