<div >
	<span class="title">Статистика</span> 
	<span align="center" class="paginblock" ><?php echo $pagin?></span >
	<!--<span class="filtername exportbutton" onclick="export_xlsx('<?php echo $filter; ?>');">xlsx</span> <span class="filtername exportbutton" onclick="export_csv('<?php echo $filter; ?>');">csv</span>  -->
</div>
<hr class="hr_title">
	<div class="desktop"> 
		<span id="temp" class="temp" ondblclick="$('#temp').hide();"></span>
	
	<?php 
	//echo $_SERVER['QUERY_STRING'];
	//	print_r( $actext);
	?>
	<!--основной блок -->
	<span class="title">
		<?php  if($fltr['exten']){
				 echo $fltr['exten'];
				 if($name){
				  echo " ".$name['name']." ".$name['type'];
				  
				 }
			  }else{?> Общая статистика<?php };?></span> 
		<div align="center">
		
		<br>
		<table> 
			<tr>
				<td class="blacktitle" align="left"><i class="fa fa-bars"></i> Параметр</td>
				<td class="blacktitle"><i class="fa fa-bell"></i> Суммарно </td>
				<td class="minititle"><i class="fa fa-arrow-left arrow_left"></i> Исходящие</td>
				<td class="title"> <i class="fa fa-arrow-right arrow_right"></i> Входящие</td>
			</tr>
			<tr class="cdr" >
				<td class="stat_item" align="left">Количество звонков</td>
				<td class="stat_item"><div class="leftpart"><?php echo $total;?></div><div class="green rightpart" >100%</div></td>
				<td class="stat_item  arrow_left"><?php echo $count_out;?> </td>
				<td class="stat_item  arrow_right"><?php echo $count_inc;?> </td>
			</tr>
			<tr class="cdr">
				<td class="stat_item" align="left">Отвеченные</td>
				<td class="stat_item"><div class="leftpart"><?php echo $count_talk;$prc=round(($count_talk*100)/$total,2 );?> </div> <div  class="green rightpart"><?php echo $prc; ?>%</div></td>
				<td class="stat_item  arrow_left"><?php echo $count_ans_out;?> </td>
				<td class="stat_item  arrow_right"><?php echo $count_ans_inc;?> </td>
			</tr>
			<tr class="cdr">
				<td class="stat_item" align="left">Не отвеченные</td>
				<td class="stat_item"><div class="leftpart"><?php echo $count_noans;$prc=round(($count_noans*100)/$total,2 );?></div>  <div class="green rightpart"><?php echo $prc; ?>%</div></td>
				<td class="stat_item  arrow_left"><?php echo $count_noans_out;?> </td>
				<td class="stat_item  arrow_right"><?php echo $count_noans_inc;?></td>
			</tr>
			<tr class="cdr">
				<td class="stat_item" align="left">Занятые</td>
				<td class="stat_item"><div class="leftpart"><?php echo $count_busy;$prc=round(($count_busy*100)/$total,2 );?></div> <div class="green rightpart"><?php echo $prc; ?>%</div></td>
				<td class="stat_item  arrow_left"><?php echo $count_busy_out;?></td>
				<td class="stat_item  arrow_right"><?php echo $count_busy_inc;?></td>
			</tr>
			<tr class="cdr">
				<td class="stat_item" align="left">Ошибочные</td>
				<td class="stat_item"><div class="leftpart"><?php echo $count_fail;$prc=round(($count_fail*100)/$total,2 );?></div> <div class="green rightpart"><?php echo $prc; ?>%</div></td>
				<td class="stat_item  arrow_left"><?php echo $count_fail_out;?></td>
				<td class="stat_item  arrow_right"><?php echo $count_fail_inc;?></td>
			</tr>
			<!--tr class="cdr">
				<td class="stat_item" align="left">Другие</td>
				<td class="stat_item"></td>
				<td class="stat_item  arrow_left"></td>
				<td class="stat_item  arrow_right"></td>
				<td>
			</tr-->
			<tr class="cdr">
				<td class="stat_item" align="left">Продолжительность</td>
				<td class="stat_item"><?php echo $h=floor($count_talktime_all/3600); echo  ":".$m=floor(($count_talktime_all-3600*$h)/60);echo ":".$s=($count_talktime_all-(3600*$h+$m*60));?></td>
				<td class="stat_item  arrow_left"><?php echo $h=floor($count_talktime_out/3600); echo  ":".$m=floor(($count_talktime_out-3600*$h)/60);echo ":".$s=($count_talktime_out-(3600*$h+$m*60));?></td>
				<td class="stat_item  arrow_right"><?php echo $h=floor($count_talktime_inc/3600); echo  ":".$m=floor(($count_talktime_inc-3600*$h)/60);echo ":".$s=($count_talktime_inc-(3600*$h+$m*60));?></td>
			</tr>
			<tr class="cdr">
				<td class="stat_item" align="left">Средняя продолжительность</td>
				<td class="stat_item"> <?php echo date("H:i:s", $count_talktime_all/$count_talk);//$count_talktime_all; $count_talk;?></td>
				<td class="stat_item  arrow_left"></td>
				<td class="stat_item  arrow_right"></td>
			</tr>
		</table>
		<br>
		<!-- блок   c Рейтингом-->
		<?php if(!$fltr['exten']){ ?>
		
		<div align="left"><span class="title callable" onclick="$('#rating').toggle('50')">Рейтинг активности абонентов</span></div><br>
		<div id="rating">
			<br>
			<table> 
				<tr>
					<td class="blacktitle" ><i class="fa fa-trophy"></i></td>
					<td class="blacktitle" ><i class="fa fa-user"></i> Абонент</td>
					<td class="blacktitle"><i class="fa fa-clock-o"></i> Продолжительность </td>
					
				</tr>
				
			  <?php $i=1;foreach ($rating as $ext):?>
				 <tr class="cdr" >
				 <td class="stat_item"><?php echo $i;?></td>
				 <td class="stat_item callable" onclick="set_filter('<?php echo $ext['no'];?>');"><div class="leftpart"><?php echo $ext['no'];?></div>  <div class="green rightpart"><?php echo $ext['name']; ?></div></td>
				 <td class="stat_item"><?php echo $h=floor($ext['dur']/3600); echo  ":".$m=floor(($ext['dur']-3600*$h)/60);echo ":".$s=($ext['dur']-(3600*$h+$m*60));$i++;?></td>
				 </tr>
			  <?php endforeach;?>
				</tr>
				
			</table>
		</div>
		<?php };?>
		<br>
		<!-- блок   c DID-->
		<?php if($acc){?>
		<div align="left"><span class="title callable" onclick="$('#calltracking').toggle('50')">Колл трекинг</span></div><br>
		<div id="calltracking">
		<br>
			<table> 
				<tr>
					<td class="blacktitle" ><i class="fa fa-trophy"></i></td>
					<td class="blacktitle" ><i class="fa fa-long-arrow-right"></i> Входящая линия</td>
					<td class="blacktitle"><i class="fa fa-bar-chart"></i> Количество </td>
					<td class="blacktitle"><i class="fa fa-clock-o"></i> Продолжительность </td>
					
				</tr>
				
		  <?php $i=1;foreach ($acc as $key=> $val):?>
			 <tr class="cdr" >
			 <td class="stat_item"><?php echo $i;?></td>
			 <td class="stat_item"><?php echo $key;?></td>
			 <td class="stat_item"><?php echo $val['count'];?></td>
			 <td class="stat_item"><?php echo $h=floor($val['dur']/3600); echo  ":".$m=floor(($val['dur']-3600*$h)/60);echo ":".$s=($val['dur']-(3600*$h+$m*60));$i++;?></td>
			 </tr>
		  <?php endforeach;?>
			</tr>
				
			</table>
		</div>
		<?php };?>
		</div>
		
	</div>
	<?php
	$page='stat';
 require('filter.php');
