<?php 
require_once('/tcpdf/tcpdf.php');
 $product_id = $_REQUEST['product_id'];
$sku=$_REQUEST['sku'];
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
                    Vendors Violated by '.$sku.'
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




// set document information
//$pdf->SetCreator(PDF_CREATOR);
//$pdf->SetAuthor('Kraus USA');
//$pdf->SetTitle('Recent Violations');
//$pdf->SetSubject('TCPDF Tutorial');
//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

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
$pdf->SetFont('times', '', 14, '', true);

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

$query1 = "SELECT  distinct w.`name` as vendor , crawl.id,
    format(r.violation_amount,2) as violation_amount,
    format( r.vendor_price,2) as vendor_price,
    format(r.map_price,2) as map_price,
    r.website_product_url
    FROM crawl_results  r
    INNER JOIN website w
    ON r.website_id=w.id
    INNER JOIN catalog_product_flat_1 p
    ON p.entity_id=r.product_id
    AND p.entity_id='" . $product_id . "'
        inner join crawl
on crawl.id=r.crawl_id
    WHERE crawl.id = 
(select max(crawl.id) from crawl) 
		    AND r.violation_amount>0.05
                    and w.excluded=0
		    ORDER BY r.violation_amount DESC ";

$result = mysql_query($query1);
//echo 'Sellers Violated '.$product_id;
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
   
         <td style="width:250px">Seller </td>    
         <td style="width:85px">Vendor Price</td>    
         <td style="width:85px">Map Price</td>    
         <td style="width:85px">Violation Amount</td>    
        </tr>  
            </table>
         <table class="border">
EOD;
while ($row = mysql_fetch_assoc($result)) {
	$html.=<<<EOD
	 
	<tr>
            <td style="width:250px">{$row['vendor']}</td>
            <td style="width:85px"> $ {$row['vendor_price']}</td>
            <td style="width:85px"> $ {$row['map_price']}</td>
            <td style="width:85px"> $ {$row['violation_amount']}</td>
        
           
                
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
$pdf->Output("Sellers_Violated_".$product_id.'-'.date('Y-m-d'), 'I');
// 