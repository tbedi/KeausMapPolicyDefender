<?php
include_once '../toMoney.php';
$sku = $_REQUEST['sku'];
session_start();
$violators_array = $_SESSION['product2Array'];
if (isset($_SESSION['product2Array'])) {
    //   print_r($violators_array);
}


header('Content-Type: application/vnd.ms-excel'); //define header info for browser    
header('Content-Disposition: attachment; filename=' . "Dealers_Violated_" . $sku . "_" . date('d-m-y'));
header('Pragma: no-cache');
header('Expires: 0');

echo '<table border=1><tr>';
echo '<td>Dealers </td>';
echo '<td>Dealers Price </td>';
echo '<td>Map Price </td>';
echo '<td>Violation Amount </td>';

print('</tr>');

foreach ($violators_array as $violators_array) {


    $output = "<tr>";

    $output .= "<td>" . $violators_array->vendor . "</td>";
    $output .= "<td>" . toMoney($violators_array->vendor_price) . "</td>";
    $output .= "<td>" . toMoney($violators_array->map_price) . "</td>";
    $output .= "<td>" . toMoney($violators_array->violation_amount) . "</td>";


    print(trim($output)) . "</tr>\t\n";
}

echo "</table>";
?>
