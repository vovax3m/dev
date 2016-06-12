//<!----FWD 
	function clear_fwd(){
		$(':input')
		.not(':button, :submit,:reset,:hidden')
		.val('')
		.removeAttr('checked')
		.removeAttr('selected');
		$('#exten').val($('#dvoext').html());
		
	}
	function showdvo(ext){
		//clearform
		clear_fwd();
		
		//fill form
		if($('#cu'+ext).val() !=undefined  ){
		//custom numbers
			//console.log($('#cu'+ext).val());
			
			$('#extsettings').hide();
			$('#customsettings').show();
			$('#customext').html(ext);
			$('#customexten').val(ext);
			$('#dial').val($('#cu'+ext).val());
		}else{
		//regular numbers
			
			$('#customsettings').hide();
			$('#extsettings').show();
			$('#dvoext').html(ext);
			$('#exten').val(ext);
			$('#cf').val($('#cf'+ext).val());
			$('#cfb').val($('#cfb'+ext).val());
			$('#cfu').val($('#cfu'+ext).val());
			if($('#cw'+ext).val() == 'ENABLED'){
				$('#cw').prop('checked', true); 
			}
			if($('#dnd'+ext).val()=='YES'){
				$('#dnd').prop('checked', true); 
			}
		}

	}
// FWD	----> 

function sufl(b,a){
		var type=$('#spy_type').val(); 
		
		$.ajax({
			type:"GET",
			url: '/monitor/spy/?a='+a+'&b='+b+'&type='+type,
			success: function(data) {
				//$('#conv_content').html(data);
				//console.log('spy '+data);
				
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError); 
			} 
		});
	}
	
function drop(ch){
		//console.log(ch);
		$.ajax({
			type:"GET",
			url: '/monitor/dropcall/?ch='+ch,
			success: function(data) {
				//$('#conv_content').html(data);
				console.log('drop '+data);
				
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError); 
			} 
		});
	}
	
function change_RPP(el){
	$.ajax({
			url: '/history/changeRPP/'+el,
			success: function(data) {
				$('#temp').html(data); 
				window.location.reload();
			},
			// в случае ошибки алерим ошибку
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError); 
			},
	});
}
function change_page(el){
	$.ajax({
			url: '/history/changepage/'+el,
			success: function(data) {
				$('#temp').html(data); 
				window.location.reload();
			},
			// в случае ошибки алерим ошибку
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError); 
			},
	});
}

function inactive(){
	var i=confirm('Данная функция доступна в полной версии. Для перехода на страницу с подробной информацией нажмите "OK", для отмены "Отмена"');
	if(i){
		window.location='purchase';
	}
	
}

function callto(no){
	$('#nomer').val(no);
	
}
function dialnum(a,c){
	//alert(' Функционал в разработке, будем звонить на  '+$('#nomer').val());
	var b=$('#nomer').val();
	if(c){
		b = c;
		console.log('c=true');
	}
	console.log(a+'='+c+'='+b);
	$.ajax({
			url: 'ajax/click2call/'+a+'/'+b,
			// перед получение результата выводим сообщение
			beforeSend:function(data){
			$('#temp').html('дождитесь звонка на ваш внутренний номер'); 
			$('#temp').show('fast');
			},
			// при успешном выполении выводим содержимое 
			success: function(data) {
				$('#temp').html('поднимите трубку');
				$('#temp').show('fast');
				console.log('call from '+a+' to '+b);
				//console.log(data);
			},
			// в случае ошибки алерим ошибку
			error: function (xhr, ajaxOptions, thrownError) {
				console.log(thrownError); 
			} 
		});
}

function set_filter(ext){
	
	$('[name="exten"]').val(ext);
	$('#filterform').submit();
}

