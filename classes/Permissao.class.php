<?php
class Permissao extends Record {

	const TABLE = 'Permissoes';
	const PK = 'codPermissao';

	function getTotal() {
		$sql = Permissao::find(
			null,
			array('select' => 'Permissoes.*')
		);
		return $sql;
	}
}
?>