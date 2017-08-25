<?php
// <3
class Usuario {

	// Declaração das propriedades.
	var $codPessoa = 0;
	var $nome = "";
	var $email = "";
	var $permissoes = array();
	var $contatos = 0;		// Quantidade de contatos que o usuário contém.
	var $centros = 0;		// Quantidade de centros gerenciados.
	var $grupos = 0;		// Quantidade de grupos gerenciados.
	var $patronatos = 0;	// Quantidade de patronatos gerenciados.
	var $tipoPessoa = array();
	var $provedor = "";
	var $dados = array();	// Array contendo todos os dados ligados a este usuário.
	var $contatosLigados = 0;		// Quantidade de contatos ligados ao usuário.
	var $centrosLigados = 0;		// Quantidade de centros ligados ao usuário.
	var $gruposLigados = 0;		// Quantidade de grupos ligados ao usuário.
	var $patronatosLigados = 0;	// Quantidade de patronatos ligados ao usuário.
	var $colaboradoresLigados = 0;		// Quantidade de centros ligados ao usuário.
	var $contribuicoesLigadas = 0;		// Quantidade de grupos ligados ao usuário.


	// Assim que instanciado, o objeto dará valores a suas propriedades.
	function __construct($codPessoaLocal = 0, $provedorLocal = "") {
		$this->codPessoa = $codPessoaLocal;
		$this->provedor = $provedorLocal;
		$this->atualizarPropriedades();
	}

	function atualizarPropriedades() {
		$pessoa = new Pessoa($this->codPessoa);
		$this->nome = $pessoa->nome;
		$this->email = $pessoa->email;

		$this->permissoes = PessoasPermissao::getPermissoesPessoa($this->codPessoa);
		$this->contatos = Pessoa::countContato($this->codPessoa);
		$this->centros = Pessoa::countCentro($this->codPessoa);
		$this->grupos = Pessoa::countGrupo($this->codPessoa);
		$this->patronatos = Pessoa::countPatronato($this->codPessoa);
		$this->getTipoPessoa();
		$this->getDados();
		$this->getQuantidadeDadosLigados();
	}

	// Define características ao usuário de acordo com
	// os grupos, centro, patronato, etc, pertinentes a ele.
	//
	// O array é organizado por nível de acesso a informações
	// em ordem crescente.
	function getTipoPessoa() {
		$pessoa = new Pessoa($this->codPessoa);

		if ($this->centros > 0) {
			$this->tipoPessoa[] = "Gestor de centro";
		}

		if ($this->grupos > 0) {
			$this->tipoPessoa[] = "Gerente de grupo";
		}

		if ($this->patronatos > 0) {
			$this->tipoPessoa[] = "Gestor de patronato";
		}

		if ($pessoa->colaborador == 1) {
			$this->tipoPessoa[] = "Colaborador";
		} else {
			$contato = new Contato($this->codPessoa);
			if ($contato->codContatoTipo == 1) {
				$this->tipoPessoa[] = "Contato";
			} else {
				$this->tipoPessoa[] = "Contribuinte";
			}
		}
	}

