<?php

class RAM{

	private $source;
	private $result = NULL;

	public function __construct($source){

		if (is_null($source) || trim($source) === ''){
			throw new \RAMException('No input provided', 1);
		}

		$this->source = $source;
	}

	public function execute(){
		$p = new \Parser\StdParser($this->source);
		$p->parse();

		$program = $p -> getParsed();

		$reg = new \Components\Register();
		$acc = new \Components\Cell();
		$pc = 0;

		$steps = 0;

		while($pc >= 0 && $pc < count($program)){

			$steps++;
			try{
				$ret = $program[$pc]->execute($reg, $acc);
			}catch(\Exception $e){
				throw new \RAMException($e->getMessage(), $pc+1);
			}
			
			$pc++;

			if($ret > -1){
				$backup_pc = $pc; /* needed for output if something went wrong */
				$pc = $ret-1;
				if($pc < 0){
					throw new \RAMException('Tried to go to an invalid line (tried '.$pc.')', $backup_pc+1);
				}
			}else if($ret == -2){
				break;
			}
		}

		// {acc: 1, steps: 2099, register: [{id: 1, value: 1},{id: 2, value: 2},{id: 3, value: 3},{id: 4, value: 4},{id: 5, value: 5},{id: 6, value: 7},{id: 8, value: 8},{id: 9, value: 9},{id: 10, value: 10},{id: 7, value: 88}]};
		// 
		// 
		
		$amount = 10;
		$register = array();
		for($i = 1; $i <= $amount; $i++){
			$register[] = array('id' => $i, 'value' => $reg->get($i)->getValue());
		}

		$this->result = array('acc' => $acc->getValue(), 'steps' => $steps, 'register' => $register);
	}

	public function getResult(){
		return $this->result;
	}

}

?>