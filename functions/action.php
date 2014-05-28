<?php
	/**
		* @package Cr_CSV_IMPORT_FOR_DMITRIY
		* @version 1.0.4
	*/
	
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );
	
	
	if(isset($_FILES["myfile"])) 
	{ 
		$myfile = $_FILES["myfile"]["tmp_name"]; // имя файла во временном каталоге
		$myfile_name = $_FILES["myfile"]["name"]; //имя файла до его отправки на сервер
		$myfile_size = $_FILES["myfile"]["size"]; //размер принятого файла в байтах
		$myfile_type = $_FILES["myfile"]["type"]; //MIME-тип принятого файла
		$error_flag = $_FILES["myfile"]["error"]; //Код ошибки, которая может возникнуть при загрузке файла
		
		// Если ошибок не было 
		if($error_flag == 0) 
		{ 
			$csvIterator = new CsvIterator($myfile); // открываем файл
			$strins = array();
			$n =1;
			foreach ($csvIterator as $row => $data) { // режем файл на строки
				if ( !empty($data) ) 
				{
					foreach ($data as $key){ // получаем строку
						//echo $key;
						if(!empty($key))
						{
						$string[]= $key;
						}
						$n++;
					}
					
					$_SESSION['string'] = $string;
					header("Location: ".$_SERVER["HTTP_REFERER"]);
					
				} 
			}
		}	
	}	