<?php
/*For other pages*/
$date = "select date_executed FROM crawl group by date_format(date_executed, '%Y-%m-%d') ORDER BY id DESC LIMIT 0,2";
//$date = "select date_executed  FROM crawl  ORDER BY id DESC  LIMIT 0,2"; //query for fetching current and previous date

$datecp = $db_resource->GetResultObj($date); // Used in history.php
/*For other pages*/
/*Getting last Crawl Id*/
$sql = "select id FROM crawl  ORDER BY id DESC  LIMIT 1"; //query used to fetch max id
$last_crawl = $db_resource->GetResultObj($sql);
$last_crawl = $last_crawl[0]->id;
/*Getting last Crawl Id*/
/*Getting Previous Crawil Id*/
$sqlcwl = "SELECT id FROM crawl ORDER BY id DESC LIMIT 1,1"; //query used to fetch max-1 id
$last_crawl1 = $db_resource->GetResultObj($sqlcwl);
$previous_crawl_id=$last_crawl1[0]->id;
/*Getting Previous Crawil Id*/
 
/*top violations by dealer */
$sql = " SELECT SQL_CALC_FOUND_ROWS website_id, website.name name, count(crawl_results.website_id) countcurrent
		FROM website
		INNER JOIN crawl_results ON website.id = crawl_results.website_id
		INNER JOIN crawl ON crawl.id=crawl_results.crawl_id 
			AND crawl_results.crawl_id = " . $last_crawl . "  
			AND crawl_results.violation_amount>0.05
			AND website.excluded=0 
		GROUP BY website_id, website.name
		ORDER BY countcurrent DESC
		LIMIT 10 ";
 
$dashh_array = $db_resource->GetResultObj($sql); //current array

$sql1 = "SELECT FOUND_ROWS() as total";
$current_total_violations_by_dealer = $db_resource->GetResultObj($sql1);
$current_total_violations_by_dealer = $current_total_violations_by_dealer[0]->total;
 
$current_website_ids=array();
foreach ($dashh_array as $dealer){
	$current_website_ids[]=$dealer->website_id;
}
$current_website_ids=implode($current_website_ids,",");
 
$sqld = "SELECT  website_id, count(crawl_results.website_id) countprev
		 FROM website
		 INNER JOIN crawl_results ON website.id = crawl_results.website_id
		 INNER JOIN crawl ON crawl.id=crawl_results.crawl_id
			AND crawl_results.crawl_id = " . $previous_crawl_id . "
			AND crawl_results.violation_amount>0.05
			AND website.excluded=0
			AND website_id IN (".$current_website_ids.")
		 GROUP BY website_id, website.name";
 
$dashh1_array = $db_resource->GetResultObj($sqld); //previous array
$newArray=array(); 
foreach ($dashh_array as $cur_dealer) {
	$res=array();
	$res['website_id']=$cur_dealer->website_id;
	$res['name']=$cur_dealer->name;
	$res['countcurrent']=$cur_dealer->countcurrent;
	foreach ($dashh1_array as $prev_dealer) {
		if ($prev_dealer->website_id==$cur_dealer->website_id) {
			$res['countprev']=$prev_dealer->countprev;
			break;
		} 
	}
	if (!isset($res['countprev']))
		$res['countprev']=0;
	array_push($newArray,$res);
}
/*top violations by dealer */
/*Top violations by Sku*/
$sqlc = "SELECT SQL_CALC_FOUND_ROWS catalog_product_flat_1.sku sku1, crawl_results.product_id, count(crawl_results.product_id) as currentcount
		 FROM crawl_results
		 INNER JOIN catalog_product_flat_1 ON crawl_results.product_id = catalog_product_flat_1.entity_id
		 INNER JOIN crawl ON crawl.id = crawl_results.crawl_id 
			AND crawl_results.crawl_id = " . $last_crawl . "
		 INNER JOIN website ON website.id=crawl_results.website_id
		WHERE crawl_results.violation_amount > 0.05			
			AND website.excluded=0
		GROUP BY crawl_results.product_id, catalog_product_flat_1.sku
		ORDER BY currentcount desc
		LIMIT 10 ";

$dashc_array = $db_resource->GetResultObj($sqlc);


$sql1 = "SELECT FOUND_ROWS() as total";
$current_total_violations_by_product = $db_resource->GetResultObj($sql1);
$current_total_violations_by_product = $current_total_violations_by_product[0]->total;

$current_productids=array();
foreach ($dashc_array as $product){
	$current_productids[]=$product->product_id;
}
$current_productids=implode($current_productids,",");
 
