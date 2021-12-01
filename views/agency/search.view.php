<div>
    <div class="ui action input">
    	<input type="text" id="searchInputAgency" placeholder="Suchen..." onkeyup="agency.searchAgency(this.value);">
    	<button class="ui icon button" onclick="agency.searchAgency($('#searchInput').val());">
			<i class="search icon"></i>
      	</button>
    </div>
</div>
<div id="searchResult"></div>
<button onClick="agency.newAgency(null, $('#searchInputAgency').val());">Agentur hinzufügen</button>
<script type="text/javascript">agency.searchAgency('');</script>
