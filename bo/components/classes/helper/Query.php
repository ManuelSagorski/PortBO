<?php
namespace bo\components\classes\helper;

class Query
{
    private $type;
    private $fields = [];
    private $from;
    private $join = [];
    private $values = [];
    private $conditions = [];
    private $order;
    
    public $sqlstrg;
    public $parameter = [];
    
    public function __construct($type) {
        $this->type = $type;
    }

    public function __toString() {
        $this->build();
        return $this->sqlstrg;
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
    
    public function condition($condition) {
        $this->conditions = $condition;
        return $this;
    }
    
    public function order($order) {
        $this->order = $order;
        return $this;
    }
    
    public function build() {
        $this->parameter = [];
        
        /*
         * type
         */
        $this->sqlstrg = $this->type . " ";
        switch($this->type) {
            case "select":
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
                
                foreach($this->values as $name => $value) {
                    if($name !== array_key_first($this->values)) {
                        $this->sqlstrg .= ", ";
                    }
                    $this->sqlstrg .= $name . " = ?";
                    
                    $this->parameter[] = $value;
                }
                
                $this->sqlstrg .= " ";
                break;
        }
        
        /*
         * conditions
         */
        if(!empty($this->conditions)) {
            foreach($this->conditions as $name => $value) {
                if($name === array_key_first($this->conditions)) {
                    $this->sqlstrg .= "where ";
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
        
        return $this;
    }
}

