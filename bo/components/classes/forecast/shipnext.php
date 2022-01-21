<?php
namespace bo\components\classes\forecast;

use bo\components\classes\logger;
use bo\components\classes\company;

class shipnext extends scraping
{
    const URL_SHIPNEXT = 'https://shipnext.com/api/v1/ports/public/{{portName}}';
    
    private $json;
    // private $ports = [2 => 'kiel-dekel-deu', 3 => 'lubeck-delbc-deu', 10 => 'brunsbuttel-debrb-deu', 11 => 'stade-desta-deu', 11 => 'butzfleth-debuz-deu'];
    private $ports = [1 => 'hamburg-deham-deu', 2 => 'kiel-dekel-deu', 3 => 'lubeck-delbc-deu', 10 => 'brunsbuttel-debrb-deu', 11 => 'stade-desta-deu', 111 => 'butzfleth-debuz-deu'];
    
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
                               
                if(new \DateTime() < $arrival || $arrival->diff(new \DateTime(), true)->days < 2) {
                    $this->expectedVessels[] = $vessel;
                }
            }
            
            $this->updateDB();
        }
    }
    
    private function getSegelliste($url) {
        $this->json = json_decode($this->getHTML($url));
        
        if(empty($this->json)) {
            logger::writeLogError('getShipnext', 'Shipnext Scrapping lieferte keine Daten.');
            return false;
        }
        
        return true;
    }
}

?>