/* Сброс фильтра истории звонков
	все просто, очищаем поля, снимаем чекбоксы
*/
function reset_cdr_filter(){
	/*console.log('reset');
	$('#startdate').val('');
	$('#enddate').val('');
	$('#incom').prop("checked", false) ;
	$('#outcom').prop("checked", false) ;
	$('#recyes').prop("checked", false) ;
	$('#recno').prop("checked", false) ;
	$('[name="exten"]').val('');
	$('[name="durtype"]').val('');
	$('[name="durtime"]').val('');
	$('[name="anstype"]').val('');
	*/
	$(':input','#filterform')
		.not(':button, :submit,:reset,:hidden')
		.val('')
		.removeAttr('checked')
		.removeAttr('selected');
	
}
/* Сброс формы ivr 
	все просто, очищаем поля
*/
function resetivrform(){
	//console.log('reset');
	$('#upload').val('');
	$('#setas').val('');
	$('#upload_link').val('Выберите Файл');
	
}
/*
 функция сохранения файла 
 ввод
  ждет 3 параметра 
   fn=название файла,
   n=дата,
   type =тип 
  вывод 
   заполняем блок id="temp" ссылкой, отображаем блок
*/
function save(fn,n,type){
	// если тип cdr  то файл записи разговора, для этого нужна дата звонка
	if(type=='cdr'){
		// формируем квери стринг строку запроса 
		var string='&fn='+fn+'&cdr=true&date='+n;
		//обращаемся к url c параметрами
		$.ajax({
			url: 'ajax/downloadivr/?'+string,
			// перед получение результата выводим сообщение
			beforeSend:function(data){
			    $('#temp').html('Пожалуйста, подождите..'); 
				$('#temp').show('fast');
			},
			// при успешном выполении выводим содержимое 
			success: function(data) {
				$('#temp').html(data);
				$('#temp').show('fast');
			},
			// в случае ошибки алерим ошибку
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError); 
			} 
		});
	// если тип не == cdr
	}else{
		// строка запроса несколько урезана, поэтому  удаленный скрипт отдает нам файл приветствия
		var string='&fn='+fn;
		$.ajax({
			url: 'ajax/downloadivr/?'+string,
			// перед получение результата выводим сообщение
			beforeSend:function(data){
				$('#temp').html('Пожалуйста, подождите..'); 
				$('#temp').show('fast');
			},
			// при успешном выполении выводим содержимое 
			success: function(data) {
				$('#temp').html(data);
				$('#temp').show('500');
			},
			// в случае ошибки алетрим ошибку
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError); 
			}
					  
		});
	}
}
/*
 функция воспроизведения файла 
 ввод
  ждет 3 параметра 
   fn=название файла,
   n=дата,
   type =тип 
  вывод 
   заполняем блок id="temp" html плеером, отображаем блок 
*/
function pause(id){ 
	console.log('function pause called');
	$('#pause'+id).hide();
	$('#replay'+id).show();
	$('#audio'+id).trigger("pause");
}
function replay(id){
	console.log('function playagain called');
	$('#pause'+id).show();
	$('#replay'+id).hide();
	$('#play'+id).hide();
	$('#audio'+id).trigger("play");
}

