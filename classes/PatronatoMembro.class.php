<?php
class PatronatoMembro extends Record {

	const TABLE = 'PatronatosMembros';
	const PK = 'codMembro';

	function getMembro($codPatronato, $membroAtivo = false) {
		$condicoes = ($membroAtivo == false) ? array('PatronatosMembros.codPatronato = ?', $codPatronato) : array('PatronatosMembros.codPatronato = ? AND PatronatosMembros.membroAtivo = ?', $codPatronato, $membroAtivo);
		$sql = Pessoa::find(
			$condicoes,
			array('select' => 'nome, Pessoas.codPessoa, codMembro',
				  'joins' => 'INNER JOIN PatronatosMembros ON Pessoas.codPessoa = PatronatosMembros.codPessoa'
			)
		);
		return $sql;
	}
}
?>
