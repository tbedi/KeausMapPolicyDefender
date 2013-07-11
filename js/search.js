        window.tableSearch = {};     
        tableSearch.init = function() {
           
            this.Rows = document.getElementById('data').getElementsByTagName('TR');
            this.RowsLength = tableSearch.Rows.length;
            this.RowsText = [];
           
         
            for (var i = 0; i < tableSearch.RowsLength; i++) {
                this.RowsText[i] = (tableSearch.Rows[i].innerText) ? tableSearch.Rows[i].innerText.toUpperCase() : tableSearch.Rows[i].textContent.toUpperCase();
            }
        }
      	
                
        tableSearch.runSearch = function() {
         
            this.Term = document.getElementById('textBoxSearch').value.toUpperCase();
           
          
            for (var i = 0, row; row = this.Rows[i], rowText = this.RowsText[i]; i++) {
                row.style.display = ((rowText.indexOf(this.Term) != -1) || this.Term === '') ? '' : 'none';
            }
        }
		
		
		 
        tableSearch.search = function(e) {
           
            var keycode;
            if (window.event) { keycode = window.event.keyCode; }
            else if (e) { keycode = e.which; }
            else { return false; }
            if (keycode == 13) {
                tableSearch.runSearch();
            }
            else { return false; }
        }
