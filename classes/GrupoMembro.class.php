<?php
class GrupoMembro extends Record {

	const TABLE = 'GruposMembros';
	const PK = 'codMembro';

	static function getMembro($codGrupo, $membroAtivo = false) {
		$condicoes = ($membroAtivo == false) ? array('GruposMembros.codGrupo = ?', $codGrupo) : array('GruposMembros.codGrupo = ? AND GruposMembros.membroAtivo = ?', $codGrupo, $membroAtivo);
		$sql = Pessoa::find(
			$condicoes,
			array('select' => 'nome, GruposMembros.codPessoa, codMembro, codGrupo',
				  'joins' => 'INNER JOIN GruposMembros ON Pessoas.codPessoa = GruposMembros.codPessoa',
				  'order' => 'nome'
			)
		);
		return $sql;
	}

		static function getNumeroMembros($codGrupo, $membroAtivo = false) {
		$condicoes = ($membroAtivo == false) ? array('GruposMembros.codGrupo = ?', $codGrupo) : array('GruposMembros.codGrupo = ? AND GruposMembros.membroAtivo = ?', $codGrupo, $membroAtivo);
		$sql = Pessoa::count(
			$condicoes,
			array('joins' => 'INNER JOIN GruposMembros ON Pessoas.codPessoa = GruposMembros.codPessoa'
			)
		);
		return $sql;
	}

	static function getGrupo($codPessoa) {
		$sql = GrupoMembro::find(
			array('codPessoa = ? AND membroAtivo = 1', $codPessoa),
			array('select' => 'codGrupo'
			)
		);
		return $sql;
	}
}
?>
