<?php
$titulo = "Grava Informacões";
header('Content-Type: application/json');
require '../autoload.php';
require '../util/dataHora.php';

$informacoes = Centro::getDados();

$totalCentros = 0;
$totalGrupos = 0;
$totalColaboradores = 0;
$totalContatos = 0;
$totalContribuintes = 0;
if (isset($informacoes['Grupos'])) {
	foreach ($informacoes['Grupos'] as $codCentro => $quantidadeGrupos) {
		$numeroGruposPorCentro[$codCentro] = 0;
		$numeroColaboradoresPorCentro[$codCentro] = 0;
		$numeroContatosPorCentro[$codCentro] = 0;
		$numeroContribuintesPorCentro[$codCentro] = 0;
		if (isset($informacoes['Colaboradores'][$codCentro])) {
			foreach ($informacoes['Colaboradores'][$codCentro] as $codGrupo => $quantidadeColaboradores) {
				$numeroColaboradoresPorGrupo[$codGrupo] = 0;
				$numeroContatosPorGrupo[$codGrupo] = 0;
				$numeroContribuintesPorGrupo[$codGrupo] = 0;
				if (isset($informacoes['Contatos'][$codCentro][$codGrupo])) {
					foreach ($informacoes['Contatos'][$codCentro][$codGrupo] as $codColaborador => $quantidadeContatos) {
						$numeroContatosPorColaborador[$codColaborador] = 0;
						$numeroContribuintesColaborador[$codColaborador] = 0;
					}
				}
			}
		}
	}
}

$totalCentros = $informacoes['Centros'];
if (isset($informacoes['Grupos'])) {
	foreach ($informacoes['Grupos'] as $codCentro => $quantidadeGrupos) {
		$centros[] = $codCentro;
		$totalGrupos += $quantidadeGrupos;
		$numeroGruposPorCentro[$codCentro] = $quantidadeGrupos;
		if (isset($informacoes['Colaboradores'][$codCentro])) {
			foreach ($informacoes['Colaboradores'][$codCentro] as $codGrupo => $quantidadeColaboradores) {
				$grupos[] = $codGrupo; 
				$totalColaboradores += $quantidadeColaboradores;
				$numeroColaboradoresPorCentro[$codCentro] += $quantidadeColaboradores;
				$numeroColaboradoresPorGrupo[$codGrupo] = $quantidadeColaboradores;
				if (isset($informacoes['Contatos'][$codCentro][$codGrupo])) {
					foreach ($informacoes['Contatos'][$codCentro][$codGrupo] as $codColaborador => $quantidadeContatos) {
						$colaboradores[] = $codColaborador;
						$totalContatos += $quantidadeContatos['Contatos'];
						$totalContribuintes += $quantidadeContatos['Contribuintes'];
						$numeroContatosPorCentro[$codCentro] += $quantidadeContatos['Contatos'];
						$numeroContribuintesPorCentro[$codCentro] += $quantidadeContatos['Contribuintes'];
						$numeroContatosPorGrupo[$codGrupo] += $quantidadeContatos['Contatos'];
						$numeroContribuintesPorGrupo[$codGrupo] += $quantidadeContatos['Contribuintes'];
						$numeroContatosPorColaborador[$codColaborador] = $quantidadeContatos['Contatos'];
						$numeroContribuintesColaborador[$codColaborador] = $quantidadeContatos['Contribuintes'];
					}
				}
			}
		}
	}
}

$informacoesTotais = null;
$informacoesCentros = null;
$informacoesGrupos = null;
$informacoesColaboradores = null;

$informacoesTotais = $totalCentros."-".$totalGrupos."-".$totalColaboradores."-".$totalContatos."-".$totalContribuintes;
foreach ($centros as $codCentro) {
	$informacoesCentros = $informacoesCentros." Ce ".$codCentro."=".$numeroGruposPorCentro[$codCentro]."-".$numeroColaboradoresPorCentro[$codCentro]."-".$numeroContatosPorCentro[$codCentro]."-".$numeroContribuintesPorCentro[$codCentro];
}
foreach ($grupos as $codGrupo) {
	$informacoesGrupos = $informacoesGrupos." Gr ".$codGrupo."=".$numeroColaboradoresPorGrupo[$codGrupo]."-".$numeroContatosPorGrupo[$codGrupo]."-".$numeroContribuintesPorGrupo[$codGrupo];
}
foreach ($colaboradores as $codColaborador) {
	$informacoesColaboradores = $informacoesColaboradores." Co ".$codColaborador."=".$numeroContatosPorColaborador[$codColaborador]."-".$numeroContribuintesColaborador[$codColaborador];
}
$informacoes = new Informacoes();
$informacoes->data = date("Y-m-d");
$informacoes->totais = $informacoesTotais;
$informacoes->centros = $informacoesCentros;
$informacoes->grupos = $informacoesGrupos;
$informacoes->colaboradores = $informacoesColaboradores;
$informacoes->store();

?>
