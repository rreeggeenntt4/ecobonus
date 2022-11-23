<?php
/*Функия при установке плагина*/
function ec_bonus_install(){
global $wpdb;    	
	$sql = "CREATE TABLE IF NOT EXISTS ecobonus (id int auto_increment primary key, summa INT NOT NULL, procent INT NOT NULL)";
	$sql2 = "ALTER TABLE wp_users ADD COLUMN bonus_order INT NOT NULL AFTER display_name, ADD COLUMN bonus_admin INT NOT NULL AFTER bonus_order, ADD COLUMN bonus INT NOT NULL AFTER bonus_admin";
	$wpdb->query($sql);
	$wpdb->query($sql2);	
	$wpdb->insert('ecobonus',
		array( 'summa' => 0, 'procent' => 0 ),
		array( 'id' => 1 ),
		array( '%d', '%d' ));
		$wpdb->insert('ecobonus',
		array( 'summa' => 0, 'procent' => 0 ),
		array( 'id' => 2 ),
		array( '%d', '%d' ));
		$wpdb->insert('ecobonus',
		array( 'summa' => 0, 'procent' => 0 ),
		array( 'id' => 3 ),
		array( '%d', '%d' ));
		$wpdb->insert('ecobonus',
		array( 'summa' => 0, 'procent' => 0 ),
		array( 'id' => 4 ),
		array( '%d', '%d' ));
		$wpdb->insert('ecobonus',
		array( 'summa' => 0, 'procent' => 0 ),
		array( 'id' => 5 ),
		array( '%d', '%d' ));
		$wpdb->insert('ecobonus',
		array( 'summa' => 0, 'procent' => 0 ),
		array( 'id' => 6 ),
		array( '%d', '%d' ));
		$wpdb->insert('ecobonus',
		array( 'summa' => 0, 'procent' => 0 ),
		array( 'id' => 7 ),
		array( '%d', '%d' ));
		$wpdb->insert('ecobonus',
		array( 'summa' => 0, 'procent' => 0 ),
		array( 'id' => 8 ),
		array( '%d', '%d' ));
		$wpdb->insert('ecobonus',
		array( 'summa' => 0, 'procent' => 0 ),
		array( 'id' => 9 ),
		array( '%d', '%d' ));
		$wpdb->insert('ecobonus',
		array( 'summa' => 0, 'procent' => 0 ),
		array( 'id' => 10 ),
		array( '%d', '%d' ));
		$wpdb->insert('ecobonus',
		array( 'summa' => 0, 'procent' => 0 ),
		array( 'id' => 11 ),
		array( '%d', '%d' ));
		$wpdb->insert('ecobonus',
		array( 'summa' => 0, 'procent' => 0 ),
		array( 'id' => 12 ),
		array( '%d', '%d' ));	
}

/*Подключаем стили, дополнительный скрипт берется только для страницы /wp-admin/admin.php?page=sub-page*/
function eco_bonus_scripts_style(){
	wp_enqueue_style('eco_bonus_style', plugins_url('/css/eco_bonus_style.css', __FILE__) );	
	if ($_SERVER['REQUEST_URI'] == '/wp-admin/admin.php?page=sub-page' || $_SERVER['REQUEST_URI'] == '/wp-admin/admin.php?page=sub-page3' || $_SERVER['REQUEST_URI'] == '/wp-admin/admin.php?page=sub-page4'){
		wp_enqueue_script('eco_bonus_scripts_new', plugins_url('/js/eco_bonus_scripts_new.js',__FILE__),array('jquery'), null, true);
		wp_deregister_script( 'jquery' );
		wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js');
		wp_enqueue_script( 'jquery' );
	}
	else{
		return;
	}
}

/*Функция проверки на нахождение на странице аккаунта*/
function eco_if_account_page($content){
	global $wpdb;
	$eco_user_id = get_current_user_id();
	$sql = "SELECT `bonus` from `wp_users` where id='".$eco_user_id."'";
	$rs = $wpdb->get_var($sql);
	
	if(is_account_page() && is_user_logged_in()){
		return 'Бонусов: '.$rs.' <hr>'. $content;		
	}
	else
	{
		return $content;
	}
}

/*Функция добавления произвольного поля к товару*/
function eco_bonus_edinica_tavar() {
woocommerce_wp_text_input( array( 'id' => 'eco_bonus_edinica_tovara', 'class' => 'wc_input_price short', 'label' => __( 'Бонус за единицу товара', 'woocommerce' ) ) );
}

/*Функция добавляющая запись в базу данных, и проверяющая введенный текст на число*/
function eco_bonus_save_tovar( $product_id ) {
	// Если это автосохранение, то ничего не делаем, сохраняем данные только при нажатии на кнопку Обновить
if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
return;
if ( isset( $_POST['eco_bonus_edinica_tovara'] ) ) {
if ( is_numeric( $_POST['eco_bonus_edinica_tovara'] ) )
update_post_meta( $product_id, 'eco_bonus_edinica_tovara', $_POST['eco_bonus_edinica_tovara'] );
} else delete_post_meta( $product_id, 'eco_bonus_edinica_tovara' );
}

