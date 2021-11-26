<div id="search_input">
    <div class="ui action input">
    	<input type="text" id="searchInput" placeholder="Suchen..." onkeyup="vessel.searchVessel(this.value);">
    	<button class="ui icon button" onclick="vessel.searchVessel($('#searchInput').val());">
			<i class="search icon"></i>
      	</button>
    </div>
</div>
<div id="searchResult"></div>
<button onClick="vessel.newVessel(null, $('#searchInput').val());">Schiff hinzufügen</button>
<script type="text/javascript">vessel.searchVessel('');</script>
