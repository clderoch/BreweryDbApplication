<?php
	require "Brewerydb.php";
			
	$connect = new Pintlabs_Service_Brewerydb('87f4a33b9a1875fe7ccaeb9d565bf186');
	$connect->setFormat('json'); 
	$params = ['withBreweries' => 'y','hasLabels' => 'y'];
	try {
		$response = $connect->request('beer/random', $params, 'GET'); 
	} catch (Exception $exception) {
		$response = array('error' => $exception->getMessage());
	}
	echo json_encode($response);
?>	