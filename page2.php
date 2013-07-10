
<?php  
$paginate = '';
	if($lastpage > 1)
	{	
	  
		$paginate .= "<div class='paginate'>";
		// Previous
		if ($page > 1){
			$paginate.= "<a href='$targetpage?tab=$tab_name&page=$prev$additional_params'>previous</a>";
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
					$paginate.= "<a href='$targetpage?tab=$tab_name&page=$counter$additional_params'>$counter</a>";}					
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
						$paginate.= "<a href='$targetpage?tab=$tab_name&page=$counter$additional_params'>$counter</a>";}					
				}
				$paginate.= "...";
				$paginate.= "<a href='$targetpage?tab=$tab_name&page=$LastPagem1$additional_params'>$LastPagem1</a>";
				$paginate.= "<a href='$targetpage?tab=$tab_name&page=$lastpage$additional_params'>$lastpage</a>";		
			}
			// Middle hide some front and some back
			elseif($lastpage - ($stages * 2) > $page && $page > ($stages * 2))
			{
				$paginate.= "<a href='$targetpage?tab=$tab_name&page=1$additional_params'>1</a>";
				$paginate.= "<a href='$targetpage?tab=$tab_name&page=2$additional_params'>2</a>";
				$paginate.= "...";
				for ($counter = $page - $stages; $counter <= $page + $stages; $counter++)
				{
					if ($counter == $page){
						$paginate.= "<span class='current'>$counter</span>";
					}else{
						$paginate.= "<a href='$targetpage?tab=$tab_name&page=$counter$additional_params'>$counter</a>";}					
				}
				$paginate.= "...";
				$paginate.= "<a href='$targetpage?tab=$tab_name&page=$LastPagem1$additional_params'>$LastPagem1</a>";
				$paginate.= "<a href='$targetpage?tab=$tab_name&page=$lastpage$additional_params'>$lastpage</a>";		
			}
			// End only hide early pages
			else
			{
				$paginate.= "<a href='$targetpage?tab=$tab_name&page=1$additional_params'>1</a>";
				$paginate.= "<a href='$targetpage?tab=$tab_name&page=2$additional_params'>2</a>";
				$paginate.= "...";
				for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++)
				{
					  if ($counter == $page){
						$paginate.= "<span class='current'>$counter</span>";
					}else{
						$paginate.= "<a href='$targetpage?tab=$tab_name&page=$counter$additional_params'>$counter</a>";}					
				}
			}
		}
					
				// Next
		if ($page < $counter - 1){ 
			$paginate.= "<a href='$targetpage?tab=$tab_name&page=$next$additional_params'>next</a>";
		}else{
			$paginate.= "<span class='disabled'>next</span>";
			}
			
		$paginate.= "</div>";		
	
	
}
 //echo $total_pages.' Results';
 // pagination
 echo $paginate;
 ?>