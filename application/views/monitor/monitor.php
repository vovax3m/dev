<div>
	<span class="title">Текущее состояние</span>
	<div id="rightcol" >
		<div class="search"  title="Введите номер для звонка"> 
			<input type="text" class="nomer" id="nomer" placeholder="номер">
			<span class="call" onclick="dialnum('<?php echo $this->input->cookie('auth_exten', TRUE);?>');"><i class="fa fa-phone-square hand"  title="Позвонить"></i></span>
			<?php if($is_full=='FULL'){?>
			<span  class="book_button"onclick="AddToBook();"><i class="fa fa-book hand"  title="Добавить номер в книгу"></i></span>
			<?php }else{?>
			<span  class="book_button inactive"onclick="inactive();"><i class="fa fa-book hand"  title="Добавить номер в книгу"></i></span>
			<?php }?>
		</div>
		<div class="hidden" id="shortbookform" >
			<input type="text" class="nomer" id="sbf_name" placeholder="имя">
			<input type="text" class="nomer" id="sbf_ty" placeholder="комментарий">
			<input type="submit" class="nomer" id="sbf_submit" value="добавить" onclick="book_add('1');$('#shortbookform').toggle('300');">
		</div>
	</div>
	
</div>

<hr class="hr_title">

	
	
	<div id="extlist" class="leftpart" >
	<?php //print_r($ext); ?>
	<div align="center"><span class="minititle">Внутренние номера</span></div>	
	<br>
	
		<?php
				$text_size='twentypx';
				foreach($ext as $param){
					//print_r($param);
					if($param[1]=='sip' and $param[2]=='OK') {
						$style='back-green '.$text_size;
						$title="SIP, состояние ".$param[2]. "   ";
					}else{
						$style='back-red '.$text_size;
						$title="SIP, состояние ".$param[2]. "   ";
					}
					if(is_numeric($param[1])){
						$style='back-grey '.$text_size;
						$title="   ".$param[1]."   ";
					}
				?> 
				<span   class="extitem hand <?php echo $style ?>" title="<?php echo $param['n'].' '.$param['t'].'&#013;'.$title ?>" onclick="callto('<?php echo $param[0] ?>')">
					<?php echo $param[0];?> 
				</span> 
				<?php }?>
				
				
			
		
	</div>
	
	<div id="conversations" class="rightpart"  align="center">
		<div align="center"><span class="minititle">Разговоры</span></div>
		<?php if($is_full=='FULL'):?>
			<?php if($isadmin):?>
			<br>
			<div class="fpart" title="Присоединение: Вас слышат обе стороны&#013;Суфлер: Вас слышит только абонент с левой стороны&#013;Прослушивание: Вас никто не слышит">
				Режим <i class='fa fa-bullhorn'></i>: 
				<select  title="режим суфлера" id="spy_type">
					<option value="qBx">Присоединение</option>
					<option value="wx">Суфлер</option>
					<option value="qx">Прослушивание</option>
				</select>
			</div>
			<?php endif;?>
		<?php else:?>	
			<?php if($isadmin):?>
			<br>
			<div class="fpart inactive" title="Присоединение: Вас слышат обе стороны&#013;Суфлер: Вас слышит только абонент с левой стороны&#013;Прослушивание: Вас никто не слышит" readonly>
				Режим <i class='fa fa-bullhorn'></i>: 
				<select  title="режим суфлера" id="spy_type">
					<option value="qBx">Присоединение</option>
					<option value="wx">Суфлер</option>
					<option value="qx">Прослушивание</option>
				</select>
			</div>
			<?php endif;?>
		<?php endif;?>
		
		<div class="fpart"> 
		<?php if($is_full=='FULL'):?>
			<?php if($isadmin){?>
				Обновление <i class='fa fa-refresh'></i>:
				<select   title="время обновления" id="update_timer" onchange="change_timer()">
					<option value="1000">1 секунда</option>
					<option value="3000" >3 секунды</option>
					<option value="5000" selected>5  секунд</option>
					<option value="10000">10 секунд</option>
					<option value="30000">30 секунд</option>
				</select>
			<?php }else{?>
				<input type="hidden" id="update_timer" value="5000" >
			<?php }?>
		<?php else:?>
			<?php if($isadmin){?>
				<span class="inactive "  title="время обновления" >Обновление <i class='fa fa-refresh'></i>:</span>
				<select   id="update_timer" onchange="inactive()" readonly>
					<option value="1000">1 секунда</option>
					<option value="3000" >3 секунды</option>
					<option value="5000" selected>5  секунд</option>
					<option value="10000">10 секунд</option>
					<option value="30000">30 секунд</option>
				</select>
			<?php }else{?>
				<input type="hidden" id="update_timer" value="5000" >
			<?php }?>
		<?php endif;?>		
			<input type="hidden" id="interval_id">
		</div>
		
		<br>
		<div id="conv_content">
		</div>
	</div>
	<script>
	function FillConv() {
		
		// получаем готовые данные и вставляем в блок
		$.ajax({
			type:"GET",
			url: '/monitor/getconv/?act=get',
			success: function(data) {
				$('#conv_content').html(data);
				console.log(data);
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError); 
			} 
		});
	}
	// задаем таймер обновления данных
	var i=setInterval(FillConv, $('#update_timer').val() );
	//сохраняем его id в скрытое поле
	$('#interval_id').val(i);
	// изменение значения  времени обновления
	function change_timer(){
		// останавливаем старый интервал
		clearInterval($('#interval_id').val());
		// запускаем номей с периодом указанном в поле update_timer
		var i=setInterval(FillConv, $('#update_timer').val() );
		//сохраняем его id в скрытое поле
		$('#interval_id').val(i);
	}
	//get data on page load
	FillConv();
	</script>