<?php
include "db.php";
$header="";
$data="";
$query = "SELECT * FROM crawl";

$export = mysql_query ($query ) or die ( "Sql error : " . mysql_error( ) );

$fields = mysql_num_fields ( $export );

for ( $i = 0; $i < $fields; $i++ )
{
    $header .= mysql_field_name( $export , $i ) . "\t";
}

while( $row = mysql_fetch_row( $export ) )
{
    $line = '';
    foreach( $row as $value )
    {                                            
        if ( ( !isset( $value ) ) || ( $value == "" ) )
        {
            $value = "\t";
        }
        else
        {
            $value = str_replace( '"' , '""' , $value );
            $value = '"' . $value . '"' . "\t";
        }
        $line .= $value;
    }
    $data .= trim( $line ) . "\n";
}
$data = str_replace( "\r" , "" , $data );

if ( $data == "" )
{
    $data = "\n(0) Records Found!\n";                        
}


//header("Content-type: text/csv");
//header('Content-Disposition: attachment; filename="export.csv"');
//print "$header\n$data";
header ( 'Content-Type: application/vnd.ms-excel') ;
header ( 'Content-Disposition: attachment;filename=export.csv' );
header ( 'HTTP/1.1 200 OK' );
header ( 'Date: ' . date ( 'D M j G:i:s T Y' ) );
header ( 'Last-Modified: ' . date ( 'D M j G:i:s T Y' ) );

print chr(255) . chr(254) . mb_convert_encoding($header+$data, 'UTF-16LE', 'UTF-8');
exit;
fputcsv($header, $data);

?>