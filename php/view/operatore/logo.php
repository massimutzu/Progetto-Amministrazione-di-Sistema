<div class="page-header navbar navbar-fixed-top">
	<!-- BEGIN HEADER INNER -->
	<div class="page-header-inner container">
		<div class="page-logo">
			<a href="operatore"> <img src="../assets/img/logo.png" alt="logo"
				class="logo-default" style="height: 50px">
			</a>
		</div>
		<div class="page-top">
			<div class="top-menu">
				<ul class="nav navbar-nav pull-right">
					<li class="dropdown dropdown-user"><a href="javascript:;"
						class="dropdown-toggle" data-toggle="dropdown"
						data-hover="dropdown" data-close-others="true"> <span
							class="username username-hide-on-mobile"> Opzioni Operatore</span> <i
							class="fa fa-angle-down"></i>
					</a>
						<ul class="dropdown-menu dropdown-menu-default">
							<li><a data-toggle="modal" data-target="#EditProfile" href="#"> <i
									class="icon-user"></i>Il mio profilo
							</a></li>
							<li><a data-toggle="modal" data-target="#EditPassword" href="#">
									<i class="icon-lock"></i>La mia Password
							</a></li>
							<li class="divider"></li>
							<li><a href="logout"> <i class="icon-power"></i>Log Out
							</a></li>
						</ul></li>
				</ul>
			</div>
		</div>
	</div>
</div>


<?php
$clu = $this->loggedUserData ();
?>
<form action="operatore" method="POST">
	<div class="modal fade" id="EditProfile" tabindex="-1" role="dialog"
		aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog" style="width: 700px">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true"></button>
					<h4 class="modal-title">Il mio Profilo</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-2">
							<label>Nome</label>
						</div>
						<div class="col-md-4">
							<input id="nome" name="nome" type="text" class="form-control"
								value="<?=$clu->getNome ();?>"> 
								<input name="cmd" type="hidden" value="mydata">
								<input id="CurrentId" type="hidden" value="<?=$clu->getId ();?>">
						</div>
						<div class="col-md-2">
							<label>Cognome</label>
						</div>
						<div class="col-md-4">
							<input id="cognome" name="cognome" type="text"
								class="form-control" value="<?=$clu->getCognome ();?>">
						</div>
					</div>				
					<div class="row">
						<div class="col-md-2">
							<label>Cellulare</label>
						</div>
						<div class="col-md-4">
							<input id="cellulare" name="cellulare" type="text"
								class="form-control" value="<?=$clu->getCellulare ();?>">
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							<label>Email</label>
						</div>
						<div class="col-md-4">
							<input id="email" name="email" type="text" class="form-control"
								value="<?=$clu->getEmail ();?>">
						</div>
					</div>
				</div>
				<div class="modal-footer">

					<button type="submit" class="btn blue" type="submit">Salva</button>
					<button type="button" class="btn default" data-dismiss="modal">Chiudi</button>
				</div>
			</div>
		</div>
	</div>
</form>
<form action="operatore" method="POST">
	<div class="modal fade" id="EditPassword" tabindex="-1" role="dialog"
		aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true"></button>
					<h4 class="modal-title">La mia password</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6">
							<label>Vecchia Password</label>
						</div>
						<div class="col-md-6">
							<input name="cmd" type="hidden" value="password"> <input
								type="password" name="oldpassword" class="form-control">
						</div>
						<div class="col-md-6">
							<label>Nuova Password</label>
						</div>
						<div class="col-md-6">
							<input type="password" name="newpassword1" class="form-control">
						</div>
						<div class="col-md-6">
							<label>Ripeti Password</label>
						</div>
						<div class="col-md-6">
							<input type="password" name="newpassword2" class="form-control">
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<button type="submit" class="btn blue">Aggiorna</button>
					<button type="button" class="btn default" data-dismiss="modal">Chiudi</button>
				</div>
			</div>
		</div>
	</div>
</form>

