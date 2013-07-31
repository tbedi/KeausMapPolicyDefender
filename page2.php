
<?php  
$stages=3;
$paginate = '';
// $page_param=( $page_param ? $page_param :  "page" );
$page_param=((!$page_param) ? "page" : $page_param );
	if($lastpage > 1)
	{	
	  
		$paginate .= "<div class='paginate'>";
		// Previous
		if ($page > 1){
			$paginate.= "<a onclick=\"switch_page(".$prev.",'".$page_param."')\" href='javascript:void(0);'>previous</a>";
		}else{
			$paginate.= "<span class='disabled'>previous</span>";	}
			

		
		// Pages	
		if ($lastpage < 7 + ($stages * 2))	// Not enough pages to breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page){
					$paginate.= "<span class='current'>$counter</span>";
				}else{
					$paginate.= "<a onclick=\"switch_page(".$counter.",'".$page_param."')\" href='javascript:void(0);'>$counter</a>";}					
			}
		}
		elseif($lastpage > 5 + ($stages * 2))	// Enough pages to hide a few?
		{
			// Beginning only hide later pages
			if($page < 1 + ($stages * 2))		
			{
				for ($counter = 1; $counter < 4 + ($stages * 2); $counter++)
				{
					if ($counter == $page){
						$paginate.= "<span class='current'>$counter</span>";
					}else{
						$paginate.= "<a onclick=\"switch_page(".$counter.",'".$page_param."')\" href='javascript:void(0);'>$counter</a>";}					
				}
				$paginate.= "...";
				$paginate.= "<a onclick=\"switch_page(".$LastPagem1.",'".$page_param."')\" href='javascript:void(0);'>$LastPagem1</a>";
				$paginate.= "<a onclick=\"switch_page(".$lastpage.",'".$page_param."')\" href='javascript:void(0);'>$lastpage</a>";		
			}
			// Middle hide some front and some back
			elseif($lastpage - ($stages * 2) > $page && $page > ($stages * 2))
			{
				$paginate.= "<a onclick=\"switch_page(1,'".$page_param."')\" href='javascript:void(0);'>1</a>";
				$paginate.= "<a onclick=\"switch_page(2,'".$page_param."')\" href='javascript:void(0);'>2</a>";
				$paginate.= "...";
				for ($counter = $page - $stages; $counter <= $page + $stages; $counter++)
				{
					if ($counter == $page){
						$paginate.= "<span class='current'>$counter</span>";
					}else{
						$paginate.= "<a onclick=\"switch_page(".$counter.",'".$page_param."')\"  href='javascript:void(0);' >$counter</a>";}					
				}
				$paginate.= "...";
				$paginate.= "<a onclick=\"switch_page(".$LastPagem1.",'".$page_param."')\"   href='javascript:void(0);'  >$LastPagem1</a>";
				$paginate.= "<a onclick=\"switch_page(".$lastpage.",'".$page_param."')\"   href='javascript:void(0);' >$lastpage</a>";		
			}
			// End only hide early pages
			else
			{
				$paginate.= "<a onclick=\"switch_page(1,'".$page_param."')\"   href='javascript:void(0);' >1</a>";
				$paginate.= "<a onclick=\"switch_page(2,'".$page_param."')\"   href='javascript:void(0);'  >2</a>";
				$paginate.= "...";
				for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++)
				{
					  if ($counter == $page){
						$paginate.= "<span class='current'>$counter</span>";
					}else{
						$paginate.= "<a onclick=\"switch_page(".$counter.",'".$page_param."')\" href='javascript:void(0);' >$counter</a>";}					
				}
			}
		}
					
				// Next
		if ($page < $counter - 1){ 
			$paginate.= "<a onclick=\"switch_page(".$next.",'".$page_param."')\" href='javascript:void(0);' >next</a>";
		}else{
			$paginate.= "<span class='disabled'>next</span>";
			}
			
		$paginate.= "</div>";		
	
	
}
 //echo $total_pages.' Results';
 // pagination
 echo $paginate;
 ?>