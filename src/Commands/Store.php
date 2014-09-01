<?php

namespace RAM\Commands;

use \RAM\Commands\Command;

class Store extends \RAM\Commands\Command{

	private $op;

	public function __construct($op){
		$this->op = $op;
	}

	public function execute(\RAM\Register $reg, \RAM\Cell $acc){
		
		$op = $this->resolveIndex($this->op, $reg, $acc);
		
		if ($op['type'] == Command::LITERAL){
			throw new \Exception('Cannot store into a literal');
		}
		
		$reg->get($op['value'])->setValue($acc->getValue());

		return -1;
	}
	
}

?>