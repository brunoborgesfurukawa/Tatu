<?php
class Moeda extends Record {

	const TABLE = 'Moedas';
	const PK = 'codMoeda';

	static function getMoeda() {
		$sql = Moeda::find("",
			array('select' => 'codMoeda,descricao')
		);
		return $sql;
	}
}

?>

