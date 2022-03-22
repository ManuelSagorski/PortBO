<div>
    <div class="ui action input">
    	<input type="text" id="searchInputVessel" placeholder="Name/IMO/ENI" onkeyup="vessel.searchVessel(this.value);">
    	<button class="ui icon button" onclick="vessel.searchVessel($('#searchInputVessel').val());">
			<i class="search icon"></i>
      	</button>
    </div>
</div>
<div id="searchResult"></div>
<div id="drySearchResult"></div>
<button onClick="vessel.newVessel(null, $('#searchInputVessel').val());">Schiff hinzufügen</button>
<script type="text/javascript">vessel.searchVessel('');</script>
