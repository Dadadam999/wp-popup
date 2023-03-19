<?php
/**
 * @package wppopup
 * @author Bogdanov Andrey (swarzone2100@yandex.ru)
 */

namespace wppopup;
use wppopup\TableMananger;
use wppopup\Tables\SettingsTable;
use WP_REST_Request;

class Main
{
    protected $tableMananger;
    protected $wpdb;
    protected $user_id;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->init();
    }

    protected function init() : self
    {
        $this->tableMananger = new TableMananger;
        $this->initShortcode();
        $this->addMenu();

        if (isset($_POST['wppopupSettingsNonce']))
           $this->saveSetting();

        return $this;
    }

    protected function addMenu()
    {
      add_action('admin_menu', function()
      {
          add_menu_page(
              'Настройки wp-popup',
              'Popup',
              'administrator',
              'settings_wppopup',
              array($this, 'callbackPopupSettings'),
              'dashicons-admin-generic',
              20
          );
      });
    }

    public function callbackPopupSettings()
    {
       wp_enqueue_style( 'wp-popup-admin', plugins_url('wp-popup/assets/css/admin.css') );
       $settings = $this->tableMananger->settingsTable->Get();
       $html = '';
       $html .= '<div class="container">';
       $html .= '<h1 class="h3 text-center my-5">Настройки онка Popup</h1>';
       $html .= '<div style=" max-width: 500px; margin: 0px auto;">';
       $html .= '<form id="rendered-form" action="" method="post">';
       $html .=  wp_nonce_field('wppopupSettingsNonce-wpnp', 'wppopupSettingsNonce');
       $html .= '<div class="rendered-form">';
       $html .= '<div class="paragraph">';
       $html .= '<p style="font-size: 20pt;" id="control-8758843">Описание</p>';
       $html .= '<p style="padding-top:5px;	font-size: 10pt;" id="control-8758844">Разместите шорткод <b style="color:blue;">[wp-popup]</b> на страницах, где необходимо показывать вплывающее окно!</p>';
       $html .= '</div>';
       $html .= '<div class="paragraph">';
       $html .= '<p style="font-size: 20pt;" id="control-8758843">Сообщение</p>';
       $html .= '</div>';
       $html .= '<div class="formbuilder-textarea form-group field-message"><label for="message" class="formbuilder-textarea-label">Текст сообщения<span class="tooltip-element" tooltip="Введите сообщение, выводимое в окне">?</span></label><textarea type="textarea" placeholder="Введите сообщение, выводимое в окне" class="form-control" name="message" maxlength="4096" id="message" title="Введите сообщение, выводимое в окне">' . $settings['message'] . '</textarea></div>';
       $html .= '<div class="formbuilder-text form-group field-message_class"><label for="message_class" class="formbuilder-text-label">CSS класс<span class="tooltip-element" tooltip="Указанный CSS класс будет применен к блоку сообщения">?</span></label><input type="text" placeholder=".example" class="form-control" name="message_class"  maxlength="256" id="message_class" title="Указанный CSS класс будет применен к блоку сообщения" value="' . $settings['message_class'] . '"><div>';
       $html .= '<div class="paragraph">';
       $html .= '<p style="font-size: 20pt;" id="control-4944741">Кнопка подтверждения</p>';
       $html .= '</div>';
       $html .= '<div class="formbuilder-text form-group field-btnok_text"><label for="btnok_text" class="formbuilder-text-label">Текст кнопки</label><input type="text" placeholder="Подтвердить" class="form-control" name="btnok_text"  maxlength="256" id="btnok_text" value="' . $settings['btnok_text'] . '"></div>';
       $html .= '<div class="formbuilder-text form-group field-btnok_url"><label for="btnok_url" class="formbuilder-text-label">URL ссылка<span class="tooltip-element" tooltip="Переход по данному url после нажатия кнопки">?</span></label><input type="text" placeholder="https://example.com" class="form-control" name="btnok_url"  maxlength="512" id="btnok_url" title="Переход по данному url после нажатия кнопки" value="' . $settings['btnok_url'] . '"></div>';
       $html .= '<div class="formbuilder-text form-group field-btnok_class"><label for="btnok_class" class="formbuilder-text-label">CSS класс<span class="tooltip-element" tooltip="Указанный CSS класс будет применен к кнопке">?</span></label><input type="text" placeholder=".example" class="form-control" name="btnok_class"  maxlength="256" id="btnok_class" title="Указанный CSS класс будет применен к кнопке" value="' . $settings['btnok_class'] . '"></div>';
       $html .= '<div class="paragraph">';
       $html .= '<p style="font-size: 20pt;" id="control-607787">Кнопка отказа</p>';
       $html .= '</div>';
       $html .= '<div class="formbuilder-text form-group field-btnno_text"><label for="btnno_text" class="formbuilder-text-label">Текст кнопки</label><input type="text" placeholder="Отказаться" class="form-control" name="btnno_text"  maxlength="256" id="btnno_text" value="' . $settings['btnno_text'] . '"></div>';
       $html .= '<div class="formbuilder-text form-group field-btnno_url"><label for="btnno_url" class="formbuilder-text-label">URL ссылки<span class="tooltip-element" tooltip="Переход по данному url после нажатия кнопки">?</span></label><input type="text" placeholder="https://example.com" class="form-control" name="btnno_url"  id="btnno_url" title="Переход по данному url после нажатия кнопки" value="' . $settings['btnno_url'] . '"></div>';
       $html .= '<div class="formbuilder-text form-group field-btnno_class"><label for="btnno_class" class="formbuilder-text-label">CSS класс<span class="tooltip-element" tooltip="Указанный CSS класс будет применен к кнопке">?</span></label><input type="text" placeholder=".example" class="form-control" name="btnno_class"  maxlength="256" id="btnno_class" title="Указанный CSS класс будет применен к кнопке" value="' . $settings['btnno_class'] . '"></div>';
       $html .= '<div class="paragraph">';
       $html .= '<p style="font-size: 20pt;" id="control-3187938">Прочие настройки</p>';
       $html .= '</div>';
       $html .= '<div class="formbuilder-number form-group field-interval"><label for="interval" class="formbuilder-number-label">Интервал в минутах повтора сообщения</label><input type="number" class="form-control" name="interval_show"  min="1" step="1" id="interval_show" value="' . $settings['interval_show'] . '"></div>';
       $html .= '<div class="formbuilder-date form-group field-last_show"><label for="last_show" class="formbuilder-date-label">Дата обновления<span class="tooltip-element" tooltip="Дата последнего показа сообщения пользователям">?</span></label><input type="datetime" class="form-control" name="last_show" disabled  id="last_show" title="Дата последнего показа сообщения пользователям" value="' . $settings['last_show'] . '"></div>';
       $html .= '<div class="formbuilder-button form-group field-Save"><button type="submit" class="btn-primary btn" name="Save"  style="primary" id="Save">Сохранить настройки</button></div>';
       $html .= '</div>';
       $html .= '</form>';
       $html .= '</div>';
       $html .= '</div>';

       echo $html;
    }

    protected function saveSetting()
    {
      $error = '';

      if( empty($_POST['message']) )
      {
        $message = 'Ваше сообщение!';
        $error .= 'Поле: Текст сообщения было установлено по умолчанию!\n';
      }
      else
        $message = $_POST['message'];

      if( empty($_POST['btnok_text']) )
      {
        $btnok_text = 'Подтвердить';
        $error .= 'Поле: Текст кнопки подтверждения было установлено по умолчанию!\n';
      }
      else
        $btnok_text = $_POST['btnok_text'];

      if( empty($_POST['btnno_text']) )
      {
        $btnno_text = 'Отказаться';
        $error .= 'Поле: Текст кнопки отказа было установлено по умолчанию!\n';
      }
      else
        $btnno_text = $_POST['btnno_text'];

      if( empty($_POST['interval_show']) )
      {
        $interval_show = '60';
        $error .= 'Поле: Интервал было установлено по умолчанию!\n';
      }
      else
        $interval_show = $_POST['interval_show'];

      $this->tableMananger->settingsTable->Update($message, $_POST['message_class'], $btnok_text, $_POST['btnok_url'], $_POST['btnok_class'], $btnno_text, $_POST['btnno_url'], $_POST['btnno_class'], $interval_show);
      echo '<script>window.onload = function() { alert("Настройки сохранены!\n' . $error . '"); };</script>';
    }

    protected function initShortcode()
    {
       wp_enqueue_style( 'wp-popup-front', plugins_url('wp-popup/assets/css/front.css') );

       add_action('wp_enqueue_scripts', function()
       {
           wp_enqueue_script(
               'wppopup-client',
               plugins_url('wp-popup/assets/js/wppopup-client.js'),
               [],
               '0.1.5'
           );
       });

       add_shortcode('wp-popup', function($atts, $content)
       {
         date_default_timezone_set('Europe/Moscow');
         $date_now = strtotime(date("Y-m-d H:i:s"));

         $settings = $this->tableMananger->settingsTable->Get();
        // $last_show = strtotime($settings['last_show']);


         $html = '';
         $html .= '<div id="modal-popup" class="modal">';
         $html .= '<div class="modal-content '.  $settings['message_class'] .'">';
         $html .= '<span class="message">' . $settings['message'] . '</span>';
         $html .= '<div = style="display: flex;">';
         $html .= '<a onclick="close_popup();" style="margin:10px;" class="button ' . $settings['btnok_class'] . '" href="' . (empty($settings['btnok_url']) ? '#' : $settings['btnok_url']) . '">' . $settings['btnok_text'] . '</a>';
         $html .= '<a onclick="close_popup();" style="margin:10px;" class="button ' . $settings['btnno_class'] . '" href="' . (empty($settings['btnok_url']) ? '#' : $settings['btnno_url']) . '">' . $settings['btnno_text'] . '</a>';
         $html .= '</div>';
         $html .= '</div>';
         $html .= '</div>';

         if ( !is_user_logged_in() )
            echo $html;
       });
    }
}
