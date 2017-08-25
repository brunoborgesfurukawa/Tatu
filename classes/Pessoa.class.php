<?php
class Pessoa extends Record {

	const TABLE = 'Pessoas';
	const PK = 'codPessoa';

	/**
	 * Faz uma pesquisa na tabela pessoas e retorna uma quantidade
	 * limitada de registros junto com um inteiro que guarda o valor
	 * de um COUNT da mesma pesquisa, porém, sem limitações de resultados.
	 *
	 * @param  integer $inicio    				A partir de qual resultado será feito a pesquisa.
	 *
	 * @param  array   $condicoes 				Guarda todas as características do WHERE na pesquisa.
	 *                            				Os índices utilizados são os seguintes:
	 *                            				[pesquisa] - Que caracteres devem existir no campo nome;
	 *
	 *                            				[tipo] - NULL irá pesquisar quem não é colaborador e 1
	 *                                			pesquisará somente colaboradores;
	 *
	 *                            				[codColaborador] - Caso a pesquisa precise retornar
	 *                             				contatos de um colaborador específico, este índice
	 *                              			deve conter o codColaborador dele.
	 *
	 * @param  array   $pessoasEspecificas		Array que armazena o codPessoa de quem deve aparecer
	 *                                      	caso a pesquisa se restrinja a somente um grupo de pessoas.
	 *
	 * @return array              				Retorna um array de dois índices, o 'resultado' contém
	 *                            				o find e o 'quantidade' armazena o count.
	 */
	static function getPessoas($inicio, $condicoes = array('pesquisa' => "", 'tipo' => "qualquer", 'codColaborador' => "qualquer", 'aprovado' => NULL), $pessoasEspecificas = NULL) {
		//FIXME Arrumar outra maneira de retornar uma pesquisa com limite de resultados, e um
		//inteiro que conta a mesma pesquisa sem limites de resultados.

		// Tratamento dos parâmetros.
		if ($condicoes['tipo'] == "qualquer") {
			$where = array('nome LIKE ?', $condicoes['pesquisa']);
		} elseif ($condicoes['tipo'] == "NULL") {
			$where = array('nome LIKE ? AND colaborador IS NULL', $condicoes['pesquisa']);
		} else {
			$where = array('nome LIKE ? AND colaborador = ?', $condicoes['pesquisa'], $condicoes['tipo']);
		}

		if ($condicoes['codColaborador'] != "qualquer") {
			$where[0] .= " AND codColaborador = ?";
			$where[] = $condicoes['codColaborador'];
		}

		if ($condicoes['aprovado'] === "0") {
			$where[0] .= " AND aprovacao = 1";
		}

		if (isset($pessoasEspecificas)) {
			$listaPessoas = "IN(";
			foreach ($pessoasEspecificas as $pessoa) {
				$listaPessoas .= "$pessoa, ";
			}
			$listaPessoas .= "'')";
			$where[0] .= " AND Pessoas.codPessoa " . $listaPessoas;
		}

		// Definição das clausulas para a pesquisa.
		$clausulas = array('select' => 'Pessoas.codPessoa, nome, dataNascimento, email, aprovacao',
				  'order' =>  'nome',
				  'offset' => $inicio,
		  		  'limit' =>  TAMANHO_PAGINA,
		  		  'joins' => 'LEFT JOIN Contatos ON Pessoas.codPessoa = Contatos.codPessoa'
			);

		// Chamada da pesquisa com o where e as clausulas definidas.
		$sql = Pessoa::find($where, $clausulas);

		// Mudança das clausulas para que dessa vez seja exibido todos os resultados.
		$clausulas = array('joins' => 'LEFT JOIN Contatos ON Pessoas.codPessoa = Contatos.codPessoa');

		// Chamada do count com clausulas que não limita a quantidade de resultados.
		$count = Pessoa::count($where, $clausulas);

		return (array('resultado' => $sql, 'quantidade' => $count));
	}

	static function getEmails() {
		$sql = Pessoa::find(
			array(),
			array('select' => 'email, codPessoa')
		);
		return $sql;
	}

	static function getPessoasEmAprovacao($inicio, $pesquisa){
		$sql = Pessoa::find(
			array('nome LIKE ? AND aprovacao = 1', $pesquisa),
			array('select' => 'codPessoa, nome, dataNascimento',
				  'order' =>  'nome',
				  'offset' => $inicio,
				  'limit' =>  TAMANHO_PAGINA
			)
		);
		return $sql;
	}

	function getTelefone() {
		$repos = new Repository('Telefone');

		$criteria = new Criteria();
		$criteria->add(new Filter('codPessoa', '=', $this->codPessoa));

		return $repos->load($criteria);
	}

	function getGrupo() {
		$repos = new Repository('Grupo');

		$criteria = new Criteria();
		$criteria->add(new Filter('codGerente', '=', $this->codPessoa));

		return $repos->load($criteria);
	}

	function getEndereco() {
		$repos = new Repository('Endereco');

		$criteria = new Criteria();
		$criteria->add(new Filter('codPessoa', '=', $this->codPessoa));

		return $repos->load($criteria);
	}

	function getContato() {
		$repos = new Repository('Contato');

		$criteria = new Criteria();
		$criteria->add(new Filter('codColaborador', '=', $this->codPessoa));

		return $repos->load($criteria);
	}

	function countContato($codPessoa) {
		$sql = Pessoa::count(
			array("codColaborador = ?", $codPessoa),
			array('joins' => 'LEFT JOIN Contatos ON Pessoas.codPessoa = Contatos.codPessoa')
			);

		return $sql;
	}

	function countCentro($codPessoa) {
		$sql = Pessoa::count(
			array("codGestor = ?", $codPessoa),
			array('joins' => 'LEFT JOIN Centros ON Pessoas.codPessoa = Centros.codGestor')
			);

		return $sql;
	}

	function countGrupo($codPessoa) {
		$sql = Pessoa::count(
			array("codGerente = ?", $codPessoa),
			array('joins' => 'LEFT JOIN Grupos ON Pessoas.codPessoa = Grupos.codGerente')
			);

		return $sql;
	}

	function countPatronato($codPessoa) {
		$sql = Pessoa::count(
			array("codGestor = ?", $codPessoa),
			array('joins' => 'LEFT JOIN Patronatos ON Pessoas.codPessoa = Patronatos.codGestor')
			);

		return $sql;
	}

	function getContribuicao() {
		$repos = new Repository('Contribuicao');

		$criteria = new Criteria();
		$criteria->add(new Filter('codPessoa', '=', $this->codPessoa));

		return $repos->load($criteria);
	}

	function getCentrosGerenciados($codPessoa) {
		$sql = Pessoa::find(
			array("codGestor = ?", $codPessoa),
			array('select' => 'Centros.codCentro',
		  		  'joins' => 'INNER JOIN Centros ON codPessoa = codGestor'));

		return $sql;
	}

	function getGruposGerenciados($codPessoa) {
		$sql = Pessoa::find(
			array("codGerente = ?", $codPessoa),
			array('select' => 'codGrupo',
		  		  'joins' => 'INNER JOIN Grupos ON codPessoa = codGerente'));

		return $sql;
	}

	function getPatronatosGerenciados($codPessoa) {
		$sql = Pessoa::find(
			array("codGestor = ?", $codPessoa),
			array('select' => 'codPatronato',
		  		  'joins' => 'INNER JOIN Patronatos ON codPessoa = codGestor'));

		return $sql;
	}
}
?>