?>
	<!--
<div class="filter">
	<span class="minititle"> Фильтр</span><br/>
	<span class="filtername"> Диапазон дат </span><br/>
		<form action="stat" method="POST">
		<input type="text" id="startdate" name="startdate" class="textfield <?php if($fltr['startdate']) echo 'activefltr';?> " value="<?php echo $fltr['startdate']?>" placeholder="начальная дата" ><br/>	
		<input type="text" id="enddate" name="enddate" class="textfield <?php if($fltr['enddate']) echo 'activefltr';?>" value="<?php echo $fltr['enddate']?>" placeholder="конечная дата"><br/>	

		<span class="filtername">Номер оператора </span><br>
		<?php if($this->cab_auth->is_admin()){ ?>
		<input type="text"  name="exten"  class="textfield <?php if($fltr['exten']) echo 'activefltr';?>" value="<?php echo $fltr['exten']?>" placeholder="внутренний номер" title="внутренний номер"><br/>
		<?php } else{?>
		<input type="hidden"  name="exten"  value="<?php echo $this->input->cookie('auth_exten', TRUE); ?>" ><?php echo $this->input->cookie('auth_exten', TRUE); ?><br/>
		<?php } ?>
		<input type="submit" value="отобразить" class="textfield hand" >	
		<input type="button" value="сбросить" class="textfield hand" onclick="reset_stat_filter();" > 
		</form>


</div>
	-->