$sqlp = "SELECT crawl_results.product_id, count(crawl_results.product_id) as prevcount
		 FROM crawl_results
		 INNER JOIN catalog_product_flat_1 ON crawl_results.product_id = catalog_product_flat_1.entity_id
		 INNER JOIN crawl ON crawl.id = crawl_results.crawl_id 
			AND crawl_results.crawl_id = " . $previous_crawl_id . "
		 INNER JOIN website ON website.id=crawl_results.website_id				
		WHERE crawl_results.violation_amount > 0.05
		    AND crawl_results.violation_amount>0.05
			AND website.excluded=0
			AND crawl_results.product_id IN (".$current_productids.")
		GROUP BY crawl_results.product_id, catalog_product_flat_1.sku  ";

$dashp_array = $db_resource->GetResultObj($sqlp);

$viosku = Array();
foreach ($dashc_array as $cur_product) {
	$res=array();
	$res['product_id']=$cur_product->product_id;
	$res['sku1']=$cur_product->sku1;
	$res['currentcount']=$cur_product->currentcount;
	foreach ($dashp_array as $prev_product) {
		if ($prev_product->product_id==$cur_product->product_id) {
			$res['prevcount']=$prev_product->prevcount;
			break;
		}
	}
	if (!isset($res['prevcount']))
		$res['prevcount']=0;
	array_push($viosku,$res);
}
 
/*Top violations by Sku*/

//violations amount by sku query
$sql3 = "SELECT catalog_product_flat_1.sku, catalog_product_flat_1.entity_id, crawl_results.violation_amount as violation_amount
		 FROM website
		 INNER JOIN prices.crawl_results ON prices.website.id = prices.crawl_results.website_id
		 INNER JOIN catalog_product_flat_1 ON catalog_product_flat_1.entity_id=crawl_results.product_id
		 INNER JOIN crawl ON crawl.id=crawl_results.crawl_id WHERE crawl_results.violation_amount>0.05
			AND website.excluded=0
			AND crawl.id = " . $last_crawl . " ORDER BY violation_amount desc
		LIMIT 10";   
$dash1_array = $db_resource->GetResultObj($sql3);

/*New & Old violations*/
$sql = "SELECT catalog_product_flat_1.sku, catalog_product_flat_1.entity_id
		FROM crawl_results
		INNER JOIN crawl ON crawl.id = crawl_results.crawl_id
		INNER JOIN catalog_product_flat_1 ON catalog_product_flat_1.entity_id = crawl_results.product_id
			AND crawl_results.crawl_id = " . $last_crawl . " 
			AND crawl_results.violation_amount > 0.05
		INNER JOIN website ON website.id=crawl_results.website_id	
		    AND website.excluded=0					
		GROUP BY crawl_results.product_id";

$sqll = "SELECT catalog_product_flat_1.sku, catalog_product_flat_1.entity_id
		FROM crawl_results
		INNER JOIN crawl ON crawl.id = crawl_results.crawl_id
		INNER JOIN catalog_product_flat_1 ON catalog_product_flat_1.entity_id = crawl_results.product_id
			AND crawl_results.crawl_id = " . $previous_crawl_id . "
			AND crawl_results.violation_amount > 0.05
		INNER JOIN website ON website.id=crawl_results.website_id	
			AND website.excluded=0				
		GROUP BY crawl_results.product_id";

$dash2_array = $db_resource->GetResultObj($sql);
$dash3_array = $db_resource->GetResultObj($sqll);
$array = array();
$array2 = array();
$resultst = array();
foreach ($dash2_array as $dash2) {
    $array[$dash2->entity_id]=$dash2->sku;
}
foreach ($dash3_array as $dash3) {
    $array2[$dash3->entity_id]=$dash3->sku;
}

$resultst = array_diff_key($array2, $array);
$resultstrt=array_diff_key($array, $array2);
/*New & Old violations*/

/*New & Old Dealers*/ 
$sql="SELECT website.name name, website.id
 FROM crawl_results r
 INNER JOIN website ON website.id=r.website_id		 		 
WHERE r.crawl_id= " . $last_crawl . "
	 AND r.violation_amount>0.05
	 AND website.excluded=0
GROUP BY r.website_id";

$sqll = "SELECT website.name name, website.id 
 FROM crawl_results r
 INNER JOIN website ON website.id=r.website_id		 		
WHERE r.crawl_id=" . $previous_crawl_id . "
	 AND r.violation_amount>0.05
	 AND website.excluded=0
GROUP BY r.website_id";

$dash6_array = $db_resource->GetResultObj($sql);
$dash7_array = $db_resource->GetResultObj($sqll);

$array = array();
$array2 = array();
 
foreach ($dash6_array as $dash6) {
     $array[$dash6->id]=$dash6->name;
}
foreach ($dash7_array as $dash7) {
     $array2[$dash7->id]=$dash7->name;
}

$resultstv = array_diff_key($array2, $array);  
$resultstrtv = array_diff_key($array, $array2);
include_once 'template/dashboard_tab.phtml';
?>