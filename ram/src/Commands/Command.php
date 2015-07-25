<?php

namespace Commands;

abstract class Command implements \Commands\ICommand{

	const LITERAL = 0;
	const REFERENCE = 1;
	const CELL_REFERENCE = 2;
	const ACCUMULATOR = 3;
	
	protected function parseSymbol($sym){
		if(preg_match_all('/(@{0,1})([1-9]{1,})|(#)(\d{1,})|(A)/', $sym, $m) == 0){
			throw new \Exception('Invalid symbol '.$sym);
		}

		$type = NULL;
		$value = NULL;
		
		if($m[3][0] == '#'){
			$type = self::LITERAL;
			$value = $m[4][0];
		}

		if($m[1][0] == '@'){
			$type = self::CELL_REFERENCE;
			$value = $m[2][0];
		}

		if($m[1][0] == '' && $m[3][0] != '#' && $m[5][0] != 'A'){
			$type = self::REFERENCE;
			$value = $m[2][0];
		}
		
		if($m[5][0] == 'A'){
			$type = self::ACCUMULATOR;
			$value = 0;
		}
		
		return array('type' => $type, 'value' => intval($value));
	}
	
	protected function resolveIndex($op, \Components\Register $reg, \Components\Cell $acc){
		$op = $this->parseSymbol($op);
	
		$value = NULL;
		$type = NULL;
		
		if ($op['type'] == Command::REFERENCE){		

			$type = Command::REFERENCE;
			$value = $op['value'];	

		}else if ($op['type'] == Command::CELL_REFERENCE){

			$type = Command::CELL_REFERENCE;
			$value = $reg->get($op['value'])->getValue();

		}else if ($op['type'] == Command::LITERAL){
		
			$type = Command::LITERAL;
			$value = $op['value'];
			
		}else if ($op['type'] == Command::ACCUMULATOR){
		
			$type = Command::ACCUMULATOR;
			
		}
		
		return array('type' => $type, 'value' => $value);
	}
	
	protected function resolve($op, \Components\Register $reg, \Components\Cell $acc){
	
		$op = $this->parseSymbol($op);
	
		$value = NULL;
		$type = NULL;
		
		if ($op['type'] == Command::REFERENCE){		

			$type = Command::REFERENCE;
			$value = $reg->get($op['value'])->getValue();	

		}else if ($op['type'] == Command::CELL_REFERENCE){

			$type = Command::CELL_REFERENCE;
			$ind = $reg->get($op['value'])->getValue();
			$value = $reg->get($ind)->getValue();

		}else if ($op['type'] == Command::LITERAL){
		
			$type = Command::LITERAL;
			$value = $op['value'];
			
		}else if ($op['type'] == Command::ACCUMULATOR){
		
			$type = Command::ACCUMULATOR;
			$value = $acc->getValue();
			
		}
		
		return array('type' => $type, 'value' => intval($value));
	}
	
}

?>