		 <!---</div>maincols-->
		
	</div> <!---twocols-->
	<div id="leftcol">  
	<!--  сначала общие разделы-->
		<a href="/history" class="menuitem">История звонков</a><hr> 
		<a href="/stat"  class="menuitem">Статистика</a><hr>
		<a href="/monitor"  class="menuitem">Мониторинг</a><hr>
		<a href="/book"  class="menuitem">Контакты</a><hr> 
		<a href="/purchase"  class="menuitem">Функционал</a><hr>
		
	<!--  потом админские-->
	<?php if($isadmin){?>
		<a href="/ivr"  class="menuitem">Приветствия</a><hr>
		<a href="/feedback"  class="menuitem">Обратная связь</a><hr>
	<?php } //endif?>
	</div>
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
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	<link href='http://fonts.googleapis.com/css?family=Play&subset=latin,cyrillic-ext' rel='stylesheet' type='text/css'>
	<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
</html>
