<?php
/**
 * @package wppopup
 * @author Bogdanov Andrey (swarzone2100@yandex.ru)
 */

namespace wppopup\Tables;

class SettingsTable
{
    protected $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    public function CreateTable()
    {
        $this->wpdb->get_results(
           "CREATE TABLE `" . $this->wpdb->prefix . "wppopup_settings`
           (
			       id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	           message VARCHAR(4096),
             message_class VARCHAR(256),
             btnok_text VARCHAR(256),
             btnok_url VARCHAR(512),
             btnok_class VARCHAR(256),
             btnno_text VARCHAR(256),
             btnno_url VARCHAR(512),
             btnno_class VARCHAR(256),
             interval_show BIGINT(20),
             last_show DATETIME,
	           UNIQUE KEY id (id)
           )"
        );

        $this->wpdb->get_results(
          "INSERT INTO `" . $this->wpdb->prefix . "wppopup_settings` (`id`, `message`, `btnok_text`, `btnno_text`, `interval_show`, `last_show`)
          VALUES (0, 'Ваше сообщение!', 'Подтвердить', 'Отказаться', 60, '" . date("Y-m-d H:i:s") . "' )"
        );
    }

    public function DeleteTable()
    {
        $this->wpdb->get_results(
          "DROP TABLE `" . $this->wpdb->prefix . "wppopup_settings`"
        );
    }

    public function Get()
    {
      return $this->wpdb->get_results(
         "SELECT * FROM `" . $this->wpdb->prefix . "wppopup_settings`",
         ARRAY_A
        )[0];
    }

    public function Update($message, $message_class, $btnok_text, $btnok_url, $btnok_class, $btnno_text, $btnno_url, $btnno_class, $interval_show)
    {
      date_default_timezone_set('Europe/Moscow');

      return $this->wpdb->get_results(
         "UPDATE `" . $this->wpdb->prefix . "wppopup_settings`
         SET `message` = '" . $message . "',
             `message_class` = '" . $message_class . "',
             `btnok_text` = '" . $btnok_text . "',
             `btnok_url` = '" . $btnok_url . "',
             `btnok_class` = '" . $btnok_class . "',
             `btnno_text` = '" . $btnno_text . "',
             `btnno_url` = '" . $btnno_url . "',
             `btnno_class` = '" . $btnno_class . "',
             `interval_show` = " . $interval_show . ",
             `last_show` = '" . date("Y-m-d H:i:s") . "'"
      );
    }

    public function UpdateLastShow()
    {
      date_default_timezone_set('Europe/Moscow');

      return $this->wpdb->get_results(
         "UPDATE `" . $this->wpdb->prefix . "wppopup_settings`
         SET `last_show` = '" . date("Y-m-d H:i:s") . "'"
      );
    }
}
