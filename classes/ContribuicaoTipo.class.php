<?php
class ContribuicaoTipo extends Record {

	const TABLE = 'ContribuicoesTipo';
	const PK = 'codTipo';

	static function getTipo() {
		$sql = ContribuicaoTipo::find("",
			array('select' => 'codTipo,descricao')
		);
		return $sql;
	}

}

?>

