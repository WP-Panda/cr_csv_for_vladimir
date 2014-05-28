<?php 
	/**
		* @package Cr_CSV_IMPORT_FOR_DMITRIY
		* @version 1.0.4
	*/
	/*----------------------------------------------------------------------------*/
	/* �������� ������
	/*----------------------------------------------------------------------------*/
	// Silence is golden.
	function register_session(){
		if( !session_id() )
        session_start();
	}
	add_action('init','register_session');
	/*----------------------------------------------------------------------------*/
	/* �������� �����������
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
			
			'�' => 'a',   '�' => 'b',   '�' => 'v',
			
			'�' => 'g',   '�' => 'd',   '�' => 'e',
			
			'�' => 'e',   '�' => 'zh',  '�' => 'z',
			
			'�' => 'i',   '�' => 'y',   '�' => 'k',
			
			'�' => 'l',   '�' => 'm',   '�' => 'n',
			
			'�' => 'o',   '�' => 'p',   '�' => 'r',
			
			'�' => 's',   '�' => 't',   '�' => 'u',
			
			'�' => 'f',   '�' => 'h',   '�' => 'c',
			
			'�' => 'ch',  '�' => 'sh',  '�' => 'sch',
			
			'�' => '',  '�' => 'y',   '�' => '',
			
			'�' => 'e',   '�' => 'yu',  '�' => 'ya',
			
			' ' =>'', '-' =>'',
			
			
			
			'�' => 'A',   '�' => 'B',   '�' => 'V',
			
			'�' => 'G',   '�' => 'D',   '�' => 'E',
			
			'�' => 'E',   '�' => 'Zh',  '�' => 'Z',
			
			'�' => 'I',   '�' => 'Y',   '�' => 'K',
			
			'�' => 'L',   '�' => 'M',   '�' => 'N',
			
			'�' => 'O',   '�' => 'P',   '�' => 'R',
			
			'�' => 'S',   '�' => 'T',   '�' => 'U',
			
			'�' => 'F',   '�' => 'H',   '�' => 'C',
			
			'�' => 'Ch',  '�' => 'Sh',  '�' => 'Sch',
			
			'�' => '',  '�' => 'Y',   '�' => '',
			
			'�' => 'E',   '�' => 'Yu',  '�' => 'Ya',
			
			);
			
			return strtr($string, $converter);
			
		}
		
	}
	
	if (!function_exists('str2url')) 
	{
		function str2url($str) {
			
			// ��������� � ��������
			
			$str = rus2translit($str);
			
			// � ������ �������
			
			$str = strtolower($str);
			
			// ������� ��� �������� ��� �� "-"
			
			$str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
			
			// ������� ��������� � �������� '-'
			
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