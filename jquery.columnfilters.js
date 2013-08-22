
(function($){

	$.fn.columnFilters = function(settings) {
		var defaults = {  
			wildCard: "*",  
			notCharacter: "!",
			caseSensitive: false,
			minSearchCharacters: 1,
			excludeColumns: [],
			alternateRowClassNames: [],
			underline: false
			};  
		settings = $.extend(defaults, settings);  
	
		return this.each(function() {
		
			function regexEscape(txt, omit) {
				var specials = ['/', '.', '*', '+', '?', '|',
								'(', ')', '[', ']', '{', '}', '\\'];
				
				if (omit) {
					for (var i=0; i < specials.length; i++) {
						if (specials[i] === omit) { specials.splice(i,1); }
					}
				}
				
				var escapePatt = new RegExp('(\\' + specials.join('|\\') + ')', 'g');
				return txt.replace(escapePatt, '\\$1');
			}
		
			var obj = $(this),
				filterRow = document.createElement('tr'),
				wildCardPatt = new RegExp(regexEscape(settings.wildCard || ''),'g'),
				filter;
		
			function addClassToColumn(iColNum, sClassName) {
				$('tbody:first tr', obj).each(
					function() {
						$('td', this).each(
							function(iCellCount) {
								if (iCellCount === iColNum) {
									$(this).addClass(sClassName);
								}
							});
					}
				);
			}
		
			function runFilters(event) {			
				$('input._filterText', obj).each(
					function(iColCount) {
						var sFilterTxt = (!settings.wildCard) ? regexEscape(this.value) : regexEscape(this.value, settings.wildCard).replace(wildCardPatt, '.*'),
							bMatch = true, 
							sFirst = settings.alternateRowClassNames[0] || '',
							sSecound = settings.alternateRowClassNames[1] || '',
							bOddRow = true;
						
						if (settings.notCharacter && sFilterTxt.indexOf(settings.notCharacter) === 0) {
							sFilterTxt = sFilterTxt.substr(settings.notCharacter.length,sFilterTxt.length);
							if (sFilterTxt.length > 0) { bMatch = false; }
						}
						if (sFilterTxt.length < settings.minSearchCharacters) {
							sFilterTxt = '';
						}
						sFilterTxt = sFilterTxt || '.*';
						sFilterTxt = settings.wildCard ? '^' + sFilterTxt : sFilterTxt;
						var filterPatt = settings.caseSensitive ? new RegExp(sFilterTxt) : new RegExp(sFilterTxt,"i");
						
						$('tbody:first tr', obj).each(
							function() {
								$('td',this).each(
									function(iCellCount) {
										if (iCellCount === iColCount) {
											var sVal = $(this).text().replace(/(\n)|(\r)/ig,'').replace(/\s\s/ig,' ').replace(/^\s/ig,'');
											$(this).removeClass('_match');
											if (filterPatt.test(sVal) === bMatch) {
												$(this).addClass('_match');
											}
										}
									}
								);
								
								if ($('td',this).length !== $('td._match',this).length) {
									$(this).css('display','none');
								}
								else {
									$(this).css('display','');
									if (settings.alternateRowClassNames && settings.alternateRowClassNames.length) {
										$(this).removeClass(sFirst).removeClass(sSecound).addClass((bOddRow) ? sFirst : sSecound);
										bOddRow = !bOddRow;
									}
								}
							}
						);
						
						if (settings.underline) {
							$(this).css('text-decoration','');
						}
					}
				);
			}
			
			function genAlternateClassNames() {
				if (settings.alternateRowClassNames && settings.alternateRowClassNames.length) {
					var sFirst = settings.alternateRowClassNames[0] || '',
						sSecound = settings.alternateRowClassNames[1] || '',
						bOddRow = true;
					
					$('tbody:first tr', obj).each(
						function() {
							if ($(this).css('display') !== 'none') {
								$(this).removeClass(sFirst).removeClass(sSecound);
								$(this).addClass((bOddRow) ? sFirst : sSecound);
								bOddRow = !bOddRow;
							}
						}
					);
				}
			}
				
			$('tbody:first tr:first td', obj).each(
				function(iColCount) {
					var filterColumn = document.createElement('td'),
						filterBox = document.createElement('input');
					
					$(filterBox).attr('type','text').attr('id','_filterText' + iColCount).addClass('_filterText');
					$(filterBox).keyup(
						function() { 
							clearTimeout(filter); 
							filter = setTimeout(runFilters, $('tbody:first tr', obj).length*2);
							if (settings.underline) {
								$(filterBox).css('text-decoration','underline');
							}
						}
					);
					$(filterColumn).append(filterBox);
					$(filterRow).append(filterColumn);
					
					if (settings.excludeColumns && settings.excludeColumns.length) {
						for (var i=0; i < settings.excludeColumns.length; i++) {
							if (settings.excludeColumns[i] === iColCount) {
								$(filterBox).css('display','none');
							}
						}
					}
					
					addClassToColumn(iColCount, '_filterCol' + iColCount);
				}
			);
				
			$(filterRow).addClass('filterColumns');
			$('thead:first', obj).append(filterRow);
			genAlternateClassNames();
			settings.notCharacter = regexEscape(settings.notCharacter || '');
		});
	};

})(jQuery);