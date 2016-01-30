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
					<img src="http://dialog64.ru/wp-content/themes/customizr/inc/img/dialog_logo2.png" class="logo"><span title="Ранняя версия, возможно неточности, перерывы в работе">2</span> <a href="mailto:cabinet@dialog64.ru" title="По вопросам, предложениям, обнаруженным ошибкам пишите на данный адрес">cabinet@dialog64.ru</a>
				</div>
		</div>
		<br>
		<br>
		<div id="wrapper" class="clearfix" align="center">
			<span class="title">Вход в личный кабинет</span><br>
			
			<?php if($trynomer){ ?>
				<span class="temp">Осталось <?php $ost=10 - $trynomer; echo $ost;?> попыток входа</span><br>
			<?php } ?>   
			<span id="temp" class="temp" ondblclick="$('#temp').hide();"><?php echo $this->input->cookie('STAT', TRUE); ?></span>  
			<form action="/auth/enter" method="POST"> 
				<p><label>Имя пользователя или вн.номер</label><br>
				<input type="text" name="username" class="loginform" value="<?php echo $u?>"></p>
				<p><label>Пароль</label><br>
				<input type="password" name="passwd" class="loginform" value="<?php echo $p?>"></p>
				<p>
				<input type="submit" name="submit" value="Войти" class="loginform"></p> 
			<form>
			
		
		 </div><!---wrapper-->

         
		<div class="footer">
	<table >
		<tr>
			<td>410012, г. Саратов, ул. Московская, д. 66, Офис 408</td><td  class="hand"  onclick="callto('740740');">Абонентский отдел: (8452) 740-740</td><td> Поддержка: cabinet@dialog64.ru</td>
		</tr>
		<tr>
			<td  class="hand" onclick="callto('740740');"> Телефон / факс: (8452) 740-740</td><td   class="hand"  onclick="callto('740808');"> Бюро ремонта (местная связь): (8452) 740-808</td><td> </td>
		</tr>	
		<tr>
			<td> E-mail: info@dialog64.ru</td>
			<td  class="hand"  onclick="callto('740909');"> Техническая поддержка: (8452) 740-909 </td>
			<td>render time {elapsed_time}  s.</td>
			
		</tr>
	</table>	
</div>

	</div><!---pagewidth-->
</body>

</html>
