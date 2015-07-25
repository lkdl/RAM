<?php

namespace Commands;

class GotoCmd extends \Commands\Command{

	private $line;

	public function __construct($line){
		$this->line = $line;
	}

	public function execute(\Components\Register $reg, \Components\Cell $acc){
		$line = $this->resolve($this->line, $reg, $acc);
		return $line['value'];
	}
	
}

?>