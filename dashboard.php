<?php 
//dashboard page
$sql1 = "SELECT 
website.name name, crawl_results.website_id, count(crawl_results.website_id) countcurrent, tab1.countprev countprev, count(crawl_results.website_id)-tab1.countprev diff, count(crawl_results.website_id)+tab1.countprev countsum
from website
inner join
crawl_results
on website.id = crawl_results.website_id
inner join crawl
on crawl.id=crawl_results.crawl_id
and crawl_results.crawl_id = (select max(id) from crawl)
inner join
(
SELECT
website.name name, count(crawl_results.website_id) countprev
from website
inner join
crawl_results
on website.id = crawl_results.website_id
inner join crawl
on crawl.id=crawl_results.crawl_id
and crawl_results.crawl_id = (select max(id) from crawl where id != (select max(id) from crawl))
group by website.name
) as tab1 on tab1.name = website.name
group by website.name
order by countcurrent desc limit 10
";
$resultDls = mysql_query($sql1);

$sql2 = "select
catalog_product_flat_1.sku, crawl_results.product_id, 
count(crawl_results.product_id) as currentcount, if(tab1.prevcount is null,0,tab1.prevcount) prevcount, count(crawl_results.product_id)-if(tab1.prevcount is null,0,tab1.prevcount) diff,
count(crawl_results.product_id)+if(tab1.prevcount is null,0,tab1.prevcount) countsum
from crawl_results inner join catalog_product_flat_1 on crawl_results.product_id = catalog_product_flat_1.entity_id
inner join crawl on crawl.id = crawl_results.crawl_id and crawl_results.crawl_id = (select max(id) from crawl) left join ( select
catalog_product_flat_1.sku sku1, count(crawl_results.product_id) as prevcount from crawl_results inner join catalog_product_flat_1 on crawl_results.product_id = catalog_product_flat_1.entity_id
inner join crawl on crawl.id = crawl_results.crawl_id and crawl_results.crawl_id = (select max(id) from crawl where id != (select max(id) from crawl))
and crawl_results.violation_amount>0.05
group by crawl_results.product_id ) as tab1 on tab1.sku1 = catalog_product_flat_1.sku group by crawl_results.product_id order by currentcount desc limit 10";
$resulttop = mysql_query($sql2);

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
crawl.id =
(select max(crawl.id) from crawl) order by violation_amount desc
limit 10";
$result3 = mysql_query($sql3);

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
group by crawl_results.product_id limit 0,10";
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
group by crawl_results.product_id limit 0,10";
            $resulta = mysql_query($sql);
            $resultb = mysql_query($sqll);
            $array=array();
            $array2=array();
            $resultst = array();
           while($row = mysql_fetch_array($resulta))
           {
                   
                       array_push($array,$row['sku']);
            };
           while($row2 = mysql_fetch_array($resultb)){
               array_push($array2,$row2['sku']);  
           };
               
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
group by crawl_results.product_id ";
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
group by crawl_results.product_id ";
            $resultc = mysql_query($sql);
            $resultd = mysql_query($sqll);
            $array=array();
            $array2=array();
            $resultstrt = array();
           while($row = mysql_fetch_array($resultc))
           {
                   
                       array_push($array,$row['sku']);
            };
           while($row2 = mysql_fetch_array($resultd)){
               array_push($array2,$row2['sku']);  
           };
               
             $resultstrt = array_diff($array,$array2);
             
$sql = "SELECT website.name name from website
        inner join
    crawl_results ON website.id = crawl_results.website_id
        inner join
    crawl ON crawl.id = crawl_results.crawl_id
        and crawl_results.crawl_id not in (select id from crawl
where date_format(date_executed, '%Y-%m-%d')
between DATE_ADD(sysdate(), INTERVAL -3 DAY) and sysdate())
group by website.name ";
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
group by website.name ";
            $resulte = mysql_query($sql);
            $resultf = mysql_query($sqll);
            $array=array();
            $array2=array();
            $resultstv = array();
           while($row = mysql_fetch_array($resulte))
           {
                   
                       array_push($array,$row['name']);
            };
           while($row2 = mysql_fetch_array($resultf)){
               array_push($array2,$row2['name']);  
           };
               
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
group by website.name ";
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
group by website.name";
            
            $resultg = mysql_query($sql);
            $resulth = mysql_query($sqll);
            $array=array();
            $array2=array();
            $resultstrtv = array();
           while($row = mysql_fetch_array($resultg))
           {
                   
                       array_push($array,$row['name']);
            };
           while($row2 = mysql_fetch_array($resulth)){
               
               array_push($array2,$row2['name']);
               
           };
               
             $resultstrtv = array_diff($array,$array2);

include_once 'template/dashboard_tab.phtml';
?>