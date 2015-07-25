<?php

namespace Commands;

class Store extends \Commands\Command{

	private $op;

	public function __construct($op){
		$this->op = $op;
	}

	public function execute(\Components\Register $reg, \Components\Cell $acc){
		
		$op = $this->resolveIndex($this->op, $reg, $acc);
		
		if ($op['type'] == \Commands\Command::LITERAL){
			throw new \Exception('Cannot store into a literal');
		}
		
		$reg->get($op['value'])->setValue($acc->getValue());

		return -1;
	}
	
}

?>