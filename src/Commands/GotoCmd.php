<?php

namespace RAM\Commands;

use \RAM\Commands\Command;

class GotoCmd extends \RAM\Commands\Command{

	private $line;

	public function __construct($line){
		$this->line = $line;
	}

	public function execute(\RAM\Register $reg, \RAM\Cell $acc){
		$line = $this->resolve($this->line, $reg, $acc);
		return $line['value'];
	}
	
}

?>