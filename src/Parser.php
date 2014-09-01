<?php

namespace RAM;

class Parser{
	
	private $lines;
	private $parsed = array();

	public function __construct($input){
		$this->lines = explode("\r\n", $input);
	}

	public function parse(){
		if(count($this->parsed) > 0){
			return $this->parsed;
		}

		foreach($this->lines as $line){
			$line = trim($line);
			if($line == ''){
				continue;
			}

			$cmd = NULL;

			if(preg_match_all('/^LOAD (.+)$/', $line, $m)){
				$cmd = new \RAM\Commands\Load($m[1][0]);
			}

			if(preg_match_all('/^STORE (.+)$/', $line, $m)){
				$cmd = new \RAM\Commands\Store($m[1][0]);
			}
			
			if(preg_match_all('/^ADD (.+)$/', $line, $m)){
				$cmd = new \RAM\Commands\Add($m[1][0]);
			}
			
			if(preg_match_all('/^SUB (.+)$/', $line, $m)){
				$cmd = new \RAM\Commands\Sub($m[1][0]);
			}
			
			if(preg_match_all('/^MULT (.+)$/', $line, $m)){
				$cmd = new \RAM\Commands\Mult($m[1][0]);
			}
			
			if(preg_match_all('/^DIV (.+)$/', $line, $m)){
				$cmd = new \RAM\Commands\Div($m[1][0]);
			}
			
			if(preg_match_all('/^END$/', $line, $m)){
				$cmd = new \RAM\Commands\End();
			}
			
			if(preg_match_all('/^GOTO (.+)$/', $line, $m)){
				$cmd = new \RAM\Commands\GotoCmd($m[1][0]);
			}
			
			if(preg_match_all('/^IF (.+?)\s*(>|>=|=|<=|<)\s*(.+?) GOTO (.+?)$/', $line, $m)){
				$cmd = new \RAM\Commands\IfCmd($m[1][0],$m[2][0],$m[3][0],$m[4][0]);
			}

			if(!is_null($cmd)){
				$this->parsed[] = $cmd;
			}else{
				throw new \Exception('Unknown command '.$line);
			}
		}
	}

	public function getParsed(){
		return $this->parsed;
	}

}

?>