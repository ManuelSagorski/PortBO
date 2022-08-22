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

        $vessels = (new Query("select"))
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
            ->fetchAll(Vessel::class);
         
        foreach ($vessels as $vessel) { ?>
	    	<div class="searchResultRow vessel">
	    		<a onClick="<?php echo $searchTarget; ?>.openDetails(<?php echo $vessel->getID(); ?>)">
	    			<?php echo $vessel->getName(); ?>
	    		</a>
	    	</div>
            <div class="ui special popup vesselInfo">
                <div class="header"><i class="ship icon"></i> <?php echo $vessel->getName(); ?></div>
                <div class='content'>
					<table>
						<tr>
							<td>Type:</td>
							<td><?php echo $vessel->getTyp(); ?> </td>
						</tr>
						<tr>
							<td>IMO:</td>
							<td><?php echo $vessel->getIMO(); ?> </td>
						</tr>
						<tr>
							<td>MMSI:</td>
							<td><?php echo $vessel->getMMSI(); ?> </td>
						</tr>
						<tr>
							<td>Contact details:</td>
							<td>
								<?php echo ($vessel->hasMail)?'<i class="envelope outline icon"></i>':''; ?>
								<?php echo ($vessel->hasPhone)?'<i class="phone icon"></i>':''; ?>
							</td>
						</tr>
						<tr>
							<td>Last Contact:</td>
							<td><?php echo Vessel::getLastContactVessel($vessel->getID()); ?></td>
						</tr>
					</table>
				</div>
            </div>
        <?php }
        
        echo "<script>$('.searchResultRow').popup({inline: true, delay: {show: 1000, hide: 100}});</script>";
    }
    
    public function vesselDrySearch() {
        global $user;
        
        if($user->getProjectId() == 1) {
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
    }
    
    public function userForContact() {
        global $user;
        
        if($user->getLevel() < 4) {
            ?><div onclick="selectSuggested('user', this.textContent);"><?php echo $user->getFirstName() . " " . $user->getSurname(); ?></div><?php
        }
        else {
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

