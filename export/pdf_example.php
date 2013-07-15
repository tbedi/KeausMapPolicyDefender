<?php
header("Content-type: text/csv");
header("Cache-Control: no-store, no-cache");
header('Content-Disposition: attachment; filename="filename.csv"');

/* columns */
$arr_columns = array (
		'id',
		'title',
		'sku' 
);
$arr_data = array ();

$arr_data_row = array (
		"test1",
		"test2",
		"test3" 
);
/* push data to array */
array_push ( $arr_data, $arr_data_row );

exportCSV ( $arr_data, $arr_columns );
function exportCSV($data, $col_headers = array(), $return_string = false) {
	$stream = ($return_string) ? fopen ( 'php://temp/maxmemory', 'w+' ) : fopen ( 'php://output', 'w' );
	
	if (! empty ( $col_headers )) {
		fputcsv ( $stream, $col_headers );
	}
	foreach ( $data as $record ) {
		fputcsv ( $stream, $record );
	}
	
	if ($return_string) {
		rewind ( $stream );
		$retVal = stream_get_contents ( $stream );
		fclose ( $stream );
		return $retVal;
	} else {
		fclose ( $stream );
	}
}
