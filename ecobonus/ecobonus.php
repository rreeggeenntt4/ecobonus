<?php
/**
 * @package ecobonus
 */
/*
Plugin Name: Бонусная программа
Plugin URI: http://ecohome.az
Description: Программа подключает на сайт систему бонусов. К странице товара в админке администратора добавляются дополнительные поля. В личном кабинете пользователя добавляется сумма имеющихся у него бонусов и указывается скидка которую пользователь получит. Количество бонусов зависит от количества купленных товаров и классификации товара.
Version: 1.0
Author: Автор решил не назваться
Author URI: http://ecohome.az
License: GPLv2 or later
Text Domain: ecobonus
*/

/*
Данный плагин специально разработан для сайта http://ecohome.az.

Эта программа является свободным программным обеспечением; Вы можете 
изменить его в соответствии с условиями GNU General Public License.

Смотрите GNU General Public License для более подробной информации.
*/


/*Активация, деактивация, удаление плагина*/
register_activation_hook(__FILE__, 'ec_bonus_install');
register_deactivation_hook(__FILE__, 'ec_bonus_uninstall');
register_uninstall_hook(__FILE__, 'ec_bonus_delete');



/*Подключение файлов*/
require __DIR__ . '/eco_functions.php';
/*add_filter ('the_content','eco_otkrit_content');*/

/*Добавление скриптов, стилей, ajax*/
add_action('admin_enqueue_scripts', 'eco_bonus_scripts_style');
add_action('wp_enqueue_scripts', 'eco_bonus_scripts_style');

/*Работа с ajax*/
add_action('wp_ajax_eco_bonus', 'wp_ajax_eco_bonus');
add_action('wp_ajax_update_bonus', 'wp_ajax_update_bonus');

/*Добавление меню в админке*/
add_action('admin_menu', 'eco_add_menu');

/*Дополнительный вывод на странице аккаунт*/
add_filter('the_content','eco_if_account_page');

/*Добавляем поле к товару*/
add_action( 'woocommerce_product_options_pricing', 'eco_bonus_edinica_tavar' );
add_action( 'woocommerce_product_after_variable_attributes', 'eco_bonus_edinica_tavar' );

/*Сохраняем товар с дополнительным полем*/
add_action( 'save_post', 'eco_bonus_save_tovar' );

/*Выводим бонус на странице товара*/
add_action( 'woocommerce_single_product_summary', 'eco_bonus_show', 5 );

 
// Дополнительно: Для вывода на страницах архивов (в товарных категориях)
add_action( 'woocommerce_after_shop_loop_item_title', 'eco_bonus_show' );

/*Обрабатываем статус заказа выполнен*/
add_filter( 'woocommerce_order_status_completed', 'eco_add_bonus_summ_to_acc_user' );

/*Добавляем произвольное поле данных покупателя в ордер */
/*add_filter( 'woocommerce_checkout_fields' , 'eco_woocommerce_checkout_fields' );*/

/*Добавляем дополнительное поле в ордеру расчета цены скидок в ордере*/
/*add_action( 'woocommerce_checkout_order_review', 'eco_woocommerce_checkout_order_review',10,2 );*/

/*Формируем вывод результатов в корзине*/
add_action( 'woocommerce_cart_totals_before_order_total', 'eco_woocommerce_cart_totals_before_order_total' );

/*Формируем результат ордера*/
add_filter('woocommerce_cart_totals_order_total_html', 'eco_woocommerce_cart_totals_order_total_html');

/*Формирование цены в строке total ордера*/
/*add_action( 'woocommerce_cart_totals_before_order_total', 'eco_cart_total_before_total' );*/

/*add_filter( 'woocommerce_cart_totals_fee_html', 'eco_cart_totals_fee_html');*/

/*Приступить к оформлению заказа*/
/*add_action ( 'woocommerce_proceed_to_checkout' , 'eco_action_woocommerce_proceed_to_checkout' , 10 , 2 ); */

/*Нажатие кнопки подтвердить заказ*/
/*add_action( "woocommerce_order_status_pending", "eco_woocommerce_order_status_pending");*/

/*После создания ордера*/
/*add_action( 'woocommerce_new_order', 'eco_woocommerce_new_order',  1, 1  );*/

/*Обновление ордера*/
/*add_action ( 'woocommerce_checkout_update_order_meta', 'eco_woocommerce_checkout_update_order_meta');*/

add_filter( 'woocommerce_cart_contents_total', 'eco_woocommerce_cart_contents_total')

/*add_filter ('woocommerce_update_order_review_fragments', 'eco_woocommerce_update_order_review_fragments');*/
/*add_action('wp_ajax_woocommerce_update_order_review','eco_woocommerce_update_order_review');*/







?>