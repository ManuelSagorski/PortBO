<div>
    <div class="ui action input">
    	<input type="text" id="searchInputVessel" placeholder="Search..." onkeyup="lookup.searchVessel(this.value);" autocomplete="off">
    	<button class="ui icon button" onclick="lookup.searchVessel($('#searchInputVessel').val());">
			<i class="search icon"></i>
      	</button>
    </div>
</div>
<div id="searchResult"></div>