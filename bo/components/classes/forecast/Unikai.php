<?php
namespace bo\components\classes\forecast;

use bo\components\classes\helper\Logger;

class Unikai extends Scraping
{
    const URL_UNIKAI_SEGELLISTE = "http://sailinglist.unikai.de/";

    private $html;
    
    public function getForecast() {
        if(!$this->getSegelliste()) {
            return false;
        }
        
        $resultlist = $this->html->find('.result');
        
        $vessel['company'] = 'Unikai';
        $vessel['port'] = 1;
        
        foreach($resultlist[0]->find('.light, .dark') as $tr) {
            $tds = $tr->find('td');
            
            $vessel['arrivalDate'] = $tds[0]->plaintext;
            $vessel['leavingDateTime'] = $tds[0]->plaintext;
            $vessel['name'] = $tds[1]->plaintext;
            $vessel['agency'] = $tds[4]->plaintext;
            $vessel['imo'] = '';
            
            $this->expectedVessels[] = $vessel;
        }

        $this->updateDB();
        
    }
    
    private function getSegelliste() {
        $this->html = $this->getHTML(self::URL_UNIKAI_SEGELLISTE);
        
        if(empty($this->html)) {
            Logger::writeLogError('getUnikai', 'Unikai Scrapping lieferte keine Daten.');
            return false;
        }
        
        return true;
    }
    
}