	// FIXME este método deve ser reestruturado, mas definindo a propriedade 'dados'
	// em um array com a mesma estrutura.
	function getDados() {
		$temCentro = false;
		$temPatronato = false;

		// Verifica qual o maior nível da pessoa.
		switch ($this->tipoPessoa[0]) {

			// Caso o usuário seja gestor de algum centro,
			// os mesmos serão armazenados no primeiro indice.
			case 'Gestor de centro':
				$centros = Pessoa::getCentrosGerenciados($this->codPessoa);

				foreach ($centros as $centro) {
					$this->dados['Centro' . $centro->codCentro] = array("cod" => $centro->codCentro);
				}
			$temCentro = true;

			// Guarda os grupos dos centros e os que são gerenciados pelo usuário.
			case 'Gerente de grupo':
			if ($temCentro) {
				foreach ($this->dados as $indice => $valor) {
					$centro = new Centro($valor["cod"]);
					$grupos = $centro->grupo;

					foreach ($grupos as $grupo) {
						$this->dados[$indice]['Grupo' . $grupo->codGrupo] = array("cod" => $grupo->codGrupo);
					}
				}
			}

			$grupos = Pessoa::getGruposGerenciados($this->codPessoa);

			foreach ($grupos as $grupo) {
				$this->dados['Grupo' . $grupo->codGrupo] = array("cod" => $grupo->codGrupo);
			}

			foreach ($this->dados as $indiceCentro => $centro) {
				if (substr($indiceCentro, 0, 3) == "Cen") {

					foreach ($centro as $indiceGrupo => $grupo) {
						if (substr($indiceGrupo, 0, 3) != "cod") {
							$colaboradores = GrupoMembro::getMembro($grupo["cod"], 1);

							foreach ($colaboradores as $colaborador) {
								$this->dados[$indiceCentro][$indiceGrupo]['Colaborador' . $colaborador->codPessoa] = array("cod" => $colaborador->codPessoa);
							}
						}
					}
				}
			}

			foreach ($this->dados as $indiceGrupo => $grupo) {
				if (substr($indiceGrupo, 0, 3) == "Gru") {
					$colaboradores = GrupoMembro::getMembro($grupo["cod"], 1);

					foreach ($colaboradores as $colaborador) {
						$this->dados[$indiceGrupo]['Colaborador' . $colaborador->codPessoa] = array("cod" => $colaborador->codPessoa);							}
				}
			}

			// Armazena os patronatos do usuário
			case 'Gestor de patronato':
				$patronatos = Pessoa::getPatronatosGerenciados($this->codPessoa);

				foreach ($patronatos as $patronato) {
					$this->dados['Patronato' . $patronato->codPatronato] = array("cod" => $patronato->codPatronato);
				}
				$temPatronato = true;

				foreach ($this->dados as $indice => $valor) {
					if (substr($indice, 0, 3) == "Pat") {
						$colaboradores = PatronatoMembro::getMembro($valor["cod"], 1);

						foreach ($colaboradores as $colaborador) {
							$this->dados[$indice]['Colaborador' . $colaborador->codPessoa] = array("cod" => $colaborador->codPessoa);
						}
					}
				}

			// Armazenas os colaboradores e os contatos dos mesmos em todos os grupos do array.
			// Caso a pessoa tenha contatos, estas serão armazenadas no primeiro índice.
			case 'Colaborador':
			foreach ($this->dados as $indice => $valor) {
				if (substr($indice, 0, 3) == "Gru" || substr($indice, 0, 3) == "Pat") {

					foreach ($valor as $indiceColaborador => $codColaborador) {
						if ($indiceColaborador != "cod") {
							$colaborador = new Pessoa($codColaborador["cod"]);

							foreach ($colaborador->contato as $contato) {
								$this->dados[$indice][$indiceColaborador]['Contato' . $contato->codPessoa] = array("cod" => $contato->codPessoa, 'tipo' => $contato->codContatoTipo);

								$pessoa = new Pessoa($contato->codPessoa);

								foreach ($pessoa->contribuicao as $contribuicao) {
									$this->dados[$indice][$indiceColaborador]['Contato' . $contato->codPessoa]['Contribuicao' . $contribuicao->codContribuicao] = array("cod" => $contribuicao->codContribuicao);
								}
							}
						}
					}
				}

				if (substr($indice, 0, 3) == "Cen") {
					foreach ($valor as $indiceGrupo => $grupo) {

						if (substr($indiceGrupo, 0, 3) == "Gru") {
							foreach ($grupo as $indiceColaborador => $codColaborador) {

								if ($indiceColaborador != "cod") {
									$colaborador = new Pessoa($codColaborador["cod"]);

									foreach ($colaborador->contato as $contato) {
										$this->dados[$indice][$indiceGrupo][$indiceColaborador]['Contato' . $contato->codPessoa] = array("cod" => $contato->codPessoa, 'tipo' => $contato->codContatoTipo);

										$pessoa = new Pessoa($contato->codPessoa);

										foreach ($pessoa->contribuicao as $contribuicao) {
											$this->dados[$indice][$indiceGrupo][$indiceColaborador]['Contato' . $contato->codPessoa]['Contribuicao' . $contribuicao->codContribuicao] = array("cod" => $contribuicao->codContribuicao);
										}
									}
								}
							}
						}
					}
				}
			}

			$usuario = new Pessoa($this->codPessoa);

			foreach ($usuario->contato as $contato) {
				$this->dados['Contato' . $contato->codPessoa] = array("cod" => $contato->codPessoa, 'tipo' => $contato->codContatoTipo);

				$pessoa = new Pessoa($contato->codPessoa);

				foreach ($pessoa->contribuicao as $contribuicao) {
					$this->dados['Contato' . $contato->codPessoa]['Contribuicao' . $contribuicao->codContribuicao] = array("cod" => $contribuicao->codContribuicao);
				}
			}

			// Armazena todas as contribuições caso a pessoa seja SOMENTE Contribuinte.
			case ('Contribuinte' || 'Contato'):
				$pessoa = new Pessoa($this->codPessoa);

				foreach ($pessoa->contribuicao as $contribuicao) {
					$this->dados['Contribuicao' . $contribuicao->codContribuicao] = array("cod" => $contribuicao->codContribuicao);
				}

			break;
		}
	}

	function getQuantidadeDadosLigados() {
		$this->contatosLigados = count($this->getTodosContatos());
		$this->centrosLigados = count($this->getTodosCentros());
		$this->gruposLigados = count($this->getTodosGrupos());
		$this->patronatosLigados = count($this->getTodosPatronatos());
		$this->colaboradoresLigados = count($this->getTodosColaboradores());
		$this->contribuicoesLigadas = count($this->getTodasContribuicoes());
	}

