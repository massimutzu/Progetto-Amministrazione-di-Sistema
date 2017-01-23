<div class="content" id="content">
	<form class="login-form" action="login" method="post"
		novalidate="novalidate">
		<input type="hidden" name="cmd" value="login" />
		<h3 class="form-title">Login</h3>
		<div class="alert alert-danger display-hide">
			<button class="close" data-close="alert"></button>
			<span>Inserire un nome utente e una password. </span>
		</div>
		<div class="form-group">
			<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
			<label class="control-label visible-ie8 visible-ie9">Username</label>
			<input id="user" name="user"
				class="form-control form-control-solid placeholder-no-fix"
				type="text" autocomplete="off" placeholder="Username"
				name="username">
		</div>
		<div class="form-group">
			<label class="control-label visible-ie8 visible-ie9">Password</label>
			<input id="password" name="password"
				class="form-control form-control-solid placeholder-no-fix"
				type="password" autocomplete="off" placeholder="Password"
				name="password">
		</div>
		<div class="form-actions">
			<button type="submit" class="btn btn-success uppercase">Login</button>
		</div>
	</form>

</div>