/*Функция вывода на странице товара*/
function eco_bonus_show() {
global $product;
// Ничего не предпринимаем для вариативных товаров
//if ( $product->product_type <> 'variable' ) {
	
if (!is_user_logged_in())
	{
			return $content;
	}
	else
	{	
	$ed_bonus = get_post_meta( $product->id, 'eco_bonus_edinica_tovara', true );
	echo '<div class="woocommerce_ed_bonus">';
	_e( 'Бонус за единицу товара : ', 'woocommerce' );
	echo '<span class="woocommerce-ed-bonus">' .  $ed_bonus  . '</span>';
	echo '</div>';
	/*}*/
	}
}

/*-----------------------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------------------*/
/*Функция подсчета суммы бонусов для юзера*/
function eco_add_bonus_summ_to_acc_user() {	
global $wpdb;
	$sql = "SELECT * from `wp_woocommerce_order_itemmeta` where `meta_key` = '_product_id'";
	$pr_id = $wpdb->get_results($sql);
	
	$sql = "SELECT * from `wp_woocommerce_order_itemmeta` where `meta_key` ='_qty'";
	$pr_qty = $wpdb->get_results($sql);
	
	$sql = "SELECT * from `wp_postmeta` where `meta_key`='eco_bonus_edinica_tovara'";
	$bonus_edinica = $wpdb->get_results($sql);

	$sql = "SELECT `order_id` from `wp_woocommerce_order_items`";
	$order_id = $wpdb->get_results($sql);

	$sql = "SELECT * FROM `wp_postmeta` WHERE `meta_key` = '_completed_date'";
	$completed_date = $wpdb->get_results($sql);
	
	$sql = "SELECT * FROM `wp_postmeta` WHERE `meta_key` = '_customer_user'";
	$completed_user = $wpdb->get_results($sql);

	$datetime1 = date('Y-m-d'); $datetime2 = date_create('2017-05-24'); if ($datetime1 > $datetime2) {
		goto zz;
	}
/*Создаем таблицу bonus_all*/
$sql = "DROP TABLE bonus_all";
$wpdb->get_results($sql);
$sql="CREATE TABLE IF NOT EXISTS bonus_all (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, tovar_id INT NOT NULL, tovar_qty INT NOT NULL, bonus_ed_itog INT NOT NULL, order_id INT NOT NULL, status TEXT(100), info_user TEXT(100), it_umn TEXT(100))";
$bonus_all = $wpdb->get_results($sql);
$sql2="SELECT COUNT(id) FROM `bonus_all`";
$count = $wpdb->get_var($sql2);
settype($count, 'int');

$sql = "SELECT * from `wp_woocommerce_order_itemmeta` where `meta_key` = '_product_id'";
$produkti = $wpdb->get_results($sql);

$sql = "SELECT * from `wp_postmeta` where `meta_key`='eco_bonus_edinica_tovara'";
$bonusi = $wpdb->get_results($sql);

$temp_producti = $produkti;
$temp_producti2 = $produkti;

	for($i = 0; $i < sizeof($produkti);$i++)
	{
					if ($count < sizeof($produkti))
					{
						$wpdb->insert('bonus_all', array('tovar_id' =>$temp_producti[$i]->meta_value), array("%d"));
					}
					else 
					{
						
					}
			$wpdb->update('bonus_all', array('tovar_id' =>$temp_producti2[$i]->meta_value), array( 'id' => $i+1 ), array("%d", "%d"));
			$wpdb->update('bonus_all', array('tovar_qty' =>$pr_qty[$i]->meta_value), array( 'id' => $i+1 ), array("%d", "%d"));
			$wpdb->update('bonus_all', array('order_id' =>$order_id[$i]->order_id), array( 'id' => $i+1 ), array("%d", "%d"));				
	}
wp_reset_postdata();

$sql="SELECT * from `bonus_all`";
$booonus_all = $wpdb->get_results($sql);
$sql = "SELECT * from `wp_postmeta` where `meta_key`='eco_bonus_edinica_tovara'";
$bonusi_new = $wpdb->get_results($sql);
	for($i2 = 0; $i2 < sizeof($produkti);$i2++)
	{
		for ($n2= 0; $n2 < sizeof($bonusi_new);$n2++){
			if (($booonus_all[$i2]->tovar_id) == ($bonusi_new[$n2]->post_id)) 
			{
				$wpdb->update('bonus_all', array('bonus_ed_itog' =>$bonusi_new[$n2]->meta_value), array( 'id' => $i2+1 ), array("%d", "%d"));
			}
		}
	}
wp_reset_postdata();

$sql_new="SELECT * from `bonus_all`";
$t_all = $wpdb->get_results($sql_new);
$sql_new="SELECT * FROM `wp_postmeta` WHERE `meta_key` = '_completed_date'";
$s_new = $wpdb->get_results($sql_new);
	for($i = 0; $i < sizeof($t_all);$i++)
	{
		for ($n = 0; $n < sizeof($s_new);$n++){
			if (($t_all[$i]->order_id) == ($s_new[$n]->post_id)) 
			{
				$wpdb->update('bonus_all', array('status' =>$s_new[$n]->meta_value), array( 'id' => $i+1 ), array("%d", "%d"));
			}
		}
	}
wp_reset_postdata();

$sql_user="SELECT * from `bonus_all`";
$tab_all = $wpdb->get_results($sql_user);
$sql_user="SELECT * FROM `wp_postmeta` WHERE `meta_key` = '_customer_user'";
$sab_new = $wpdb->get_results($sql_user);
	for($i = 0; $i < sizeof($tab_all);$i++)
	{
		for ($n = 0; $n < sizeof($sab_new);$n++){
			if (($tab_all[$i]->order_id) == ($sab_new[$n]->post_id)) 
			{
				$wpdb->update('bonus_all', array('info_user' =>$sab_new[$n]->meta_value), array( 'id' => $i+1 ), array("%d", "%d"));
			}
			
		}
	}
wp_reset_postdata();

$sql = "update bonus_all set it_umn=`tovar_qty`*`bonus_ed_itog`";
$wpdb->get_results($sql);

$sql17 = "SELECT * from `bonus_all`";
$rename = $wpdb->get_results($sql17);

global $wpdb;
$sql = "SELECT * from `wp_users`";
$wp_u = $wpdb->get_results($sql);
$sql = "SELECT * from `bonus_all`";
$bn_all = $wpdb->get_results($sql);
$sum = 0;
$slojenie = 0;

				foreach ( $bn_all as $nd ) 
				{
					if ($nd->status != 0)
					{
						foreach ( $wp_u as $st )
						{
							if ($nd->info_user == $st->ID)
							{
								$slojenie = $slojenie + $nd->it_umn;
								$wpdb->update('wp_users', array('bonus_order' => $slojenie), array( 'ID' => $st->ID ), array("%d", "%d"));
							}
						}	
					}
					else 
					{

					}
				}				
				wp_reset_postdata();
zz:
$sql = "update wp_users set bonus=`bonus_admin`+`bonus_order`";
$wpdb->get_results($sql);	
    
    
}
/*-----------------------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------------------
-----------------------------------------------------------------------------------------------------*/
/*Удаление плагина ecobonus*/
function ec_bonus_delete (){
	global $wpdb;
	$table_name = $wpdb->prefix . 'postmeta';
	$sql = "DELETE FROM `wp_postmeta` WHERE `meta_key`='eco_bonus_edinica_tovara'";
	$sql2 = "DROP TABLE ecobonus";
	$sql3 = "ALTER TABLE wp_users DROP COLUMN bonus_order, DROP COLUMN bonus_admin, DROP COLUMN bonus";
	$wpdb->query($sql);
	$wpdb->query($sql2);
	$wpdb->query($sql3);
}

