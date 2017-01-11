<?php
	require "Brewerydb.php";
	$connect = new Pintlabs_Service_Brewerydb('87f4a33b9a1875fe7ccaeb9d565bf186');
	$connect->setFormat('json'); 
	
	$breweryId = $_POST["breweryId"];
	$params = ['hasLabels' => 'y',];
	try {
		$response = $connect->request("brewery/".$breweryId."/beers", $params, 'GET'); 
	} catch (Exception $e) {
		$response = array('error' => $e->getMessage());
	}
	echo json_encode($response);

?>	