<?php
class Informacoes extends Record {

	const TABLE = 'Informacoes';
	const PK = 'data';

	public function getInformacoes($dados = "total", $cod = null, $data = null) {
		if(empty($data)) {
			$data = date("Y-m-d");
		}
		$informacoes = new Informacoes($data);
		$numeroCentros = 0;
		$numeroGrupos = 0;
		$numeroColaboradores = 0;
		$numeroContribuintes = 0;
		$numeroContatos = 0;
		
		switch ($dados) {
			case 'total':
				$v = explode("-", $informacoes->totais);
				$numeroCentros = $v[0];
				$numeroGrupos = $v[1];
				$numeroColaboradores = $v[2];
				$numeroContatos = $v[3];
				$numeroContribuintes = $v[4];
				break;

			case 'centro':
				$centros = explode(" Ce ", $informacoes->centros);
				foreach ($centros as $centro) {
					$dadosCentros[] = explode("=", $centro);
				}
				foreach ($dadosCentros as $dadosCentro) {
					$quantidadesPorCentro[$dadosCentro[0]] = explode("-", $dadosCentro[1]);
				}
				$numeroGrupos = $quantidadesPorCentro[$cod][0];
				$numeroColaboradores = $quantidadesPorCentro[$cod][1];
				$numeroContatos = $quantidadesPorCentro[$cod][2];
				$numeroContribuintes = $quantidadesPorCentro[$cod][3];
				break;

			case 'grupo':
				$grupos = explode(" Gr ", $informacoes->grupos);
				foreach ($grupos as $grupo) {
					$dadosGrupos[] = explode("=", $grupo);
				}
				foreach ($dadosGrupos as $dadosGrupo) {
					$quantidadesPorGrupo[$dadosGrupo[0]] = explode("-", $dadosGrupo[1]);
				}
				$numeroColaboradores = $quantidadesPorGrupo[$cod][0];
				$numeroContatos = $quantidadesPorGrupo[$cod][1];
				$numeroContribuintes = $quantidadesPorGrupo[$cod][2];
				break;

			case 'colaborador':
				$colaboradores = explode(" Co ", $informacoes->colaboradores);
				foreach ($colaboradores as $colaborador) {
					$dadosColaboradores[] = explode("=", $colaborador);
				}
				foreach ($dadosColaboradores as $dadosColaborador) {
					$quantidadesPorColaborador[$dadosColaborador[0]] = explode("-", $dadosColaborador[1]);
				}
				$numeroContatos = $quantidadesPorColaborador[$cod][0];
				$numeroContribuintes = $quantidadesPorColaborador[$cod][1];
				break;
		}
		return (object) array('Centros' => $numeroCentros, 'Grupos' => $numeroGrupos, 'Colaboradores' => $numeroColaboradores, 'Contribuintes' => $numeroContribuintes, 'Contatos' => $numeroContatos);
	}

}

?>