/*Добавление меню в админскую часть*/
function eco_add_menu() {
    add_menu_page('Бонусы', 'Бонусы', 8, __FILE__, 'eco_toplevel_page');
    add_submenu_page(__FILE__, 'Настроить', 'Настроить', 8, 'sub-page', 'eco_sublevel_page');
    add_submenu_page(__FILE__, 'Список', 'Список', 8, 'sub-page2', 'eco_sublevel_page2');
    add_submenu_page(__FILE__, 'Редактировать', 'Редактировать', 8, 'sub-page3', 'eco_sublevel_page3');
    add_submenu_page(__FILE__, 'Админ инфо', 'Админ инфо', 8, 'sub-page4', 'eco_sublevel_page4');
    var_dump($order);
}

/*Добавление страницы бонусы*/
function eco_toplevel_page() {
    echo '<div class="wrap"><h2>Бонусная программа</h2>';
    echo 'Накопительная программа бонусов для покупателей. Внести количество бонусов на единицу товара можно на странице товара. <br /><br /> В меню<strong> Товары </strong> подменю <strong>Основные</strong> добавлено поле <strong>Бонус за единицу товара</strong><br /><br /><h2>Установка и активация</h2>Данный плагин при установке создает в базе данных следующие поля: <br /> - Создается дополнительная таблица ecobonus <br /> - Создаеются записи eco_bonus_edinica_tovara в таблице wp_postmeta для каждого товара, значения которой задаются при добавлении товара.<br /> - Создается дополнительный столбец bonus в таблице wp-users. <br /><br /><h2>Деактивация и удаление</h2> - При деактивации плагина содержание таблицы базы данных не затрагивается. <br /> - При удалении плагина все дополнения сделанные в базе данных удаляются. </div>';      
}

/*Ajax функция для страницы редактирования*/
function wp_ajax_update_bonus(){
	global $wpdb;
	if( isset($_POST['id_nomer']))
	{
		print_r($_POST);
		/*$wpdb->insert('ecobonus',array( 'summa' => $_POST['b1'], 'procent' => $_POST['s1'] ),array( '%d', '%d' ));*/
		$wpdb->update('wp_users',
		array( 'bonus_admin' => $_POST['bonus_nomer'] ),
		array( 'id' => $_POST['id_nomer'] ),
		array( '%d', '%d' ));
		
		$sql = "update wp_users set bonus=`bonus_admin`+`bonus_order`";
		$wpdb->get_results($sql);		
	}
	wp_die('Запрос завершен, изменения будут видны после перезагрузки страницы!');	
}

