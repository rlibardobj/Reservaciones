<div class="usuario-perfil">
	<p>Bienvenido, {username}. Desde esta página puede modificar su nombre, email y contraseña.</p>
	<p>{notificacion}</p>
	<form method="post">
		<input type="text" id="nombre" name="nombre" placeholder="Nombre" value="{username}">
		<input type="email" id="email" name="email" placeholder="Email" value="{email}">
		<input type="password" id="contrasenna" name="contrasenna" placeholder="Contraseña actual">
		<input type="password" id="nueva-contrasenna" name="nueva_contrasenna" placeholder="Nueva contraseña">
		<input type="submit" id="submit" value="Guardar cambios"> 
	</form>
	<script type="text/javascript">
		$('#submit').button();
	</script>
</div>