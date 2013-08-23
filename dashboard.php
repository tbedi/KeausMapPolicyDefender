<?php
//dashboard page
$sql = "select id, date_executed  from crawl  ORDER BY id DESC  LIMIT 1";
$result = mysql_query($sql);
$last_crawl = mysql_fetch_assoc($result);

$sqlcwl = "SELECT * FROM crawl ORDER BY id DESC LIMIT 1,1";
$result1 = mysql_query($sqlcwl);
$last_crawl1 = mysql_fetch_assoc($result1);


$sql = "SELECT 
website.name name, website_id, count(crawl_results.website_id) countcurrent
from website
inner join
crawl_results
on website.id = crawl_results.website_id
inner join crawl
on crawl.id=crawl_results.crawl_id
and crawl_results.crawl_id = " . $last_crawl['id'] . " 
and crawl_results.violation_amount>0.05 and website.excluded=0 
group by website.name, website_id
order by countcurrent desc limit 10";

$sqld = "SELECT
website.name name, website_id, count(crawl_results.website_id) countprev
from website
inner join
crawl_results
on website.id = crawl_results.website_id
inner join crawl
on crawl.id=crawl_results.crawl_id
and crawl_results.crawl_id = " . $last_crawl1['id'] . "
and crawl_results.violation_amount>0.05 and website.excluded=0
group by website.name, website_id order by countprev desc limit 10";

  $dashh_array = $db_resource->GetResultObj($sql);
  $dashh1_array = $db_resource->GetResultObj($sqld);
  
$newArray = Array();
foreach($dashh1_array as $k=>$val)
{
//        if ($val = NULL ){
//        $val= 0;
//    }
//    print_r ($val);
    if(array_key_exists($k, $dashh_array))
    {
        $newArray[$k] = array_merge((array)$val,(array)$dashh_array[$k]);
    }
    else{
        $newArray[$k] = array_merge((array)0,(array)$dashh_array[$k]);
        
    }
}
  
$sqlc = "select
catalog_product_flat_1.sku,
crawl_results.product_id,
count(crawl_results.product_id) as currentcount
from
crawl_results
inner join
catalog_product_flat_1 ON crawl_results.product_id = catalog_product_flat_1.entity_id
inner join
crawl ON crawl.id = crawl_results.crawl_id
and crawl_results.crawl_id = " . $last_crawl['id'] . " 
where
crawl_results.violation_amount > 0.05
group by crawl_results.product_id
order by currentcount desc limit 10";

$sqlp = "select
catalog_product_flat_1.sku sku1,
count(crawl_results.product_id) as prevcount
from
crawl_results
inner join
catalog_product_flat_1 ON crawl_results.product_id = catalog_product_flat_1.entity_id
inner join
crawl ON crawl.id = crawl_results.crawl_id
and crawl_results.crawl_id = " . $last_crawl1['id'] . "
where
crawl_results.violation_amount > 0.05
group by crawl_results.product_id
order by prevcount desc limit 10";


$dashc_array = $db_resource->GetResultObj($sqlc);
$dashp_array = $db_resource->GetResultObj($sqlp);
             $viosku = Array();
foreach($dashc_array as $k1=>$val1)
{
    if(array_key_exists($k1, $dashp_array))
    {
        $viosku[$k1] = array_merge((array)$val1,(array)$dashp_array[$k1]);
    }
}




//$sql2 = "select
//catalog_product_flat_1.sku, crawl_results.product_id,
//count(crawl_results.product_id) as currentcount, 
//if(tab1.prevcount is null,0,tab1.prevcount) prevcount, 
//count(crawl_results.product_id)-if(tab1.prevcount is null,0,tab1.prevcount) diff,
//count(crawl_results.product_id)+if(tab1.prevcount is null,0,tab1.prevcount) countsum
//from crawl_results inner join catalog_product_flat_1 
//on crawl_results.product_id = catalog_product_flat_1.entity_id
//inner join crawl on crawl.id = crawl_results.crawl_id 
//and crawl_results.crawl_id = " . $last_crawl['id'] . " left join ( select
//catalog_product_flat_1.sku sku1, 
//count(crawl_results.product_id) as prevcount from crawl_results 
//inner join catalog_product_flat_1 on crawl_results.product_id = catalog_product_flat_1.entity_id
//inner join crawl on crawl.id = crawl_results.crawl_id and crawl_results.crawl_id = " . $last_crawl1['id'] . "
//group by crawl_results.product_id ) as tab1 on tab1.sku1 = catalog_product_flat_1.sku 
//where crawl_results.violation_amount>0.05 group by crawl_results.product_id order by currentcount desc limit 10";
//$dash_array = $db_resource->GetResultObj($sql2);



$sql3 = "SELECT
catalog_product_flat_1.sku,
crawl_results.violation_amount as violation_amount
from website
inner join
prices.crawl_results
on prices.website.id = prices.crawl_results.website_id
inner join catalog_product_flat_1
on catalog_product_flat_1.entity_id=crawl_results.product_id
inner join
crawl
on crawl.id=crawl_results.crawl_id
where crawl_results.violation_amount>0.05
and
website.excluded=0
AND crawl.id = (SELECT id FROM crawl ORDER BY id DESC LIMIT 1)
and
crawl.id = " . $last_crawl['id'] . " order by violation_amount desc
limit 10";
$dash1_array = $db_resource->GetResultObj($sql3);

