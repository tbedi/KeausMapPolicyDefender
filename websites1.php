<?php
$title = "Kraus Price Defender | websites1.php";
////pagination
$tableName = "website";
$targetpage = "websites.php";
$limit = 10;

$where = "";


$query = "SELECT COUNT(*) as num FROM $tableName";
$total_pages = mysql_fetch_array(mysql_query($query));
$total_pages = $total_pages['num'];

$stages = 3;
$page = 1;
if (isset($_GET['page'])) {
    $page = mysql_escape_string($_GET['page']);
    $start = ($page - 1) * $limit;
} else {
    $start = 0;
    $page = 1;
}
?>
<table class="table1" >
    <tr>
        <td >
            <div class="divt1">

               <input  class="recent_violation_search search" placeholder="Search here..." type="text" size="30"  maxlength="1000" value="<?php if (isset($_GET['action'])&& $_GET['action'] == 'search') echo $_GET['value'];  ?>"  id="textBoxSearch"    /> 
</div>
            <div class="divt2">
                <a href="javascript:void(0);" class="btn-search"  onclick="recent_violation_search();">Search</a>
            </div>
        </td>
        <td>
            <div class="results-per-page" style="float:left;padding-top:6px;" >
            	<select name="recent-limit" class="recent-res-per-page dropdown"  >
                    <option value="" selected > Limit </option>
            		<option <?php // echo  ($limit==10) ? "selected" : "" ; ?> value="15">15</option>
            		<option  <?php //echo  ($limit==15) ? "selected" : "" ; ?> value="25">25</option>
            		<option  <?php // echo  ($limit==20) ? "selected" : "" ; ?>  value="50">50</option>
                        <option  <?php //echo  ($limit==15) ? "selected" : "" ; ?> value="75">75</option>
            		<option  <?php // echo  ($limit==20) ? "selected" : "" ; ?>  value="100">100</option>
            	</select>
            </div>
    </td>  
    </tr>
</table>
<div class="cleaner1" >

</div>



    <table class="GrayBlack" align="center">
        <tbody id="data">
            <tr>
                <td>S.no</td>
                <td>Website Name</td>
                <td>Website Link</td>
                <td>Data Created</td>
                <td>Excluded</td>
                <td>Edit</td>
            </tr>
 <?php
            // Get page data
$query1 = "SELECT
crawl_results.website_product_url,
website.id,
website.name,
website.domain,
website.date_created,
website.excluded
from
crawl_results inner join website
on
crawl_results.website_id = website.id LIMIT $start, $limit";
            $result = mysql_query($query1);

            // Initial page num setup
            if ($page == 0) {
                $page = 1;
            }
            $prev = $page - 1;
            $next = $page + 1;
            $lastpage = ceil($total_pages / $limit);
            $LastPagem1 = $lastpage - 1;


            $paginate = '';
            if ($lastpage > 1) {
                while ($row = mysql_fetch_array($result)) {
                    ?>

                    <tr>
                        <td ><?php echo $row['id']; ?></td>
                        <td ><?php echo $row['name']; ?></td> 
                        <?php echo "<td>"."<a href =".$row['website_product_url']. ">" .$row['domain'] . "</a></td>";  ?> 
                        <td ><?php echo $row['date_created']; ?></td>
                        <td ><?php echo $row['excluded']; ?></td>
                        <td ><a href="website_edit.php?id=<?php echo($row['id']); ?>" title="Edit" > <img src="images/icon_edit.png" /> </a> </td>

                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
    <?php
    $paginate .= "<div class='paginate' align='left' >";
        // Previous
    if ($page > 1) {
        $paginate.= "<a href='$targetpage?page=$prev'>previous</a>";
    } else {
        $paginate.= "<span class='disabled'>previous</span>";
    }
        // Pages
    if ($lastpage < 7 + ($stages * 2)) { // Not enough pages to breaking it up
        for ($counter = 1; $counter <= $lastpage; $counter++) {
            if ($counter == $page) {
                $paginate.= "<span class='current'>$counter</span>";
            } else {
                $paginate.= "<a href='$targetpage?page=$counter'>$counter</a>";
            }
        }
    } elseif ($lastpage > 5 + ($stages * 2)) { // Enough pages to hide a few?
        // Beginning only hide later pages
        if ($page < 1 + ($stages * 2)) {
            for ($counter = 1; $counter < 4 + ($stages * 2); $counter++) {
                if ($counter == $page) {
                    $paginate.= "<span class='current'>$counter</span>";
                } else {
                    $paginate.= "<a href='$targetpage?page=$counter'>$counter</a>";
                }
            }
            $paginate.= "...";
            $paginate.= "<a href='$targetpage?page=$LastPagem1'>$LastPagem1</a>";
            $paginate.= "<a href='$targetpage?page=$lastpage'>$lastpage</a>";
        }
        // Middle hide some front and some back
        elseif ($lastpage - ($stages * 2) > $page && $page > ($stages * 2)) {
            $paginate.= "<a href='$targetpage?page=1'>1</a>";
            $paginate.= "<a href='$targetpage?page=2'>2</a>";
            $paginate.= "...";
            for ($counter = $page - $stages; $counter <= $page + $stages; $counter++) {
                if ($counter == $page) {
                    $paginate.= "<span class='current'>$counter</span>";
                } else {
                    $paginate.= "<a href='$targetpage?page=$counter'>$counter</a>";
                }
            }
            $paginate.= "...";
            $paginate.= "<a href='$targetpage?page=$LastPagem1'>$LastPagem1</a>";
            $paginate.= "<a href='$targetpage?page=$lastpage'>$lastpage</a>";
        }
        // End only hide early pages
        else {
            $paginate.= "<a href='$targetpage?page=1'>1</a>";
            $paginate.= "<a href='$targetpage?page=2'>2</a>";
            $paginate.= "...";
            for ($counter = $lastpage - (2 + ($stages * 2)); $counter <= $lastpage; $counter++) {
                if ($counter == $page) {
                    $paginate.= "<span class='current'>$counter</span>";
                } else {
                    $paginate.= "<a href='$targetpage?page=$counter'>$counter</a>";
                }
            }
        }
    }

    // Next
    if ($page < $counter - 1) {
        $paginate.= "<a href='$targetpage?page=$next'>next</a>";
    } else {
        $paginate.= "<span class='disabled'>next</span>";
    }

    $paginate.= "</div>";



    //echo $total_pages . ' Results';
    // pagination
    echo $paginate;
    ?>


<script type="text/javascript">
 function recent_violation_search() {
     var field = "domain";
     var value = $(".recent_violation_search").val();
     var url_options="";
     if (value.length) {
         url_options += "&action=search&field=" + field + "&value=" + value;
     }

      var search_link = "websites.php?tab=recent"+url_options;
     window.open(search_link, "_self");
 }
 function product_violation_show_all() {
	  var search_link = "websites.php?tab=recent";
	  window.open(search_link, "_self");
 }
 $('.recent-res-per-page').change(function() {
	var limit=$(this).val();
        var url="";
         url="websites.php?tab=recent";
	queryParameters['limit'] = limit;
	location.search=url+$.param(queryParameters);
});
</script>

<div class="cleaner1" >

</div>



