
<!-- Modal -->
    <div class="modal fade" id="searchModal" role="dialog">
	   <div class="modal-dialog modal-lg">
 
	<!-- Modal content-->
		 <div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title">

	<?php if ($titles): ?>
                <div class="col-xs-6">
                  <b>
                  <label>Nom de document ou de dossier contenant</label>
                  </b>
                  <input id="mySearch" type="text" class="form-control warning" value="<?= $query ?>">
                </div>
    <?php else: ?>
                <label>Documents contenant le terme [<b><?= $query ?></b>]</label>
    <?php endif; ?>
	          </h4>
	        </div>
	        <div class="modal-body">
	        
                 <div class="dataTable_wrapper">
                   <table id="data-search" width="100%" class="table table-striped table-bordered table-hover" >
						<thead>
						  <tr>
	<?php if (! $titles): ?>                           
								<th>N°</th>
                            	<th>Nom</th>
                            	<th>Auteur</th>
								<th>Contenu</th>
<!--                            	<th>Date</th>	-->
	<?php else: ?>                            
								<th>Nom</th>
								<th>Type</th>
	<?php endif; ?>	
                          </tr>
						</thead>
						
						<tbody>
						
	<?php if (!empty($results)): ?>
		
		<?php foreach ($results as $ligne): ?> 
		
			<?php if (! $titles): ?>
			
				<tr>
					<td><?= $ligne['pos'] ?></td>
                  	<td>
                      <a href="<?= $ligne['champs']['url'] ?>" target="blank">
                      <?= substr($ligne['champs']['title'], 0, 60) ?>
                      </a>
                    </td>
                  	<td><?= substr($ligne['champs']['author'], 0, 60) ?></td>
                  	<td><?= substr($ligne['champs']['sample'], 0, 120) ?></td>
<!--					<td><?= $ligne['champs']['modtime'] ?></td>	-->
				</tr>
			<?php else: ?>
				<tr>
					<td>
				<?php if ($ligne['type'] == 'dossier') : ?>
				
						<a href="index.php?p=<?= rawurlencode($ligne['url']) ?>">
				<?php else: ?>
						<a href="<?= $ligne['url'] ?>" target="blank">
				<?php endif; ?>					
						<?= normalizeString(substr($ligne['nom'], 0, 80)) ?>
						</a>
					</td>
					<td><?= $ligne['type'] ?></td>
				</tr>
				
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>						
						</tbody>
						
					</table>
				  </div>
						        
	        </div>
	        <div class="modal-footer">
	          <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
	        </div>
	      </div>
	      
	   </div>
	</div>