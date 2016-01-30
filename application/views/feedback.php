<div >
	<span class="title">Обратная связь</span> 
	<hr class="hr_title">
	<span id="temp" class="temp" ><?php echo $this->input->cookie('STAT', TRUE); $this->input->set_cookie('STAT', '', '-3600'); ?></span>
</div>
<div >
	<form action="/feedback/handler" method="POST"  enctype="multipart/form-data" autocomplete="on" >
	<p>
		<span >Укажите тему сообщения</span>
		<br>
		 <select name="fb_theme" class="textfield_FB " >
			<?php if($subj){?>
			<option value="<?php echo $subj?>" selected><?php echo $subj?></option>
			<?php } ?>
			<option value="Общий вопрос">Общий вопрос</option>
			<option value="Ошибка в работе кабинета">Ошибка в работе кабинета</option>
			<option value="Проблемы в работе Виртуальной АТС">Проблемы в работе Виртуальной АТС</option>
			<option value="Перенастройка конфигурации Виртуальной АТС">Перенастройка конфигурации Виртуальной АТС</option>
			<option value="Заявка на подключение\отключение услуги">Заявка на подключение\отключение услуги</option>
			<option value="Запрос бухгалтерских документов">Запрос бухгалтерских документов</option>
			<option value="Запрос детализации">Запрос детализации</option>
			<option value="Приобретение номера 845 499 8800">Приобретение номера 845 499 8800</option>
			<option value="Другое">Другое</option>
		 </select>
	</p>
	
	<p>
		<span >Представьтесь</span>
		<br>
		 <input type="text" class="textfield_FB "  name="fb_name" value="" placeholder="Имя Фамилия, Должность" required>
	</p>
	
	<p>
		<span ">Укажите номер для связи с Вами</span>
		<br>
		 <input  type="text"  class="textfield_FB " name="fb_phone" value="" placeholder="89012345678" required>
	</p>
	
	<p>
		<span >Сообщение</span>
		<br>
		 <textarea name="fb_mess" class="textfield_FB_TA "   placeholder="прошу подключить дополнительный внутренний номер 114 по адресу: г.Саратов ул. Астраханская 43, корпус Б, офис 204" cols="20" rows="20" ><?php if($mess) echo $mess;?> </textarea>
	</p>
	
	
	<p>
		<span  >Прикрепите файл </span>
		<br>
		 <input type="file" name="fb_file" class="textfield_FB ">
	</p>
	<p>
		
		 <input type="submit"  class="textfield_FB " value="Отправить" name="fb_submit" >
	</p>
 </div>