$sql = "select 
catalog_product_flat_1.sku
from
crawl_results
inner join
crawl ON crawl.id = crawl_results.crawl_id
inner join
catalog_product_flat_1 ON catalog_product_flat_1.entity_id = crawl_results.product_id
and crawl_results.crawl_id not in (select id from crawl
where date_format(date_executed, '%Y-%m-%d')
between DATE_ADD(sysdate(), INTERVAL -3 DAY) and sysdate())
group by crawl_results.product_id ";
             $sqll = "select 
catalog_product_flat_1.sku
from
crawl_results
inner join
crawl ON crawl.id = crawl_results.crawl_id
inner join
catalog_product_flat_1 ON catalog_product_flat_1.entity_id = crawl_results.product_id
and crawl_results.crawl_id in (select id from crawl
where date_format(date_executed, '%Y-%m-%d')
between DATE_ADD(sysdate(), INTERVAL -3 DAY) and sysdate())
group by crawl_results.product_id limit 10";
             $dash2_array = $db_resource->GetResultObj($sql);
             $dash3_array = $db_resource->GetResultObj($sqll);
            $array=array();
            $array2=array();
            $resultst = array();
            foreach ($dash2_array as $dash2) {
             array_push($array,$dash2->sku);
            }
            foreach ($dash3_array as $dash3) {
             array_push($array2,$dash3->sku);
            }
              
             $resultst = array_diff($array,$array2);
             
             $sql = "select 
catalog_product_flat_1.sku
from
crawl_results
inner join
crawl ON crawl.id = crawl_results.crawl_id
inner join
catalog_product_flat_1 ON catalog_product_flat_1.entity_id = crawl_results.product_id
and crawl_results.crawl_id in (select 
max(id)
from
crawl)
group by crawl_results.product_id limit 10 ";
             $sqll = "select 
catalog_product_flat_1.sku
from
crawl_results
inner join
crawl ON crawl.id = crawl_results.crawl_id
inner join
catalog_product_flat_1 ON catalog_product_flat_1.entity_id = crawl_results.product_id
and crawl_results.crawl_id in (select 
max(id)
from
crawl
where
id not in (select 
max(id)
from
crawl))
group by crawl_results.product_id limit 10";
             $dash4_array = $db_resource->GetResultObj($sql);
             $dash5_array = $db_resource->GetResultObj($sqll);
            $array=array();
            $array2=array();
            $resultstrt = array();
            foreach ($dash4_array as $dash4) {
             array_push($array,$dash4->sku);
            }
            foreach ($dash5_array as $dash5) {
             array_push($array2,$dash5->sku);
            }
               
             $resultstrt = array_diff($array,$array2);
             
$sql = "SELECT website.name name from website
        inner join
    crawl_results ON website.id = crawl_results.website_id
        inner join
    crawl ON crawl.id = crawl_results.crawl_id
        and crawl_results.crawl_id not in (select id from crawl
where date_format(date_executed, '%Y-%m-%d')
between DATE_ADD(sysdate(), INTERVAL -3 DAY) and sysdate())
group by website.name limit 10";
             $sqll = "SELECT 
    website.name name
from
    website
        inner join
    crawl_results ON website.id = crawl_results.website_id
        inner join
    crawl ON crawl.id = crawl_results.crawl_id
        and crawl_results.crawl_id in (select id from crawl
where date_format(date_executed, '%Y-%m-%d')
between DATE_ADD(sysdate(), INTERVAL -3 DAY) and sysdate())
group by website.name limit 10";
              $dash6_array = $db_resource->GetResultObj($sql);
             $dash7_array = $db_resource->GetResultObj($sqll);

            $array=array();
            $array2=array();
            $resultstv = array();
            foreach ($dash6_array as $dash6) {
             array_push($array,$dash6->name);
            }
            foreach ($dash7_array as $dash7) {
             array_push($array2,$dash7->name);
            }
               
             $resultstv = array_diff($array,$array2);
             
 $sql = "SELECT 
    website.name name
from
    website
        inner join
    crawl_results ON website.id = crawl_results.website_id
        inner join
    crawl ON crawl.id = crawl_results.crawl_id
        and crawl_results.crawl_id = (select 
            max(id)
        from
            crawl)
group by website.name limit 10";
             $sqll = "SELECT 
    website.name name
from
    website
        inner join
    crawl_results ON website.id = crawl_results.website_id
        inner join
    crawl ON crawl.id = crawl_results.crawl_id
        and crawl_results.crawl_id = (select 
            max(id)
        from
            crawl
where id !=(select max(id) from crawl))
group by website.name limit 10";
            $dash8_array = $db_resource->GetResultObj($sql);
             $dash9_array = $db_resource->GetResultObj($sqll);

            $array=array();
            $array2=array();
            $resultstrtv = array();
            foreach ($dash8_array as $dash8) {
             array_push($array,$dash8->name);
            }
            foreach ($dash9_array as $dash9) {
             array_push($array2,$dash9->name);
            }

               
             $resultstrtv = array_diff($array,$array2);

include_once 'template/dashboard_tab.phtml';
?>
