<?php
include_once '../toMoney.php';
session_start();
$violators_array = $_SESSION['productArray'];
if (isset($_SESSION['productArray'])) {
    //  print_r($violators_array);
}
$filename = "Product_Violations-" . date('d-m-y') . ".xls";
header('Content-Type: application/vnd.ms-excel'); //define header info for browser
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');


echo '<table border=1><tr>';
echo '<td>SKU </td>';
echo '<td>Map Price </td>';
echo '<td>Violation Count</td>';
echo '<td> Max Violation</td>';
echo '<td>Min Violation </td>';

print('</tr>');




foreach ($violators_array as $violators_array) {


    $output = "<tr>";

    $output .= "<td>" . $violators_array->sku . "</td>";
    $output .= "<td>" . toMoney($violators_array->map_price) . "</td>";
    $output .= "<td>" . $violators_array->i_count . "</td>";
    $output .= "<td>" . toMoney($violators_array->maxvio) . "</td>";
    $output .= "<td>" . toMoney($violators_array->minvio) . "</td>";


    print(trim($output)) . "</tr>\t\n";
}

echo "</table>";
?>
