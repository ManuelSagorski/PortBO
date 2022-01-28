<?php
namespace bo\components\classes;

use bo\components\classes\helper\DBConnect;

class Forecast extends AbstractDBObject
{
    protected static $tableName = "port_bo_scedule";
    
    private $id;
    private $arriving;
    private $leaving;
    private $name;
    private $imo;
    private $company;
    private $agency;
    private $port_id;
    private $status;
    
    private $companysExpectMail = Array("essberger", "stolt", "maersk", "federal", "naree");
    
    public $hasMail = false;
    public $expectMail = false;
    public $inDry = false;
    public $vessel;
    
    public function __construct() {
        $sqlstrgMail = "select * from port_bo_vessel v right join port_bo_vesselContactDetails vcd on v.id = vcd.vessel_id where vcd.type = 'Email' and ";
        $sqlstrgVessel = "select * from port_bo_vessel v where ";
        $sqlstrgDry = "select * from port_bo_dry v where ";
        
        if(!empty($this->imo)) {
            $condition = "v.imo = ?";
            $param = Array($this->imo);
        }
        else {
            $condition = "UPPER(v.name) = ?";
            $param = Array(strtoupper($this->name));
        }        
        
        $mail = DBConnect::execute($sqlstrgMail . $condition, $param);        
        if($mail->rowCount() > 0) {
            $this->hasMail = true;
        }
        else {
            $dry = DBConnect::execute($sqlstrgDry . $condition, $param);
            if($dry->rowCount() > 0) {
                $this->inDry = true;
            }
        }
        
        $this->vessel = DBConnect::fetchSingle($sqlstrgVessel . $condition, Vessel::class, $param);
        
        if(!$this->hasMail and !$this->inDry) {
            foreach($this->companysExpectMail as $company) {
                if(is_numeric(stripos($this->name, $company))) {
                    $this->expectMail = true;
                }
            }
        }
    }
    
    public static function forecastItemDone($id) {
        DBConnect::execute("update port_bo_scedule set status = 1 where id = ?", Array($id));
    }

    public static function forecastItemReopen($id) {
        DBConnect::execute("update port_bo_scedule set status = 0 where id = ?", Array($id));
    }

    
    public static function forecastItemRemove($id) {
        DBConnect::execute("delete from port_bo_scedule where id = ?", Array($id));
    }
    
    public static function addForecast($data) {
        $sqlstrg = "insert into port_bo_scedule (arriving, name, company, agency, port_id) values (?, ?, ?, ?, ?)";
        DBConnect::execute($sqlstrg, Array($data['eta'], $data['name'], $data['terminal'], $data['agency'], $data['portID']));
    }
    /*
     Getter und Setter
     */
    public function getID() {
        return $this->id;
    }
    public function getArriving() {
        return $this->arriving;
    }
    public function getLeaving() {
        return $this->leaving;
    }
    public function getName() {
        return $this->name;
    }
    public function getIMO() {
        return $this->imo;
    }
    public function getCompany() {
        return $this->company;
    }
    public function getAgency() {
        return $this->agency;
    }
    public function getStatus() {
        return $this->status;
    }
}

