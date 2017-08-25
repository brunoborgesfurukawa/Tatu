<?php
class Contato extends Record {

	const TABLE = 'Contatos';
	const PK = 'codPessoa';

	static function getContatos($codColaborador, $codContatoTipo = false) {
		$condicoes = ($codContatoTipo == false) ? array('codColaborador = ? AND codContatoStatus != 4', $codColaborador) : array('codColaborador = ? AND codContatoTipo = ? AND codContatoStatus != 4', $codColaborador, $codContatoTipo);
		$sql = Contato::find(
			$condicoes,
			array('select' => 'Contatos.codPessoa, codContatoTipo, codContatoStatus, dados',
				  'joins' => 'INNER JOIN Pessoas ON Contatos.codPessoa = Pessoas.codPessoa',
				  'order' => 'nome'
			)
		);
		return $sql;
	}

	static function getNumeroContatos($codColaborador, $codContatoTipo = false) {
		$condicoes = ($codContatoTipo == false) ? array('codColaborador = ? AND codContatoStatus != 4', $codColaborador) : array('codColaborador = ? AND codContatoTipo = ? AND codContatoStatus != 4', $codColaborador, $codContatoTipo);
		$sql = Contato::count(
			$condicoes
		);
		return $sql;
	}

	static function getContribuicao() {
		$repos = new Repository('Contribuicoes');

		$criteria = new Criteria();
		$criteria->add(new Filter('codPessoa', '=', $this->codPessoa));

		return $repos->load($criteria);
	}

	function getColaborador() {
		$repos = new Repository('Pessoa');

		$criteria = new Criteria();
		$criteria->add(new Filter('Pessoas.codPessoa', '=', $this->codColaborador));

		return $repos->load($criteria);
	}

}
?>
