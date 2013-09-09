<?php
//dashboard page


$sql = "select id, date_executed  from crawl  ORDER BY id DESC  LIMIT 1";
$result = mysql_query($sql);
$last_crawl = mysql_fetch_assoc($result);

$sqlcwl = "SELECT id FROM crawl ORDER BY id DESC LIMIT 1,1";
$result1 = mysql_query($sqlcwl);
$last_crawl1 = mysql_fetch_assoc($result1);

$sqldays = "select id from crawl order by id desc limit 1,3";
$resultday = $db_resource->GetResultObj($sqldays);
$s='';
foreach ($resultday as $da) {
$s = $s.$da->id.',';
}
$s=  substr($s, 0, strlen($s)-1);

//top violations by dealer query

$sql = "SELECT website_id,
website.name name, count(crawl_results.website_id) countcurrent
from website
inner join
crawl_results
on website.id = crawl_results.website_id
inner join crawl
on crawl.id=crawl_results.crawl_id
and crawl_results.crawl_id = " . $last_crawl['id'] . " 
and crawl_results.violation_amount>0.05 and website.excluded=0 
group by website_id, website.name
order by countcurrent desc limit 8";

$sqld = "SELECT  website_id,
website.name name, count(crawl_results.website_id) countprev
from website
inner join
crawl_results
on website.id = crawl_results.website_id
inner join crawl
on crawl.id=crawl_results.crawl_id
and crawl_results.crawl_id = " . $last_crawl1['id'] . "
and crawl_results.violation_amount>0.05 and website.excluded=0
group by website_id, website.name order by countprev desc";

$dashh_array = $db_resource->GetResultObj($sql);
$dashh1_array = $db_resource->GetResultObj($sqld);

foreach ($dashh_array as $k => $val) {
    foreach ($val as $k1 => $val1) {
        foreach ($dashh1_array as $kx => $valx) {
            foreach ($valx as $kx1 => $valx1) {
                if ($k1 == $kx1 && $val1 == $valx1) {
                    $newArray[$k] = array_merge((array) $val, (array) $dashh_array[$k], (array) $valx);
                }
            }
        }
    }
}

$sqlc = "select
catalog_product_flat_1.sku sku1,
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
group by crawl_results.product_id, catalog_product_flat_1.sku
order by currentcount desc limit 8";

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
group by crawl_results.product_id, catalog_product_flat_1.sku
order by prevcount desc ";


$dashc_array = $db_resource->GetResultObj($sqlc);
$dashp_array = $db_resource->GetResultObj($sqlp);

$viosku = Array();
foreach ($dashc_array as $k => $val) {

    foreach ($val as $k2 => $val2) {
        foreach ($dashp_array as $ky => $valy) {
            foreach ($valy as $ky2 => $valy2) {
                if ($k2 == $ky2 && $val2 == $valy2) {
                    $viosku[$k] = array_merge((array) $val, (array) $dashc_array[$k], (array) $valy);
                }
            }
        }
    }
}

//violations amount by sku query

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
and
crawl.id = " . $last_crawl['id'] . " order by violation_amount desc
limit 8";
$dash1_array = $db_resource->GetResultObj($sql3);

//Stopped Violations SKU query

$sql = "select
catalog_product_flat_1.sku
from
crawl_results
inner join
crawl ON crawl.id = crawl_results.crawl_id
inner join
catalog_product_flat_1 ON catalog_product_flat_1.entity_id = crawl_results.product_id
and crawl_results.crawl_id in (".$s.")
and crawl_results.violation_amount > 0.05
group by crawl_results.product_id
";

$sqll = "select
catalog_product_flat_1.sku
from
crawl_results
inner join
crawl ON crawl.id = crawl_results.crawl_id
inner join
catalog_product_flat_1 ON catalog_product_flat_1.entity_id = crawl_results.product_id
and crawl_results.crawl_id = " . $last_crawl['id'] . "
and crawl_results.violation_amount > 0.05
group by crawl_results.product_id
";
$dash2_array = $db_resource->GetResultObj($sql);
$dash3_array = $db_resource->GetResultObj($sqll);
$array = array();
$array2 = array();
$resultst = array();
foreach ($dash2_array as $dash2) {
    array_push($array, $dash2->sku);
}
foreach ($dash3_array as $dash3) {
    array_push($array2, $dash3->sku);
}

