<?php
/**
 * @package wppopup
 * @author Bogdanov Andrey (swarzone2100@yandex.ru)
 */

 namespace wppopup;

 use WP_REST_Request;
 use wpdb;

 use wppopup\Tables\SettingsTable;

 class TableMananger
 {
   protected $wpdb;
   public $settingsTable;

   public function __construct()
   {
       global $wpdb;
       $this->wpdb = $wpdb;
       $this->Init();
   }

   protected function Init() : self
   {
     $this->settingsTable = new SettingsTable();
     return $this;
   }

   public function Install()
   {
     $this->settingsTable->CreateTable();
   }

   public function Uninstall()
   {
     $this->settingsTable->DeleteTable();
   }
 }
?>
