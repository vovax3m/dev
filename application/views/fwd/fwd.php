<div>
	<span class="title">Переадресации</span>
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
<br>
<?php if($isadmin):?>	
	<div id="extlist" class="leftpart" >
	<?php// print_r($ext); ?>
	<div align="center"><span class="minititle">Внутренние номера</span></div>	
	<br>
	
		<?php
				$text_size='twentypx';
				foreach($ext as $param){
					//print_r($param);
					if($param[1]=='sip' and $param[2]=='OK') {
						if(array_key_exists($param[0], $fwd)) {
							$style='back-red '.$text_size;
							//echo
							$hiddenform=
							'<input type="hidden" id="cf'.$param[0].'"  value="'.$fwd[$param[0]]['CF'].'">
							<input type="hidden" id="cfb'.$param[0].'"  value="'.$fwd[$param[0]]['CFB'].'">
							<input type="hidden" id="cfu'.$param[0].'"  value="'.$fwd[$param[0]]['CFU'].'">
							<input type="hidden" id="cw'.$param[0].'"  value="'.$fwd[$param[0]]['CW'].'">
							<input type="hidden" id="dnd'.$param[0].'"  value="'.$fwd[$param[0]]['DND'].'">';
						}else{
							$style='back-green '.$text_size;
						}
						
						
					}else{
						if(array_key_exists($param[0], $fwd)) {
							$style='back-red '.$text_size;
							$hiddenform=
							'<input type="hidden" id="cf'.$param[0].'"  value="'.$fwd[$param[0]]['CF'].'">
							<input type="hidden" id="cfb'.$param[0].'"  value="'.$fwd[$param[0]]['CFB'].'">
							<input type="hidden" id="cfu'.$param[0].'"  value="'.$fwd[$param[0]]['CFU'].'">
							<input type="hidden" id="cw'.$param[0].'"  value="'.$fwd[$param[0]]['CW'].'">
							<input type="hidden" id="dnd'.$param[0].'"  value="'.$fwd[$param[0]]['DND'].'">';
						}else{
							$style='back-green '.$text_size;
						}
						
					}
					if(($param[1]) !='sip'){
						$hiddenform='<input type="hidden" id="cu'.$param[0].'"  value="'.$param[1].'">';
							
						
							$style='back-grey '.$text_size;
						   $title="   ".$param[1]."   ";
						
						
					}
				?> 
				<span   class="extitem hand <?php echo $style ?>" title="<?php echo $param['n'].' '.$param['t'].$title;?>" onclick="showdvo('<?php echo $param[0] ?>')">
					<?php echo $param[0]; echo $hiddenform; ?> 
				</span>  
				<?php 
				unset($style);
				unset($title);
				unset($hiddenform);
				}?>
	</div>
<?php  else: // isadmin
	//print_r($fwd);
	foreach($fwd as $ext  => $param):
		$hiddenform=
							'<input type="hidden" id="cf'.$ext.'"  value="'.$param['CF'].'">
							<input type="hidden" id="cfb'.$ext.'"  value="'.$param['CFB'].'">
							<input type="hidden" id="cfu'.$ext.'"  value="'.$param['CFU'].'">
							<input type="hidden" id="cw'.$ext.'"  value="'.$param['CW'].'">
							<input type="hidden" id="dnd'.$ext.'"  value="'.$param['DND'].'">';
		echo $hiddenform; 										
	endforeach;
			
 endif; // isadmin?>	
	<div id="conversations" <?php if($isadmin): ?>class="rightpart"<?php endif;?>  align="center">
	<div align="center"><span id="temp" class="temp" ><?php echo $stat=$this->input->cookie('STAT', TRUE); $this->input->set_cookie('STAT', '', '-3600'); ?></span><?php if($stat):?><script>setTimeout("$('#temp').hide(100)", 5000);</script><?php endif;?></div>
		<div id="extsettings" class="hidden" align="center">
			
			<form action="/fwd/set" method="POST">
			<div align="center"><span class="minititle" id="dvoext"></span></div>
			<input type="hidden" id="exten" name="exten" value="" class="textfield_FB">
			<p><span class="filtername" title="Вызов будет поступать сразу на указанный номер">Безусловная переадресация</span><br>
			<input type="text" id="cf" name="cf"  value="" class="textfield_FB"></p>
			<p><span class="filtername" title="Вызов будет поступать на указанный номер при занятости основного и при 3 звонке, при активной услуге 'Ожидание вызова' ">Переадресация по занятости</span><br> 
			<input type="text" id="cfb" name="cfb" value="" class="textfield_FB"></p>
			<p><span class="filtername" title="Вызов будет поступать на указанный номер при неответу на основной, или в случае его недоступности">Переадресация по неответу</span><br>
			<input type="text" id="cfu"  name="cfu" value="" class="textfield_FB"></p>
			<table class="textfield_table">
				<tr>
					<td>
						<p><span class="filtername" title="Режим ожидания для второго звонка если линия занята">Ожидание вызова</span><br>
			<input type="checkbox" id="cw" name="cw" class="textfield_FB"></p>
					</td>
					<td>
			<p><span class="filtername" title="Режим  'Не беспокоить' вызовы не будут поступать">Не беспокоить</span><br>
			<input type="checkbox" id="dnd"  name="dnd"  class="textfield_FB"> </p>
					</td>
				</tr>
			</table>
			<p><input type="button" id="clear"  value="Очистить" class="textfield_FB" onclick="clear_fwd()"> </p>
			<p><input type="submit" id="sub"  value="Применить" class="textfield_FB"> </p>
			</form>
		</div>
		<div id="customsettings" class="hidden" align="center">
			
			<form action="/fwd/setcustom" method="POST">
				<div align="center"><span class="minititle" id="customext"></span></div>
			<input type="hidden" id="customexten" name="exten" value="" class="textfield_FB">
			<p><span class="filtername" title="Вызов будет поступать на указанный номер">Назначение</span><br>
			<input type="text" id="dial" name="dial"  value="" class="textfield_FB"></p>
			<p><input type="submit" id="sub"  value="Применить" class="textfield_FB"> </p>
			</form>
		</div>
	</div>

	<?php 
		if($this->input->cookie('gotoext', TRUE)){ 
			echo "<script>showdvo(".$this->input->cookie('gotoext', TRUE).");</script>";
		};
		if($gotoext){ 
			echo "<script>showdvo(".$gotoext.");</script>";
		};
		$this->input->set_cookie('gotoext', '', '-3600'); 
	?>
	