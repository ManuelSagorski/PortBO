<?php
namespace bo\components\classes\forecast;

use bo\components\classes\helper\Logger;

class PWL extends Scraping
{
    const URL_PWL = 'https://www.pwl.de/portale/isps-autocomplete';
    
    private $json;
    
    public function __construct()
    {}
    
    public function getForecast() {
        $this->json = json_decode($this->getHTML(self::URL_PWL));
        
        if(empty($this->json)) {
            Logger::writeLogError('getPWL', 'PWL Scrapping lieferte keine Daten.');
            return false;
        }        
        
        $vessel['agency'] = 'PWL';
        $vessel['leavingDateTime'] = '';
        
        foreach($this->json as $ship) {
            if(!empty($ship->vessel)) {
                switch($ship->harbour) {
                    case 'HAMBURG':
                        $vessel['port'] = 1;
                        break;
                    case 'STADE':
                        $vessel['port'] = 11;
                        break;
                    case 'BRUNSBÜTTEL':
                        $vessel['port'] = 10;
                        break;
                    default:
                        $vessel['port'] = '';
                        break;
                }
                
                if(!empty($vessel['port'])) {
                    $vessel['name'] = $ship->vessel;
                    $vessel['imo'] = $ship->imo_number;
                    $vessel['company'] = $ship->terminal;
                    
                    $eta = explode(" ", $ship->eta_first_berth);
                    if(empty($eta[2]))
                        $eta[2] = "00:00";
                    
                    if((date("M") == 'Nov' || date("M") == 'Dec') && ($eta[1] == 'Jan' || $eta[1] == 'Feb')) {
                        $year = date('Y', strtotime('+1 year'));
                    }
                    else {
                        $year = date('Y');
                    }
                    $vessel['arrivalDate'] = date("Y-m-d H:i:s", strtotime($eta[0] . " " . $eta[1] . " " . $year . " " . $eta[2]));
                    
                    $this->expectedVessels[] = $vessel;
                }
            }
        }
        $this->updateDB();
    }
}