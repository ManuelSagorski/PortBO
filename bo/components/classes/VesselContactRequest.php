<?php
namespace bo\components\classes;

class VesselContactRequest extends AbstractDBObject
{
    public const TABLE_NAME = "port_bo_vesselContactRequest";
    
    private $id;
    private $vessel_id;
    private $requesting_group_id;
    private $requested_group_id;
    private $request_key;
    
    public function __construct()
    {}
    
    public function getID() {
        return $this->id;
    }
    public function getVesselID() {
        return $this->vessel_id;
    }
    public function getRequestingGroupID() {
        return $this->requesting_group_id;
    }
    public function getRequestedGroupID() {
        return $this->requested_group_id;
    }
    public function getRequestKey() {
        return $this->request_key;
    }
}

?>