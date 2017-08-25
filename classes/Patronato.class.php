<?php
class Patronato extends Record {

	const TABLE = 'Patronatos';
	const PK = 'codPatronato';

	static function getPesquisa($inicio, $pesquisa, $codGestor = "qualquer", $patronatosEspecificos = NULL) {
		// Tratamento dos parâmetros.
		if ($codGestor == "qualquer") {
			$where = array('Patronatos.nome LIKE ?', $pesquisa);
		} else {
			$where = array('Patronatos.nome LIKE ? AND Patronatos.codGestor = ?', $pesquisa, $codGestor);
		}

		if (isset($patronatosEspecificos)) {
			$listaPatronatos = "IN(";
			foreach ($patronatosEspecificos as $patronato) {
				$listaPatronatos .= "$patronato, ";
			}
			$listaPatronatos .= "'')";
			$where[0] .= " AND Patronatos.codPatronato " . $listaPatronatos;
		}

		// Definição das clausulas para a pesquisa.
		$clausulas = array('select' => 'Patronatos.codPatronato, Patronatos.codGestor, Patronatos.nome, Pessoas.nome as nomeGestor',
				  		   'joins' => 'INNER JOIN Pessoas ON Patronatos.codGestor = Pessoas.codPessoa',
				  		   'offset' => $inicio,
		  		  		   'limit' =>  TAMANHO_PAGINA
		  		  	);
		// Chamada da pesquisa com o where e as clausulas definidas.
		$sql = Patronato::find($where, $clausulas);

		// Mudança das clausulas para que dessa vez seja exibido todos os resultados.
		$clausulas = array('joins' => 'INNER JOIN Pessoas ON Patronatos.codGestor = Pessoas.codPessoa');

		// Chamada do count com clausulas que não limita a quantidade de resultados.
		$count = Patronato::count($where, $clausulas);

		return (array('resultado' => $sql, 'quantidade' => $count));
	}

	function getPatronatoMembro($codPatronato) {
		$sql = Patronato::find(
			array('Patronatos.codPatronato = ?', $codPatronato),
			array('select' => 'Patronatos.codPatronato, Patronatos.codGestor, Patronatos.nome, Pessoas.nome as nomeGestor',
				  'joins' => 'INNER JOIN Pessoas ON Patronatos.codGestor = Pessoas.codPessoa'
			)
		);
		return $sql;
	}

	function getGestor($codGestor = null) {
		$gestor = new Pessoa($codGestor);

		return $gestor;
	}

	function getPessoa() {
		$repos = new Repository('PatronatoMembro');

		$criteria = new Criteria();
		$criteria->add(new Filter('codPatronato', '=', $this->codPatronato));

		return $repos->load($criteria);
	}
}
?>
