<?php
namespace components\classes;

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
    public $vessel;
    
    public function __construct() {
        $mail = dbConnect::execute("select v.*
                                      from port_bo_vessel v right join port_bo_vesselContactDetails vcd on v.id = vcd.vessel_id
                                     where UPPER(v.name) = ?
                                       and vcd.type = 'Email'", Array(strtoupper($this->name)));
        if($mail->rowCount() > 0) {
            $this->hasMail = true;
        }

        $this->vessel = dbConnect::fetchSingle("select * from port_bo_vessel v where UPPER(name) = ?", vessel::class, Array(strtoupper($this->name)));
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