function play(fn,n,type,id){
	console.log('function play called');
	// формируем строку запроса
	var string='&fn='+fn;
	// если указан  парамето тип и он равен cdr
	if(type=='cdr'){
		// переформировываем стоку запроса
		var string='&fn='+fn+'&cdr=true&date='+n;
		//выполняем  аякс запрос 
		$.ajax({
			url: 'ajax/downloadivr/?'+string,
			// перед получение результата выводим сообщение
			beforeSend:function(data){
				$('#temp').html('Пожалуйста, подождите..'); 
				$('#temp').show('fast');
			},
			// при успешном выполении выводим аудио блок 
			success: function(data) {
				$('#temp').html('<div align="center">'+fn +' <br><audio  autoplay controls id="audio'+id+'" ><source src="files/'+fn+'" type="audio/x-wav"> </audio></div>');
				$('#temp').show('fast');
				$("[id^='pause']").hide();
				$("[id^='replay']").hide();
				$("[id^='play']").show();
				$('#pause'+id).show();
				$('#play'+id).hide();
				document.getElementById('audio'+id).addEventListener('play', function(){
					console.log('playing');
				//	pause(id);
				$('#pause'+id).show();
					$('#replay'+id).hide();
					$('#play'+id).hide();
				});
				document.getElementById('audio'+id).addEventListener('pause', function(){
					console.log('pausing');
				//	playagain(id);
					$('#pause'+id).hide();
					$('#replay'+id).show();
				});
			},
			// в случае ошибки алерим ошибку
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError); 
			} 
		});
	// если тип не == cdr
	}else{
		//выполняем  аякс запрос 
		$.ajax({
			url: 'ajax/downloadivr/?'+string,
			// перед получение результата выводим сообщение
			beforeSend:function(data){
				$('#play'+n).html('Пожалуйста, подождите..'); 
			},
			// при успешном выполении выводим аудио блок 
			success: function(data) {
				$('#play'+n).html('<audio  autoplay controls><source src="files/'+fn+'" type="audio/x-wav"> </audio>');
				//$('#temp').show('500');
			},
			// в случае ошибки алерим ошибку
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError); 
			} 
		});
	}
}
/*
	Функция формирования файлов экспорта звонков
	принимает строку запроса
	отдает  ссылку на файл
*/
function export_xlsx(qs){
	// прибавляем параметр export_xlsx=true для обработки удаленным скриптом, чтобы не было ограничений по выводу строк(пагинации)
	var string=qs+'&export_xlsx=true';
	//выполняем аякс  запрос
	$.ajax({
		// обращаемся к контроллеру ajax
		url: 'ajax/export_xlsx?'+string,
		// перед получением результата выводим сообщение
		beforeSend:function(data){
				$('#temp').html('Пожалуйста, подождите..'); 
				$('#temp').show('fast');
		},
		// при успешном выполении выводим данные ссылку из файла
		success: function(data) {
			$('#temp').html(data);
			$('#temp').show('500');
		},
		// в случае ошибки алерим ошибку
		error: function (xhr, ajaxOptions, thrownError) {
			alert(thrownError);
		}
				  
	});
}
/*
	Функция формирования файлов экспорта звонков аналогично экселю
	принимает строку запроса
	отдает  ссылку на файл
*/
function export_csv(qs){
	var string=qs+'&export_csv=true';
	//выполняем аякс  запрос
	$.ajax({
		url: 'ajax/export_csv?'+string,
		// перед получением результата выводим сообщение
		beforeSend:function(data){
				$('#temp').html('Пожалуйста, подождите..'); 
				$('#temp').show('fast');
		},
		// при успешном выполении выводим данные ссылку из файла
		success: function(data) {
			$('#temp').html(data);
			$('#temp').show('500');
		},
		// в случае ошибки алерим ошибку
		error: function (xhr, ajaxOptions, thrownError) {
			alert(thrownError);
		}
				  
	});
}



$(function(){
	

	// инициализация модуля datepicker выбор даты
	//начальная дата
	$('#startdate').datepicker({ 
		dateFormat: "yy-mm-dd",
		changeMonth: true,
		changeYear: true,
		showOtherMonths: true,
		selectOtherMonths: true,
		firstDay: "1",
		onClose: function( selectedDate ) {
			$( "#enddate" ).datepicker( "option", "minDate", selectedDate );
		}
	});
	//конечная дата
	$('#enddate').datepicker({ 
		dateFormat: "yy-mm-dd",
		changeMonth: true,
		changeYear: true,
		showOtherMonths: true,
		selectOtherMonths: true,
		firstDay: "1",
		onClose: function( selectedDate ) {
			$( "#startdate" ).datepicker( "option", "maxDate", selectedDate ); 
		}
	});
	// настройки  модуля datepicker
	$.datepicker.regional['ru'] = { 
		closeText: 'Закрыть', 
		prevText: '&#x3c;Пред', 
		nextText: 'След&#x3e;', 
		currentText: 'Сегодня', 
		monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь', 
								'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'], 
		monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн', 
										'Июл','Авг','Сен','Окт','Ноя','Дек'], 
		dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'], 
		dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'], 
		dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'], 
		dateFormat:  "yy-mm-dd",
		firstDay: 1, 
		isRTL: false 
	}; 
	// установка региональных настроек
	$.datepicker.setDefaults($.datepicker.regional['ru']); 	 			
});

