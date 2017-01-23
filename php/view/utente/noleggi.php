<div class="page-content-wrapper">
	<div class="page-content" id="content">
		<div class="panel panel-default">
			<div class="panel-heading">Lista Noleggi In Scadenza</div>
			<div class="panel-body">
			<?php if (count($elenco_noleggi) == 0) { ?>
    				<p>Nessun Noleggio Trovato</p>
			<?php } else { ?>
										<table id="NoleggiTable">
					<thead>
						<tr>
							<th>Titolo</th>
							<th>Autore</th>
							<th>Inizio Noleggio</th>
							<th>Fine Noleggio</th>
							<th>Giorni</th>
							<th>Stato</th>

						</tr>
					</thead>
					<tbody>
								         <?php foreach ( $elenco_noleggi as $noleggio ) {?>							                				
														<tr>
							<td><?= $noleggio->getTitolo() ?></td>
							<td><?= $noleggio->getAutore() ?></td>
							<td><?= $noleggio->getStart_rent() ?></td>
							<td><?= ($noleggio->getEnd_rent()!=null) ? $noleggio->getEnd_rent():''?></td>
							<td><?= $noleggio->getGiorniNoleggio() ?></td>
							<td><?= ($noleggio->getEnd_rent()!=null) ? '<button type="button" class="btn btn-circle green">Chiuso</button>': '<button type="button" class="btn btn-circle yellow">Aperto</button>'?></td>		
						</tr>	
						<?php } ?>
					</tbody>
				</table>
			<?php } ?>
			</div>
		</div>
	</div>
</div>
