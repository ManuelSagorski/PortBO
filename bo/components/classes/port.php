<?php
namespace bo\components\classes;

class port
{
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
        $sqlstrg = "insert into port_bo_port (name, short, mtLink) values (?, ?, ?)";
        dbConnect::execute($sqlstrg, array($this->name, $this->short, $this->mtLink));
        
        $port = dbConnect::fetchSingle("select * from port_bo_port where name = ? and short = ?", port::class, array($this->name, $this->short));
        
        echo $_SESSION['user'];
        
        $sqlstrg = "insert into port_bo_userToPort (user_id, port_id) values (?, ?)";
        dbConnect::execute($sqlstrg, array($_SESSION['user'], $port->getID()));
        
        logger::writeLogCreate('port', 'Neuen Hafen angelegt: ' . $this->name);
    }
    
    /*
     * Static Funktion die den Namen zu einer PortID liefert
     */
    public static function getPortName($id) {
        $sqlstrg = "select * from port_bo_port where id = ?";
        $result = dbConnect::execute($sqlstrg, array($id));
        $row = $result->fetch();
        return $row['name'] ?? '';
    }
    
    /*
     * Static Funktion die die ID zu einem Hafen liefert
     */
    public static function getPortID($name) {
        $sqlstrg = "select * from port_bo_port where name = ?";
        $result = dbConnect::execute($sqlstrg, array($name));
        $row = $result->fetch();
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