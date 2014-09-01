<?php

namespace RAM\Commands;

interface ICommand{

	public function execute(\RAM\Register $reg, \RAM\Cell $acc);
	
}

?>