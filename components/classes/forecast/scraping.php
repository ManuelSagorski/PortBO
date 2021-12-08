<?php
namespace components\classes\forecast;

use components\classes\logger;
use components\classes\dbConnect;

class scraping
{    
    public $expectedVessels = [];
    
    protected function getHTML($url) {
        $getSite = curl_init();
        
        curl_setopt($getSite, CURLOPT_URL, $url);
        curl_setopt($getSite, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($getSite, CURLOPT_FOLLOWLOCATION, true);
        
        if(($data = curl_exec($getSite)) === false){
            $message = curl_error($getSite) . " - " . $url;
            logger::writeLogError('Curl', $message);
            $retVal = false;
        }
        elseif(($statuscode=curl_getinfo($getSite, CURLINFO_HTTP_CODE)) == 200){
            $retVal = str_get_html($data);
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
            
            $sqlstrg = "select * from port_bo_scedule where name = ? and DATE(arriving) = ?";
            $parameter[] = $vessel['name'];
            $parameter[] = $arrival->format('Y-m-d');
            if(!empty($vessel['company'])) {
                $sqlstrg .= " and company = ?";
                $parameter[] = $vessel['company'];
            }
            
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
                $parameter[] = $row['id'];
                 
                dbConnect::execute(str_replace('{{updateValues}}', $updateValues, $sqlstrg), $parameter);
            }
            else {
                $sqlstrg = "insert into port_bo_scedule (arriving, leaving, name, imo, company, agency, port_id) values (?, ?, ?, ?, ?, ?, ?)";                
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
        dbConnect::execute("delete from port_bo_scedule where leaving < CURDATE()", Array());
    }
}

?>