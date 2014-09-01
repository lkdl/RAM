<?php

namespace RAM\Commands;

use \RAM\Commands\Command;

class IfCmd extends \RAM\Commands\Command{

	private $op1;
	private $op2;
	private $compare;
	private $line;

	public function __construct($op1, $compare, $op2, $goto){
		$this->op1 = $op1;
		$this->op2 = $op2;
		$this->compare = $compare;
		$this->line = $goto;
		
	}

	public function execute(\RAM\Register $reg, \RAM\Cell $acc){
	
		$op1 = $this->resolve($this->op1, $reg, $acc);
		$op2 = $this->resolve($this->op2, $reg, $acc);
		$line = $this->resolve($this->line, $reg, $acc);
		
		$ev = false;
		
		if($this->compare == '='){
			$ev = ($op1['value'] == $op2['value']);
		}
		
		if($this->compare == '>'){
			$ev = ($op1['value'] > $op2['value']);
		}
		
		if($this->compare == '>='){
			$ev = ($op1['value'] >= $op2['value']);
		}
		
		if($this->compare == '<'){
			$ev = ($op1['value'] < $op2['value']);
		}
		
		if($this->compare == '<='){
			$ev = ($op1['value'] <= $op2['value']);
		}
		
		if($ev){
			return $line['value'];
		}else{
			return -1;
		}
	}
	
}

?>