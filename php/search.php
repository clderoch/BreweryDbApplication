<?php
	require "Brewerydb.php";
	$connect = new Pintlabs_Service_Brewerydb('87f4a33b9a1875fe7ccaeb9d565bf186');
	$connect->setFormat('json'); 
	
	$name = $_POST["name"];
	$type = $_POST["type"];
		$params = ['name' => $name,'hasLabels' => 'y'];
		try {
			$response = $connect->request($type, $params, 'GET'); 
		} catch (Exception $e) {
			$response = array('error' => $e->getMessage());
		}
		echo json_encode($response);
?>	