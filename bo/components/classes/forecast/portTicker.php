<?php
namespace bo\components\classes\forecast;

use bo\components\classes\logger;
use bo\components\classes\company;

class portTicker extends scraping
{
    const URL_PORTTICKER = 'https://api-20.portticker.com/data/?api=1&port={{portName}}&s=1&_=1633355308225';
    
    private $json;
    private $ports = [1 => 'DEHAM', 2 => 'DEKEL', 3 => 'DELBC', 10 => 'DEBRB', 11 => 'DEBUZ'];
    
    public function getForecast() {
        foreach ($this->ports as $key => $port) {
            if(!$this->getSegelliste(str_replace('{{portName}}', $port, self::URL_PORTTICKER))) {
                continue;
            }
            
            $vessel['port'] = $key;
            
            foreach($this->json->data as $ship) {
                $vessel['name'] = strip_tags($ship->name);
                $vessel['imo'] = $ship->imo;
                $vessel['arrivalDate'] = '';
                if(!empty($ship->reta)) {
                    $vessel['arrivalDate'] = date("Y-m-d H:i:s", $ship->reta);
                }
                if(empty($vessel['arrivalDate']) && !empty($ship->seta)) {
                    $vessel['arrivalDate'] = date("Y-m-d H:i:s", $ship->seta);
                }
                if(empty($vessel['arrivalDate']) && !empty($ship->aeta)) {
                    $vessel['arrivalDate'] = date("Y-m-d H:i:s", $ship->aeta);
                }
                
                if(!empty($ship->term)) {
                    if(isset(company::$companyNameMapper[$ship->term])) {
                        $vessel['company'] = company::$companyNameMapper[$ship->term];
                    }
                    else {
                        $vessel['company'] = $ship->term;
                    }
                }
                else {
                    $vessel['company'] = '';
                }
                if(!empty($ship->agentl)) {
                    $vessel['agency'] = $ship->agentl;
                }
                else {
                    $vessel['agency'] = '';
                }
                $vessel['leavingDateTime'] = '';
                
                $this->expectedVessels[] = $vessel;
            }
            
            $this->updateDB();
        }
    }
    
    private function getSegelliste($url) {
        $this->json = json_decode($this->getHTML($url));
        
        if(empty($this->json)) {
            logger::writeLogError('getPortTicker', 'PortTicker Scrapping lieferte keine Daten.');
            return false;
        }
        
        return true;
    }
}

?>