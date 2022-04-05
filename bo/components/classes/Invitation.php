<?php
namespace bo\components\classes;

class Invitation extends AbstractDBObject
{
    public const TABLE_NAME = "port_bo_invitation";
    
    private $id;
    private $project_id;
    private $invitation_key;
    
    public function generateInvitationKey($projectID) {
        $key = md5(time());
        $this->insertDB(["project_id" => $projectID, "invitation_key" => $key]);
        
        return $key;
    }
    
    public function getID() {
        return $this->id;
    }
    public function getProjectID() {
        return $this->project_id;
    }
    public function getInvitaionKey() {
        return $this->invitation_key;
    }
}

?>