$resultst = array_diff($array, $array2);

//Started Violations SKU query

$sql = "select 
catalog_product_flat_1.sku
from
crawl_results
inner join
crawl ON crawl.id = crawl_results.crawl_id
inner join
catalog_product_flat_1 ON catalog_product_flat_1.entity_id = crawl_results.product_id
and crawl_results.crawl_id = " . $last_crawl['id'] . " 
and crawl_results.violation_amount > 0.05
group by crawl_results.product_id ";
$sqll = "select 
catalog_product_flat_1.sku
from
crawl_results
inner join
crawl ON crawl.id = crawl_results.crawl_id
inner join
catalog_product_flat_1 ON catalog_product_flat_1.entity_id = crawl_results.product_id
and crawl_results.crawl_id = " . $last_crawl1['id'] . "
and crawl_results.violation_amount > 0.05
group by crawl_results.product_id";

$dash4_array = $db_resource->GetResultObj($sql);
$dash5_array = $db_resource->GetResultObj($sqll);
$array = array();
$array2 = array();
$resultstrt = array();
foreach ($dash4_array as $dash4) {
    array_push($array, $dash4->sku);
}
foreach ($dash5_array as $dash5) {
    array_push($array2, $dash5->sku);
}

$resultstrt = array_diff($array, $array2);

//Stopped Violations Dealer query

$sql = "SELECT
website.name name
from
website
inner join
crawl_results ON website.id = crawl_results.website_id
inner join
crawl ON crawl.id = crawl_results.crawl_id
and crawl_results.crawl_id in (".$s.")
and website.excluded = 0
and crawl_results.violation_amount > 0.05
group by website.name
";
$sqll = "SELECT
website.name name
from
website
inner join
crawl_results ON website.id = crawl_results.website_id
inner join
crawl ON crawl.id = crawl_results.crawl_id
and crawl_results.crawl_id = " . $last_crawl['id'] . "
and website.excluded = 0
and crawl_results.violation_amount > 0.05
group by website.name
";
$dash6_array = $db_resource->GetResultObj($sql);
$dash7_array = $db_resource->GetResultObj($sqll);

$array = array();
$array2 = array();
$resultstv = array();
foreach ($dash6_array as $dash6) {
    array_push($array, $dash6->name);
}
foreach ($dash7_array as $dash7) {
    array_push($array2, $dash7->name);
}

$resultstv = array_diff($array, $array2);

//Started Violations Dealer

$sql = "SELECT 
    website.name name
from
    website
        inner join
    crawl_results ON website.id = crawl_results.website_id
        inner join
    crawl ON crawl.id = crawl_results.crawl_id
        and crawl_results.crawl_id = " . $last_crawl['id'] . " 
            and website.excluded = 0
and crawl_results.violation_amount > 0.05
 group by website.name ";

$sqll = "SELECT 
    website.name name
from
    website
        inner join
    crawl_results ON website.id = crawl_results.website_id
        inner join
    crawl ON crawl.id = crawl_results.crawl_id
        and crawl_results.crawl_id = " . $last_crawl1['id'] . " 
            and website.excluded = 0
and crawl_results.violation_amount > 0.05
            group by website.name ";

$dash8_array = $db_resource->GetResultObj($sql);
$dash9_array = $db_resource->GetResultObj($sqll);

$array = array();
$array2 = array();
$resultstrtv = array();
foreach ($dash8_array as $dash8) {
    array_push($array, $dash8->name);
}
foreach ($dash9_array as $dash9) {
    array_push($array2, $dash9->name);
}


$resultstrtv = array_diff($array, $array2);

include_once 'template/dashboard_tab.phtml';
?>
