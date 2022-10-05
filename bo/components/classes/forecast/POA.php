<?php
namespace bo\components\classes\forecast;

use bo\components\classes\helper\Logger;

class POA extends Scraping
{
    const URL_POA = 'https://myport.portofamsterdam.com/admin/poa_aevt_api/ships';
    
    private $json;
    
    public function getForecast() {
        $this->json = json_decode($this->getHTML(self::URL_POA), true);
        
        if(empty($this->json)) {
            Logger::writeLogError('getPOA', 'POA Scrapping lieferte keine Daten.');
            return false;
        }
        
        $vessel['port'] = 144;
        $vessel['imo'] = '';
        $vessel['company'] = '';
        
        
        foreach($this->json['data'] as $oneVessel) {
            $id = $oneVessel['relationships']['status']['data']['id'];
            
            if(!empty($id)) {
                $vessel['name'] = $oneVessel['attributes']['name'];
                if(!empty($oneVessel['attributes']['agent']))
                    $vessel['agency'] = $oneVessel['attributes']['agent'];
                else
                    $vessel['agency'] = '';
                
                $relationship = array_filter($this->json['included'], function($item) use($id){
                    return $item['id'] === $id;
                });
                
               
                
                foreach ($relationship as $tmp){
                    $arrival = new \DateTime($tmp['attributes']['dateArrival']['date']);
                    $vessel['arrivalDate'] = $arrival->format('Y-m-d H:i:s');
                    
                    $departure = new \DateTime($tmp['attributes']['dateDeparture']['date']);
                    $vessel['leavingDateTime'] = $departure->format('Y-m-d H:i:s');
                    
                    $portId = $tmp['relationships']['berth']['data']['id'];
                    
                    if(!empty($portId)) {
                        $relPort = array_filter($this->json['included'], function($item) use($portId){
                            return $item['id'] === $portId;
                        });
                        
                        foreach ($relPort as $port){
                            $vessel['company'] = $port['attributes']['name'];
                        }
                    }
                }
                if(new \DateTime() < $arrival || $arrival->diff(new \DateTime(), true)->days < 4) {
                    $this->expectedVessels[] = $vessel;
                }
            }
        }
        
        $this->updateDB();
    }
}

