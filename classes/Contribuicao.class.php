<?php
class Contribuicao extends Record {

	const TABLE = 'Contribuicoes';
	const PK = 'codContribuicao';

	static function getContribuicao($inicio, $contribuicoesEspecificas = NULL) {
		$where = NULL;

		// Tratamento dos parâmetros.
		if (isset($contribuicoesEspecificas)) {
			$listaContribuicoes = "IN(";
			foreach ($contribuicoesEspecificas as $contribuicao) {
				$listaContribuicoes .= "$contribuicao, ";
			}
			$listaContribuicoes .= "'')";
			$where = "Contribuicoes.codContribuicao " . $listaContribuicoes;
		}

		$clausulas = array(
						'select' => 'Contribuicoes.*, Pessoas.*,ContribuicoesStatus.descricao AS descricaoStatus, ContribuicoesTipo.*,Moedas.descricao AS moedaTipo',
				  		'joins' => 'INNER JOIN Pessoas ON Contribuicoes.codPessoa = Pessoas.codPessoa
				  		INNER JOIN ContribuicoesStatus ON Contribuicoes.codStatus = ContribuicoesStatus.codStatus
				  		INNER JOIN ContribuicoesTipo ON Contribuicoes.codTipo = ContribuicoesTipo.codTipo
				  		INNER JOIN Moedas ON Contribuicoes.codMoeda = Moedas.codMoeda',
				  		'offset' => $inicio,
						'order' => 'Contribuicoes.dataFim DESC',
		  				'limit' =>  TAMANHO_PAGINA
					);

		$sql = Contribuicao::find($where, $clausulas);

		$clausulas = array(
				  		'joins' => 'INNER JOIN Pessoas ON Contribuicoes.codPessoa = Pessoas.codPessoa
				  		INNER JOIN ContribuicoesStatus ON Contribuicoes.codStatus = ContribuicoesStatus.codStatus
				  		INNER JOIN ContribuicoesTipo ON Contribuicoes.codTipo = ContribuicoesTipo.codTipo
				  		INNER JOIN Moedas ON Contribuicoes.codMoeda = Moedas.codMoeda'
					);

		$count = Contribuicao::count($where, $clausulas);

		return (array('resultado' => $sql, 'quantidade' => $count));
	}

	static function getTotal($nome = '%%') {
		$repos = new Repository('Contribuicao');

		$criteria = new Criteria();
		$criteria->add(new Filter('nome', 'LIKE', $nome));

		return $repos->count($criteria);
	}

	static function getContribuicoes($cod = null, $chamar = "tudo") {
		switch ($chamar) {
			case 'tudo':
				$condicao = null;
				$joins = 'INNER JOIN Centros ON Contribuicoes.codCentro = Centros.codCentro';
				break;

			case 'centro':
				$condicao = array('Contribuicoes.codCentro = ? ', $cod);
				$joins = 'INNER JOIN Grupos ON Contribuicoes.codGrupo = Grupos.codGrupo';
				break;

			case 'grupo':
				$condicao = array('Contribuicoes.codGrupo = ? ', $cod);
				$joins = 'INNER JOIN Pessoas ON Contribuicoes.codColaborador = Pessoas.codPessoa';
				break;

			case 'colaborador':
				$condicao = array('Contribuicoes.codColaborador = ? ', $cod);
				$joins = 'INNER JOIN Pessoas ON Contribuicoes.codPessoa = Pessoas.codPessoa';
				break;
		}
		$sql = Contribuicao::find(
			$condicao,
			array('select' => 'codMoeda, base, valor, dataInicio, dataFim, nome',
				  'joins' => $joins,
				  'order' => 'valor')
		);
		return $sql;
	}

	static function getContribuicoesAtivas() {
		$sql = Contribuicao::find(
			array('codStatus = 1 OR codStatus = 2'),
			array('select' => 'codContribuicao, codPessoa, codStatus, dataFim' )
		);
		return $sql;
	}

}

?>


