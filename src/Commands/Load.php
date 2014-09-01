<?php

namespace RAM\Commands;

use \RAM\Commands\Command;

class Load extends \RAM\Commands\Command{

	private $op;

	public function __construct($op){
		$this->op = $op;
	}

	public function execute(\RAM\Register $reg, \RAM\Cell $acc){
		
		$op = $this->resolve($this->op, $reg, $acc);
		$acc->setValue($op['value']);

		return -1;
	}
	
}

?>