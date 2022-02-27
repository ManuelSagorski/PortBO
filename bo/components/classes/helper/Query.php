<?php
namespace bo\components\classes\helper;

use bo\components\classes\Agency;
use bo\components\classes\Company;
use bo\components\classes\Port;
use bo\components\classes\User;
use bo\components\classes\VesselContact;
use bo\components\classes\VesselInfo;
use bo\components\classes\SettingsExternLinks;

class Query
{
    private $type;
    private $fields = [];
    private $distinct;
    private $from;
    private $join = [];
    private $values = [];
    private $valuesString = [];
    private $conditions = [];
    private $conditionString;
    private $conditionLogic;
    private $order;
    private $limit;
    private $project;
    private $projectCondition = [];
   
    private $projectTables = [
        Agency::TABLE_NAME,
        Company::TABLE_NAME,
        Logger::LOG_TABLE,
        Port::TABLE_NAME,
        User::TABLE_NAME,
        VesselContact::TABLE_NAME,
        VesselInfo::TABLE_NAME,
        SettingsExternLinks::TABLE_NAME
    ];
    
    public $sqlstrg;
    public $parameter = [];
    
    public function __construct($type) {
        $this->type = $type;
        $this->conditionLogic = "and";
        $this->project = null;
        $this->distinct = false;
    }

    public function __toString() {
        $this->build();
        return $this->sqlstrg;
    }
    
    public function project($projectID) {
        $this->project = $projectID;
        return $this;
    }
    
    public function distinct() {
        $this->distinct = true;
        return $this;
    }
    
    public function fields(...$fields) {
        $this->fields = $fields;
        return $this;
    }
    
    public function table($table, $alias = null) {
        $this->from[] = Array($table, $alias);
        return $this;
    }
    
    public function join($table, $alias, $keyA, $keyB) {
        $this->from[] = Array($table, $alias);
        $this->join[] = Array("join", $keyA, $keyB);
        return $this;
    }

    public function leftJoin($table, $alias, $keyA, $keyB) {
        $this->from[] = Array($table, $alias);
        $this->join[] = Array("left join", $keyA, $keyB);
        return $this;
    }

    public function rightJoin($table, $alias, $keyA, $keyB) {
        $this->from[] = Array($table, $alias);
        $this->join[] = Array("right join", $keyA, $keyB);
        return $this;
    }
    
    public function values($values) {
        $this->values = $values;
        return $this;
    }

    public function valuesString($valuesString) {
        $this->valuesString = $valuesString;
        return $this;
    }
    
    public function condition($condition) {
        $this->conditions['equal'][] = $condition;
        return $this;
    }

    public function conditionLike($condition) {
        $this->conditions['like'][] = $condition;
        return $this;
    }
    
    public function conditionNot($condition) {
        $this->conditions['notEqual'][] = $condition;
        return $this;
    }

    public function conditionGreater($condition) {
        $this->conditions['greater'][] = $condition;
        return $this;
    }

    public function conditionLower($condition) {
        $this->conditions['lower'][] = $condition;
        return $this;
    }
    
    /**
     * Beispiel: conditionSting(["vc.user_id = ? or up.user_id = ?" => [$wert1, $wert2]])
     * 
     * Wird nach den anderen Bedinungen geklammert als and Block angefügt.
     * 
     * @param Array $conditionString
     * @return \bo\components\classes\helper\Query
     */
    public function conditionString($conditionString) {
        $this->conditionString = $conditionString;
        return $this;
    }
    
    public function or() {
        $this->conditionLogic = "or";
        return $this;
    }
    
    public function order($order) {
        $this->order = $order;
        return $this;
    }

    public function limit($limit) {
        $this->limit = $limit;
        return $this;
    }
    
    public function execute() {
        $this->build();
        return DBConnect::execute($this->sqlstrg, $this->parameter);
    }
    
    public function fetchSingle($class) {
        $this->build();
        return DBConnect::fetchSingle($this->sqlstrg, $class, $this->parameter);
    }
    
    public function fetchAll($class) {
        $this->build();
        return DBConnect::fetchAll($this->sqlstrg, $class, $this->parameter);
    }
    
