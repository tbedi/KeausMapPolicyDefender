<?php
require_once('./tcpdf/tcpdf.php');

//require_once('export/tcpdf/tcpdf.php');
static $str=0;
session_start();
$violators_array=$_SESSION['recentArray'];
class Bshree extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $sql = "select max(DATE_FORMAT(crawl.date_executed, '%d %b %Y')) as maxd
                 from crawl;";
        $result = mysql_query($sql);
        while ($row = mysql_fetch_assoc($result)) {
            $str = $row['maxd'];
        }
        $image_file = 'Kraus-Logo-HQ.png';

        // Set font
        $this->SetFont('helvetica', 'B', 15);
        // Title
        if (count($this->pages) === 1) { // Do this only on the first page
            $this->Image($image_file, 15, 4, 30, '', '', '', '', false, 300, '', false, false, 0, false, false, false);
            $html .= '
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    Recent Violations '.$str.'
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
$pdf->SetTitle('Recent Violations');

$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . ' 001', PDF_HEADER_STRING, array(0, 64, 255), array(0, 64, 128));
$pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

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
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once(dirname(__FILE__) . '/lang/eng.php');
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
$pdf->setTextShadow(array('enabled' => true, 'depth_w' => 0.2, 'depth_h' => 0.2, 'color' => array(196, 196, 196), 'opacity' => 1, 'blend_mode' => 'Normal'));
/* Example adding for products */



$html = <<<EOD

         
         <style type="text/css">

table.border{background:#e0eaee;margin:1px auto;padding:8px; }
         table.border td{padding:4px;border:1px solid 87B5F1;text-align:center; font-size: 14px;   }
              table.border1 {background:#e0eaee;margin:1px auto;padding:8px;}
         table.border1 td{padding:4px;border:1px solid 87B5F1;text-align:center;
                         background-color:#eee;}
</style>  
   <table class="border1"> 
     <tr>
        <td style="width:200px">SKU </td>  
         <td style="width:200px">Seller </td>    
         <td style="width:70px">Vendor Price</td>    
         <td style="width:70px">Map Price</td>    
         <td style="width:80px">Violation Amount</td>    
      </tr>
    </table>
        <table class="border"> 
EOD;

foreach ($violators_array as $violators_array ){
    $html.=<<<EOD
	 
	<tr>
            <td style="width:200px">{$violators_array->sku}</td>
            <td style="width:200px">{$violators_array->name}</td>
            <td style="width:70px"> $ {$violators_array->vendor_price}</td>
            <td style="width:70px"> $ {$violators_array->map_price}</td>
            <td style="width:80px"> $ {$violators_array->violation_amount}</td>    
        </tr>
	  
EOD;
}
$html.=<<<EOD

   </table>            
        
        
        
EOD;

/* Example addding for products */

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
//$pdf->writeHTML($html, true, 0, true, 0);
// ---------------------------------------------------------
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
ob_clean();
$pdf->Output("Recent_Violations" . '-' . date('Y-m-d'), 'I');
// 
