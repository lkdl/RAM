<?php

namespace RAM\Commands;

use \RAM\Commands\Command;

class Div extends \RAM\Commands\Command{

	private $op;

	public function __construct($op){
		$this->op = $op;
	}

	public function execute(\RAM\Register $reg, \RAM\Cell $acc){
	
		$op = $this->resolve($this->op, $reg, $acc);
		
		$acc->setValue(floor($acc->getValue() / $op['value']));

		return -1;
	}
	
}

?>