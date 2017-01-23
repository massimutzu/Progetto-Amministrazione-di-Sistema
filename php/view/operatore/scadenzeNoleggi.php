
<div class="page-content-wrapper">
	<div class="page-content" id="content">
		<div class="panel panel-default">
			<div class="panel-heading">Lista Noleggi</div>
			<div class="panel-body">
			<?php if (count($elenco_noleggi) == 0) { ?>
    				<p>Nessun Noleggio In Scadenza</p>
			<?php } else { ?>
				<table id="NoleggiTable">
					<thead>
						<tr>
						<th>Inventario</th>
							<th>Titolo</th>
							<th>Autore</th>
							<th>Utente</th>
							<th>Inizio Noleggio</th>							
							<th>Giorni Noleggio</th>
							<th>Ritardo</th>
						</tr>
					</thead>
					<tbody>
								         <?php foreach ( $elenco_noleggi as $noleggio ) {?>							                				
														<tr>
							<td><?= $noleggio->getCodiceInventario() ?></td>
							<td><?= $noleggio->getTitolo() ?></td>
							<td><?= $noleggio->getAutore() ?></td>
							<td><?= $noleggio->getNome() ?> <?= $noleggio->getCognome() ?></td>
							<td><?= $noleggio->getStart_rent() ?></td>							
							<td>
							<button class="btn btn-circle blue"><?= $noleggio->getGiorniNoleggio() ?></button>
							
							</td>	
							<?php 
							if ($noleggio->getGiorniNoleggio()>Settings::$MaxDayForRent){
							?>	
								<td><button class="btn btn-circle red">  <?= $noleggio->getGiorniNoleggio()-Settings::$MaxDayForRent ?></button>
								</td>				
							
							<?php }
							else 
							{?>
							<td>
							<button class="btn btn-circle green">0</button>
							</td>
							
							<?php }?>						
						</tr>	
						<?php } ?>
					</tbody>
				</table>
			<?php } ?>
			</div>
		</div>
	</div>
</div>