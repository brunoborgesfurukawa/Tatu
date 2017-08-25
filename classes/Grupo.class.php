<?php
class Grupo extends Record {

	const TABLE = 'Grupos';
	const PK = 'codGrupo';

	static function getGrupo($inicio, $pesquisa, $codGerente = "qualquer", $gruposEspecificos = NULL) {
		// Tratamento dos parâmetros.
		if ($codGerente == "qualquer") {
			$where = array('Grupos.nome LIKE ?', $pesquisa);
		} else {
			$where = array('Grupos.nome LIKE ? AND Grupos.codGerente = ?', $pesquisa, $codGerente);
		}

		if (isset($gruposEspecificos)) {
			$listaGrupos = "IN(";
			foreach ($gruposEspecificos as $grupo) {
				$listaGrupos .= "$grupo, ";
			}
			$listaGrupos .= "'')";
			$where[0] .= " AND Grupos.codGrupo " . $listaGrupos;
		}

		// Definição das clausulas para a pesquisa.
		$clausulas = array('select' => 'Grupos.codGrupo, Grupos.nome, Centros.nome as nomeCentro, Pessoas.nome as nomeGerente, Grupos.codGerente as codGerente,Grupos.codCentro as codCentro',
						   'joins' => 'INNER JOIN Centros ON Centros.codCentro = Grupos.codCentro
						   INNER JOIN Pessoas ON Grupos.codGerente = Pessoas.codPessoa',
						   'offset' => $inicio,
						   'order' => 'Grupos.nome',
		  				   'limit' =>  TAMANHO_PAGINA
					);

		// Chamada da pesquisa com o where e as clausulas definidas.
		$sql = Grupo::find($where, $clausulas);

		// Mudança das clausulas para que dessa vez seja exibido todos os resultados.
		$clausulas = array('joins' => 'INNER JOIN Centros ON Centros.codCentro = Grupos.codCentro
						   INNER JOIN Pessoas ON Grupos.codGerente = Pessoas.codPessoa');

		// Chamada do count com clausulas que não limita a quantidade de resultados.
		$count = Grupo::count($where, $clausulas);

		return (array('resultado' => $sql, 'quantidade' => $count));
	}

	static function getGrupoMembro($codGrupo) {
		$sql = Grupo::find(
			array('codGrupo = ?', $codGrupo),
			array('select' => 'Grupos.codGrupo, Grupos.nome, Grupos.codGerente, Grupos.codCentro, Centros.nome as nomeCentro, Pessoas.nome as nomeGerente',
				  'joins' => 'INNER JOIN Centros ON Centros.codCentro = Grupos.codCentro
				  INNER JOIN Pessoas ON Grupos.codGerente = Pessoas.codPessoa'
			)
		);
		return $sql;
	}

	static function getGerente($codGerente = null) {
		$gerente = new Pessoa($codGerente);

		return $gerente;
	}

	static function getCentro($codCentro = null) {
		$centro = new Centro($codCentro);

		return $centro;
	}

	function getPessoa() {
		$repos = new Repository('Pessoa');

		$criteria = new Criteria();
		$criteria->add(new Filter('codPessoa', '=', $this->codGerente));

		return $repos->load($criteria);
	}
}
?>
