<?php
namespace bo\components\classes;

class SettingsExternLinks extends AbstractDBObject
{
    public const TABLE_NAME = "port_bo_settingsExternLinks";
    
    private $id;
    private $name;
    private $link;    
    
    /**
     * Konstructor
     */
    public function __construct($data = null) {
        if(!empty($data)) {
            $this->name = $data['linkName'];
            $this->link = $data['linkUrl'];
        }
    } 
    
    public function addLink() {
        $this->insertDB([
            "name" => $this->name,
            "link" => $this->link
        ]);
    }
    
    public function updateLink($data) {
        $this->updateDB([
            "name" => $data['linkName'],
            "link" => $data['linkUrl']
        ], ["id" => $this->id]);
    }
    
    public function deleteLink() {
        $this->deleteDB(["id" => $this->id]);
    }
    
    /*
     * Getter und Setter
     */
    public function getID() {
        return $this->id;
    }
    public function getName() {
        return $this->name;
    }
    public function getLink() {
        return $this->link;
    }
}

