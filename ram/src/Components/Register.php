<?php

namespace Components;

class Register{
	
	private $reg;

	public function __construct($amount = 10){
		$this->reg = array();
		for($i = 0; $i < $amount; $i++){
			$this->reg[] = new \Components\Cell();
		}
	}

	public function get($ind){
		if($ind > count($this->reg)){
			throw new \Exception('Requested index is bigger than the amount of registers');
		}

		return $this->reg[$ind-1];
	}
}

?>