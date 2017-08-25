<?php
class Registro extends Record {

	const TABLE = 'Registros';
	const PK = 'codRegistro';

	static function getRegistro($codPessoa) {
		$sql = Pessoa::find(
			array('Registros.codPessoa = ?', $codPessoa),
			array('select' => 'nome, Registros.codPessoa, dataHora, descricao',
				  'joins' => 'INNER JOIN Registros ON Pessoas.codPessoa = Registros.codPessoa'
			)
		);
		return $sql;
	}
}
?>