    public function build() {
        $this->parameter = [];
        
        /*
         * Project relevance
         */
        if(isset($_SESSION['project']) && $this->project === null) {
            $this->project = $_SESSION['project'];
        }
        
        if($this->project !== null && $this->project != 0) {
            switch($this->type) {
                case "insert":
                    if(in_array($this->from[0][0], $this->projectTables)) {
                        $this->values["project_id"] = $this->project;
                    }
                    break;
                case "select":
                    foreach ($this->from as $key => $table) {
                        if(in_array($table[0], $this->projectTables)) {
                            $condition = '';
                            if(!empty($table[1])) {
                                $condition = $table[1] . ".";
                            }
                            $condition .= "project_id";
                            
                            $this->projectCondition = [$condition => $this->project];
                        }
                    }
                    break;
            }
        }
                
        /*
         * type
         */
        $this->sqlstrg = $this->type . " ";
        switch($this->type) {
            case "select":
                if($this->distinct)
                    $this->sqlstrg .= "distinct ";
                
                if(empty($this->fields)) {
                    $this->sqlstrg .= "* from ";
                }
                else {
                    $this->sqlstrg .= implode(',', $this->fields) . " from ";
                }
                break;
                
            case "insert":
                $this->sqlstrg .= "into ";
                break;
                
            case "delete":
                $this->sqlstrg .= "from ";
                break;
        }
        
        
        /*
         * table and join
         */
        foreach($this->from as $key => $table) {
            if($key !== array_key_first($this->from))
                $this->sqlstrg .= $this->join[0][0] . " ";
            $this->sqlstrg .= $table[0] . " ";
            if(!empty($table[0])) {
                $this->sqlstrg .= $table[1] . " ";
            }
            if($key !== array_key_first($this->from)) {
                $this->sqlstrg .= "on " . $this->from[0][1] . "." . $this->join[0][1] . " = " . $this->from[1][1] . "." . $this->join[0][2] . " ";
            }
        }
        
        /*
         * insert, update
         */
        switch($this->type) {
            case "insert":
                $this->sqlstrg .= "(";
                foreach($this->values as $name => $value) {
                    if($name !== array_key_first($this->values))
                        $this->sqlstrg .= ", ";
                        $this->sqlstrg .= $name;
                        $this->parameter[] = $value;
                }
                $this->sqlstrg .= ") value (";
                for($i=1;$i<=count($this->values);$i++) {
                    if($i>1)
                        $this->sqlstrg .= ", ";
                        $this->sqlstrg .= "?";
                }
                $this->sqlstrg .= ")";
                break;
                
            case "update":
                $this->sqlstrg .= "set ";
                
                if(!empty($this->valuesString)) {
                    $this->sqlstrg .= $this->valuesString;
                }
                else {
                    foreach($this->values as $name => $value) {
                        if($name !== array_key_first($this->values)) {
                            $this->sqlstrg .= ", ";
                        }
                        $this->sqlstrg .= $name . " = ?";
                        
                        $this->parameter[] = $value;
                    }
                }
                
                $this->sqlstrg .= " ";
                break;
        }
        
        /*
         * conditions
         */
        $first = false;
        if(!empty($this->conditions)) {
            foreach($this->conditions as $type => $conditions) {
                foreach ($conditions as $condition) {
                    foreach ($condition as $name => $value) {
                        if(!$first) {
                            $this->sqlstrg .= "where (";
                            $first = true;
                        }
                        else {
                            $this->sqlstrg .= $this->conditionLogic . " ";
                        }
                            
                        $this->sqlstrg .= $name;
                        
                        switch($type) {
                            case "equal":
                                $this->sqlstrg .= " = ? ";
                                break;
                            case "like":
                                $this->sqlstrg .= " like ? ";
                                break;
                            case "notEqual":
                                $this->sqlstrg .= " <> ? ";
                                break;
                            case "greater":
                                $this->sqlstrg .= " > ? ";
                                break;
                            case "lower":
                                $this->sqlstrg .= " < ? ";
                                break;
                        }
                        
                        $this->parameter[] = $value;
                    }
                }
            }
            
            if(!empty($this->conditionString)) {
                if(!$first) {
                    $this->sqlstrg .= "where (";
                    $first = true;
                }
                else {
                    $this->sqlstrg .= "and ";
                }
                $this->sqlstrg .= "(" . key($this->conditionString) . ")";
                foreach ($this->conditionString[key($this->conditionString)] as $parameter) {
                    $this->parameter[] = $parameter;
                }
            }
            
            if($first)
                $this->sqlstrg .= ") ";
        }
        
        if(!empty($this->projectCondition)) {
            foreach ($this->projectCondition as $name => $value) {
                if(!$first) {
                    $this->sqlstrg .= "where ";
                    $first = true;
                }
                else {
                    $this->sqlstrg .= "and ";
                }
                
                $this->sqlstrg .= $name . " = ? ";
                $this->parameter[] = $value;
            }
        }
        
        /*
         * order
         */
        if(!empty($this->order)) {
            $this->sqlstrg .= "order by " . $this->order;
        }

        /*
         * limit
         */
        if(!empty($this->limit)) {
            $this->sqlstrg .= " limit " . $this->limit;
        }
        
        return $this;
    }
}