/*Вывод страницы редактирования*/
function eco_sublevel_page3() {
    echo '<div class="wrap"><h2>Список пользователей и бонусы.</h2>';
    echo 'Выводится список пользователей и накопленные ими бонусы <h3>Изменения будут видны после перезагрузки страницы!</h3></div>';
    echo '<div class="messages"></div>';
    global $wpdb;    
    $eco_user_id = "";
    $sql = "SELECT * from `wp_users`";
    $rs = $wpdb->get_results($sql);
echo'<div class="wrap"><table class="wp-list-table widefat striped">
<thead class="eco_thead_class2">
<tr>
<td>ID</td><td>Логин пользователя</td><td>Email</td><td>Расширенное имя</td><td>Бонус Ордер</td><td>Бонус Админ</td><td>Бонус всего</td>
</tr>
</thead>
<tbody class="eco_tbody_class">
<tr>
<td>
<table class="wp-list-table widefat striped">';
if( $rs ) {	
	foreach ( $rs as $ID ) {
		echo "<tr><td>";
		echo $ID->ID;
		echo "</td></tr>";
	}
}
echo'</table></td><td><table class="wp-list-table widefat striped">';
if( $rs ) {	
	foreach ( $rs as $ID ) {
		echo "<tr><td>";
		echo $ID->user_login;
		echo "</td></tr>";
	}
}
echo'</table></td><td><table class="wp-list-table widefat striped">';
if( $rs ) {	
	foreach ( $rs as $ID ) {
		echo "<tr><td>";
		echo $ID->user_email;
		echo "</td></tr>";
	}
}
echo'</table></td><td><table class="wp-list-table widefat striped">';
if( $rs ) {	
	foreach ( $rs as $ID ) {
		echo "<tr><td>";
		echo $ID->display_name;
		echo "</td></tr>";
	}
}
echo'</table></td><td><table class="wp-list-table widefat striped">';

if( $rs ) {	
	foreach ( $rs as $ID ) {
		echo "<tr><td>";
		echo $ID->bonus_order;
		echo "</td></tr>";
	}
}
echo'</table></td><td><table class="wp-list-table widefat striped">';
if( $rs ) {	
	foreach ( $rs as $ID ) {
		echo "<tr><td>";
		echo $ID->bonus_admin;
		echo "</td></tr>";
	}
}
echo'</table></td><td><table class="wp-list-table widefat striped">';
if( $rs ) {	
	foreach ( $rs as $ID ) {
		echo "<tr><td>";
		echo $ID->bonus;
		echo "</td></tr>";
	}
}
echo'</table>
</td>
</tr>
</tbody>
</table>
</div>'; 

echo'<div class="wrap"><br /><h2>Редактировать значение поля "Бонус Админ"</h2>
<form>
<table class="eco_bonus_admin_table wp-list-table widefat">
<tr>
<td>Введите ID пользователя</td><td>Введите значение поля "Бонус Админ"</td>
</tr>
<tr>
<td><input type="text" name="name" id="id_nomer"></td><td><input type="text" name="name2" id="bonus_nomer"></td>
</tr>
<tr><td colspan="2"><a href="#" type="submit" id="btn_submit2" class="eco_submit_class">Обновить</a></td></tr>
</table>
</form>
</div>
';
wp_reset_postdata();
}

/*Вывод страницы Список*/
function eco_sublevel_page2() {
    echo '<div class="wrap"><h2>Список пользователей и бонусы.</h2>';
    echo 'Выводится список пользователей и накопленные ими бонусы' . '<br /><br /></div>';
    global $wpdb;    
    $eco_user_id = "";
    $sql = "SELECT * from `wp_users` where `bonus`<>'".$eco_user_id."'";
    $rs = $wpdb->get_results($sql);
echo'<div class="wrap"><table class="wp-list-table widefat fixed striped users">';
echo'<thead><tr>';
echo'<td>Логин пользователя</td><td>Email</td><td>Расширенное имя</td><td>Бонус Ордер</td><td>Бонус Админ</td><td>Бонус всего</td>';
echo'</tr></thead><tbody id="the-list"><tr>';
echo'<td><table class="wp-list-table widefat striped">';
if( $rs ) {	
	foreach ( $rs as $ID ) {
		echo "<tr><td>";
		echo $ID->user_login;
		echo "</tr></td>";
	}
	wp_reset_postdata();
}
echo'</table></td><td><table class="wp-list-table widefat striped">';
if( $rs ) {	
	foreach ( $rs as $ID ) {
		echo "<tr><td>";
		echo $ID->user_email;
		echo "</tr></td>";
	}
	wp_reset_postdata();
}
echo'</table></td><td><table class="wp-list-table widefat striped">';
if( $rs ) {	
	foreach ( $rs as $ID ) {
		echo "<tr><td>";
		echo $ID->display_name;
		echo "</tr></td>";
	}
	wp_reset_postdata();
}
echo'</table></td><td><table class="wp-list-table widefat striped">';
if( $rs ) {	
	foreach ( $rs as $ID ) {
		echo "<tr><td>";
		echo $ID->bonus_order;
		echo "</tr></td>";
	}
	wp_reset_postdata();
}
echo'</table></td><td><table class="wp-list-table widefat striped">';
if( $rs ) {	
	foreach ( $rs as $ID ) {
		echo "<tr><td>";
		echo $ID->bonus_admin;
		echo "</tr></td>";
	}
	wp_reset_postdata();
}
echo'</table></td><td><table class="wp-list-table widefat striped">';
if( $rs ) {	
	foreach ( $rs as $ID ) {
		echo "<tr><td>";
		echo $ID->bonus;
		echo "</tr></td>";
	}
	wp_reset_postdata();
}
echo'</table></td>';
echo'</tr>';
echo'</tbody></table></div>'; 
}

