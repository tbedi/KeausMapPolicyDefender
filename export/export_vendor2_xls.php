<?php
session_start();
include_once '../toMoney.php';
$vviolationTitle = $_SESSION['vviolationTitle'];

$violators_array = $_SESSION['vendor2Array'];
if (isset($_SESSION['vendor2Array'])) {
    // print_r($violators_array);
}

header('Content-Type: application/vnd.ms-excel');
//header('Content-Disposition: attachment; filename=' . "Products_Violated_by_" . $_SESSION['vviolationTitle'] . '-' . date('d-m-y'));
header('Content-Disposition: attachment; filename=' . "Products_Violated_by_" . $_SESSION['vviolationTitle'] . "_" . date('d-m-y'));
header('Pragma: no-cache');
header('Expires: 0');
echo '<table border=1><tr>';
echo '<td>SKU </td>';
echo '<td>Dealers Price </td>';
echo '<td>Map Price </td>';
echo '<td>Violation Amount </td>';

print('</tr>');

foreach ($violators_array as $violators_array) {


    $output = "<tr>";

    $output .= "<td>" . $violators_array->sku . "</td>";
    $output .= "<td>" . toMoney($violators_array->vendor_price) . "</td>";
    $output .= "<td>" . toMoney($violators_array->map_price) . "</td>";
    $output .= "<td>" . toMoney($violators_array->violation_amount) . "</td>";

    print(trim($output)) . "</tr>\t\n";
}
echo "</table>";
?>
