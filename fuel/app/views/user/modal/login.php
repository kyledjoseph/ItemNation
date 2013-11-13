<div id="loginModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Login</h4>
			</div>
			<div class="modal-body">
					<div class="row">
						<div class="col-12 col-sm-6 social-login">
							<?= Html::anchor('login/facebook?r=' . Uri::string(), Asset::img('facebook-login.png')) ?>
						</div>
						<div class="col-12 col-sm-6 form-login">
							<?= Form::open(array('action' => 'login')) ?>
							<div class="form-group">
								<label for="exampleInputPassword">Email</label>
								<input name="email" type="email" class="form-control" placeholder="you@email.com">
							</div>
							<div class="form-group">
								<label for="password">Password</label>
								<input name="password" type="password" class="form-control" placeholder="******">
							</div>
							<span id="error_login" class="modal_error"></span>
							<div class="form-group">
								<label for="rememberme">Remember me</label>
								<input name="rememberme" type="checkbox"><a href="#" class="pull-right switch-to-signup">Sign Up</a>
							</div>
						</div>
					</div>
				<div class="modal-footer in-form">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<input type="hidden" name="redirect" value="<?= Uri::string() ?>" ?>
					<button id="user_login" type="submit" class="btn btn-primary">Login</button>
				</div>
				<?= Form::close() ?>
			</div>
		</div>
	</div>
</div>
</div>
