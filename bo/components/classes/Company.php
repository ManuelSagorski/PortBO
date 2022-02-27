<?php
namespace bo\components\classes;

use bo\components\classes\helper\Logger;
use JsonSerializable;
use bo\components\classes\helper\Query;

class Company extends AbstractDBObject implements JsonSerializable
{
    public const TABLE_NAME = "port_bo_company";
    
    private $id;
    private $project_id;
    private $name;
    private $port_id;
    private $info;
    private $mtLink;
    private $pmLink;
    
    public static $companyNameMapper = array(
        "OSW 5-6" => "Oswaldkai",
        "OSW 7-8" => "Oswaldkai",
        "HABEMA 2" => "HaBeMa",
        "CTA 1" => "CTA",
        "CTA 2" => "CTA",
        "CTA 4" => "CTA",
        "CTT 5" => "CTT",
        "Eurogate-CTH" => "Eurogate",
        "BUKAI 1-2" => "Burchardkai"
    );
    
    public function __construct($data = null) {
        if(!empty($data)) {
            $this->name = $data['companyName'];
            $this->port_id = $data['portID'];
            $this->info = $data['companyInfo'];
            $this->mtLink = $data['companyMTLink'];
            $this->pmLink = $data['companyPMLink'];
        }
    }
    
    /*
     * Funktion die einen neuen Liegeplatz anlegt
     */
    public function addCompany() {
        $this->insertDB([
            "name" => $this->name,
            "port_id" => $this->port_id,
            "info" => $this->info,
            "mtLink" => $this->mtLink,
            "pmLink" => $this->pmLink
        ]);
       
        Logger::writeLogCreate('company', 'Neuer Liegeplatz angelegt: ' . $this->name);
    }
    
    /*
     * Static Funktion zum Bearbeiten eines Liegeplatzes
     */
    public function editCompany($data) {
        $this->updateDB([
            "name" => $data['companyName'],
            "info" => $data['companyInfo'],
            "mtLink" => $data['companyMTLink'],
            "pmLink" => $data['companyPMLink']
        ], ["id" => $this->id]);
    }
    
    /*
     * Static Funktion die einen Liegeplatz löscht
     */
    public function deleteCompany() {
        $this->deleteDB(["id" => $this->id]);
    }
    
    /*
     * Static Funktion die den Namen zu einer LiegeplatzID liefert
     */
    public static function getCompanyName($id) {
        $row = (new Query("select"))
            ->table(self::TABLE_NAME)
            ->condition(["id" => $id])
            ->execute()->fetch();
        
        return $row['name'] ?? '';
    }
    
    public static function getCompanyByName($name) {
        $row = (new Query("select"))
            ->table(self::TABLE_NAME)
            ->condition(["name" => $name])
            ->execute()
            ->fetch();
        
        return $row['id'] ?? '';
    }
    
    /*
     Getter und Setter
     */
    public function getID() {
        return $this->id;
    }
    public function getProjectId() {
        return $this->project_id;
    }
    public function getName() {
        return $this->name;
    }
    public function getPortID() {
        return $this->port_id;
    }
    public function getInfo() {
        return $this->info;
    }
    public function getMTLink() {
        return $this->mtLink;
    }
    public function getPMLink() {
        return $this->pmLink;
    }
    
    public function jsonSerialize() {
        return [
            'companyName' => $this->name,
            'companyInfo' => $this->info,
            'portName' => Port::getPortName($this->port_id),
            'companyMTLink' => $this->mtLink,
            'companyPMLink' => $this->pmLink
        ];
    }
}

?>