/*Ajax */
function wp_ajax_eco_bonus(){
	global $wpdb;
	if( isset($_POST['b1'])){
		print_r($_POST);
		/*$wpdb->insert('ecobonus',array( 'summa' => $_POST['b1'], 'procent' => $_POST['s1'] ),array( '%d', '%d' ));*/
		$wpdb->update('ecobonus',
		array( 'summa' => $_POST['b1'], 'procent' => $_POST['s1'] ),
		array( 'id' => 1 ),
		array( '%d', '%d' ));
		$wpdb->update('ecobonus',
		array( 'summa' => $_POST['b2'], 'procent' => $_POST['s2'] ),
		array( 'id' => 2 ),
		array( '%d', '%d' ));
		$wpdb->update('ecobonus',
		array( 'summa' => $_POST['b3'], 'procent' => $_POST['s3'] ),
		array( 'id' => 3 ),
		array( '%d', '%d' ));
		$wpdb->update('ecobonus',
		array( 'summa' => $_POST['b4'], 'procent' => $_POST['s4'] ),
		array( 'id' => 4 ),
		array( '%d', '%d' ));
		$wpdb->update('ecobonus',
		array( 'summa' => $_POST['b5'], 'procent' => $_POST['s5'] ),
		array( 'id' => 5 ),
		array( '%d', '%d' ));
		$wpdb->update('ecobonus',
		array( 'summa' => $_POST['b6'], 'procent' => $_POST['s6'] ),
		array( 'id' => 6 ),
		array( '%d', '%d' ));
		$wpdb->update('ecobonus',
		array( 'summa' => $_POST['b7'], 'procent' => $_POST['s7'] ),
		array( 'id' => 7 ),
		array( '%d', '%d' ));
		$wpdb->update('ecobonus',
		array( 'summa' => $_POST['b8'], 'procent' => $_POST['s8'] ),
		array( 'id' => 8 ),
		array( '%d', '%d' ));
		$wpdb->update('ecobonus',
		array( 'summa' => $_POST['b9'], 'procent' => $_POST['s9'] ),
		array( 'id' => 9 ),
		array( '%d', '%d' ));
		$wpdb->update('ecobonus',
		array( 'summa' => $_POST['b10'], 'procent' => $_POST['s10'] ),
		array( 'id' => 10 ),
		array( '%d', '%d' ));
		$wpdb->update('ecobonus',
		array( 'summa' => $_POST['b11'], 'procent' => $_POST['s11'] ),
		array( 'id' => 11 ),
		array( '%d', '%d' ));
		$wpdb->update('ecobonus',
		array( 'summa' => $_POST['b12'], 'procent' => $_POST['s12'] ),
		array( 'id' => 12 ),
		array( '%d', '%d' ));
	}	
	wp_die('Запрос завершен');	
}

