<?php

namespace Parser;

class StdParser{
	
	private $lines;
	private $parsed = array();

	public function __construct($input){
		$this->lines = preg_split('/\r\n|\r|\n/', $input);
	}

	public function parse(){
		if(count($this->parsed) > 0){
			return $this->parsed;
		}

		$lc = 0;
		foreach($this->lines as $line){
			$line = trim($line);
			$lc++;
			$cmd = NULL;

			if(preg_match_all('/^\-\-.*$/', $line, $m) || preg_match_all('/^\s*$/', $line, $m)){
				$cmd = new \Commands\NOP();
			}

			if(preg_match_all('/^LOAD (.+)$/', $line, $m)){
				$cmd = new \Commands\Load($m[1][0]);
			}

			if(preg_match_all('/^STORE (.+)$/', $line, $m)){
				$cmd = new \Commands\Store($m[1][0]);
			}
			
			if(preg_match_all('/^ADD (.+)$/', $line, $m)){
				$cmd = new \Commands\Add($m[1][0]);
			}
			
			if(preg_match_all('/^SUB (.+)$/', $line, $m)){
				$cmd = new \Commands\Sub($m[1][0]);
			}
			
			if(preg_match_all('/^MULT (.+)$/', $line, $m)){
				$cmd = new \Commands\Mult($m[1][0]);
			}
			
			if(preg_match_all('/^DIV (.+)$/', $line, $m)){
				$cmd = new \Commands\Div($m[1][0]);
			}
			
			if(preg_match_all('/^END$/', $line, $m)){
				$cmd = new \Commands\End();
			}
			
			if(preg_match_all('/^GOTO (.+)$/', $line, $m)){
				$cmd = new \Commands\GotoCmd($m[1][0]);
			}
			
			if(preg_match_all('/^IF (.+?)\s*(>|>=|=|<=|<)\s*(.+?) GOTO (.+?)$/', $line, $m)){
				$cmd = new \Commands\IfCmd($m[1][0],$m[2][0],$m[3][0],$m[4][0]);
			}

			if(!is_null($cmd)){
				$this->parsed[] = $cmd;
			}else{
				throw new \RAMException('Unknown command: '.$line, $lc);
			}
		}
	}

	public function getParsed(){
		return $this->parsed;
	}

}

?>