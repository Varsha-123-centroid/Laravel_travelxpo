<?php include('Crypto.php')?>
<?php

	error_reporting(0);
	
	$workingKey='76F08E97FED563A85A91221D9CBF034C';		//Working Key should be provided here.
	$encResponse=$_POST["encResp"];			//This is the response sent by the CCAvenue Server
	$rcvdString=decrypt($encResponse,$workingKey);		//Crypto Decryption used as per the specified working key.
	$order_status="";
	$decryptValues=explode('&', $rcvdString);
	$dataSize=sizeof($decryptValues);
	echo "<center>";

	for($i = 0; $i < $dataSize; $i++) 
	{
		$information=explode('=',$decryptValues[$i]);
		if($i==0)	$order_id=$information[1];
		if($i==1)	$tracking_id=$information[1];
		if($i==2)	$bank_ref_no=$information[1];
		if($i==3)	$order_status=$information[1];
		
		$_SESSION['order_status'] =  $order_status;
		//if($i==3)	$order_status=$information[1];
	}

	if($order_status==="Success")
	{
		$msg="Thank you for payment with us.  Your transaction is successful. Successfully Done.";
		
		$_SESSION['order_id'] =  $order_id;
		$_SESSION['tracking_id'] =  $tracking_id;
		$_SESSION['bank_ref_no'] =  $bank_ref_no;
		$_SESSION['bank_msg'] =  $msg;
	
		
	}
	else if($order_status==="Aborted")
	{
		$msg= "<br>Thank you for Payment with us.We will keep you posted regarding the status of your order through e-mail";
		$_SESSION['bank_msg'] =  $msg;
	}
	else if($order_status==="Failure")
	{
		$msg= "<br>Thank you for Payment with us.However,the transaction has been declined.";
		$_SESSION['bank_msg'] =  $msg;
	}
	else
	{
		$msg= "<br>Security Error. Illegal access detected";
	    $_SESSION['bank_msg'] =  $msg;
	}
header("Location: http://3.108.180.21/travelexpo/api/pay-activate");
exit;

/*	echo "<br><br>";

	echo "<table cellspacing=4 cellpadding=4>";
	for($i = 0; $i < $dataSize; $i++) 
	{
		$information=explode('=',$decryptValues[$i]);
	    	echo '<tr><td>'.$information[0].'</td><td>'.$information[1].'</td></tr>';
	}

	echo "</table><br>";
	echo "</center>"; */
?>
