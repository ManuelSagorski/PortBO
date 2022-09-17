<?php
namespace bo\components\classes\forecast;

use bo\components\classes\helper\Logger;

class POR extends Scraping
{
    const URL_POA = 'https://ats-api.portofrotterdam.com/api/ship-visits';
    
    private $json;
    private $listType = ['Present', 'Expected'];
    
    public function getForecast() {
        $this->json = json_decode($this->getHTML(self::URL_POA), true);
        
        if(empty($this->json)) {
            Logger::writeLogError('getPOR', 'POR Scrapping lieferte keine Daten.');
            return false;
        }
        
        $vessel['port'] = 160;
        $vessel['leavingDateTime'] = "";
        
        foreach ($this->listType as $listType) {
            foreach($this->json[$listType] as $oneVessel) {
                $vessel['imo'] = $oneVessel['imo'];
                $vessel['name'] = $oneVessel['name'];
                
                if(!empty($oneVessel['operator']))
                    $vessel['agency'] = $oneVessel['operator'];
                else
                    $vessel['agency'] = '';
                
                $arrival = new \DateTime($oneVessel['datetime']);
                $vessel['arrivalDate'] = $arrival->format('Y-m-d H:i:s');
                    
                if(!empty($oneVessel['berth']))
                    $vessel['company'] = $oneVessel['berth'];
                else
                    $vessel['company'] = '';                
                
                if(new \DateTime() < $arrival || $arrival->diff(new \DateTime(), true)->days < 4) {
                    $this->expectedVessels[] = $vessel;
                }
            }
        }
        
        $this->updateDB();
        
        
    }
}

