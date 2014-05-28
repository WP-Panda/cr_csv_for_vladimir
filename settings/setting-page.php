<?php 
	/**
		* @package Cr_CSV_IMPORT_FOR_DMITRIY
		* @version 1.0.4
	*/
	add_action('admin_menu', 'register_cr_csv_importer_submenu_page');
	
	function register_cr_csv_importer_submenu_page() {
		add_submenu_page( 'tools.php', 'Cr CSV', 'Cr CSV', 'manage_options', 'cr-csv-importer-submenu-page', 'cr_csv_importer_submenu_page_callback' ); 
	}
	
	if (!empty($_SESSION['string'])) {
		header("Location: ".$_SERVER["HTTP_REFERER"]); 
	}
	
	function cr_csv_importer_submenu_page_callback() {		
		echo '<div class="wrap cr-setting-page">';
	echo '<h2>Cr CSV Настройки<span id="icon-main" class="dashicons dashicons-admin-generic"></span></h2>'; ?>
	
	
	<ul id="info-nav">
		<li><a href="#intro"><?php _e('CSV Импорт','wp_panda') ?></a></li>
		<li><a href="#about"><?php _e('Настройки','wp_panda') ?></a></li>
	</ul>
	<div class="clear" style='clear:both;'></div>
	<div id="info">
		<div class='tab-body' id="intro">
			
			<form id='csv-upload-form' enctype="multipart/form-data" ACTION="<?php echo CR_CSV_IMPORTER_URL .'functions/action.php';?>" METHOD=POST> 
				Выберете файл: <input name="myfile" type="file"> 
				<input type="submit" class='button button-primary' value="Загрузить"> 
			</form>
			
			<?php
				if (!empty($_SESSION['string'])) {
					
					set_time_limit(1800);
					
					$count_new_cat = 0;
					$count_new_post = 0;
					
					$string= $_SESSION['string'];
					unset($_SESSION['string']);	
					foreach ($string as $row ) {
						
						$array = explode(';', $row); // режем строку по разделителю
						$link = mysql_real_escape_string(htmlspecialchars(strip_tags(trim($array[2]) ) ) ); //cсылка
						$size = mysql_real_escape_string(htmlspecialchars(strip_tags(trim($array[1]) ) ) ); //размер
						$info = mysql_real_escape_string(htmlspecialchars(strip_tags(trim($array[0]) ) ) ); //информация
						
						/*----------------------------------------------------------------------------*/
						/* определяем текст записи
						/*----------------------------------------------------------------------------*/
						
							$output ='';
							$output .= '<div class="name1">Название : ' . $info  .'</div>'; // название 
							if(!empty($size) ) $output .=  '<div class="size1">Размер : ' . $size .'</div>'; // вторая ячейка размер
							$output .=  "<div class='link1'>Ссылка : <a href='".$link."' alt=''>".$link."</a></div>"; //третья ячейка  ссылка
						
						/*----------------------------------------------------------------------------*/
						/* определяем категорию записи
						/*----------------------------------------------------------------------------*/
						
						$rashirenie = trim(getExtension2($info )); //получаем расширение (категорию)
						
						$rashirenie_itog = $rashirenie ? $rashirenie : '';  //определяем переменную с расширением пустая или нет
						
						if(!empty ($rashirenie_itog ) )  // если расширение есть
						{
							$cat_slug = str2url($rashirenie_itog); // задаем слаг, при необходимости конвертируем кирилицу в латиницу
							
							$cat_slug = strtolower($cat_slug); //переводим в нижний регистр
							
							$cats = get_category_by_slug($cat_slug); // получаем информацию о  категори по слагу
							
							if(!empty( $cats ) )  // если категория получена
							{ 
								$id_cat = $cats->term_id; // получаем  ее id
							}
							else // если категория отсутствует
							{
								$catarr = array(
								'cat_name' => $rashirenie_itog,
								'category_nicename' => $cat_slug
								);
								
								$id_cat = wp_insert_category($catarr); // добавляем категорию и получаем ее id
								
								$count_new_cat++;
								
								echo '<h3>Добавленна категория - ' . $rashirenie_itog . '</h3>'; // выводим сообщение об этом
							}
						}
						else // если расширение отсутствует
						{
							$id_cat  = get_option('default_id');//задаем категорию из настроек
							$id_cat = $id_cat ? $id_cat  :1;
						}
						
						/*----------------------------------------------------------------------------*/
						/*  Готовим строку для определения названия и тегов
						/*----------------------------------------------------------------------------*/
						
						if (!empty( $rashirenie ) ) // определяем строку без расширения
						{
							$stroka = substr($info , 0, strrpos($info , '.'));  // если расширение есть отбравыаем его
							} else { 
							$stroka = $info; // если нет ничего не делаем
						}
						
						$name_zagatovka = trim( mb_strtolower( preg_replace("/[^a-zA-ZабвгдеёжзийклмнопрстуфхцчшщьыъэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЬЫЪЭЮЯ0-9]/", ' ', $stroka) ) ); //МЕНЯЕМ ВСЕ  КРОМЕ ЦИФР И БУКВ НА ПРОБЕЛЫ переводим в нижний гегистр режем пробел первый и последний
						
						/*----------------------------------------------------------------------------*/
						/* определяем название записи
						/*----------------------------------------------------------------------------*/
						
						$name = preg_replace('| +|', ' ', $name_zagatovka); // заменяем множественные пробелы на один
						
						/*----------------------------------------------------------------------------*/
						/* определяем теги
						/*----------------------------------------------------------------------------*/
						
						$tags = preg_replace('/\b(\w{1,2}|\d+)\b/ui','',$name_zagatovka);//удаляем слова только из цифр и слова короче 2-х букв
						$tags = preg_replace('| +|', ' ', trim($tags)); // удаляем первый и последний пробел, заменяем множественные пробелы на один
						
						if(!empty($tags)) 
						{ //  ели теги имеются
							$tag = str_replace(' ', ',', $tags); // заменяем пробелы на запятые
						}
						else // в противном случае 
						{
							$tag='';
						}
						
						/*----------------------------------------------------------------------------*/
						/* добавляем запись
						/*----------------------------------------------------------------------------*/
						if( !empty( $link ) && !empty( $name ) ) 
						{
							$post = array(
								'comment_status' => 'closed', // 'closed' означает, что комментарии закрыты.
								'ping_status' => 'closed', // 'closed' означает, что пинги и уведомления выключены.
								'post_category' => array($id_cat),// Категория 
								'post_content' => $output, // Полный текст записи.
								'post_status' => 'publish', // Статус
								'post_title' => $name, // Заголовок
								'post_type' => 'post', // Тип записи.
								'tags_input' =>$tag // Метки поста.
							);
							wp_insert_post($post);
							
							echo 'Добавленна запись - ' . $name . '  В рубрику - ' . $rashirenie_itog ;
							echo '<br>';
							$count_new_post++;
						}
					}
					echo '<h3>Добавленно новых категорий - ' . $count_new_cat . '</h3>';
					echo '<h3>Добавленно новых записей - ' . $count_new_post . '</h3>';
					
				}
				
			?>
			
		</div>
		<div class='tab-body' id="about">
			<?php cr_exemple_settings_page() ?>
		</div>
	</div>
	<?php echo '</div>';	?>
<? }				