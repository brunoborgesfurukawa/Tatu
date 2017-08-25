<?php

// Converte o argumento do formato SQL para o formato brasileiro.
function data_pt($data) {
	return date('d/m/Y', strtotime($data));
}

// Converte o argumento do formato brasileiro para o formato SQL.
function data_sql($data) {
	return implode('-', array_reverse(explode('/', $data)));
}

// Converte o argumento do formato brasileiro para o formato SQL.
function data_hora_pt($data_sql) {
	return date('d/m/y H:i', strtotime($data_sql));
}

function diferencaMeses($data1, $data2) {
	$valores1 = explode("-", $data1);
	$valores2 = explode("-", $data2);
	$ano1 = $valores1[0];
	$ano2 = $valores2[0];
	$mes1 =	$valores1[1];
	$mes2 = $valores2[1];
	return ($mes2[1] - $mes1[1]) + (($ano2 - $ano1)*12);
}

function diferencaDatas($data1, $data2) {
	$d1 = new DateTime($data1);
	$d2 = new DateTime($data2);
	return $d1->diff($d2);
}

/**
 * Calcula a diferença de anos, meses, dias, horas e minutos entre duas datas (timestamp).
 *
 * Devido a anos bisextos, o cálculo não é completamente preciso.
 *
 * @param string $data1
 * @param string $data2
 *
 * @return object
 */
function diferencaDatas2($data1, $data2) {
	$diff = abs(strtotime($data2) - strtotime($data1));
	$anos = floor($diff / (365.25 * 24 * 60 * 60));

	$diff -= $anos * 365.25 * 24 * 60 * 60;
	$meses = floor($diff / (30 * 24 * 60 * 60));

	$diff -= $meses * 30 * 24 * 60 * 60;
	$dias = floor($diff / (24 * 60 * 60));

	$diff -= $dias * 24 * 60 * 60;
	$horas = floor($diff / (60 * 60));

	$diff -= $horas * 60 * 60;
	$minutos = floor($diff / 60);

	$diff -= $minutos * 60;
	$segundos = $diff;

	return (object) array(
			'y' => $anos,
			'm' => $meses,
			'd' => $dias,
			'h' => $horas,
			'i' => $minutos,
			's' => $segundos
		);
}

function ultimoDiaMes_pt($data){
      list($dia, $mes, $ano) = explode("/", $data);
      return date("d/m/Y", mktime(0, 0, 0, $mes+1, 0, $ano));
}

function ultimoDiaMes_sql($data){
      list($ano, $mes, $dia) = explode("-", $data);
      return date("Y-m-d", mktime(0, 0, 0, $mes+1, 0, $ano));
}

/**
 * Retorna a diferença em segundos de dois valores no formato H:M:S.
 *
 * @param  [string] $hora1 Subtraendo da operação
 * @param  [string] $hora2 Minuendo da operação
 * @return  [integer]      Diferença
 */
function diferencaHoras($hora1, $hora2) {
	$hora1 = explode(':', $hora1);
	$hora2 = explode(':', $hora2);
	$segundos1 = ($hora1[0] * 3600) + ($hora1[1] * 60) + $hora1[2];
	$segundos2 = ($hora2[0] * 3600) + ($hora2[1] * 60) + $hora2[2];
	return $segundos2 - $segundos1;
}

/**
 * Converte um valor em segundos para o formato H:M.
 *
 * @param  [integer] $segundos
 * @return [string]
 */
function formatarHora($segundos) {
	$hora = floor($segundos / 3600);
	if ($hora < 10) {
		$hora = '0' . $hora;
	}

	$restoHora = $segundos % 3600;
	$minutos = floor($restoHora / 60);
	if ($minutos < 10) {
		$minutos = '0' . $minutos;
	}

	return $hora . ':' . $minutos;
}

// Transforma segundos em horas.
function converterParaHora($segundos) {
	return $segundos / 3600;
}

// Acrescenta dias a uma data no formato Y-m-d considerando os dias limites de cada mês.
function somarDias($data, $dias) {
	$date = new DateTime($data);
	$date->add(new DateInterval("P{$dias}D"));
	return $date->format('Y-m-d');
}

function somarDias2($data, $dias) {
	$data = str_replace('-', '', $data);
	$ano = substr($data, 0, 4);
	$mes = substr($data, 4, 2);
	$dia = substr($data, 6, 2);
	$novaData = mktime(0, 0, 0, $mes, $dia + $dias, $ano);
	return strftime('%Y-%m-%d', $novaData);
}

function mesNome($data) {
	switch ($data) {
    case 1:
        $data = "Janeiro";
        break;
    case 2:
        $data = "Fevereiro";
        break;
    case 3:
        $data = "Março";
        break;
    case 4:
        $data = "Abril";
        break;
    case 5:
        $data = "Maio";
        break;
    case 6:
        $data = "Junho";
        break;
    case 7:
        $data = "Julho";
        break;
    case 8:
        $data = "Agosto";
        break;
    case 9:
        $data = "Setembro";
        break;
    case 10:
        $data = "Outubro";
        break;
    case 11:
        $data = "Novembro";
        break;
    case 12:
        $data = "Dezembro";
        break;
}
	return $data;
}

function adicionarZero($numero) {
	if ($numero>=1 && $numero<10) {
		$retorno = "0".$numero;
	}
	else {
		$retorno = $numero;
	}
	return $retorno;
}
?>
