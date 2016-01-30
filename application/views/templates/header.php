
<!doctype html>
<html>
<head>
	<title>Диалог.Кабинет</title>
	<meta charset="utf-8"/>
	<link rel="stylesheet" href="<?php echo $base_url;?>static/style/style.css" type="text/css"/> 
	

	<script type="text/javascript" src="<?php echo $base_url;?>static/js/jquery.js"></script>  
	<script type="text/javascript" src="<?php echo $base_url;?>static/js/main.js"></script>   
	<!--if full version-->
	<script type="text/javascript" src="<?php echo $base_url;?>static/js/ajaxupload.js"></script>  
	<!--<script type="text/javascript" src="<?php echo $base_url;?>static/js/ajaxupload.min.js"></script>   -->
	<script type="text/javascript" src="<?php echo $base_url;?>static/js/book.js"></script>   
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script> 


</head>
<body>
<div id="pagewidth">
	<div id="header" >
	<div class="lefthead">
		<a href="<?php echo $base_url;?>" title="Переход к главной странице"><img src="http://dialog64.ru/wp-content/themes/customizr/inc/img/dialog_logo2.png" class="logo"></a>

		</div><span title="Ранняя версия, возможно неточности, перерывы в работе" class="hand">3</span> <!--a href="mailto:cabinet@dialog64.ru" title="По вопросам, предложениям, обнаруженным ошибкам пишите на данный адрес">cabinet@dialog64.ru</a-->
		<div class="exithead" > 
			
			<?php 
			/* проверка если пользователь с админскими правами то показываем баланс*/
			if($isadmin): ?>
				<span class="border-ccc"  title="Остаток кредитного лимита" >
					<span class="balans">
						<?php $this->load->library('funcs');
						echo $this->funcs->getsaldo(); ?>
					</span>
					
					<span class="rouble">
						<i class="fa fa-rub"></i>
					</span>
				</span>&nbsp;&nbsp;
			<?php endif;?>
			    <span class="border-ccc">
					<!--i class="fa fa-phone green "></i-->
					
					<span class="extension "  title="Ваш номер (имя)">
						<span class="balans"><?php echo $this->input->cookie('auth_exten', TRUE); ?></span>
						(<i class="fa fa-user "></i>&nbsp;<?php echo $this->input->cookie('auth_username', TRUE); ?>)				
					</span>
				</span>
				&nbsp;&nbsp;
				<span  class="border-ccc"  title="Выход из кабинета">
				<a href="/auth/logout"><i class="fa fa-sign-out exitbutton" ></i> Выход</a>
				</span>
				<br>
				<span class="company_name" onclick="$('#rekvizit').toggle();">
					<?php echo $this->config->item('klient_name');?>
				</span>
				<div class="hidden floatblock" id="rekvizit"  onclick="$('#rekvizit').hide();">
					<div>Тут будут отображаться реквизиты данного клиента. Номер горовора и тд и тп.</div>
				</div>
			</span>
		</div>
		<!--
		<div class="righthead" title="Название Вашей организации">
			
			
		</div>
		-->
	</div>
	<div id="wrapper" class="clearfix">
	<div id="twocols">
	<!--<div id="maincol"> -->
	