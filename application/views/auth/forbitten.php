<!doctype html>
<html>
<head>
	<title>Личный кабинет</title>
	<meta charset="utf-8"/>
	<link rel="stylesheet" href="<?php echo $this->config->item('base_url');?>/static/style/style.css" type="text/css"/> 
	<link href='http://fonts.googleapis.com/css?family=Play&subset=latin,cyrillic-ext' rel='stylesheet' type='text/css'>
</head>
<body>
	<div id="pagewidth">
			<div id="header">
				<div class="lefthead">
					<img src="http://dialog64.ru/wp-content/themes/customizr/inc/img/dialog_logo2.png" class="logo">
				</div>
		</div>
		<br>
		<br>
		<div id="wrapper" class="clearfix" align="center">
			<span class="title">Вход в  данный раздел кабинета не разрешен<br><br><a href="<?php echo $this->config->item('base_url');?>"><?php echo $this->config->item('base_url');?></a> </span><br> 
		
			
		
		 </div><!---wrapper-->

         
		<div id="footer">
				<table >
		<tr>
			<td>410012, г. Саратов, ул. Московская, д. 66, Офис 408</td><td  class="hand"  onclick="callto('740740');">Абонентский отдел: (8452) 740-740</td>
		</tr>
		<tr>
			<td  class="hand" onclick="callto('740740');"> Телефон / факс: (8452) 740-740</td><td   class="hand"  onclick="callto('740808');"> Бюро ремонта (местная связь): (8452) 740-808</td>
		</tr>	
		<tr>
			<td> E-mail: info@dialog64.ru</td>
			<td  class="hand"  onclick="callto('740909');"> Техническая поддержка: (8452) 740-909 </td>
			<td>render time {elapsed_time}  s.</td>
			
		</tr>
	<table>	
		</div>

	</div><!---pagewidth-->
</body>

</html>
