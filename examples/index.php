<?php

header('Content-Type: application/json');

$ex = array(
	'factorial' => array('name' => 'Factorial', 'path' => 'fac.ram'),
	'gauss_formula' => array('name' => 'Gaussian Sum Formula', 'path' => 'gauss_formula.ram')
);

$resp = array('type' => NULL, 'data' => NULL);

$get = isset($_GET['get']) && trim($_GET['get']) !== '' ? $_GET['get'] : NULL;

if(is_null($get)){
	$names = array();
	foreach ($ex as $id => $inf) {
		$names[] = array('value' => $id, 'name' => $inf['name']);
	}
	$resp['type'] = 'list';
	$resp['data'] = $names;
}else{
	$p = NULL;
	foreach ($ex as $id => $inf) {
		if($id === $get){
			$p = $inf['path'];
			break;
		}
	}

	if(is_null($p)){
		$resp['type'] = 'error';
		$resp['data'] = 'Program not found';
	}else{
		$resp['type'] = 'program';
		$resp['data'] = file_get_contents($p);
	}
}

echo json_encode($resp);

?>