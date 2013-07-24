<?php 
require_once('export/tcpdf/tcpdf.php');


class Bshree extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = 'images/Kraus-Logo-HQ.png';
        
        // Set font
        $this->SetFont('helvetica', 'B', 15);
        // Title
          if (count($this->pages) === 1) { // Do this only on the first page
               $this->Image($image_file, 10, 7, 30, '', '', '', '', false, 300, '', false, false, 0, false, false, false);
            $html .= '
                    <p> </p>   
                    Recent Violations
                ';
            }

            $this->writeHTML($html, true, false, false, false, '');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
         $this->writeHTML('Kraus USA', true, false, false, false, '');
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    
         
        
    }
}

// create new PDF document
$pdf = new Bshree(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);




$pdf->SetAuthor('Kraus USA');
$pdf->SetTitle('Recent Violations');

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
$limit=10;
  

$where = "";

if (isset($_GET['action']) && $_GET['action'] == 'search' && isset($_GET['tab']) && $_GET['tab'] == 'recent') {
	$field = strtolower($_GET['field']);
	$value = strtolower($_GET['value']);
	$where = "  AND  catalog_product_flat_1." . $field . "  LIKE '%" . $value . "%'";
}


if (isset($_GET['page']) && isset($_GET['tab']) && $_GET['tab'] == 'recent') {
	$page = mysql_escape_string($_GET['page']);
	$start = ($page - 1) * $limit;
} else {
	$start = 0;
	$page = 1;
}
$query1 = "select catalog_product_flat_1.sku,
website.name as wname,
format(crawl_results.vendor_price,2) as vendor_price,
format(crawl_results.map_price,2) as map_price,
format(crawl_results.violation_amount,2) as violation_amount,
crawl_results.website_product_url
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
crawl.id =
(select max(crawl.id) from crawl) " . $where . "
order by violation_amount desc LIMIT $start, $limit";

$result = mysql_query($query1);
 $html=<<<EOD

         
         <style type="text/css">

table.border{background:#e0eaee;margin:1px auto;padding:8px;}
         table.border td{padding:10px;border:1px solid 87B5F1;text-align:center;
background-color:#eee;}
</style>  
   <table class="border"> 
    <tr>
   <td>SKU </td>  
         <td>Seller </td>    
         <td>Vendor Price</td>    
         <td>Map Price</td>    
         <td>Violation Amount</td>    
            
   
   
   
   
   </tr>     
EOD;
 
while ($row = mysql_fetch_assoc($result)) {
	$html.=<<<EOD
	 
	<tr>
            <td>{$row['sku']}</td>
            <td>{$row['wname']}</td>
            <td>{$row['vendor_price']}</td>
            <td>{$row['map_price']}</td>
            <td>{$row['violation_amount']}</td>
         
           
                
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
$pdf->Output("Recent_Violations".'-'.date('Y-m-d'), 'I');
// 
