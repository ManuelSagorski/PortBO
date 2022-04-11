<?php 
include '../../components/config.php';
?>
<div>
    <div class="ui action input">
    	<input type="text" id="searchInputAgency" placeholder="<?php $t->_('search'); ?>..." onkeyup="agency.searchAgency(this.value);">
    	<button class="ui icon button" onclick="agency.searchAgency($('#searchInput').val());">
			<i class="search icon"></i>
      	</button>
    </div>
</div>
<div id="searchResult"></div>
<button onClick="agency.newAgency(null, $('#searchInputAgency').val());"><?php $t->_('add-new-agency'); ?></button>
<script type="text/javascript">agency.searchAgency('');</script>