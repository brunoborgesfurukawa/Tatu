<?php
class Contribuinte extends Record {

	const TABLE = 'Contribuintes';
	const PK = 'codPessoa';

	function getContribuintes() {
		$sql = Contribuintes::find(
			null,
			array('select' => 'Contribuintes.*, Pessoas.*,Contribuicoes.*',
				  'joins' => 'INNER JOIN Contribuintes ON Contribuintes.codPessoa = Pessoas.codPessoa
				  			  INNER JOIN Contribuintes ON Contribuintes.codColaborador = Pessoas.codPessoa'
			)
		);
		return $sql;
	}

}

?>