/*Вывод страницы настроить*/
function eco_sublevel_page() {
    echo '<div class="wrap"><h2>Настроить программу</h2>';
    echo 'Внести изменения можно в любой время. Накопленные бонусы для пользователя никуда не пропадут, а начисления начнуться по новой программе.<br /> Бонусы и скидки вводятся числом.</div>';
    global $wpdb;   
    $sqlb1 = "SELECT `summa` from `ecobonus` where id='1'";
	$b1 = $wpdb->get_var($sqlb1);
	$sqlb2 = "SELECT `summa` from `ecobonus` where id='2'";
	$b2 = $wpdb->get_var($sqlb2);
	$sqlb3 = "SELECT `summa` from `ecobonus` where id='3'";
	$b3 = $wpdb->get_var($sqlb3); 
	$sqlb4 = "SELECT `summa` from `ecobonus` where id='4'";
	$b4 = $wpdb->get_var($sqlb4); 
	$sqlb5 = "SELECT `summa` from `ecobonus` where id='5'";
	$b5 = $wpdb->get_var($sqlb5); 
	$sqlb6 = "SELECT `summa` from `ecobonus` where id='6'";
	$b6 = $wpdb->get_var($sqlb6); 
	$sqlb7 = "SELECT `summa` from `ecobonus` where id='7'";
	$b7 = $wpdb->get_var($sqlb7); 
	$sqlb8 = "SELECT `summa` from `ecobonus` where id='8'";
	$b8 = $wpdb->get_var($sqlb8); 
	$sqlb9 = "SELECT `summa` from `ecobonus` where id='9'";
	$b9 = $wpdb->get_var($sqlb9); 
	$sqlb10 = "SELECT `summa` from `ecobonus` where id='10'";
	$b10 = $wpdb->get_var($sqlb10); 
	$sqlb11 = "SELECT `summa` from `ecobonus` where id='11'";
	$b11 = $wpdb->get_var($sqlb11);
	$sqlb12 = "SELECT `summa` from `ecobonus` where id='12'";
	$b12 = $wpdb->get_var($sqlb12); 
	
	$sqls1 = "SELECT `procent` from `ecobonus` where id='1'";
	$s1 = $wpdb->get_var($sqls1);
	$sqls2 = "SELECT `procent` from `ecobonus` where id='2'";
	$s2 = $wpdb->get_var($sqls2);
	$sqls3 = "SELECT `procent` from `ecobonus` where id='3'";
	$s3 = $wpdb->get_var($sqls3);
	$sqls4 = "SELECT `procent` from `ecobonus` where id='4'";
	$s4 = $wpdb->get_var($sqls4);
	$sqls5 = "SELECT `procent` from `ecobonus` where id='5'";
	$s5 = $wpdb->get_var($sqls5);
	$sqls6 = "SELECT `procent` from `ecobonus` where id='6'";
	$s6 = $wpdb->get_var($sqls6);
	$sqls7 = "SELECT `procent` from `ecobonus` where id='7'";
	$s7 = $wpdb->get_var($sqls7);
	$sqls8 = "SELECT `procent` from `ecobonus` where id='8'";
	$s8 = $wpdb->get_var($sqls8);
	$sqls9 = "SELECT `procent` from `ecobonus` where id='9'";
	$s9 = $wpdb->get_var($sqls9);
	$sqls10 = "SELECT `procent` from `ecobonus` where id='10'";
	$s10 = $wpdb->get_var($sqls10);
	$sqls11 = "SELECT `procent` from `ecobonus` where id='11'";
	$s11 = $wpdb->get_var($sqls11);
	$sqls12 = "SELECT `procent` from `ecobonus` where id='12'";
	$s12 = $wpdb->get_var($sqls12);    
echo'
<div class="wrap"> 
<br/>
    <div class="messages"></div>
<br/>  
<table class="eco_bonus_admin_table wp-list-table widefat striped "><thead class="eco_thead_class"><tr><td>Уровень</td><td>Бонусы</td><td>Скидка (%)</td></tr></thead>
<form>
<tbody class="eco_tbody_class">
	<tr><td>1<td><input id="b1" type="text" name="b1" value="' .$b1. '"></td><td><input id="s1" type="text" name="s1" value="' . $s1 . '"></td></tr>
	<tr><td>2<td><input id="b2" type="text" name="b2" value="' .$b2. '"></td><td><input id="s2" type="text" name="s2" value="' . $s2 . '"></td></tr>
	<tr><td>3<td><input id="b3" type="text" name="b3" value="' .$b3. '"></td><td><input id="s3" type="text" name="s3" value="' . $s3 . '"></td></tr>
	<tr><td>4<td><input id="b4" type="text" name="b4" value="' .$b4. '"></td><td><input id="s4" type="text" name="s4" value="' . $s4 . '"></td></tr>
	<tr><td>5<td><input id="b5" type="text" name="b5" value="' .$b5. '"></td><td><input id="s5" type="text" name="s5" value="' . $s5 . '"></td></tr>
	<tr><td>6<td><input id="b6" type="text" name="b6" value="' .$b6. '"></td><td><input id="s6" type="text" name="s6" value="' . $s6 . '"></td></tr>
	<tr><td>7<td><input id="b7" type="text" name="b7" value="' .$b7. '"></td><td><input id="s7" type="text" name="s7" value="' . $s7 . '"></td></tr>
	<tr><td>8<td><input id="b8" type="text" name="b8" value="' .$b8. '"></td><td><input id="s8" type="text" name="s8" value="' . $s8 . '"></td></tr>
	<tr><td>9<td><input id="b9" type="text" name="b9" value="' .$b9. '"></td><td><input id="s9" type="text" name="s9" value="' . $s9 . '"></td></tr>
	<tr><td>10<td><input id="b10" type="text" name="b10" value="' .$b10. '"></td><td><input id="s10" type="text" name="s10" value="' . $s10 . '"></td></tr>
	<tr><td>11<td><input id="b11" type="text" name="b11" value="' .$b11. '"></td><td><input id="s11" type="text" name="s11" value="' . $s11 . '"></td></tr>
	<tr><td>12<td><input id="b12" type="text" name="b12" value="' .$b12. '"></td><td><input id="s12" type="text" name="s12" value="' . $s12 . '"></td></tr>
</tbody>
<tfoot>
<tr><td colspan="3"><a href="#" type="submit" id="btn_submit" class="eco_submit_class">Обновить</a></td></tr>
</tfoot>
</form>
</table>

</div>
';    
} 
/*=========================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================*/
function eco_sublevel_page4() {
	echo '<div class="wrap" style="color:green;">';
	global $wpdb;
	$sql = "SELECT * from `wp_woocommerce_order_itemmeta` where `meta_key` = '_product_id'";
	$pr_id = $wpdb->get_results($sql);
	
	$sql = "SELECT * from `wp_woocommerce_order_itemmeta` where `meta_key` ='_qty'";
	$pr_qty = $wpdb->get_results($sql);
	
	$sql = "SELECT * from `wp_postmeta` where `meta_key`='eco_bonus_edinica_tovara'";
	$bonus_edinica = $wpdb->get_results($sql);

	$sql = "SELECT `order_id` from `wp_woocommerce_order_items`";
	$order_id = $wpdb->get_results($sql);

	$sql = "SELECT * FROM `wp_postmeta` WHERE `meta_key` = '_completed_date'";
	$completed_date = $wpdb->get_results($sql);
	
	$sql = "SELECT * FROM `wp_postmeta` WHERE `meta_key` = '_customer_user'";
	$completed_user = $wpdb->get_results($sql);
	
	$datetime1 = date('Y-m-d'); $datetime2 = date_create('2017-05-24'); if ($datetime1 > $datetime2) {
		goto z;
	}
	

/*Создаем таблицу bonus_all*/
$sql = "DROP TABLE bonus_all";
$wpdb->get_results($sql);
$sql="CREATE TABLE IF NOT EXISTS bonus_all (id INT NOT NULL PRIMARY KEY AUTO_INCREMENT, tovar_id INT NOT NULL, tovar_qty INT NOT NULL, bonus_ed_itog INT NOT NULL, order_id INT NOT NULL, status TEXT(100), info_user TEXT(100), it_umn TEXT(100))";
$bonus_all = $wpdb->get_results($sql);
$sql2="SELECT COUNT(id) FROM `bonus_all`";
$count = $wpdb->get_var($sql2);
settype($count, 'int');

$sql = "SELECT * from `wp_woocommerce_order_itemmeta` where `meta_key` = '_product_id'";
$produkti = $wpdb->get_results($sql);

$sql = "SELECT * from `wp_postmeta` where `meta_key`='eco_bonus_edinica_tovara'";
$bonusi = $wpdb->get_results($sql);

$temp_producti = $produkti;
$temp_producti2 = $produkti;

	for($i = 0; $i < sizeof($produkti);$i++)
	{
					if ($count < sizeof($produkti))
					{
						$wpdb->insert('bonus_all', array('tovar_id' =>$temp_producti[$i]->meta_value), array("%d"));
					}
					else 
					{
						
					}
			$wpdb->update('bonus_all', array('tovar_id' =>$temp_producti2[$i]->meta_value), array( 'id' => $i+1 ), array("%d", "%d"));
			$wpdb->update('bonus_all', array('tovar_qty' =>$pr_qty[$i]->meta_value), array( 'id' => $i+1 ), array("%d", "%d"));
			$wpdb->update('bonus_all', array('order_id' =>$order_id[$i]->order_id), array( 'id' => $i+1 ), array("%d", "%d"));				
	}
wp_reset_postdata();

$sql="SELECT * from `bonus_all`";
$booonus_all = $wpdb->get_results($sql);
$sql = "SELECT * from `wp_postmeta` where `meta_key`='eco_bonus_edinica_tovara'";
$bonusi_new = $wpdb->get_results($sql);
	for($i2 = 0; $i2 < sizeof($produkti);$i2++)
	{
		for ($n2= 0; $n2 < sizeof($bonusi_new);$n2++){
			if (($booonus_all[$i2]->tovar_id) == ($bonusi_new[$n2]->post_id)) 
			{
				$wpdb->update('bonus_all', array('bonus_ed_itog' =>$bonusi_new[$n2]->meta_value), array( 'id' => $i2+1 ), array("%d", "%d"));
			}
		}
	}
wp_reset_postdata();

$sql_new="SELECT * from `bonus_all`";
$t_all = $wpdb->get_results($sql_new);
$sql_new="SELECT * FROM `wp_postmeta` WHERE `meta_key` = '_completed_date'";
$s_new = $wpdb->get_results($sql_new);
	for($i = 0; $i < sizeof($t_all);$i++)
	{
		for ($n = 0; $n < sizeof($s_new);$n++){
			if (($t_all[$i]->order_id) == ($s_new[$n]->post_id)) 
			{
				$wpdb->update('bonus_all', array('status' =>$s_new[$n]->meta_value), array( 'id' => $i+1 ), array("%d", "%d"));
			}
		}
	}
wp_reset_postdata();

$sql_user="SELECT * from `bonus_all`";
$tab_all = $wpdb->get_results($sql_user);
$sql_user="SELECT * FROM `wp_postmeta` WHERE `meta_key` = '_customer_user'";
$sab_new = $wpdb->get_results($sql_user);
	for($i = 0; $i < sizeof($tab_all);$i++)
	{
		for ($n = 0; $n < sizeof($sab_new);$n++){
			if (($tab_all[$i]->order_id) == ($sab_new[$n]->post_id)) 
			{
				$wpdb->update('bonus_all', array('info_user' =>$sab_new[$n]->meta_value), array( 'id' => $i+1 ), array("%d", "%d"));
			}
			
		}
	}
wp_reset_postdata();

$sql = "update bonus_all set it_umn=`tovar_qty`*`bonus_ed_itog`";
$wpdb->get_results($sql);

$sql17 = "SELECT * from `bonus_all`";
$rename = $wpdb->get_results($sql17);
    echo '<h1>Дополнительная информация</h1>';
	echo '<table class="wp-list-table widefat striped">';
	echo '<thead>
					<tr>
					<td>Товар ID</td><td>Количество</td><td>Бонус ед.тов</td><td>Ордер ID</td><td>Статус</td><td>info_user</td><td>it_umn</td>
					</tr>
					</thead>';
			if( $rename ) {	
				foreach ( $rename as $nd ) {					
					echo '<tr>';
					echo '<td>'.$nd->tovar_id.'</td><td>'.$nd->tovar_qty.'</td><td>'.$nd->bonus_ed_itog.'</td><td>'.$nd->order_id.'</td><td>'.$nd->status.'</td><td>'.$nd->info_user.'</td><td>'.$nd->it_umn.'</td>';
					echo '</tr>';
				}
				wp_reset_postdata();
			}
	echo'</table>';

global $wpdb;
$sql = "SELECT * from `wp_users`";
$wp_u = $wpdb->get_results($sql);
$sql = "SELECT * from `bonus_all`";
$bn_all = $wpdb->get_results($sql);
$sum = 0;
$slojenie = 0;

				foreach ( $bn_all as $nd ) 
				{
					if ($nd->status != 0)
					{
						echo '<br /> Условие пройдено.';
					
						foreach ( $wp_u as $st )
						{
							if ($nd->info_user == $st->ID)
							{
								$slojenie = $slojenie + $nd->it_umn;
								$wpdb->update('wp_users', array('bonus_order' => $slojenie), array( 'ID' => $st->ID ), array("%d", "%d"));
								echo '<br /><br /> Сумма равна = '.$slojenie.' $nd->info_user = '.$nd->info_user.' $st->ID = '.$st->ID;
								echo '<br /> $nd->info_user = $st->ID = '.var_dump($nd->info_user == $st->ID);
							}
						}
						echo '<br /> Статус равен = '.$nd->status;	
										
						
					}
					else 
					{
						echo '<br /> Условие не пройдено.';
					}
					echo '<br /> Цикл завершен';
					echo '<hr>';
				}				
				wp_reset_postdata();

z:
$sql = "update wp_users set bonus=`bonus_admin`+`bonus_order`";
$wpdb->get_results($sql);
echo '</div>';
}

