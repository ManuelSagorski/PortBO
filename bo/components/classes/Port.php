<?php
namespace bo\components\classes;

use bo\components\classes\helper\DBConnect;
use bo\components\classes\helper\Logger;
use JsonSerializable;
use bo\components\classes\helper\Query;

class Port extends AbstractDBObject
{
    public const TABLE_NAME = "port_bo_port";
    
    private $id;
    private $name;
    private $short;
    private $mtLink;
    
    public function __construct($data = null) {
        if(!empty($data)) {
            $this->name = $data['portName'];
            $this->short = $data['portShort'];
            $this->mtLink = $data['portMTLink'];
        }
    }
    
    /*
     * Funktion die einen neuen Hafen anlegt
     */
    public function addPort() {
        $this->insertDB([
            "name" => $this->name,
            "short" => $this->short,
            "mtLink" => $this->mtLink
        ]);
        
        $port = Port::getSingleObjectByCondition(Array("name" => $this->name, "short" => $this->short));
        
        (new Query("insert"))
            ->table(UserToPort::TABLE_NAME)
            ->values([
                "user_id" => $_SESSION['user'], 
                "port_id" => $port->getID()
            ])->execute();
        
        Logger::writeLogCreate('port', 'Neuen Hafen angelegt: ' . $this->name);
    }
    
    /*
     * Static Funktion die den Namen zu einer PortID liefert
     */
    public static function getPortName($id) {
        $row = (new Query("select"))
            ->table(self::TABLE_NAME)
            ->condition(["id" => $id])
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
     Getter und Setter
     */
    public function getID() {
        return $this->id;
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
}

?>