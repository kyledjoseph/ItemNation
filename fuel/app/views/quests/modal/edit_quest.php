<div class="modal fade" id="questModal">
	<div class="modal-dialog">
	  <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title">Edit Quest</h4>
		</div>

		<?= Form::open(array('action' => $quest->url('edit'), 'method' => 'POST', 'class' => 'form-vertical push-center', 'ajax-action' => 'update', 'ajax-target' => 'details-wrapper', 'ajax-modal'=>'questModal')) ?>
		<div class="modal-body">
			<div class="form-group">
				<label for="name">What are you shopping for?</label>
				<input name="name" class="form-control" type="text" id="inputWhat" value="<?= $quest->name ?>" />
			</div>

			<div class="form-group">
				<label class="control-label" for="description">Tell your friends more about what you're looking for.</label>
				<textarea name="description" class="form-control" id="inputDescription" rows="6"><?= $quest->description ?></textarea>
			</div>
			<div class="control-group">
				<label class="control-label" for="purchase_within">When would you like to buy it?</label>
				<div class="controls">
					<?= Form::select('purchase_within', $quest->purchase_within_option(), Model_Quest::purchase_within_fields(), array('class' => 'form-control')) ?>
				</div>
			</div>
		</div>
		<div class="modal-footer">
				<button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
				<button type="submit" class="btn btn-primary">Save Changes</button>
			</div>
		</form>
	  </div>
	</div>
  </div>
