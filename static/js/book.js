function AddToBook(){
	/*
	добавление с поля набора номера
	*/
	//alert(' Функционал в разработке, будем добавлять  в адресуную книгу  '+$('#nomer').val());
	$('#shortbookform').toggle('300');
}

function book_edit(id){
	var name=$('#na'+id).text();
	var nomer=$('#no'+id).text();
	var type=$('#ty'+id).text();
	
	$('#na'+id).html('<input type="text" value="'+name.trim()+'" class="textfield3">');
	$('#no'+id).html('<input type="text" value="'+nomer.trim()+'" class="textfield3">');
	$('#ty'+id).html('<input type="text" value="'+type.trim()+'" class="textfield3">');
	$('#save'+id).show();
	$('#edit'+id).hide();
	//console.log(name +'='+nomer+'='+type);
}
function book_save(id){
	
	var n_na=$('#na'+id ).children().val();
	var n_no=$('#no'+id ).children().val();
	var n_ty=$('#ty'+id ).children().val();
	
	$.ajax({
			url: '/book/set/',
			type:"POST",
			data:{
				'id' : id,
				'na' : n_na,
				'no' : n_no,
				'ty' : n_ty
				},
			success: function(data) {
				$('#na'+id).html(n_na);
				$('#no'+id).html(n_no);
				$('#ty'+id).html(n_ty);
				$('#save'+id).hide();
				$('#edit'+id).show();
				console.log('success');;
			},
			// в случае ошибки алерим ошибку
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError); 
			},
	});
	//if succees ajax save

}
function search_book(what){
	//var what=$('#search_book' ).val();
	$.ajax({
			url: '/book/search/',
			type:"POST",
			data:{
				
				'what' : what
				},
				success: function(data) {
				console.log(data);
				window.location.reload();
				},
			// в случае ошибки алерим ошибку
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError); 
			},
		});
}
function book_add(ph){
	if(ph){
		var new_na=$('#sbf_name' ).val();
		var new_no=$('#nomer').val();
		var new_ty=$('#sbf_ty' ).val();
	}else{
		var new_na=$('#new_na' ).val();
		var new_no=$('#new_no').val();
		var new_ty=$('#new_ty' ).val();
	}
	//alert(new_na+' '+new_no+new_ty);
	if(!new_na){
		alert('Имя обязательно для заполения');
		return false;
	}
	if(!new_no){
		alert(' номер обязательно для заполения');
		return false;
	}
	$.ajax({
			url: '/book/add/',
			type:"POST",
			data:{
				
				'na' : new_na,
				'no' : new_no,
				'ty' : new_ty
				},
				success: function(data) {
					if(!isNaN(data)){
						var id=data;
						$('#temp').html('номер добавлен в базу');
						$('#temp').show('fast');
						console.log('добавлено'+id);
						$('#booklist').prepend('<tr class="new" id="row'+id+'"><td id="no'+id+'">'+new_no+'</td><td id="na'+id+'">'+new_na+'</td><td id="ty'+id+'">'+new_ty+'</td><td> <span id="save'+id+'" style="display:none" onclick="book_save(\''+id+'\');"><i class="fa fa-save hand"></i></span>&nbsp;&nbsp;<i class="fa fa-edit hand" onclick="book_edit(\''+id+'\');"></i>&nbsp;&nbsp;<i class="fa fa-trash hand" onclick="book_del(\''+id+'\');"></i></td>	</tr>');
						$('#new_na' ).val('');
						$('#new_no').val('');
						$('#new_ty' ).val('');
						return true;
					}
					if(data=='exist'){
						alert('номер уже есть в базе');
						return false;
					}
					if(data=='wrong'){
						alert('недопустимый номер');
						return false;
					}
					console.log('out='+data);
				},
			// в случае ошибки алерим ошибку
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError); 
			},
		});
}
function book_del(id){
	var conf=confirm('Подтвердите удаление');
	if(conf==true){
	
		$.ajax({
			url: '/book/del/',
			type:"POST",
			data:{
				'id' : id
				},
				success: function(data) {
					console.log('#row'+id);
					$('#row'+id).hide();
				},
			// в случае ошибки алерим ошибку
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError); 
			},
		});
	}
	
}
function export_book(what){
	//var what=$('#search_book' ).val();
	$.ajax({
			url: '/book/export/',
			type:"POST",
			data:{
				
				'what' : what
				},
				success: function(data) {
				
				$('#temp').html(data); 
				},
			// в случае ошибки алерим ошибку
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError); 
			},
		});
}


////////////////////////////////////////////////////////////
$(document).ready(function() {

      var button = $('#uploadButton'), interval;

      $.ajax_upload(button, {
            action : '/book/import',
            name : 'file',
            onSubmit : function(file, ext) {
              // показываем картинку загрузки файла
            // $("img#load").attr("src", "123");
              $("#uploadButton font").text('Загружаем');

              /*
               * Выключаем кнопку на время загрузки файла
               */
              this.disable();

            },
            onComplete : function(file, response) {
              // убираем картинку загрузки файла
             // $("img#load").attr("src", "123");
              $("#uploadButton font").text('Импорт');
			  $("#result").html(response);
              // снова включаем кнопку
              this.enable();

              // показываем что файл загружен
             // $("<li>" + file + "</li>").appendTo("#files");

            }
          });
    });