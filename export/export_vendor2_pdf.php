<?php 
require_once('./tcpdf/tcpdf.php');
include_once '../toMoney.php';
include_once './db_class.php';
include_once './db.php';
session_start();
 $conVendorExport="";
$vviolationTitle=$_SESSION['vviolationTitle'];

if(isset($_SESSION['vviolationTitle']))
$_SESSION['vviolationTitle'];

if(isset($_POST['listv']))
 $_SESSION['listv']=$_POST['listv'];
if(isset($_POST['selectallvendor']))
 $_SESSION['selectallvendor']=$_POST['selectallvendor'];

if (isset($_SESSION['website_id'])) {
    $web_id = $_SESSION['website_id'];
}
$db_resource = new DB ();


 
if (isset( $_SESSION['listv']) and  $_SESSION['listv']!=0)
{
    $arrExportVendor=  $_SESSION['listv'];
    
    // print_r($arrExportRecent);
    $conVendorExport=" and crawl_results.id in (". $arrExportVendor. ")" ;
    
}
 else {
     $conVendorExport="";
}
     if  (isset($_SESSION['selectallvendor']) and $_SESSION['selectallvendor']=='1')
{

      $conVendorExport="";
}

/* sorting */
if ( isset($_GET['sort']) && isset($_GET['dir']) &&  isset($_GET['grid']) && $_GET['grid']=="vvendor_2"  ) {
	$direction =$_GET['dir'];
	$order_field =$_GET['sort'];
	$_SESSION['sort_vvendor_2_dir']=$_GET['dir'];
	$_SESSION['sort_vvendor_2_field']=$_GET['sort'];
} else if (isset($_SESSION['sort_vvendor_2_field']) && isset($_SESSION['sort_vvendor_2_dir']) ) {
	$direction = $_SESSION['sort_vvendor_2_dir'];
	$order_field =$_SESSION['sort_vvendor_2_field'];
} else {
	$direction = "desc";
	$order_field = "violation_amount";
	$_SESSION['sort_vvendor_2_dir'] = "desc";
	$_SESSION['sort_vvendor_2_field'] = "violation_amount";
}
 
$order_by = " ORDER BY " . $order_field . " " . $direction . " ";
/* sorting */


$sql = "select id, date_executed  from crawl  ORDER BY id DESC  LIMIT 1";
$result = mysql_query($sql);
$last_crawl = mysql_fetch_assoc($result);


$sql ="select distinct crawl_results.website_id,date_format(crawl.date_executed,'%m-%d-%Y') as date_executed,
website.name as wname,crawl_results.id as id,
catalog_product_flat_1.entity_id,
 catalog_product_flat_1.name as name,
 catalog_product_flat_1.sku, 
crawl_results.vendor_price ,
 crawl_results.map_price ,
 crawl_results.violation_amount ,
crawl_results.website_product_url 
from crawl_results
 inner join crawl on crawl.id=crawl_results.crawl_id
inner join
website
on website.id = crawl_results.website_id
inner join catalog_product_flat_1
on catalog_product_flat_1.entity_id=crawl_results.product_id
where  crawl_results.violation_amount>0.05 
and
website.excluded=0 " . $conVendorExport . " 
and website_id = $web_id  ".$order_by; 

 
$violators_array=$db_resource->GetResultObj($sql);


class Bshree extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = 'Kraus-Logo-HQ.png';
       
        // Set font
        $this->SetFont('helvetica', 'B', 15);
        // Title
          if (count($this->pages) === 1) { // Do this only on the first page
               $this->Image($image_file, 15, 4, 30, '', '', '', '', false, 300, '', false, false, 0, false, false, false);
           $web_name = $_REQUEST['wname'];
 
               $html .= '
                   &nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;   
                    Products violated by '.$_SESSION['vviolationTitle'].'
                ';
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
$pdf->SetTitle('Vendor Violation');




// set default header data
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


 $html=<<<EOD
 	
         
         <style type="text/css">
table.border{background:#e0eaee;margin:1px auto;padding:4px;}
         table.border td{padding:10px;border:1px solid 87B5F1;text-align:center;}
      
        table.border1 {background:#e0eaee;margin:1px auto;padding:4px;}
         table.border1 td{padding:10px;border:1px solid 87B5F1;text-align:center;
                         background-color:#eee;}
</style>  
          <table class="border1"> 
    <tr>
         <td style="width:260px">SKU </td>  
         <td style="width:95px">Dealers Price</td>    
         <td style="width:95px">Map Price</td>    
         <td style="width:95px">Violation Amount</td>  
         <td style="width:90px">Date</td> 
    </tr>    
         </table>
         <table class="border">
EOD;
foreach ($violators_array as $violators_array ) {
	$html.=<<<EOD
	 
	<tr>
            <td style="width:260px">{$violators_array->sku}</td>
            <td style="width:95px"> $ {$violators_array->vendor_price}</td>
            <td style="width:95px"> $ {$violators_array->map_price}</td>
            <td style="width:95px"> $ {$violators_array->violation_amount}</td>
            <td style="width:90px">{$violators_array->date_executed}</td>
          
            
           
                
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
        $pdf->Output("Products_Violated_by_".$_SESSION['vviolationTitle'].'-'.date('Y-m-d'), 'I');
