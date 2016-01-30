
<div >
	<span class="title">История звонков</span><br>
	<span ><?php  if($total>0) echo $total .' записей.';?> </span>
	<span align="center" class="paginblock" >
		<?php if($pagin):?>
		<?php echo $pagin?>
		Переход <input type="text"  size="3" class="paginInput" title="перейти на страницу" onchange="change_page(this.value)">
		<?php endif;?>
		Строк <input type="text" size="3" class="paginInput" title="строк на странице" value="<?php echo $RPP?>" onchange="change_RPP(this.value)">
	</span >
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
			<input type="text" class="nomer" id="sbf_ty" placeholder="комментрарий">
			<input type="submit" class="nomer" id="sbf_submit" value="добавить" onclick="book_add('1');$('#shortbookform').toggle('300');">
		</div>
	</div>
	<?php if($is_full=='FULL'){?>
		<span class="filtername exportbutton hand" onclick="export_xlsx('<?php echo $filter; ?>'); " title="Экспорт звонков в MS EXCEL">xlsx</span> <span class="filtername exportbutton hand" onclick="export_csv('<?php echo $filter; ?>');" title="Экспорт звонков в текст, разделенный запятыми">csv</span> 
	<?php }else{?>
		<span class="filtername exportbutton hand inactive" onclick="inactive();" title="Экспорт звонков в MS EXCEL">xlsx</span> <span class=" inactive filtername exportbutton hand" onclick="inactive();" title="Экспорт звонков в текст, разделенный запятыми">csv</span> 
	<?php }?>
</div>

<hr class="hr_title">

<div class="desktop">
		<?php //print_r($total); ?>
		<?php if(count($cdr)==0 ){ 	?>
		<span>Записей не найдено</span>
		<?php }else{?>
		<span id="temp" class="temp" ><?php echo $this->input->cookie('STAT', TRUE); $this->input->set_cookie('STAT', '', '-3600'); ?></span>
	<table class="table_cdr" id="table">
		<thead>
			<tr class="cdr">
				<td>Тип</td>
				<td>Дата</td>
				<td>Линия</td>
				<td>Источник</td>
				<td>Назначение</td> 
				<td>Статус</td>
				<td>Длительность</td>
				<td>Прослушать</td>
				<!--td>Сохранить</td-->
			</tr>
		</thead>
		<tbody>
	
		
		<?php 
		$i=1;
		foreach($cdr as $one){ ?>
			
			<tr class="cdr">
			
			<!-- столбец направление -->
			<td>
				<?php if($one['direct']=='incom'){ ?>
								<i class="fa fa-arrow-right arrow_right " title="<?php echo $i ?>">
				<?php }else{	?>		 
								<i class="fa fa-arrow-left arrow_left" title="<?php echo $i ?>">
				<?php }	?>
			</td>	
			
			<!-- столбец дата звонка -->
			<td><?php echo $one['calldate'];	?></td> 
			
			<!-- столбец DID -->
			<td title="<?php echo $one['channel']." ".$one['dstchannel']?>"><?php echo $one['account'];	?></td> 
			
			<!-- столбец источник -->
			<td onclick="callto('<?php echo $one['src'];?>')" ondblclick="dialnum('<?php echo $this->input->cookie('auth_exten', TRUE);?>','<?php echo $one['src'];?>')" class="callable"><?php echo $one['src'];?><?php echo $one['src_name'];?></td> 
			
			<!-- столбец назначение--> 
			<td onclick="callto('<?php echo $one['dst'];?>')"  ondblclick="dialnum('<?php echo $this->input->cookie('auth_exten', TRUE);?>','<?php echo $one['dst'];?>')" class="callable"><?php echo $one['dst'];?><?php echo $one['dst_name'];?></td >
			
			<!-- столбец статус-->
			<td><?php echo $one['status'];?></td> 
			
			<!-- столбец длительность -->
			<td><?php echo $one['dur'];?></td>
			
			<!-- столбец воспроизвести сохранить -->
			<td>
			<?php if($one['recordingfile']){?>
					<span  id="pause<?php echo $i;?>"  style="display:none" onclick="pause('<?php echo $i;?>')"><i class="fa fa-pause hand"></i></span>
					<span  id="replay<?php echo $i;?>"  style="display:none" onclick="replay('<?php echo $i;?>')"><i class="fa fa-play-circle hand"></i></span>
					<span  id="play<?php echo $i;?>"><i class="fa fa-play hand " onclick="play('<?php echo $one['recordingfile']; ?>','<?php $d=explode(' ',$one['calldate']); echo $d[0] ?>','cdr','<?php echo $i;?>')" title="Слушать"></i></span>
			<?php };?>
			<!--/td>
			<td-->
			&nbsp;
			<?php if($one['recordingfile']){?>
							<i class="fa fa-download hand" onclick="save('<?php echo $one['recordingfile']; ?>','<?php $d=explode(' ',$one['calldate']); echo $d[0] ?>','cdr')"   title="Сохранить"></i>
			<?php };?>
			</td>
		</tr>
		<?php
			$i++;
			} ;?>
		</tbody>
	</table>
	<br/>
	<?php } //  end of if(count($cdr)) ?>
</div> 

<?php
$page='history';
 require('filter.php');
?>

<div id="addtobook" class="floatblock hidden">
	<label>Номер</label><br>
	<input type="text" value=""  placeholder="123456" class="textfield" id="new_no" ><br>
	<label>Имя</label><br>
	<input type="text" value=""  placeholder="Иван Иванович" class="textfield" id="new_na" ><br>
	<label>Комментарий</label><br>
	<input type="text" value="" placeholder="рабочий" class="textfield" id="new_ty"><br>
	<i class="fa fa-plus hand" onclick="book_add();"></i><label class="hand" onclick="book_add();"> Добавить</label><br>
	
</div>
	

