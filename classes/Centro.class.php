<?php
class Centro extends Record {

	const TABLE = 'Centros';
	const PK = 'codCentro';

	static function getCentro($inicio, $pesquisa, $codGestor = "qualquer", $centrosEspecificos = NULL) {
		// Tratamento dos parâmetros.
		if ($codGestor == "qualquer") {
			$where = array('Centros.nome LIKE ?', $pesquisa);
		} else {
			$where = array('Centros.nome LIKE ? AND Centros.codGestor = ?', $pesquisa, $codGestor);
		}

		if (isset($centrosEspecificos)) {
			$listaCentros = "IN(";
			foreach ($centrosEspecificos as $centro) {
				$listaCentros .= "$centro, ";
			}
			$listaCentros .= "'')";
			$where[0] .= " AND Centros.codCentro " . $listaCentros;
		}

		$clausulas = array('select' => 'Centros.codCentro, Centros.nome, Centros.local, Pessoas.nome AS gestor,Centros.codGestor as codGestor',
						  'joins' => 'INNER JOIN Pessoas ON Pessoas.codPessoa = Centros.codGestor',
						  'offset' => $inicio,
						  'order' => 'Centros.nome',
		  				  'limit' =>  TAMANHO_PAGINA
					);

		$sql = Centro::find($where, $clausulas);

		// Mudança das clausulas para que dessa vez seja exibido todos os resultados.
		$clausulas = array('joins' => 'INNER JOIN Pessoas ON Pessoas.codPessoa = Centros.codGestor');

		// Chamada do count com clausulas que não limita a quantidade de resultados.
		$count = Centro::count($where, $clausulas);

		return (array('resultado' => $sql, 'quantidade' => $count));
	}

	static function getCentros(){
		$sql = Centro::find(
			array(),
			array("order" => "nome"));
		return $sql;
	}


	static function getCentroMembro($codCentro) {
		$sql = Centro::find(
			array('codCentro = ?', $codCentro),
			array('select' => 'Centros.nome, Centros.local, Centros.codGestor, Pessoas.nome AS gestor',
				  'joins' =>  'INNER JOIN Pessoas ON Pessoas.codPessoa = Centros.codGestor'
			)
		);
		return $sql;
	}

	static function getMembro() {
		$sql = Pessoa::find(
			array('colaborador = ?', 1),
			array('select' => 'codPessoa, nome'
			)
		);
		return $sql;
	}

	static function getGrupos($codCentro) {
		$sql = Grupo::find(
			array('codCentro = ?', $codCentro),
			array('select' => 'nome,codGrupo')
		);
		return $sql;
	}

	function getGrupo() {
		$repos = new Repository('Grupo');

		$criteria = new Criteria();
		$criteria->add(new Filter('codCentro', '=', $this->codCentro));

		return $repos->load($criteria);
	}

	function getDados() {
		$centros = Centro::getCentros();
		$numeroCentros = count($centros);
		foreach ($centros as $centro) {
			$grupos = Centro::getGrupos($centro->codCentro);
			$numeroGrupos[$centro->codCentro] = count($grupos);
			foreach ($grupos as $grupo) {
				$colaboradores = GrupoMembro::getMembro($grupo->codGrupo,1);
				$numeroColaboradores[$centro->codCentro][$grupo->codGrupo] = count($colaboradores);
				foreach ($colaboradores as $colaborador) {
					$contatos = Contato::getContatos($colaborador->codPessoa,1);
					$contribuintes = Contato::getContatos($colaborador->codPessoa,2);
					$numeroContatos[$centro->codCentro][$grupo->codGrupo][$colaborador->codPessoa] = array('Contribuintes' => count($contribuintes),'Contatos' => count($contatos));
				}
			}
		}
		return array('Centros' => $numeroCentros, 'Grupos' => $numeroGrupos, 'Colaboradores' => $numeroColaboradores, 'Contatos' => $numeroContatos);
	}
}
