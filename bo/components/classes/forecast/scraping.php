<?php
namespace bo\components\classes\forecast;

use bo\components\classes\helper\logger;
use bo\components\classes\helper\dbConnect;

class scraping
{    
    public $expectedVessels = [];
    
    protected function getHTML($url, $type = 'html') {
        $getSite = curl_init();
        
        $agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/95.0.4638.54 Safari/537.36';
        
        curl_setopt($getSite, CURLOPT_URL, $url);
        curl_setopt($getSite, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($getSite, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($getSite, CURLOPT_USERAGENT, $agent);
        
        if(($data = curl_exec($getSite)) === false){
            $message = curl_error($getSite) . " - " . $url;
            logger::writeLogError('Curl', $message);
            $retVal = false;
        }
        elseif(($statuscode=curl_getinfo($getSite, CURLINFO_HTTP_CODE)) == 200){
            if($type == 'html') {
                $retVal = str_get_html($data);
            }
            if($type == 'json') {
                $retVal = $data;
            }
        }
        else{
            $message = curl_error($getSite) . " - " . $url;
            logger::writeLogError('Curl', $message);
            $retVal = false;
        }
        
        curl_close($getSite);
        return $retVal;
    }
    
    protected function updateDB() {
        $this->cleanDB();
        
        foreach($this->expectedVessels as $vessel) {
            if(isset($vessel['arrivalTime'])) {
                $arrival = new \DateTime($vessel['arrivalDate'] . " " . $vessel['arrivalTime']);
            }
            else {
                $arrival = new \DateTime($vessel['arrivalDate']);
            }
            
            if(!empty($vessel['leavingDateTime'])) {
                $departure = new \DateTime($vessel['leavingDateTime']);
                $departure = $departure->format('Y-m-d H:i:s');
            }
            else {
                $departure = null;
            }

            $parameter = [];
            
            $sqlstrg = "select * from port_bo_scedule where name = ? and DATE(arriving) >= ? and DATE(arriving) <= ? and port_id = ?";
            $parameter[] = $vessel['name'];
            
            $arrival->modify('-2 day');
            $parameter[] = $arrival->format('Y-m-d');
            $arrival->modify('+4 days');
            $parameter[] = $arrival->format('Y-m-d');
            $arrival->modify('-2 day');
            
            $parameter[] = $vessel['port'];
            
            /*
             * 15.01.2022
             * Unterscheidung zwischen verschiedenen Liegeplätze jetzt während Corona noch nicht notwendig
             */
             
            /*
            if(!empty($vessel['company'])) {
                $sqlstrg .= " and (company = ? or company = '')";
                $parameter[] = $vessel['company'];
            }
            */
            
            $result = dbConnect::execute($sqlstrg, $parameter);
            $row = $result->fetch();
            
            if($result->rowCount() > 0) {
                $parameter = [];
                
                $sqlstrg = "update port_bo_scedule set arriving = ?{{updateValues}} where id = ?";

                $parameter[] = $arrival->format('Y-m-d H:i:s');
                
                $updateValues = '';
                if(!empty($departure)) {
                    $updateValues .= ", leaving = ?";
                    $parameter[] = $departure;
                }
                if(!empty($vessel['agency'])) {
                    $updateValues .= ", agency = ?";
                    $parameter[] = $vessel['agency'];
                }
                if(!empty($vessel['company'])) {
                    $updateValues .= ", company = ?";
                    $parameter[] = $vessel['company'];
                }

                if(intval($vessel['imo']) == 0) {
                    $vessel['imo'] = "";
                }
                
                if(!empty($vessel['imo'])) {
                    $updateValues .= ", imo = ?";
                    $parameter[] = $vessel['imo'];
                }
                $parameter[] = $row['id'];
                 
                dbConnect::execute(str_replace('{{updateValues}}', $updateValues, $sqlstrg), $parameter);
            }
            else {
                /*
                $today = new \DateTime();
                $diffdays = $arrival->diff($today);
                
                echo "Tage unterschied: " . $diffdays->days . "<br>";
                */
                
                $sqlstrg = "insert into port_bo_scedule (arriving, leaving, name, imo, company, agency, port_id) values (?, ?, ?, ?, ?, ?, ?)";

                if(intval($vessel['imo']) == 0) {
                    $vessel['imo'] = "";
                }
                
                dbConnect::execute($sqlstrg, Array(
                    $arrival->format('Y-m-d H:i:s'), 
                    $departure, 
                    $vessel['name'],
                    $vessel['imo'],
                    $vessel['company'], 
                    $vessel['agency'], 
                    $vessel['port']
                ));
            }
        }
    }
    
    private function cleanDB() {
        dbConnect::execute("delete from port_bo_scedule where leaving < CURDATE() and arriving < CURDATE() and arriving <> '0000-00-00 00:00:00'", Array());
        dbConnect::execute("delete from port_bo_scedule where arriving < CURDATE() and status = 0 and arriving <> '0000-00-00 00:00:00'", Array());
    }
}

?>