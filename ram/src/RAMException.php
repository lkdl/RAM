<?php

class RAMException extends \Exception{

	private $scLine;
	
	public function __construct($msg, $scLine = -1){
		parent::__construct($msg);

		$this->scLine = $scLine;
	}

	public function getSCLine(){
		return $this->scLine;
	}
}

?>