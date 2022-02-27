<?php
namespace bo\components\controller;

use bo\components\classes\helper\Query;
use bo\components\classes\Vessel;
use bo\components\classes\Dry;
use bo\components\classes\User;
use bo\components\classes\Agency;
use bo\components\classes\Company;

class SearchController
{
    private $searchExpression1;
    private $searchExpression2;
    
    public function __construct(){
        $this->searchExpression1 = '%' . trim($_GET['expression']) . '%';
        $this->searchExpression2 = trim($_GET['expression']) . '%';
    }
    
    public function vessel() {
        $searchLimit = "20";
        $searchTarget = "vessel";
        if(isset($_GET['searchLimit']))
            $searchLimit = $_GET['searchLimit'];  
        if(isset($_GET['searchTarget']))
            $searchTarget = $_GET['searchTarget']; 

        $result = (new Query("select"))
            ->table(Vessel::TABLE_NAME)
            ->or()
            ->conditionLike([
                "name" => $this->searchExpression1, 
                "IMO" => $this->searchExpression2, 
                "ENI" => $this->searchExpression2, 
                "MMSI" => $this->searchExpression2
            ])
            ->order("ts_erf desc")
            ->limit($searchLimit)
            ->execute();
            
        while($row = $result->fetch()) {?>
	    	<div class="searchResultRow"><a onClick="<?php echo $searchTarget; ?>.openDetails(<?php echo $row['id']; ?>)"><?php echo $row['name']; ?></a></div>
		<?php }
    }
    
    public function vesselDrySearch() {
        $result = (new Query("select"))
            ->table(Dry::TABLE_NAME)
            ->or()
            ->conditionLike(["name" => $this->searchExpression1, "imo" => $this->searchExpression2])
            ->limit("5")
            ->execute();
        
        while($row = $result->fetch()) {?>
    	    <div class="searchResultRow"><i class="gb uk flag"></i> <?php echo $row['name']; ?></div>
   		<?php }
    }
    
    public function userForContact() {
        $result = (new Query("select"))
            ->table(User::TABLE_NAME)
            ->or()
            ->conditionLike(["first_name" => $this->searchExpression1, "surname" => $this->searchExpression1])
            ->limit("10")
            ->execute();
        
        while($row = $result->fetch()) {?>
        	<div onclick="selectSuggested('user', this.textContent);"><?php echo $row['first_name'] . " " . $row['surname']; ?></div>
        <?php } 
    }
    
    public function agentForContact() {
        $result = (new Query("select"))
            ->table(Agency::TABLE_NAME)
            ->or()
            ->conditionLike(["name" => $this->searchExpression1, "short" => $this->searchExpression1])
            ->order("ts_erf desc")
            ->limit("10")
            ->execute();
        
        while($row = $result->fetch()) {?>
    	    <div onclick="selectSuggested('agent', this.textContent);"><?php echo $row['name']; ?></div>
		<?php }
    }
    
    public function companyForContact() {
        $result = (new Query("select"))
            ->table(Company::TABLE_NAME)
            ->conditionLike([
                "name" => $this->searchExpression1,
                "port_id" => $_GET['parameter']
            ])
            ->limit("5")
            ->execute();
        
        while($row = $result->fetch()) {?>
    	    <div onclick="selectSuggested('company', this.textContent);"><?php echo $row['name']; ?></div>
		<?php }
    }
    
    public function agency() {
        $result = (new Query("select"))
            ->table(Agency::TABLE_NAME)
            ->or()
            ->conditionLike(["name" => $this->searchExpression1, "short" => $this->searchExpression1])
            ->order("ts_erf desc")
            ->limit("20")
            ->execute();
        
        while($row = $result->fetch()) {?>
    	    <div class="searchResultRow"><a onClick="agency.openDetails(<?php echo $row['id']; ?>)"><?php echo $row['name']; ?></a></div>
    	<?php }
    }
}

