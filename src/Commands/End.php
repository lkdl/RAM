<?php

namespace RAM\Commands;

use \RAM\Commands\Command;

class End extends \RAM\Commands\Command{

	public function __construct(){
	}

	public function execute(\RAM\Register $reg, \RAM\Cell $acc){
	
		return -2;
	}
	
}

?>