	function getTodasContribuicoes() {
		$retorno = array();

		// Só é possível existir contribuições nos níveis 1, 2, 4 e 5.

		// Primeiro índice.
		foreach ($this->dados as $indiceUm => $primeiroNivel) {
			if (substr($indiceUm, 0, 5) == "Contr") {
				$retorno[] = $primeiroNivel["cod"];

			} else {
				// Verifica se há mais um nível após esse.
				if (is_array($primeiroNivel)) {

					// Segundo índice.
					foreach ($primeiroNivel as $indiceDois => $segundoNivel) {
						if (substr($indiceDois, 0, 5) == "Contr") {
							$retorno[] = $segundoNivel["cod"];

						} else {
							if (is_array($segundoNivel)) {

								// Terceiro índice.
								foreach ($segundoNivel as $indiceTres => $terceiroNivel) {
									if (is_array($terceiroNivel)) {

										// Quarto índice.
										foreach ($terceiroNivel as $indiceQuatro => $quartoNivel) {
											if (substr($indiceQuatro, 0, 5) == "Contr") {
												$retorno[] = $quartoNivel["cod"];
											} else {
												if (is_array($quartoNivel)) {

													// Quinto índice.
													foreach ($quartoNivel as $indiceCinco => $quintoNivel) {
														if (substr($indiceCinco, 0, 5) == "Contr") {
															$retorno[] = $quintoNivel["cod"];
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}

		return $retorno;
	}

	function getTodosContatos() {
		$retorno = array();

		// Só é possível existir contatos nos níveis 1, 3 e 4.

		// Primeiro índice.
		foreach ($this->dados as $indiceUm => $primeiroNivel) {
			if (substr($indiceUm, 0, 5) == "Conta") {
				$retorno[] = $primeiroNivel["cod"];

			} else {
				// Verifica se há mais um nível após esse.
				if (is_array($primeiroNivel)) {

					// Segundo índice.
					foreach ($primeiroNivel as $indiceDois => $segundoNivel) {
						if (is_array($segundoNivel)) {

							// Terceiro índice.
							foreach ($segundoNivel as $indiceTres => $terceiroNivel) {
								if (substr($indiceTres, 0, 5) == "Conta") {
									$retorno[] = $terceiroNivel["cod"];
								} else {
									if (is_array($terceiroNivel)) {

										// Quarto índice.
										foreach ($terceiroNivel as $indiceQuatro => $quartoNivel) {
											if (substr($indiceQuatro, 0, 5) == "Conta") {
												$retorno[] = $quartoNivel["cod"];
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}

		return $retorno;
	}

	function getTodosColaboradores() {
		$retorno = array();

		// Só é possível existir colaboradores nos níveis 2 e 3.

		// Primeiro índice.
		foreach ($this->dados as $indiceUm => $primeiroNivel) {
			// Verifica se há mais um nível após esse.
			if (is_array($primeiroNivel)) {

				// Segundo índice.
				foreach ($primeiroNivel as $indiceDois => $segundoNivel) {
					if (substr($indiceDois, 0, 3) == "Col") {
						$retorno[] = $segundoNivel["cod"];
					} else {
						if (is_array($segundoNivel)) {

							// Terceiro índice.
							foreach ($segundoNivel as $indiceTres => $terceiroNivel) {
								if (substr($indiceTres, 0, 3) == "Col") {
									$retorno[] = $terceiroNivel["cod"];
								}
							}
						}
					}
				}
			}
		}

		return $retorno;
	}

	function getTodosGrupos() {
		$retorno = array();

		// Só é possível existir grupos nos níveis 1 e 2.

		// Primeiro índice.
		foreach ($this->dados as $indiceUm => $primeiroNivel) {
			if (substr($indiceUm, 0, 3) == "Gru") {
				$retorno[] = $primeiroNivel["cod"];

			} else {
				// Verifica se há mais um nível após esse.
				if (is_array($primeiroNivel)) {

					// Segundo índice.
					foreach ($primeiroNivel as $indiceDois => $segundoNivel) {
						if (substr($indiceDois, 0, 3) == "Gru") {
							$retorno[] = $segundoNivel["cod"];

						}
					}
				}
			}
		}
		return $retorno;
	}

	function getTodosPatronatos() {
		$retorno = array();

		// Só é possível existir patronatos no nível 1.

		foreach ($this->dados as $indiceUm => $primeiroNivel) {
			if (substr($indiceUm, 0, 3) == "Pat") {
				$retorno[] = $primeiroNivel["cod"];
			}
		}
		return $retorno;
	}

	function getTodosCentros() {
		$retorno = array();

		// Só é possível existir centros no nível 1.

		foreach ($this->dados as $indiceUm => $primeiroNivel) {
			if (substr($indiceUm, 0, 3) == "Cen") {
				$retorno[] = $primeiroNivel["cod"];

			}
		}
		return $retorno;
	}
}

?>
