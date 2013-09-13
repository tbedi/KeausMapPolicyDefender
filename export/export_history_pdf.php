<?php 
/*GLOBAL*/
/*configuration*/
require_once('tcpdf/tcpdf.php');
setlocale(LC_MONETARY, 'en_US');
include_once '../db.php';
include_once '../db_login.php';

/*Login check*/
if (!isset($_SESSION['username']))
	header('Location: login.php');

/*configuration*/
include_once '../db_class.php';
$db_resource = new DB ();
include_once '../toMoney.php';
/*GLOBAL*/
 

/*Prepare values for grid*/
$limit = 15;
$product_id =(isset($_REQUEST['product_id']) ? $_REQUEST['product_id'] : 0);
$website_id=(isset($_REQUEST['website_id']) ? $_REQUEST['website_id'] : 0);

//Calendar
$to=date("Y-m-d 23:59:59",strtotime($_GET['to']));
$from=date("Y-m-d 00:00:00",strtotime($_GET['from']));
//Sorting
$direction = $_GET['order_direction'];
$order_field = $_GET['order_field'];
$order_by = "order by " . $order_field . " " . $direction . " ";

/* Pagination */
if (isset($_GET['limit']) ) {
	$limit = $_GET['limit'];
}

if (isset($_GET['page'])) {
	// echo $_GET['page'];
	$page = mysql_escape_string($_GET['page']);
	$start = ($page - 1) * $limit;
	// echo $start;
} else {
	$start = 0;
	$page = 1;
}
$limithcon = "  LIMIT $start, $limit ";
/* Pagination */

/* Search Condition*/
$search_condition="";
if (isset($_REQUEST['action']) && $_REQUEST['action']=="search")
{
	$searched_sku=(isset($_REQUEST['sku']) ? $_REQUEST['sku'] : "" );
	$searched_dealer=(isset($_REQUEST['dealer']) ? $_REQUEST['dealer'] : "" );

	if ($searched_sku)
		$search_condition.=" AND sku = '".$searched_sku."' ";
	if ($searched_dealer)
		$search_condition.=" AND website.name  LIKE '".$searched_dealer."%' ";
}
/* Search Condition*/
if ($website_id) {
	$website_id=mysql_real_escape_string($website_id);
	$search_condition.= "  AND  website_id  = " ." $website_id ". "";
}


if ($product_id) {
	$product_id=mysql_real_escape_string($product_id);
	$search_condition.= "  AND  product_id  = " ." $product_id ". "";
}
/* Search Condition*/

/****QUERY****/
$sql = "SELECT SQL_CALC_FOUND_ROWS   catalog_product_flat_1.sku as sku, crawl_results.website_id,crawl_results.id,  date_format(crawl.date_executed,'%m-%d-%Y') as date_executed,
			 	catalog_product_flat_1.name as pname,catalog_product_flat_1.entity_id as product_id, website.name as dealer,  crawl_results.vendor_price , crawl_results.map_price ,
				crawl_results.violation_amount , crawl_results.website_product_url
				FROM website
				INNER JOIN crawl_results ON website.id = crawl_results.website_id
				INNER JOIN catalog_product_flat_1 ON catalog_product_flat_1.entity_id=crawl_results.product_id
				INNER JOIN crawl ON crawl.id=crawl_results.crawl_id
				WHERE  crawl.date_executed  BETWEEN '".$from."'  AND  '".$to."'
 				".$search_condition."
				AND	crawl_results.violation_amount>0.05
				AND website.excluded=0
				" . $order_by . "$limithcon " ;

$violators_array = $db_resource->GetResultObj($sql);

/*PDF*/


     global $html;
class Bshree extends TCPDF {
    //Page header
    public function Header() {
        $html;  
        // Logo
        $image_file = 'Kraus-Logo-HQ.png';
        
        // Set font
        $this->SetFont('helvetica', 'B', 15);
        // Title
                  if (count($this->pages) === 1) { // Do this only on the first page
               $this->Image($image_file, 15, 4, 30, '', '', '', '', false, 300, '', false, false, 0, false, false, false);
            $html .= '
                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Violation History';
            }
            $this->writeHTML($html, true, false, false, false, '');
    }

    // Page footer
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        //$this->writeHTML('Kraus USA', true, false, false, false, '');
        $timestamp = date("m/d/Y");
        if (empty($this->pagegroups)) {
            $pagenumtxt = $this->getAliasNumPage() . ' / ' . $this->getAliasNbPages();
        } else {
            $pagenumtxt = $this->getPageNumGroupAlias() . ' / ' . $this->getPageGroupAlias();
        }
        $this->Cell(10, 10, 'Kraus USA', 0, false, 'C', 0, '', 0, false, 'T', 'M');
        $this->Cell(0, 10, 'Page ' . $pagenumtxt, 0, false, 'C', 0, '', 0, false, 'T', 'M');
        $this->Cell(0, 10, 'Created on ' . $timestamp . '   ' . $this->l['w_page'] . ' ', 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

// create new PDF document
$pdf = new Bshree(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


$pdf->SetAuthor('Kraus USA');
$pdf->SetTitle('Violations History');

$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('times', '', 12, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// set text shadow effect
$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));
/* Example adding for products*/
include_once 'db.php';

  





 $html=<<<EOD

      <style type="text/css">

table.border{background:#e0eaee;margin:1px auto;padding:4px;}
         table.border td{padding:10px;border:1px solid 87B5F1;text-align:center;font}
      
        table.border1 {background:#e0eaee;margin:1px auto;padding:4px;}
         table.border1 td{padding:10px;border:1px solid 87B5F1;text-align:center;
                         background-color:#eee;}
</style>   
         
         <table class="border1"> 
    <tr>
        
         <td style="width:210px">SKU </td>    
         <td style="width:150px">Dealers</td>    
         <td style="width:75px">Dealers Price</td>    
         <td style="width:75px">MAP Price</td>    
         <td style="width:75px">Violation Amount</td> 
 		 <td style="width:80px">Date</td>    
     </tr>
         
         </table>
         <table class="border">
EOD;
foreach ($violators_array as $violators_array ) {
	$html.=<<<EOD
	 
	<tr>
            
            <td style="width:210px">{$violators_array->sku}</td>
            <td style="width:150px">{$violators_array->dealer}</td>
            <td style="width:75px">\${$violators_array->vendor_price}</td>
            <td style="width:75px">\${$violators_array->map_price}</td>
            <td style="width:75px">\${$violators_array->violation_amount}</td>
        	<td style="width:80px">{$violators_array->date_executed}</td>
            
           
                
   </tr>
	  
EOD;
}
$html.=<<<EOD
</table>
EOD;

/*Example addding for products*/  
  
// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
//$pdf->writeHTML($html, true, 0, true, 0);

// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
ob_clean();
$pdf->Output("Violation_History".'-'.date('Y-m-d'), 'I');
// 