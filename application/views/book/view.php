<div >
	<span class="title">Телефонный справочник</span>
	<div id="rightcol" >
		<div class="search"  title="Введите номер для звонка"> 
			<input type="text" class=" nomer" id="nomer" placeholder="номер">
			<span class="call" onclick="dialnum('<?php echo $this->input->cookie('auth_exten', TRUE);?>');"><i class="fa fa-phone-square hand"  title="Позвонить"></i></span>
			
			<?php if($is_full=='FULL'){?>
			<span  class="book_button" onclick="AddToBook();"><i class="fa fa-book hand"  title="Добавить номер в книгу"></i></span>
			<?php }else{?>
			<span  class="book_button inactive" onclick="inactive();"><i class="fa fa-book hand inactive"  title="Добавить номер в книгу"></i></span>
			<?php }?>
		</div>
	</div>
	<br>
</div>

<hr class="hr_title">
<span id="temp" class="temp" ><?php echo $this->input->cookie('STAT', TRUE); $this->input->set_cookie('STAT', '', '-3600'); ?></span><br>
<div class="proc60" align="center";>
<input type="text" value="<?php echo $search?>" class="textfield_95proc" id="search_book"  onchange="search_book(this.value)" placeholder=" поиск "><br><br>
<table  class="cdr_table" >
		<thead>
			<tr>
				<td>номер</td>
				<td>имя</td>
				<td>комментарий</td>
				<td>операции</td>
			</tr>
		<tr >
			<td>
				<input type="text" value=""  placeholder="123456" class="textfield3" id="new_no" >
			</td>
			<td>
				<input type="text" value=""  placeholder="Иван Иванович" class="textfield3" id="new_na" >
			</td>
			<td>
				<input type="text" value="" placeholder="рабочий" class="textfield3" id="new_ty">
			</td>
			<td class="twentypx">
			<?php if($is_full=='FULL'){?>
				<i class="fa fa-plus hand" onclick="book_add();"></i>
			<?php }else{?>	
				<i class="fa fa-plus hand inactive" onclick="inactive()";></i>
			<?php }?>
			</td>
		
		</tr>
		</thead>
		<tbody id="booklist">
		
<?php foreach($book as $r):
	$id=$r['id'];	
	?>
	<tr id="row<?php echo $id;?>" class="cdr">
		<td id="no<?php echo $id;?>" onclick="callto('<?php echo $r['nomer'];?>')"   ondblclick="dialnum('<?php echo $this->input->cookie('auth_exten', TRUE);?>','<?php echo $r['nomer'];?>')" class="callable">
			<?php echo $r['nomer'];	?>
		</td>
		<td  id="na<?php echo $id;?>">
			<?php echo $r['name'];	?>
		</td>
		<td  id="ty<?php echo $id;?>">
			<?php echo $r['type'];	?>
		</td>
		<td class="twentypx">
			<?php if($is_full=='FULL'){?>
				<i class="fa fa-save hand" id="save<?php echo $id;?>" style="display:none" onclick="book_save('<?php echo $id;?>')"></i>
			<!--	<span id="save<?php echo $id;?>" style="display:none" onclick="book_save('<?php echo $id;?>')">
					<i class="fa fa-save hand"></i>
				</span> 
				&nbsp;&nbsp;-->
				<i class="fa fa-edit hand" id="edit<?php echo $id;?>" onclick="book_edit('<?php echo $id;?>');"></i>
				&nbsp;&nbsp;
				<i class="fa fa-trash hand" onclick="book_del('<?php echo $id;?>');"></i>
			<?php }else{?>	
				<i class="fa fa-edit hand inactive" onclick="inactive();"></i>
				&nbsp;&nbsp;
				<i class="fa fa-trash hand inactive" onclick="inactive();"></i>
			<?php }?>
		</td>
	</tr>
<?php endforeach;?>
</tbody>
</table>
</div>
<div class="proc20" align="center";>
	<?php if($is_full=='FULL'){?>
	<div id="uploadButton" class="title hand"  title="Из csv файла в формате номер,имя,комментарий  каждая запись на новой строке ">
		<font>Импорт</font>
	</div>
	<?php }else{?>	
	<div onclick="inactive();" class="title hand inactive"  title="Из csv файла в формате номер,имя,комментарий  каждая запись на новой строке ">
		<font>Импорт</font>
	</div>
	<?php }?>
	<br>
	<?php if($is_full=='FULL'){?>
	<span class="title hand" title="В csv файл"><font onclick="export_book('<?php echo $search?>');">Экспорт </font></span>
	<?php }else{?>	
	<span class="title hand inactive" title="В csv файл"><font onclick="inactive();">Экспорт </font></span>
	<?php }?>
</div>
 <div id="result"></div>