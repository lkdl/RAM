<?php

namespace Commands;

interface ICommand{

	public function execute(\Components\Register $reg, \Components\Cell $acc);
	
}

?>