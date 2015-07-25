<?php

namespace Commands;

class Div extends \Commands\Command{

	private $op;

	public function __construct($op){
		$this->op = $op;
	}

	public function execute(\Components\Register $reg, \Components\Cell $acc){
	
		$op = $this->resolve($this->op, $reg, $acc);

		if($op['value'] == 0){
			throw new \Exception('Division by zero');
		}
		
		$acc->setValue(floor($acc->getValue() / $op['value']));

		return -1;
	}
	
}

?>