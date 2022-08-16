<?php
namespace bo\components\classes;

use bo\components\classes\helper\Logger;
use bo\components\classes\helper\Query;
use bo\components\classes\helper\DBConnect;

class Port extends AbstractDBObject
{
    public const TABLE_NAME = "port_bo_port";
    
    private $id;
    private $inactive;
    private $project_id;
    private $name;
    private $short;
    private $mtLink;
    private $lat;
    private $lon;
    private $vf_zoom;
    
    public function __construct($data = null) {
        if(!empty($data)) {
            $this->name = $data['portName'];
            $this->short = $data['portShort'];
            $this->mtLink = $data['portMTLink'];
            $this->lat = $data['portLat'];
            $this->lon = $data['portLon'];
            $this->vf_zoom = $data['portMapZoom'];
        }
    }
    
    /*
     * Funktion die einen neuen Hafen anlegt
     */
    public function addPort() {
        $this->insertDB([
            "name" => $this->name,
            "short" => $this->short,
            "mtLink" => $this->mtLink,
            "lat" => $this->lat,
            "lon" => $this->lon,
            "vf_zoom" => $this->vf_zoom
        ]);
        
        $port = (new Port())->getSingleObjectByID(DBConnect::getLastID());
        
        (new Query("insert"))
            ->table(UserToPort::TABLE_NAME)
            ->values([
                "user_id" => $_SESSION['user'], 
                "port_id" => $port->getID()
            ])->execute();
        
        Logger::writeLogCreate('port', 'Neuen Hafen angelegt: ' . $this->name);
    }
    
    /*
     * Aktualisiert einen Hafen in der Datenbank
     */
    public function updatePort($data) {
        $this->updateDB([
            "name" => $data['portName'],
            "short" => $data['portShort'],
            "mtLink" => $data['portMTLink'],
            "lat" => $data['portLat'],
            "lon" => $data['portLon'],
            "vf_zoom" => $data['portMapZoom']
        ], ["id" => $this->id]);
    }

    /*
     * Löscht bzw. deaktiviert einen bestehenden Port
     */
    public function deletePort() {
        (new Query('delete'))
            ->table(AgencyPortInfo::TABLE_NAME)
            ->condition(['port_id' => $this->id])
            ->execute();
        (new Query('delete'))
            ->table(Company::TABLE_NAME)
            ->condition(['port_id' => $this->id])
            ->execute();
        (new Query('delete'))
            ->table(UserToPort::TABLE_NAME)
            ->condition(['port_id' => $this->id])
            ->execute();
        
        if(!empty((new Query('select'))->table(VesselContact::TABLE_NAME)->condition(['port_id' => $this->id])->fetchAll(VesselContact::class))) {        
            (new Query('update'))
                ->table(self::TABLE_NAME)
                ->values(['inactive' => 1])
                ->condition(['id' => $this->id])
                ->execute();
        }
        else {
            $this->deleteDB(['id' => $this->id]);
        }
    }
    
    /*
     * Static Funktion die den Namen zu einer PortID liefert
     */
    public static function getPortName($id, $project = null) {
        $row = (new Query("select"))
            ->table(self::TABLE_NAME)
            ->condition(["id" => $id])
            ->project($project)
            ->execute()
            ->fetch();

        return $row['name'] ?? '';
    }
    
    /*
     * Static Funktion die die ID zu einem Hafen liefert
     */
    public static function getPortID($name) {
        $row = (new Query("select"))
            ->table(self::TABLE_NAME)
            ->condition(["name" => $name])
            ->execute()
            ->fetch();

        return $row['id'] ?? '0';
    }
    
    /*
     * Liefert alle User zurück die in einem bestimmten Hafen tätig sind
     */
    public static function getUsersForPort($id) {
        return (new Query("select"))
            ->fields("u.*")
            ->table(User::TABLE_NAME, "u")
            ->join(UserToPort::TABLE_NAME, "up", "id", "user_id")
            ->condition(["up.port_id" => $id, "u.inactive" => 0])
            ->fetchAll(User::class);
    }
    
    public static function getPortsForProject() {
        return (new Query('select'))
            ->table(self::TABLE_NAME)
            ->condition(['project_id' => $_SESSION['project'], 'inactive' => 0])
            ->order('name')
            ->fetchAll(self::class);
    }    
    
    /*
     Getter und Setter
     */
    public function getID() {
        return $this->id;
    }
    public function getInactive() {
        return $this->inactive;
    }
    public function getProjectId() {
        return $this->project_id;
    }
    public function getName() {
        return $this->name;
    }
    public function getShort() {
        return $this->short;
    }
    public function getMtLink() {
        return $this->mtLink;
    }
    public function getLat() {
        return $this->lat;
    }
    public function getLon() {
        return $this->lon;
    }
    public function getVfZoom() {
        return $this->vf_zoom;
    }
}

?>