<?php
namespace bo\components\classes;

use bo\components\classes\dbConnect;

class forecast
{
    private $id;
    private $arriving;
    private $leaving;
    private $name;
    private $imo;
    private $company;
    private $agency;
    private $port_id;
    private $status;
    
    public $hasMail = false;
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
        
        $mail = dbConnect::execute($sqlstrgMail . $condition, $param);        
        if($mail->rowCount() > 0) {
            $this->hasMail = true;
        }

        $dry = dbConnect::execute($sqlstrgDry . $condition, $param);
        if($dry->rowCount() > 0) {
            $this->inDry = true;
        }
        
        $this->vessel = dbConnect::fetchSingle($sqlstrgVessel . $condition, vessel::class, $param);
    }
    
    public static function forecastItemDone($id) {
        dbConnect::execute("update port_bo_scedule set status = 1 where id = ?", Array($id));
    }

    public static function forecastItemReopen($id) {
        dbConnect::execute("update port_bo_scedule set status = 0 where id = ?", Array($id));
    }

    
    public static function forecastItemRemove($id) {
        dbConnect::execute("delete from port_bo_scedule where id = ?", Array($id));
    }
    
    public static function addForecast($data) {
        $sqlstrg = "insert into port_bo_scedule (arriving, name, company, agency, port_id) values (?, ?, ?, ?, ?)";
        dbConnect::execute($sqlstrg, Array($data['eta'], $data['name'], $data['terminal'], $data['agency'], $data['portID']));
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

