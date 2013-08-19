<?php 
require_once('./tcpdf/tcpdf.php');
include_once './db_class.php';
include_once '../toMoney.php';
include_once './db.php';


 $product_id = $_REQUEST['product_id'];
 $sku="";
if(isset($_REQUEST['sku']))
{
$sku=$_REQUEST['sku'];
}

session_start();


$sku="";
$product_id="";
if(isset($_REQUEST['sku']))
{
$sku=$_REQUEST['sku'];
}

session_start();
if(isset($_SESSION['listp']))
 $_SESSION['listp'];
if(isset($_SESSION['selectallproduct']))
 $_SESSION['selectallproduct'];

if (isset($_SESSION['product_id'])) {
    $product_id = $_SESSION['product_id'];
}
//echo $_SESSION['selectallproduct'];
//data collection
$db_resource = new DB ();
$limit=15;
$start=0;
$limitpcon="";

if (isset($_GET['limit2'])  && isset($_GET['tab']) && $_GET['tab']=='violations-history' ) {
	$limit=$_GET['limit2'];
} 
if  (!isset($_SESSION['selectallproduct']) and $_SESSION['selectallproduct']!=='1')
{ 
       $limitpcon = "  LIMIT $start, $limit ";
}


if (isset( $_SESSION['listp']) and  $_SESSION['listp']!="")
{
    $arrExportProduct=  $_SESSION['listp'];
    
    // print_r($arrExportRecent);
    $conProductExport=" and r.id in (". $arrExportProduct. ")" ;
    
}
 else {
     $conProductExport="";
}
     if  (isset($_SESSION['selectallproduct']) and $_SESSION['selectallproduct']=='1')
{
    $limitpcon="";
      $conProductExport="";
}
/* sorting */
if ( isset($_GET['sort']) && isset($_GET['dir']) &&  isset($_GET['grid']) && $_GET['grid']=="vproduct_2"  ) {
	$direction =$_GET['dir'];
	$order_field =$_GET['sort'];
	$_SESSION['sort_vproduct_2_dir']=$_GET['dir'];
	$_SESSION['sort_vproduct_2_field']=$_GET['sort'];
} else if (isset($_SESSION['sort_vproduct_2_field']) && isset($_SESSION['sort_vproduct_2_dir']) ) {
	$direction = $_SESSION['sort_vproduct_2_dir'];
	$order_field =$_SESSION['sort_vproduct_2_field'];
} else {
	$direction = "desc";
	$order_field = "violation_amount";
	$_SESSION['sort_vproduct_2_dir'] = "desc";
	$_SESSION['sort_vproduct_2_field'] = "violation_amount";
}
 
$order_by = " ORDER BY " . $order_field . " " . $direction . " ";
/* sorting */
$sql = "select id, date_executed  from crawl  ORDER BY id DESC  LIMIT 1";
$result = mysql_query($sql);
$last_crawl = mysql_fetch_assoc($result);

$sql = "SELECT  distinct w.`name` as vendor ,
    r.violation_amount as violation_amount,r.id as id,
    w.id as website_id,
    r.vendor_price as vendor_price,
    cast(r.map_price as decimal(10,2)) as map_price,
    r.website_product_url,
    p.sku as sku
    FROM crawl_results  r
    INNER JOIN website w ON r.website_id=w.id
    INNER JOIN catalog_product_flat_1 p ON p.entity_id=r.product_id  AND p.entity_id='" . $product_id . "'
    WHERE r.crawl_id=" . $last_crawl['id'] . " AND r.violation_amount>0.05  and w.excluded=0  " . $conProductExport . " 
   " . $order_by . " $limitpcon";
 
$violators_array=$db_resource->GetResultObj($sql);
//print_r($sql);






//$violators_array=$_SESSION['product2Array'];
//if(isset($_SESSION['product2Array']))
//{
//       //print_r($violators_array);
//}






class Bshree extends TCPDF {

public $html;

    //Page header
    public function Header() {
        // Logo
        $image_file = 'Kraus-Logo-HQ.png';
        
        // Set font
        $this->SetFont('helvetica', 'B', 15);
        // Title
          if (count($this->pages) === 1) { // Do this only on the first page
               $this->Image($image_file, 15, 4, 30, '', '', '', '', false, 300, '', false, false, 0, false, false, false);
               
                $sku = $_REQUEST['sku'];
               $html .= '
                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  
                    Dealers Violated by '.$sku.'
                ';            
            }

            $this->writeHTML($html.'', true, false, false, false, '');
            
                       

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
$pdf->SetTitle('Product Violation');


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
   
         <td style="width:250px">Dealers </td>    
         <td style="width:85px">Dealers Price</td>    
         <td style="width:85px">Map Price</td>    
         <td style="width:85px">Violation Amount</td>    
        </tr>  
            </table>
         <table class="border">
EOD;
foreach ($violators_array as $violators_array ){
	$html.=<<<EOD
	 
	<tr>
            <td style="width:250px">{$violators_array->vendor}</td>
            <td style="width:85px"> $ {$violators_array->vendor_price}</td>
            <td style="width:85px"> $ {$violators_array->map_price}</td>
            <td style="width:85px"> $ {$violators_array->violation_amount}</td>
         
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
$pdf->Output("Dealers_Violated_".$product_id.'-'.date('Y-m-d'), 'I');
// 