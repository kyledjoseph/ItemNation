<div class="modal fade" id="deleteQuestModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Are you sure?</h4>
			</div>
			<div class="modal-body">
				<p>This change is permanent and can <strong>not</strong> be reversed.</p>
			</div>
			<div class="modal-footer ajax-action">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<a ajax-action="redirect" href="<?= Uri::create($quest->url('delete')) ?>">
					<button type="button" class="btn btn-danger">Delete Quest</button>
				</a>

			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
