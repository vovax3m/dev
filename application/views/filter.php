<div class="filter">
	<span class="minititle"> Фильтр</span><br/>
	<span class="filtername"> Диапазон дат </span><br/>
		<form action="/history/setfilter/<?php echo $page;?>" method="GET" id="filterform">
		
		<input type="text" id="startdate" name="startdate" class="textfield <?php if($fltr['startdate']) echo 'activefltr';?> " value="<?php echo $fltr['startdate']?>" placeholder="начальная дата" ><br/>	
		<input type="text" id="enddate" name="enddate" class="textfield <?php if($fltr['enddate']) echo 'activefltr';?>" value="<?php echo $fltr['enddate']?>" placeholder="конечная дата"><br/>	
		<span class="filtername"> Тип вызова </span><br>
		<label class="check <?php if($fltr['incom']=='on') echo 'activefltr';?>" ><input type="checkbox"  name="incom" id="incom"  <?php if($fltr['incom']=='on'){ echo 'checked';}?>>Входящие</label>
		<label class="check <?php if($fltr['outcom']=='on') echo 'activefltr';?>" ><input type="checkbox" name="outcom"  id="outcom" <?php if($fltr['outcom']=='on'){ echo 'checked';}?>>Исходящие</label></br>
		<span class="filtername"> Наличие записи</span><br>
		<label class="check <?php if($fltr['recyes']=='on') echo 'activefltr';?>" ><input type="checkbox"  name="recyes" id="recyes"  <?php if($fltr['recyes']=='on'){ echo 'checked';}?>>Есть</label>
		<label class="check <?php if($fltr['recno']=='on') echo 'activefltr';?>" ><input type="checkbox" name="recno"  id="recno" <?php if($fltr['recno']=='on'){ echo 'checked';}?>>Нет</label>
		</br>
		<span class="filtername">Внутренний номер</span><br>
		<?php if($isadmin){	?>
	
		
	<select  name="exten"  class="textfield <?php if($fltr['exten']) echo 'activefltr';?> placeholder="внутренний номер" title="внутренний номер">
	<option  value="">не выбран</option>
	<?php 	foreach($extlist as $ext){ ?>
		<option <?php if($fltr['exten']===$ext[0]) echo 'selected'?> value="<?php echo $ext[0];?>"><?php echo $ext[0];?></option>
	<?php } ?>
	 </select>
		
		<?php } else{?>
		<input type="hidden"  name="exten"  value="<?php echo $this->input->cookie('auth_exten', TRUE); ?>" ><?php echo $this->input->cookie('auth_exten', TRUE); ?><br/>
		<?php } ?>
		<span class="filtername">Время разговора</span><br>
		<select class="textfield2 <?php if($fltr['durtype']) echo 'activefltr';?>" name="durtype" title="больше или меньше установленного значения">
								<?php
								if($userdata['durtype']==$id){  
									$sel='selected';
								}
								?>
								<option  value="">выберите</option>
								<option <?php if($fltr['durtype']=='mo') echo 'selected';?> value="mo">больше ></option>
								<option <?php if($fltr['durtype']=='le') echo 'selected';?> value="le">меньше <</option>
								<option <?php if($fltr['durtype']=='is') echo 'selected';?> value="is">равно =</option>
							  </select>
		
		<input type="text"  name="durtime" class="textfield2 <?php if($fltr['durtime_sec']) echo 'activefltr';?>" value="<?php echo $fltr['durtime_sec']?>" placeholder="60" title="Длительность разговора"><br/> 
		<span class="filtername">Статус звонка</span><br>
		<select class="textfield <?php if($fltr['anstype']) echo 'activefltr';?>" name="anstype">
								<?php
								if($userdata['anstype']==$id){
									$sel='selected';
								}
								?>
								<option  value="">все</option>
								<option <?php if($fltr['anstype']=='ans') echo 'selected';?> value="ans">отвечен</option>
								<option <?php if($fltr['anstype']=='noans') echo 'selected';?> value="noans">не отвечен</option>
								<option <?php if($fltr['anstype']=='busy') echo 'selected';?> value="busy">занято</option>
								<option <?php if($fltr['anstype']=='fail') echo 'selected';?> value="fail">ошибка</option>
								<option <?php if($fltr['anstype']=='unk') echo 'selected';?> value="unk">другой</option>
								 </select>
		<br>	
		<span class="filtername">Поиск  по звонкам</span><br>	
		<span class="filtername"> Поле</span><br>	
		<select class="textfield <?php if($fltr['findfield']) echo 'activefltr';?>" name="findfield">
			<option  value="">не выбрано</option>
			<option <?php if($fltr['findfield']=='src') echo 'selected';?> value="src">Источник</option>
			<option <?php if($fltr['findfield']=='dst') echo 'selected';?> value="dst">Назначение</option>
			<option <?php if($fltr['findfield']=='both') echo 'selected';?> value="both">В обоих</option>
		 </select><br>
		 <span class="filtername">точность</span><br>
		<select class="textfield <?php if($fltr['findplace']) echo 'activefltr';?> " name="findplace">
			<option  value="">не выбрано</option>
			<option <?php if($fltr['findplace']=='begin') echo 'selected';?> value="begin">Начинается с</option>
			<option <?php if($fltr['findplace']=='end') echo 'selected';?> value="end">Заканчивается на</option>
			<option <?php if($fltr['findplace']=='incl') echo 'selected';?> value="incl">Содержится</option>
			<option <?php if($fltr['findplace']=='equal') echo 'selected';?> value="equal">Точное совпадение</option>
		 </select><br>		
		<span class="filtername">запрос</span><br>		 
		<input type="text"  name="find" class="textfield <?php if($fltr['find']) echo 'activefltr';?>" value="<?php echo $fltr['find']?>" placeholder="740740" title="Поиск"><br/>  
		<br>
		<input type="submit" value="отобразить" class="textfield" >	
		<?php if(!$isadmin){	?>
		<input type="button" value="сбросить" class="textfield" onclick="reset_cdr_filter(<?php echo $this->input->cookie('auth_exten', TRUE); ?>);">
		<?php } else{?>
		<input type="button" value="сбросить" class="textfield" onclick="reset_cdr_filter();">
		<?php } ?>
		</form>

</div>