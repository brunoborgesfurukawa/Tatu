				<table class="table table-striped table-bordered">
					<tbody>
						<tr>
							<th><?= $campo ?></th><th>Valor R$</th>
						</tr>
						<?php
							foreach ($contribuicoes as $indice => $contribuicao) {
						?>
						<tr>
							<td><?= $indice ?></td>
							<td><?= $contribuicao ?>,00</td>
						</tr>
						<?php }?>
						<tr>
							<td><b>Total</b></td>
							<td><?= $contribuicaoTotal ?>,00</td>
						</tr>
					</tbody>
				</table>
