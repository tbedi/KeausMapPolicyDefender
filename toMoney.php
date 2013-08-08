<?php

function toMoney($val,$symbol='$',$decimal=2)
{


    $no = $val; 
    $c = is_float($no) ? 1 : number_format($no,$decimal);
    $d = '.';
    $t = ',';
    $sign = ($no < 0) ? '-' : '';
    $i = $no=number_format(abs($no),$decimal); 
    $j = (($j = count($i)) > 3) ? $j % 3 : 0; 

   return  $symbol.$sign .($j ? substr($i,0, $j) + $t : '').preg_replace('/(\d{3})(?=\d)/',"$1" + $t,substr($i,$j)) ;

}




?>
