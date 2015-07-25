<?php

namespace Commands;


class Mult extends \Commands\Command{

	private $op;

	public function __construct($op){
		$this->op = $op;
	}

	public function execute(\Components\Register $reg, \Components\Cell $acc){
	
		$op = $this->resolve($this->op, $reg, $acc);
		
		$acc->setValue($acc->getValue() * $op['value']);

		return -1;
	}
	
}

?>