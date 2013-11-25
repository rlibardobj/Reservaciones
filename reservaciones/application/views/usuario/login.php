<div class="login-box">
	<p>Ingrese sus credenciales para iniciar sesión.</p>
	<form action=<?php echo '"'.base_url('usuario/login').'"'; ?> method="post" id="form-login">
		<input type="text" placeholder="Email" name="email">
		<input type="password" placeholder="Contraseña" name="password">
		<input type="submit" value="Iniciar">
	</form>
	<script type="text/javascript">
		$('input[type=submit]').button();
	</script>
</div>