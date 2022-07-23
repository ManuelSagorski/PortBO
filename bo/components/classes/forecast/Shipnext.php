<?php
namespace bo\components\classes\forecast;

use bo\components\classes\helper\Logger;

class Shipnext extends Scraping
{
    const URL_SHIPNEXT = 'https://shipnext.com/api/v1/ports/public/{{portName}}';
    
    private $json;
    private $ports = [
        1 => 'hamburg-deham-deu', 
        2 => 'kiel-dekel-deu', 
        3 => 'lubeck-delbc-deu', 
        4 => 'rendsburg-deren-deu', 
        5 => 'flensburg-deflf-deu', 
        8 => 'travemunde-detrv-deu', 
        10 => 'brunsbuttel-debrb-deu', 
        11 => 'stade-desta-deu', 
        111 => 'butzfleth-debuz-deu',
        118 => 'plymouth-gbply-gbr',
        134 => 'falmouth-gbfal-gbr',
        144 => 'amsterdam-nlams-nld',
        150 => 'ijmuiden-nlijm-nld'
    ];
    private $portIDs = [
        1 => ['5824541fe82d5211502c5809'],
        2 => ['5825aad132bb0213700f6546'],
        3 => ['582682f3da72a50108cc70c4'],
        4 => ['5828d1276742c90cc0eb71f1'],
        5 => ['58236a3e821bd20e385989eb'],
        8 => ['582682f3da72a50108cc70c4'],
        10 => ['5821a03a0dd99d0cf03f0730', '5827a8cfb20beb0a70c07e1f'],
        11 => ['5821ad650dd99d0cf03f076d', '5829b8895baa9509b886ed12'],
        118 => ['5828417a1c912f0ebcb63d00'],
        134 => ['58235e92821bd20e385989bb'],
        144 => ['581fdc7054e6080aa866a868'],
        150 => ['581fdc7054e6080aa866a868']
    ];
    
    public function getForecast() {
        foreach ($this->ports as $key => $port) {
            if(!$this->getSegelliste(str_replace('{{portName}}', $port, self::URL_SHIPNEXT))) {
                continue;
            }
            
            if($key == 111) { $key = 11; }
            
            $vessel['port'] = $key;
            $vessel['company'] = '';
            $vessel['agency'] = '';            
            $vessel['leavingDateTime'] = '';
            
            foreach($this->json->data->vesselsDirectingToPort as $ship) {
                $arrival = new \DateTime($ship->route->to->date);
               
                $vessel['name'] = strip_tags($ship->name);
                $vessel['imo'] = $ship->imo;
                $vessel['arrivalDate'] = $arrival->format('Y-m-d H:i:s');
                
                $vessel['quelle'] = 'expected';
                               
                if(new \DateTime() < $arrival || $arrival->diff(new \DateTime(), true)->days < 2) {
                    $this->expectedVessels[] = $vessel;
                }
            }
            
            foreach($this->json->data->vesselsNearPort as $ship) {               
                if(in_array($ship->route->to->portId, $this->portIDs[$key])) {
                    $arrival = new \DateTime($ship->route->to->date);
                    
                    $vessel['name'] = strip_tags($ship->name);
                    $vessel['imo'] = $ship->imo;
                    $vessel['arrivalDate'] = $arrival->format('Y-m-d H:i:s');
                    
                    $vessel['quelle'] = 'near';
                    
                    if(new \DateTime() < $arrival || $arrival->diff(new \DateTime(), true)->days < 4) {
                        $this->expectedVessels[] = $vessel;
                    }
                }
            }
            
            $this->updateDB();
        }
    }
    
    private function getSegelliste($url) {
        $this->json = json_decode($this->getHTML($url));
        
        if(empty($this->json)) {
            Logger::writeLogError('getShipnext', 'Shipnext Scrapping lieferte keine Daten.');
            return false;
        }
        
        return true;
    }
}

?>