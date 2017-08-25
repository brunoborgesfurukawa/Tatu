<?php
class PessoasPermissao extends Record {

	const TABLE = 'PessoasPermissoes';
	const PK = 'codPessoa, codPermissao';

	static function getPermissoesPessoa($codPessoa) {
		$sql = PessoasPermissao::find(
			array('codPessoa = ?', $codPessoa),
			array('select' => 'codPermissao'));

		$resultado = array();

		foreach ($sql as $permissoes) {
			$resultado[] = $permissoes->codPermissao;
		}

		return($resultado);
	}

	static function verificaPermissao($codPessoa, $necessaria, $tipoPessoa = "qualquer", $tipoUsuario = NULL) {
		$permissoes = PessoasPermissao::getPermissoesPessoa($codPessoa);

		foreach ($permissoes as $permissao) {
			if ($permissao == $necessaria || $permissao == 1) {
				return true;
			}
		}

		if (isset($tipoUsuario)) {
			foreach ($tipoUsuario as $tipo) {
				if ($tipo == $tipoPessoa) {
					return true;
				}
			}
		}

		return false;
	}

	static function apagaPermissoes($codPessoa) {
		$criteria = new Criteria();
		$criteria->add(new Filter('codPessoa', '=', $codPessoa));
		$repos = new Repository('PessoasPermissao');
		$repos->delete($criteria);

	}

	static function verificaContato($codContato, $todosContatos) {
		foreach ($todosContatos as $contato) {
			if ($codContato == $contato) {
				return true;
			}
		}

		return false;
	}

	static function verificaGrupo($codGrupo, $todosGrupos) {
		foreach ($todosGrupos as $grupo) {
			if ($codGrupo == $grupo) {
				return true;
			}
		}

		return false;
	}

	static function verificaPatronato($codPatronato, $todosPatronatos) {
		foreach ($todosPatronatos as $patronato) {
			if ($codPatronato == $patronato) {
				return true;
			}
		}

		return false;
	}

	static function verificaCentro($codCentro, $todosCentros) {
		foreach ($todosCentros as $centro) {
			if ($codCentro == $centro) {
				return true;
			}
		}

		return false;
	}

}

?>
