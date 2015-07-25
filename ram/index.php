<?php

require 'vendor/autoload.php';

function error(\RAMException $e){
	$resp = array('error' => true, 'message' => $e->getMessage(), 'line' => $e->getSCLine());
	echo json_encode($resp);
	exit(-1);
}

function _main(){
	header('Content-Type: application/json');

	$data = file_get_contents('php://input');
	$req = json_decode($data, true);
	
	$program = isset($req['program']) && trim($req['program']) !== '' ? $req['program'] : NULL;

	try{

		$ram = new \RAM($program);
		$ram->execute();

	}catch(\RAMException $e){
		error($e);
	}
	
	echo json_encode($ram->getResult());
}


_main();

?>