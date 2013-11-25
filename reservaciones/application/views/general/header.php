<header>
	<a href=<?php echo base_url(); ?>><h1>CTEC: Reservaciones</h1></a>
</header>
<nav class="main-nav">
	<ul>
	<?php
	// Mostrar lo siguiente cuando hay una sesión iniciada
	if ($this->session->userdata('usuario') !== false) { ?>
	<li><a href=<?php echo '"'.base_url('usuario').'"'; ?>><?php echo $username; ?></a></li>
	<li><a href=<?php echo '"'.base_url('usuario/salir').'"'; ?>>Cerrar sesión</a></li>
	<?php } ?>
	</ul>
</nav>
<div class="wrapper">
	