<?php
namespace bo\components\classes\forecast;

use bo\components\classes\helper\Logger;

class HHLA extends  Scraping
{
    const URL_HHLA_SEGELLISTE = "https://coast.hhla.de/api/execute-report/Standard-Report-Segelliste";
    
    private $json;
    
    public function getForecast() {
        if(!$this->getSegelliste(self::URL_HHLA_SEGELLISTE)) {
            return false;
        }
        
        $vessel['port'] = 1;
        
        foreach($this->json->resultTables[0]->rows as $row) {
            $arrival = new \DateTime($row[3]->value);
            $leaving = new \DateTime($row[1]->value);
            $aktDate = new \DateTime();
            $maxDate = new \DateTime();
            $maxDate->modify('+8 days');
            
            if($arrival->getTimestamp() > $aktDate->getTimestamp() && $arrival->getTimestamp() < $maxDate->getTimestamp()) {
                $vessel['name'] = $row[12]->value;
                $vessel['imo'] = '';
                $vessel['arrivalDate'] = $arrival->format('Y-m-d H:i:s');
                $vessel['company'] = $row[14]->value;
                $vessel['agency'] = '';
                $vessel['leavingDateTime'] = $leaving->format('Y-m-d H:i:s');
                
                $this->expectedVessels[] = $vessel;
            }

        }
        
        $this->updateDB();
    }
    
    private function getSegelliste($url) {
        $this->json = json_decode($this->getHTML($url, 'json'));
        
        if(empty($this->json)) {
            Logger::writeLogError('getHHLA', 'HHLA Scrapping lieferte keine Daten.');
            return false;
        }
        
        return true;
    }

}

