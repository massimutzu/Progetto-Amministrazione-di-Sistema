<div class="page-sidebar-wrapper">
	<div class="page-sidebar navbar-collapse collapse">
		<ul id="page-sidebar-menu"
			class="page-sidebar-menu page-sidebar-menu-hover-submenu "
			data-keep-expanded="false" data-auto-scroll="true"
			data-slide-speed="200">
				<li id="li_rents"><a> <i class="icon-basket"></i> <span class="title">Noleggi</span>
					<span id="spn_sel_rents" class=""></span></a>
				<ul class="sub-menu">
					<li id="li_rents_new" class="start active "><a href="operatore/nuovoNoleggio"> <i
							class="icon-plus"></i> <span class="title">Nuovo</span> <span
							id="spn_rents_new" class="selected"></span>
					</a></li>
					<li id="li_rents_search" class="start active "><a href="operatore/gestioneNoleggi"> <i
							class="icon-magnifier"></i> <span class="title">Ricerca</span> <span
							id="spn_rents_search" class="selected"></span>
					</a></li>
					<li id="li_rents_expire" class="start active "><a href="operatore/scadenzeNoleggi"> <i
							class="icon-calendar"></i> <span class="title">Scadenze</span> <span
							id="spn_rents_expire" class="selected"></span>
					</a></li>

				</ul></li>
			<li id="li_users"><a href="operatore/utenti"> <i class="icon-users"></i>
					<span class="title">Utenti</span> <span id="spn_sel_users" class=""></span>
			</a></li>
			<li id="li_catalogo" class=""><a href="operatore/catalogo"> <i
					class="icon-notebook"></i> <span class="title">Catalogo</span> <span
					id="spn_sel_catalogo" class="selected"></span>
			</a></li>
		
			 <?php
				$user = $this->loggedUserData ();
				if ($user->getIsAdmin ()) {
					?>
               <li id="li_operatori"><a href="operatore/operatori"> <i
					class="icon-user-following"></i> <span class="title">Operatori</span>
					<span id="spn_operatori" class="selected"></span>
			</a></li>
                                    <?php }?>
		</ul>
	</div>
</div>