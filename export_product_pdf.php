<?php 
require_once('export/tcpdf/tcpdf.php');
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

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
$query1 = "SELECT distinct 
catalog_product_flat_1.sku,
catalog_product_flat_1.entity_id as product_id,
catalog_product_flat_1.name,
format(crawl_results.vendor_price,2) as vendor_price ,
format(crawl_results.map_price,2)as map_price,
max(crawl_results.violation_amount) as maxvio,
min(crawl_results.violation_amount) as minvio,
count(crawl_results.product_id) as i_count
FROM
prices.catalog_product_flat_1
inner join
prices.crawl_results
on catalog_product_flat_1.entity_id = crawl_results.product_id 
inner join crawl
on
crawl_results.crawl_id = crawl.id 
where crawl_results.violation_amount>0.05

 and 
crawl.id = 
(select max(crawl.id) from crawl) 
group by prices.catalog_product_flat_1.sku,
prices.catalog_product_flat_1.name
order by maxvio desc LIMIT $start, $limit";

$result = mysql_query($query1);
 $html=<<<EOD
<table > 		
EOD;
while ($row = mysql_fetch_assoc($result)) {
	$html.=<<<EOD
	 
	<tr>
            <td>{$row['sku']}</td>
            <td>{$row['map_price']}</td>
            <td>{$row['i_count']}</td>
            <td>{$row['maxvio']}</td>
            <td>{$row['minvio']}</td>
            
           
                
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
$pdf->Output('Product_Violations', 'I');
// 