<div id="registerModal" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Connect with Facebook</h4>
			</div>
			<div class="modal-body">
					<div class="row">
						<div class="col-12 align-center social-login">
							<h3>Sign up for free!</h3>
							<a href="<?= Uri::create('user/auth/login/facebook?r=') ?>"><img src="/assets/img/facebook-login.png" /></a>
							<p class="pad-top align-center">By clicking Sign Up, you agree to our <a href="/terms" target="_blank">Terms and Conditions</a> that you have read our <a href="/privacy" target="_blank">Privacy Policy</a></p>
						</div>
					</div>
				<div class="modal-footer in-form">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
