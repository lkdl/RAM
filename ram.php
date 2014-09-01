<?php

require 'vendor/autoload.php';

if($_REQUEST['program'] == NULL){
	echo 'Kein Programm angegeben.';
	exit(0);
}

$p = new \RAM\Parser($_REQUEST['program']);


$p->parse();

$program = $p -> getParsed();

$reg = new \RAM\Register();
$acc = new \RAM\Cell();

//$reg->get(2)->setValue(1);
//$reg->get(3)->setValue(1);

echo "Acc: ".$acc->getValue().'<br>';

$pc = 0;
while($pc >= 0 && $pc < count($program)){
	try{
		$ret = $program[$pc]->execute($reg, $acc);
	}catch(\Exception $e){
		echo $e->getMessage();
		echo 'Fehler!';
	}

	$pc++;

	if($ret > -1){
		$pc = $ret-1;
		if($pc < 0){
			throw new Exception('GOTO line number has to be at least 1');
		}
	}else if($ret == -2){
		break;
	}
}

$amount = 10;

echo '<table>';
echo '<tr>';
for($i = 1; $i <= $amount; $i++){
	echo '<td>R'.$i.'</td>';
}
echo '</tr>';
echo '<tr>';
for($i = 1; $i <= $amount; $i++){
	echo '<td>'.$reg->get($i)->getValue().'</td>';
}
echo '</tr>';
echo '</table>';
echo '<br>Acc: '.$acc->getValue().'<br>';

?>