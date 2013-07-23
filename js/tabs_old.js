window.onload=function() {

  	var container = document.getElementById("tabContainer");
	var tabcon = document.getElementById("tabscontent");
    
	if (container) {
    
	/*Alex jQuery tabs*/
        $("#tabscontent .tabpage").each( function() { $(this).css("display","none"); }); 
	 
		$("."+selected_tab).each( function() { 
			if ($(this).hasClass('tabpage')) 
				$(this).css("display","block");
			else
				$(this).addClass("tabActiveHeader");
		});
		 // set current tab
	    var navitem = $('.tabActiveHeader').attr("id");
			 
	    //store which tab we are on
	    var ident = navitem.split("_")[1];
	    $('.tabActiveHeader').parent().attr("data-current",ident);
	/*Alex jQuery tabs*/
		
		
    //this adds click event to tabs
    var tabs = container.getElementsByTagName("li");
    for (var i = 0; i < tabs.length; i++) {
      tabs[i].onclick=displayPage;
    }
    
	}
}

// on click of one of tabs
function displayPage() {
  var current = this.parentNode.getAttribute("data-current");
  //remove class of activetabheader and hide old contents
  $("#tabHeader_" + current).removeClass("tabActiveHeader");
  document.getElementById("tabpage_" + current).style.display="none";
  
  var ident = this.id.split("_")[1];
  //add class of activetabheader to new active tab and show contents
  var tab_class=$(this).attr('class');
  selected_tab=tab_class;
  $(this).addClass("tabActiveHeader");
  document.getElementById("tabpage_" + ident).style.display="block";
  this.parentNode.setAttribute("data-current",ident);
}