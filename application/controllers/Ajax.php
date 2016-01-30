<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->library('cab_auth');  // подключаем библиотеку авторизации
		$this->load->library('funcs');        // подключаем библиотеку доп функций
		//проверка авторизации
		if(!$this->cab_auth->check()){
			$bu= base_url();  // определяем базовый путь
			setcookie('STAT', 'Пожалуйста, войдите', time()+86400); // записываем в куку сообщение
			header('Location: '.$bu.'auth/login') ; // переадресовываем на страницу с формой входа
		}
	}
	/**
	* ФУНКИЯ ЭКСПОРТА списка звонков В ЭКСЕЛЬ
	*  принимаем querystring и на основе нее получаем список звонков, 
		разбираем фильтр,оформаляем в экселе, потом шапку звонков и список
	*/
		
	function export_xlsx(){
			# 
			ini_set('memory_limit', '1024M');
			$CI =& get_instance();
		    $CI->load->library('excel');//загружаем модуль phpexcel
			$CI->load->library('iofactory');//загружаем модуль phpexcel
			$xls = new PHPExcel(); //инициализация phpexcel
			$xls->setActiveSheetIndex(0); //выбираем активный лист
			$sheet = $xls->getActiveSheet();//выбираем активный лист
			$title=$this->config->item('klient_name'); //получаем из конфига  название клиента
			$sheet->setTitle($title);  //устанавливаем титл
			$sheet->setCellValue("A1", $title);//устанавливаем заголовок
			$sheet->getStyle('A1')->getFill()->setFillType( PHPExcel_Style_Fill::FILL_SOLID); //заливка
			$sheet->getStyle('A1')->getFill()->getStartColor()->setRGB('EEEEEE'); //фон
			$sheet->getStyle('A1')->getFont()->setName('Arial'); //шрифт
			$sheet->getStyle('A1')->getFont()->setSize('14'); // размер
			$sheet->mergeCells('A1:F1'); // объединенние ячеек
			$sheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); // выравнивание по центру
			//получаем айпишнек ватс из конфига
			$vats = $this->config->item('vats'); 
			// отправляем запрос на ватс и получаем список звонков соответственно фильтру в формате json
			$f=file_get_contents('https://'.$vats.'/cabinet/cdr.php?'.$_SERVER['QUERY_STRING']); 
			$rawdata=json_decode($f,true);
			$out_total= $rawdata['total'];  // общее количество записей
			//$sheet->setCellValue("B2", $_SERVER['QUERY_STRING']);
			$sheet->setCellValue('A2',"всего записей: ".$out_total); // заполняем ячейку
			$sheet->setCellValue('A3',"Фильтр: ");
			$sheet->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A3')->getFill()->getStartColor()->setRGB('EEEEEE');
			$sheet->getStyle('A3')->getFill()->setFillType( PHPExcel_Style_Fill::FILL_SOLID); //заливка
			$sheet->mergeCells('A3:B3');
			$rowno=4; // строка по умолчанию для фильтра
			// разбираем квери стринг
			$params=explode('&',$_SERVER['QUERY_STRING']);  
			// перебираем параметры, переименовываем в понятное название
			foreach($params as $one){
				$par=explode('=',$one);
					if($par[0]=='export_xlsx') continue;
					if($par[1]){
						$name=$this->funcs->get_fltr_name($par[0]); 
						if($par[0]=='durtype'){
							switch($par[1]){
								case 'mo':
								$val ="Больше";
								break;
								case 'le':
								$val ="Меньше";
								break;
								case 'is':
								$val ="Равно";
								break; 
							}	
						}elseif($par[0]=='anstype'){
							switch($par[1]){
								case 'ans':
								$val ="Отвеченные";
								break;
								case 'noans':
								$val ="Не отвеченные";
								break;
								case 'busy':
								$val ="Занятые";
								break;
								case 'fail':
								$val ="Неудачные";
								break;
								case 'unk':
								$val ="Другие";
								break;
							}	
						}else{
							$val=$par[1];
						}
							// записываем название
							$sheet->setCellValue("A$rowno",$name.':');
							$sheet->getStyle("A$rowno")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT); 
							// записываем значение
							$sheet->setCellValue("B$rowno",$val);
							$sheet->getStyle("B$rowno")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
							$rowno++; // перебираем параметры фильтра
					}
			
			}
			$rowno++; // пропускаем строку
			$sheet->setCellValue("A$rowno", 'Дата '); 
			$sheet->getColumnDimension('A')->setAutoSize(true) ;
			 // выравнивание по центру
			$sheet->getStyle("A$rowno")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
			$sheet->getStyle("B$rowno")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
			$sheet->getStyle("C$rowno")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
			$sheet->getStyle("D$rowno")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle("E$rowno")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 			
			$sheet->getStyle("F$rowno")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
			$sheet->getStyle("G$rowno")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
			//фон
			$sheet->getStyle("A$rowno")->getFill()->getStartColor()->setRGB('EEEEEE');
			$sheet->getStyle("B$rowno")->getFill()->getStartColor()->setRGB('EEEEEE');
			$sheet->getStyle("C$rowno")->getFill()->getStartColor()->setRGB('EEEEEE');
			$sheet->getStyle("D$rowno")->getFill()->getStartColor()->setRGB('EEEEEE');
			$sheet->getStyle("E$rowno")->getFill()->getStartColor()->setRGB('EEEEEE');
			$sheet->getStyle("F$rowno")->getFill()->getStartColor()->setRGB('EEEEEE');
			$sheet->getStyle("G$rowno")->getFill()->getStartColor()->setRGB('EEEEEE');
			//заливка
			$sheet->getStyle("A$rowno")->getFill()->setFillType( PHPExcel_Style_Fill::FILL_SOLID);  
			$sheet->getStyle("B$rowno")->getFill()->setFillType( PHPExcel_Style_Fill::FILL_SOLID); 
			$sheet->getStyle("C$rowno")->getFill()->setFillType( PHPExcel_Style_Fill::FILL_SOLID); 
			$sheet->getStyle("D$rowno")->getFill()->setFillType( PHPExcel_Style_Fill::FILL_SOLID); 
			$sheet->getStyle("E$rowno")->getFill()->setFillType( PHPExcel_Style_Fill::FILL_SOLID); 
			$sheet->getStyle("F$rowno")->getFill()->setFillType( PHPExcel_Style_Fill::FILL_SOLID); 
			$sheet->getStyle("G$rowno")->getFill()->setFillType( PHPExcel_Style_Fill::FILL_SOLID); 
			
			$sheet->setCellValue("B$rowno", 'Направление');
			$sheet->getColumnDimension('B')->setAutoSize(true) ;
			$sheet->setCellValue("C$rowno", 'Источник');
			$sheet->getColumnDimension('C')->setAutoSize(true) ;
			$sheet->setCellValue("D$rowno", 'Назначение');
			$sheet->getColumnDimension('D')->setAutoSize(true) ;
			$sheet->setCellValue("E$rowno", 'Статус');
			$sheet->getColumnDimension('E')->setAutoSize(true) ;
			$sheet->setCellValue("F$rowno", 'Длительность');
			$sheet->getColumnDimension('F')->setAutoSize(true) ;
			$sheet->setCellValue("G$rowno", 'Линия');
			$sheet->getColumnDimension('G')->setAutoSize(true) ;
			$rowno++;
			//перебираем по аналогии с конроллером history
			foreach($rawdata as $k=>$s){
				if($s['total']){
					continue;
				};
				$num=explode('-',$s['channel']); 
				$num=explode('/',$num[0]);
			 	$num=$num[1];
			
				$dnum=explode('-',$s['dstchannel']);
				$dnum=explode('/',$dnum[0]);
			 	$dnum=$dnum[1];
			
				if(is_numeric($num)){
					$out_direction='Исходящий';
						$out_dst=$s['dst'];
				}else{
					$out_direction='Входящий'; 
						if(is_numeric($dnum)){ 
							$out_dst=$dnum;
						} else{
							$out_dst=$s['dst'];
						}
				}
				$out_calldate=$s['calldate'];
				$out_src=$s['src'];
				
				if($s['disposition']=='NO ANSWER'){ 
						$out_status='не отвечен';
				}elseif($s['disposition']=='ANSWERED'){ 
						$out_status='отвечен';
				}elseif($s['disposition']=='FAILED'){
						$out_status='ошибка';
				}elseif($s['disposition']=='BUSY'){
						$out_status='занят';
				}
				elseif($s['disposition']=='UNKNOWN'){
						$out_status='неизвестно';
				}
				$account=$s['account'];
				$out_dur=$this->funcs->dur2min($s['billsec']);
				// записываем в ячейки
				$sheet->setCellValue("A$rowno",$out_calldate);
				$sheet->getStyle("A$rowno")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
				$sheet->setCellValue("B$rowno",$out_direction);
				$sheet->getStyle("B$rowno")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
				$sheet->setCellValue("C$rowno",$out_src);
				$sheet->getStyle("C$rowno")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
				$sheet->setCellValue("D$rowno",$out_dst);
				$sheet->getStyle("D$rowno")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
				$sheet->setCellValue("E$rowno",$out_status);
				$sheet->getStyle("E$rowno")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
				$sheet->setCellValue("F$rowno",$out_dur );
				$sheet->getStyle("F$rowno")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
				$sheet->setCellValue("G$rowno",$account );
				$sheet->getStyle("G$rowno")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
				$rowno++;
	
			}
			// сохраняем в файл
			$objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel2007');
			$fn='export_'.date('H_i_s'); // назание файла
            $fname="files/$fn.xlsx";//задаем имя нового файла
		    $objWriter -> save($fname);//сохраняем файл	
			// получаем базовый путь (доменное имя)
			$path=$this->config->item('base_url');
			// выводим ссылку на файл
			echo '<a href="'.$path.'/'.$fname.'">Сохранить файл '.$fn.'.xlsx </a>';
		
	}
	/**
	* ФУНКИЯ ЭКСПОРТА списка звонков В CSV разделеные запятыми столбцы
	*  тоже самое что эксель, без вывода  фильтра, только звонки
	*/
	function export_csv(){
			ini_set('memory_limit', '1024M');
			$vats = $this->config->item('vats');
			// отправляем запрос на ватс и получаем список звонков соответственно фильтру в формате json
			$f=file_get_contents('https://'.$vats.'/cabinet/cdr.php?'.$_SERVER['QUERY_STRING']);
			$rawdata=json_decode($f,true);
			// перебираем строки и пишем их в файл
			foreach($rawdata as $k=>$s){
				if($s['total']){
					continue;
				};
				$num=explode('-',$s['channel']); 
				$num=explode('/',$num[0]);
			 	$num=$num[1];
			
				$dnum=explode('-',$s['dstchannel']);
				$dnum=explode('/',$dnum[0]);
			 	$dnum=$dnum[1];
			
				if(is_numeric($num)){
					$out_direction='Исходящий';
						$out_dst=$s['dst'];
				}else{
					$out_direction='Входящий'; 
						if(is_numeric($dnum)){ 
							$out_dst=$dnum;
						} else{
							$out_dst=$s['dst'];
						}
				}
				$out_calldate=$s['calldate']; 
				$out_src=$s['src'];
				
				if($s['disposition']=='NO ANSWER'){ 
						$out_status='не отвечен';
				}elseif($s['disposition']=='ANSWERED'){ 
						$out_status='отвечен';
				}elseif($s['disposition']=='FAILED'){
						$out_status='ошибка';
				}elseif($s['disposition']=='BUSY'){
						$out_status='занят';
				}
				elseif($s['disposition']=='UNKNOWN'){
						$out_status='неизвестно';
				}
				
				$out_dur=$this->funcs->dur2min($s['billsec']);
				// готовим стороку для записи в файл
				$to_file.=$out_calldate.','.$out_direction.','.$out_src.','.$out_dst.','.$out_status.','.$out_dur."\r\n";
				
				
	
			}
			$fn='export_'.date('H_i_s');
            $fname="files/$fn.csv";//задаем имя нового файла
			$h=fopen($fname,'c'); // создаем файл
			// записываем в файл
			fwrite($h,$to_file);
			fclose($h);
			$path=$this->config->item('base_url');  
			// выводим ссылку на файл
			echo '<a href="'.$path.'/'.$fname.'">Сохранить файл '.$fn.'.csv </a>';
		}	
		function savearc(){
		echo	$vats = $this->config->item('vats');
			echo system('zwget https://91.196.5.133/cabinet/files/1451392460.zip --no-check-certificate');
			
		}
		function downloadivr(){
		//	print_r($_GET);
			if($_GET['fn']){
				$fn=$_GET['fn'];
				$vats = $this->config->item('vats');
				if($_GET['cdr'] and $_GET['date']){
					$getfile = file_get_contents("http://".$vats."/cabinet/save.php?fn=".$fn.'&cdr=true&date='.$_GET['date']);
				}else{
					$getfile = file_get_contents("http://".$vats."/cabinet/save.php?fn=".$fn);
				}
				if($getfile){
					$content = file_get_contents("http://".$vats."/cabinet/files/".$getfile);
					if(file_put_contents('files/'.$fn, $content)){
						$path=$this->config->item('base_url');
						echo '<a href="'.$path.'/files/'.$getfile.'" download="'.$getfile.'">Сохранить файл '.$getfile.'</a>';
					}
				}
			}
		}
		/*
		 генерим звонок
		*/
		function click2call($a,$b){
			$vats = $this->config->item('vats');
			if($a and $b){
				$getfile = file_get_contents("http://".$vats."/cabinet/call.php?a=".$a.'&b='.$b);
				echo $getfile;
			}
			//return ($getfile: true ? false);
			echo $getfile;
		}

//end of class		
}		