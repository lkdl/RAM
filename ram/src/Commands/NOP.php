<?php

namespace Commands;

class NOP extends \Commands\Command{

	public function __construct(){}

	public function execute(\Components\Register $reg, \Components\Cell $acc){
		return -1;
	}
	
}

?>