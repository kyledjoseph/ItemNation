<div class="modal fade" id="addProductModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Add Product</h4>
			</div>
			<div class="row">
				<div class="col-6">
					<div class="modal-slice">
						<h4>Drag this to your bookmarks!</h4>
							<a class="grab bookmarklet" href="javascript:(function(){var e=document.createElement('script');e.id='itemnation-script';e.src='<?= Uri::base(); ?>bookmark.js?cacheblock=' + Math.floor(Math.random() * 999999999);document.getElementsByTagName('head')[0].appendChild(e)})();"><img class="grab" src="<?= Uri::create('assets/img/bookmark.png') ?>" alt="Add to ShopGab" /></a>
						<img width="100%" src="/assets/img/drag.png" />
						<div class="block">
							<a class="learn-more-2 block" href="http://youtu.be/NzKArZOkmEk"><i class="icon-facetime-video"></i> Not sure what to do? Click here!</a>
						</div>
					</div>
				</div>
				<div class="col-6">
					<div class="modal-slice">
						<h4>Capture from the web!</h4>
						<?= Form::open(array('action' => 'http://google.com/search', 'method' => 'get', 'target' => '_blank')) ?>
						<h5 class="pad-top">Start shopping now!</h5>
						<div class="input-group pad-top">
							<input type="text" id="google" name="q" class="form-control" placeholder="Search with Google">
							<span class="input-group-btn">
								<button class="btn btn-default btn-success btn-google-search" type="submit">Search</button>
							</span>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		</div>
	</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
  </div><!-- /.modal -->