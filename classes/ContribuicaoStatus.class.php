<?php
class ContribuicaoStatus extends Record {

	const TABLE = 'ContribuicoesStatus';
	const PK = 'codStatus';

		static function getStatus() {
		$sql = ContribuicaoStatus::find("",
			array('select' => 'codStatus,descricao')
		);
		return $sql;
	}
}

?>

