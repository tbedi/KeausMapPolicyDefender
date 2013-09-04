<?php




	//write your session reset code here.
	if (isset($_SESSION['product'])) {
                unset ($_SESSION['product']);
	}
        if (isset($_SESSION['dealer'])) {
        	unset ($_SESSION['dealer']);
        }        



	
	echo 'true'; //this will indicate the operation was successful
			

?>