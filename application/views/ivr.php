<div >
	<span class="title">Приветствия</span>  
	
</div>
<hr class="hr_title">
	<!--div class="desktop"-->
		<div align="center"><span id="temp" class="temp" ondblclick="$('#temp').hide();"><?php echo $this->input->cookie('STAT', TRUE); $this->input->set_cookie('STAT', '', '-3600');?></span></div>
		<table>
		<?php $i=0; foreach($row as $fn ){ ?>
						<tr>
							<td><span><?php echo $fn; ?></span></td>
							<td ><span id="play<?php echo $i; ?>" ><i onclick="play('<?php echo $fn; ?>','<?php echo $i; ?>')" class="fa fa-play "></i> </span></td> 
							<td ><span ><i class="fa fa-download " onclick="save('<?php echo $fn; ?>')"></i></span></td> 
						</tr>
		<?php $i++;}?>
		</table>
		<br>
		<form align="center" action="ivr/add" id="addivr" method="post" accept-charset="utf-8" enctype="multipart/form-data">
		<script>

	$(function(){
		$("#upload_link").on('click', function(e){
			e.preventDefault(); 
			$("#upload:hidden").trigger('click');
		});
	});
	</script>
		
			<div id="addfile">
							<input id="upload"  name ="upload"  style="display:none" type="file" required accept="audio/wav,audio/x-wav" onchange='inputNode = this.value.replace("C:\\fakepath\\", "");;document.getElementById("upload_link").value=  inputNode;'/>  
							<input type="button" id="upload_link" class="textfield2" value="Выберите файл">
			<div id="filename"></div>
		</div>
			<p><select  id="setas"  name ="setas" class="textfield2" required>
				<?php foreach($row as $fn ){
						if(!strstr($fn,'.bak')){?>
							<option value="<?php echo $fn; ?>"> Установить как <?php echo $fn; ?></option> 
						<?php }?>
				<?php	 }?>
			</select></p>
			<p><input type="submit" value="Установить" class="textfield2"></p>
			<p><input type="button" onclick="resetivrform()" value="Сбросить" class="textfield2"></p>
			<p align="left"><span class="minititle" >Внимание</span> <hr> Перед установкой нового приветствия рекомендруется сохранить текущее. Текущий заменяемый файл будет сохранен с пометкой .bak, и  заменен вновь устанавливаемым. <br>Требование к загружаемому файлу: pcm 8000Hz 16bit mono signed </p>
		</form>
		
	<!--/div-->

	