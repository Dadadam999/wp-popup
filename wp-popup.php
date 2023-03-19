<?php
/**
 * Plugin Name: wp-popup
 * Plugin URI: https://github.com/
 * Description: Плагин позволяет выводить сообщения не зарегистрированным пользователям.
 * Version: 1.0.0
 * Author: Bogdanov Andrey
 * Author URI: mailto://swarzone2100@yandex.ru
 *
 * @package Всплывающее уведомление
 * @author Bogdanov Andrey (swarzone2100@yandex.ru)
 * @since 1.0
*/
require_once __DIR__.'/wp-popup-autoload.php';

use wppopup\TableMananger;
use wppopup\Main;

register_activation_hook(__FILE__, 'Installwppopup');
register_deactivation_hook(__FILE__, 'Uninstallwppopup');

function Installwppopup()
{
  $tables = new TableMananger();
  $tables->Install();
}

function Uninstallwppopup()
{
  $tables = new TableMananger();
  $tables->Uninstall();
}

add_filter( 'plugin_action_links', function($links, $file)
{
  //проверка - наш это плагин или нет
  if ( $file != plugin_basename(__FILE__) )
    return $links;
  // создаем ссылку
  $settings_link = sprintf('<a href="%s">%s</a>', admin_url('admin.php?page=settings_wppopup'), 'Настройки');

  array_unshift( $links, $settings_link );
  return $links;
}, 10, 2 );

new Main();