/*=======================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================================*/
function eco_woocommerce_cart_totals_before_order_total(){
	/*if (!is_user_logged_in())
	{
			return $content;
	}
	else
	{*/
	global $woocommerce;
	global $wpdb;
	global $current_user;
	get_currentuserinfo();
	
	$totals_eco_it = WC()->cart;
	$eco_user = $current_user->ID;
	$sql = "SELECT `bonus` FROM `wp_users` WHERE `ID`='".$eco_user."'";
	$bonus_summ = $wpdb->get_results($sql);	
	$eco_bonus = $bonus_summ[0]->bonus;
	
	$sql = "SELECT * FROM `ecobonus`";
	$ecobonus_table = $wpdb->get_results($sql);
	
	for($q = 0; $q < sizeof($ecobonus_table);$q++){
		if(($eco_bonus != 0 ) and ($eco_bonus >= $ecobonus_table[$q]->summa) and (($ecobonus_table[$q]->summa) != 0 )){
			$skidka=$ecobonus_table[$q]->procent;
		}
	}
	wp_reset_postdata();	

echo '<div class="eco_table_cart">Бонусов:<div class="eco_it_cart">'.$eco_bonus.'</div></div>';
echo '<div class="eco_table_cart">Процент скидки:<div class="eco_it_cart">'.$skidka.'</div></div>';


}



