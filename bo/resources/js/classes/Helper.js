define(function() {
	
	/*
	 * Klasse Calendar - stellt Funktionen rund um die Darstellung der Kalender zur Verfügung
	 */
	var Helper = function() {
		var constructor, that = {}, my = {};
	
		constructor = function() {
			return that;
		}

		/*
		 * Filtert eine Tabelle nach einem bestimmten Suchbegriff
		 */
		that.filterTable = function(inputID, tableID, spalte) {
			let input, filter, table, tr, td, i, txtValue;

			input = document.getElementById(inputID);
			filter = input.value.toUpperCase();
			table = document.getElementById(tableID);
			tr = table.getElementsByTagName("tr");

			for (i = 0; i < tr.length; i++) {
				td = tr[i].getElementsByTagName("td")[spalte];

				if (td) {
					txtValue = td.textContent || td.innerText;

					if (txtValue.toUpperCase().indexOf(filter) > -1) {
						tr[i].style.display = "";
					}
					else {
						tr[i].style.display = "none";
					}
				}
			}
		}

		/*
		 * Pagination für eine Tabelle erstellen
		 */
		that.generatePaginationForTable = function() {
			var perPage = 3;
			var tables = document.querySelectorAll(".pagination");

			for (let i = 0; i < tables.length; i++) {
				perPage = parseInt(tables[i].dataset.pagecount);
				that.createFooters(tables[i], perPage);
				that.createTableMeta(tables[i]);
				that.loadTable(tables[i]);
			}
		}
		that.loadTable = function(table) {
			var startIndex = 0;

			if (table.querySelector('th'))
				startIndex = 1;

			var start = (parseInt(table.dataset.currentpage) * table.dataset.pagecount) + startIndex;
			var end = start + parseInt(table.dataset.pagecount);
			var rows = table.rows;

			for (var x = startIndex; x < rows.length; x++) {
				if (x < start || x >= end)
					rows[x].style.display = "none";
				else
					rows[x].style.display = "";
			}
		}
		that.createTableMeta = function(table) {
			table.dataset.currentpage = "0";
		}
		that.createFooters = function(table, perPage) {
			var hasHeader = false;
			if (table.querySelector('th'))
				hasHeader = true;

			var rows = table.rows.length;

			if (hasHeader)
				rows = rows - 1;

			var numPages = rows / perPage;
			var pager = document.createElement("div");

			// add an extra page, if we're
			if (numPages % 1 > 0)
				numPages = Math.floor(numPages) + 1;

			pager.className = "pager";
			for (var i = 0; i < numPages ; i++) {
				var page = document.createElement("div");
				page.innerHTML = i + 1;
				page.className = "pager-item";
				page.dataset.index = i;

				if (i == 0)
					page.classList.add("selected");

				page.addEventListener('click', function() {
					var parent = this.parentNode;
					var items = parent.querySelectorAll(".pager-item");
					for (var x = 0; x < items.length; x++) {
						items[x].classList.remove("selected");
					}
					this.classList.add('selected');
					table.dataset.currentpage = this.dataset.index;
					helper.loadTable(table);
				});
				pager.appendChild(page);
			}

			// insert page at the top of the table
			table.parentNode.insertBefore(pager, table.nextSibling);
		}

		return constructor.call(null);
	}

	return Helper;
});