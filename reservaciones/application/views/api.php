<!doctype html>
<html>
<head>
	<title>API Reservaciones</title>
	<meta charset='utf-8' />
	<style type="text/css">
	body {
		font-family: 'Arial', sans-serif;
	}
	::selection
	{
		background-color:#eee;
	}
	pre::selection {
		color: red;
	}
	</style>
</head>
<body>
	<h1>Referencia del API</h1>
	
	<h2>Actividades</h2>
	<h3>Todas</h3>
	<p>Para obtener una lista de todas las actividades:</p>
	<pre><?php echo base_url('api/actividades/todas'); ?></pre>
	<h3>Siguientes</h3>
	<p>Para obtener una lista de las actividades siguientes:</p>
	<pre><?php echo base_url('api/actividades/siguientes'); ?></pre>
	<h3>Pasadas</h3>
	<p>Para obtener una lista de las actividades pasadas:</p>
	<pre><?php echo base_url('api/actividades/pasadas'); ?></pre>
	
	<h2>Obtener proformas</h2>
	<p>Todos los métodos de esta categoría requieren un API_KEY para autentificarse como usuario registrado del sistema.</p>
	<h3>Todas</h3>
	<p>Para obtener una lista de todas las proformas:</p>
	<pre><?php echo base_url('api/proformas/todas/API_KEY'); ?></pre>
	<h3>Siguientes</h3>
	<p>Para obtener una lista de las proformas siguientes:</p>
	<pre><?php echo base_url('api/proformas/siguientes/API_KEY'); ?></pre>
	<h3>Pasadas</h3>
	<p>Para obtener una lista de las proformas pasadas:</p>
	<pre><?php echo base_url('api/proformas/pasadas/API_KEY'); ?></pre>
	<h3>Una sola</h3>
	<p>Para obtener la información de la proforma número 1:</p>
	<pre><?php echo base_url('api/proforma/1/API_KEY'); ?></pre>
</body>
</html>