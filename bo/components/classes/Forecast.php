<?php
namespace bo\components\classes;

use bo\components\classes\helper\Query;

class Forecast extends AbstractDBObject
{
    public const TABLE_NAME = "port_bo_scedule";
    
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
        $mailQuery = (new Query("select"))
            ->table(Vessel::TABLE_NAME, "v")
            ->rightJoin(VesselContactDetails::TABLE_NAME, "vcd", "id", "vessel_id")
            ->condition(["vcd.type" => "Email"]);
        
        $dryQuery = (new Query("select"))
            ->table(Dry::TABLE_NAME);
        
        $vesselQuery = (new Query("select"))
            ->table(Vessel::TABLE_NAME);
            
        if(!empty($this->imo)) {
            $mailQuery->condition(["v.imo" => $this->imo]);
            $dryQuery->condition(["imo" => $this->imo]);
            $vesselQuery->condition(["imo" => $this->imo]);
        }
        else {
            $mailQuery->condition(["UPPER(v.name)" => strtoupper($this->name)]);
            $dryQuery->condition(["UPPER(name)" => strtoupper($this->name)]);
            $vesselQuery->condition(["UPPER(name)" => strtoupper($this->name)]);
        }        
        
        if($mailQuery->execute()->rowCount() > 0) {
            $this->hasMail = true;
        }
        else {
            if($dryQuery->execute()->rowCount() > 0) {
                $this->inDry = true;
            }
        }

        $this->vessel = $vesselQuery->fetchSingle(Vessel::class);
        
        if(!$this->hasMail and !$this->inDry) {
            foreach($this->companysExpectMail as $company) {
                if(is_numeric(stripos($this->name, $company))) {
                    $this->expectMail = true;
                }
            }
        }
    }
    
    public static function forecastItemDone($id) {
        (new Query("update"))
            ->table(self::TABLE_NAME)
            ->values(["status" => "1"])
            ->condition(["id" => $id])
            ->execute();
    }

    public static function forecastItemReopen($id) {
        (new Query("update"))
            ->table(self::TABLE_NAME)
            ->values(["status" => "0"])
            ->condition(["id" => $id])
            ->execute();
    }

    
    public static function forecastItemRemove($id) {
        (new Query("delete"))
            ->table(self::TABLE_NAME)
            ->condition(["id" => $id])
            ->execute();
    }
    
    public static function addForecast($data) {
        (new Query("insert"))
            ->table(self::TABLE_NAME)
            ->values([
                "arriving" => $data['eta'],
                "name" => $data['name'],
                "company" => $data['terminal'],
                "agency" => $data['agency'],
                "port_id" => $data['portID']
            ])
            ->execute();
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