function eco_woocommerce_cart_totals_order_total_html(){	
	/*if (!is_user_logged_in())
	{
			return $content;
	}
	else
	{*/
		global $woocommerce;
		global $wpdb;
		global $current_user;
		get_currentuserinfo();
		$totals_eco_it = WC()->cart;
		$eco_user = $current_user->ID;
		$sql = "SELECT `bonus` FROM `wp_users` WHERE `ID`='".$eco_user."'";
		$bonus_summ = $wpdb->get_results($sql);	
		$eco_bonus = $bonus_summ[0]->bonus;		
		$sql = "SELECT * FROM `ecobonus`";
		$ecobonus_table = $wpdb->get_results($sql);		
		for($q = 0; $q < sizeof($ecobonus_table);$q++){
			if(($eco_bonus != 0 ) and ($eco_bonus >= $ecobonus_table[$q]->summa) and (($ecobonus_table[$q]->summa) != 0 )){
				$skidka=$ecobonus_table[$q]->procent;
			}
		}
		wp_reset_postdata();	
		$amount = (WC()->cart->subtotal) - ((WC()->cart->subtotal)*$skidka/100);
		WC()->cart->cart_contents_total = $amount;
		return wc_price($amount);
	/*}*/
}


 function eco_woocommerce_cart_contents_total() {
    if ( ! $this->prices_include_tax ) {
      $cart_contents_total = ($this->cart_contents_total)/20;
    } else {
      $cart_contents_total = ($this->cart_contents_total)/20 + $this->tax_total ;
    }

    return $cart_contents_total;
  }

function eco_action_woocommerce_proceed_to_checkout ($woocommerce_button_proceed_to_checkout, $int) {
	global $woocommerce;
	global $post;
	global $wpdb;

		
	echo 'Сработало действие создание ордера!!!';
	/*echo get_total();*/
	
	$amount = (WC()->cart->subtotal) - ((WC()->cart->subtotal)*$skidka/100);
		WC()->cart->cart_contents_total = $amount;
		
		/*echo $this->get_items();*/
		/*get_item_total();*/
			
		$wpdb->update('wp_woocommerce_order_itemmeta', array('meta_value' => $amount), array( 'meta_id' => 7 ), array("%d", "%d"));
		return $amount;
	
}

function eco_woocommerce_order_status_pending () {
	global $woocommerce;
	global $post;
	global $wpdb;
	echo 'Сработало действие подтвердить заказ';
	$amount = (WC()->cart->subtotal) - ((WC()->cart->subtotal)*$skidka/100);
		WC()->cart->cart_contents_total = $amount;
		$wpdb->update('wp_woocommerce_order_itemmeta', array('meta_value' => 40), array( 'meta_id' => 7 ), array("%d", "%d"));
		return $amount;
		
}




?>