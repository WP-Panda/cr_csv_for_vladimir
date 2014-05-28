<?php 
	/**
		* @package Cr_CSV_IMPORT_FOR_DMITRIY
		* @version 1.0.4
	*/
	/*----------------------------------------------------------------------------*/
	/* стартуем сессию
	/*----------------------------------------------------------------------------*/
	// Silence is golden.
	function register_session(){
		if( !session_id() )
        session_start();
	}
	add_action('init','register_session');
	/*----------------------------------------------------------------------------*/
	/* получаем рассширение
	/*----------------------------------------------------------------------------*/
	
	function getExtension2($filename) 
	{
		$path_info = pathinfo($filename);
		return $path_info['extension'];
	}	
	
	/*----------------------------------------------------------------------------*/
	/*
	/*----------------------------------------------------------------------------*/
	
	/*----------------------------------------------------------------------------*/
	/* translit
	/*----------------------------------------------------------------------------*/
	if (!function_exists('rus2translit')) 
	{
		
		function rus2translit($string) {
			
			$converter = array(
			
			'а' => 'a',   'б' => 'b',   'в' => 'v',
			
			'г' => 'g',   'д' => 'd',   'е' => 'e',
			
			'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
			
			'и' => 'i',   'й' => 'y',   'к' => 'k',
			
			'л' => 'l',   'м' => 'm',   'н' => 'n',
			
			'о' => 'o',   'п' => 'p',   'р' => 'r',
			
			'с' => 's',   'т' => 't',   'у' => 'u',
			
			'ф' => 'f',   'х' => 'h',   'ц' => 'c',
			
			'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
			
			'ь' => '',  'ы' => 'y',   'ъ' => '',
			
			'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
			
			' ' =>'', '-' =>'',
			
			
			
			'А' => 'A',   'Б' => 'B',   'В' => 'V',
			
			'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
			
			'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
			
			'И' => 'I',   'Й' => 'Y',   'К' => 'K',
			
			'Л' => 'L',   'М' => 'M',   'Н' => 'N',
			
			'О' => 'O',   'П' => 'P',   'Р' => 'R',
			
			'С' => 'S',   'Т' => 'T',   'У' => 'U',
			
			'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
			
			'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
			
			'Ь' => '',  'Ы' => 'Y',   'Ъ' => '',
			
			'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
			
			);
			
			return strtr($string, $converter);
			
		}
		
	}
	
	if (!function_exists('str2url')) 
	{
		function str2url($str) {
			
			// переводим в транслит
			
			$str = rus2translit($str);
			
			// в нижний регистр
			
			$str = strtolower($str);
			
			// заменям все ненужное нам на "-"
			
			$str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
			
			// удаляем начальные и конечные '-'
			
			$str = trim($str, "-");
			
			return $str;
			
		}
	}
	
	/*----------------------------------------------------------------------------*/
	/*
	/*----------------------------------------------------------------------------*/
	
	// The JavaScript
	function my_action_javascript() {
		//Set Your Nonce
		$ajax_nonce = wp_create_nonce( "my-special-string" );
	?>
	<script>
		jQuery(document).ready(function($) {
			/*	function hhhpo()
				{
				$('input.myfile').on('change', function (event, files, label) {
				file_name = this.value;
				});
			}*/
			$('.cr-csv-upload').click(function()
			{
				var data = {
					action: 'my_action',
					security: '<?php echo $ajax_nonce; ?>',
				};
				
				alert($('form#fffff').serialize());
				
				// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
				$.post(ajaxurl, data, function(response) {
					alert('Got this from the server: ' + response);
				});
			});
		});
	</script>
	
	<?php
	}
	add_action( 'admin_footer', 'my_action_javascript' );
	
	// The function that handles the AJAX request
	function my_action_callback() {
		global $wpdb; // this is how you get access to the database
		
		check_ajax_referer( 'my-special-string', 'security' );
		$whatever = intval( $_POST['whatever'] );
		$whatever += 10;
		echo $whatever;
		
		die(); // this is required to return a proper result
	}
add_action( 'wp_ajax_my_action', 'my_action_callback' );