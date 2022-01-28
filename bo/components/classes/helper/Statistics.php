<?php
namespace bo\components\classes\helper;

class Statistics
{
    private $startDate;
    private $endDate;
    private $port;
    
    private $global = [];
    private $period = [];
    
    private $periodContactStatisticType = Array(
        Array("fieldName" => "letterCount", "contactType" => "Brief", "condition" => ""),
        Array("fieldName" => "emailDirectCount", "contactType" => "Email", "condition" => "and agent_id = 0"),
        Array("fieldName" => "emailAgentCount", "contactType" => "Email", "condition" => "and agent_id > 0"),
        Array("fieldName" => "visitCount", "contactType" => "Besuch", "condition" => ""),
        Array("fieldName" => "phoneCount", "contactType" => "Telefon", "condition" => "")
    );
    
    public function __construct($data){
        $this->startDate = $data['dateFrom'];
        $this->endDate = $data['dateTo'];
        $this->port = $data['port'];
        
        $this->getStatistics();
    }
    
    private function getStatistics() {
        $this->getGlobalStatistics();
        $this->getPeriodStatistics();
    }
    
    private function getGlobalStatistics() {
        $result = DBConnect::execute("select count(*) as shipCount from port_bo_vessel", Array());
        $row = $result->fetch();
        $this->global['shipCount'] = $row['shipCount'];
        
        $result = DBConnect::execute("select count(DISTINCT vessel_id) as shipMailCount from port_bo_vesselContactDetails where type = 'Email'", Array());
        $row = $result->fetch();
        $this->global['shipMailCount'] = $row['shipMailCount'];
        
        $result = DBConnect::execute("select count(DISTINCT vessel_id) as shipPhoneCount from port_bo_vesselContactDetails where type = 'Telefon'", Array());
        $row = $result->fetch();
        $this->global['shipPhoneCount'] = $row['shipPhoneCount'];
    }
    
    private function getPeriodStatistics() {
        $parameter = Array($this->startDate, $this->endDate);

        $sqlstrg = "select count(*) as {{fieldName}} 
                      from port_bo_vesselContact 
                     where contact_type = '{{contactType}}' 
                       and planned = 0 
                       and date >= ? 
                       and date <= ? 
                       {{condition}}";
        
        if($this->port > 0) {
            $sqlstrg .= " and port_id = ?";
            array_push($parameter, $this->port);
        }
        
        foreach($this->periodContactStatisticType as $type) {
            $query = $sqlstrg;
            
            foreach ($type as $key => $value) {
                $query = str_replace("{{" . $key . "}}", $value, $query);
            }
            
            $result = DBConnect::execute($query, $parameter);
            $row = $result->fetch();
            $this->period[$type['fieldName']] = $row[$type['fieldName']];
        }
    }
    
    public function getGlobal() {
        return $this->global;
    }
    public function getPeriod() {
        return $this->period;
    }
}

