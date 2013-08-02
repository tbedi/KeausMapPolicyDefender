<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to customerservice@kraususa.com so we can send you a copy immediately.
 *
 * @author Alex Lukyanov
 * @copyright   Copyright (c) 2013 Kraus USA. (http://www.kraususa.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Created: Aug 2, 2013
 *
 */
class Pagination {
	
	/**
	 * @param int  $page
	 * @param int  $total_pages
	 * @param int  $limit
	 * @param string  $page_param
	 * @return string
	 */
	public function GenerateHTML($page,$total_pages,$limit,$page_param) {
		$html = "";
		
		/*Calculate Values*/
		$prev = $page - 1;
		$next = $page + 1;
		$lastpage = ceil ( $total_pages / $limit );
		$LastPagem1 = $lastpage - 1;
		$stages = 3;
		/*Calculate Values*/
		
		if ($lastpage > 1) {
			
			$html .= "<div class='paginate'>";
			// Previous
			if ($page > 1) {
				$html .= "<a onclick=\"switch_page(" . $prev . ",'" . $page_param . "')\" href='javascript:void(0);'>previous</a>";
			} else {
				$html .= "<span class='disabled'>previous</span>";
			}
			
			// Pages
			if ($lastpage < 7 + ($stages * 2)) 			// Not enough pages to breaking it up
			{
				for($counter = 1; $counter <= $lastpage; $counter ++) {
					if ($counter == $page) {
						$html .= "<span class='current'>$counter</span>";
					} else {
						$html .= "<a onclick=\"switch_page(" . $counter . ",'" . $page_param . "')\" href='javascript:void(0);'>$counter</a>";
					}
				}
			} elseif ($lastpage > 5 + ($stages * 2)) // Enough pages to hide a few?
			{
				// Beginning only hide later pages
				if ($page < 1 + ($stages * 2)) {
					for($counter = 1; $counter < 4 + ($stages * 2); $counter ++) {
						if ($counter == $page) {
							$html .= "<span class='current'>$counter</span>";
						} else {
							$html .= "<a onclick=\"switch_page(" . $counter . ",'" . $page_param . "')\" href='javascript:void(0);'>$counter</a>";
						}
					}
					$html .= "...";
					$html .= "<a onclick=\"switch_page(" . $LastPagem1 . ",'" . $page_param . "')\" href='javascript:void(0);'>$LastPagem1</a>";
					$html .= "<a onclick=\"switch_page(" . $lastpage . ",'" . $page_param . "')\" href='javascript:void(0);'>$lastpage</a>";
				}				// Middle hide some front and some back
				elseif ($lastpage - ($stages * 2) > $page && $page > ($stages * 2)) {
					$html .= "<a onclick=\"switch_page(1,'" . $page_param . "')\" href='javascript:void(0);'>1</a>";
					$html .= "<a onclick=\"switch_page(2,'" . $page_param . "')\" href='javascript:void(0);'>2</a>";
					$html .= "...";
					for($counter = $page - $stages; $counter <= $page + $stages; $counter ++) {
						if ($counter == $page) {
							$html .= "<span class='current'>$counter</span>";
						} else {
							$html .= "<a onclick=\"switch_page(" . $counter . ",'" . $page_param . "')\"  href='javascript:void(0);' >$counter</a>";
						}
					}
					$html .= "...";
					$html .= "<a onclick=\"switch_page(" . $LastPagem1 . ",'" . $page_param . "')\"   href='javascript:void(0);'  >$LastPagem1</a>";
					$html .= "<a onclick=\"switch_page(" . $lastpage . ",'" . $page_param . "')\"   href='javascript:void(0);' >$lastpage</a>";
				} 				// End only hide early pages
				else {
					$html .= "<a onclick=\"switch_page(1,'" . $page_param . "')\"   href='javascript:void(0);' >1</a>";
					$html .= "<a onclick=\"switch_page(2,'" . $page_param . "')\"   href='javascript:void(0);'  >2</a>";
					$html .= "...";
					for($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter ++) {
						if ($counter == $page) {
							$html .= "<span class='current'>$counter</span>";
						} else {
							$html .= "<a onclick=\"switch_page(" . $counter . ",'" . $page_param . "')\" href='javascript:void(0);' >$counter</a>";
						}
					}
				}
			}
			
			// Next
			if ($page < $counter - 1) {
				$html .= "<a onclick=\"switch_page(" . $next . ",'" . $page_param . "')\" href='javascript:void(0);' >next</a>";
			} else {
				$html .= "<span class='disabled'>next</span>";
			}
			
			$html .= "</div>";
		}
		
		return $html;
	}
}
?>