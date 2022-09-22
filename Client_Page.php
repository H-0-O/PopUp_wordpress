<?php
class Client_Page
{
    private $name = null , $img = null , $question_sentence = null , $chat_setting;

    public function __construct()
    {
        $this->chat_setting = json_decode(base64_decode(get_option('sh_chat_panel_settings')) , 1);
        $this->name = get_option('sh_popup_title');
        $this->img = get_option('sh_popup_image_url');
        $this->question_sentence = get_option('sh_popup_question_sentence');
        add_action('wp_enqueue_scripts' , [$this , 'add_files']);
        add_action('wp_footer' , [$this , 'generate_html']);

    }

    public function add_files()
    {
        wp_enqueue_style( 'bootstrap' , '//lib.arvancloud.com/bootstrap/5.1.3/css/bootstrap.min.css' , false , false ,'all');
        wp_enqueue_script('bootstapjs' , "//cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" , false , false , true);
        wp_enqueue_style('client-side-style' , plugin_dir_url(__FILE__).'/assets/css/client-side.css' , false , false , 'all');

        wp_register_script('client-side-js' , plugin_dir_url(__FILE__).'/assets/js/client-side.js' , false , false , true);
        wp_localize_script('client-side-js' , 'info' , [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'popup_active' => get_option('sh_popup_active'),
            'begin_timer' =>  get_option('sh_popup_begin_timer'),
            'repeat_timer' => get_option('sh_popup_repeat_timer'),
            'app' => get_option('sh_popup_app'),
            'is_repeated' => get_option('sh_popup_repeat'),
            'phone_number' => get_option('sh_popup_phone_number'),
            'extra_timer' => get_option('sh_popup_extra_timer') ,
            'open_aging' => get_option('sh_popup_open_again_after_send'),
            'chat_setting' => $this->chat_setting
        ]);
        wp_enqueue_script('client-side-js');

    }
    public function generate_html()
    {
	    $popup_active = get_option('sh_popup_active');
        if($popup_active == "on")
            $this->pop_up();
		if($this->chat_setting['active']== 'checked')
			$this->chat_panel();
    }
    private function chat_panel()
    {
        $chat_icon = null;
        if($this->chat_setting['chat_icon'])
            $chat_icon = $this->chat_setting['chat_icon'];
        elseif($this->chat_setting['theme'] == 1)
            $chat_icon = plugin_dir_url(__FILE__).'assets/svg/icons8-whatsapp-50.svg';
        else{
	        $chat_icon = plugin_dir_url(__FILE__).'assets/svg/icons8-whatsapp-48.png';
        }
		?>
        <style>
            :root{
                <?php switch($this->chat_setting['theme']): case '1' : default: ?>
                --caht-account-back-color:  rgba(0, 0, 0, 0.8);
                --chat-account-list-back-color : rgba(204, 204, 204, 0.62);
                --chat-title-back-color : rgba(0, 0, 0, 0.2);
                --chat-body-back : rgba(0, 0, 0, 0.5);
                --chat-footer-back : rgba(0, 0, 0, 0.2);
                --chat--message-back : rgba(0, 0, 0, 0.3);
                --chat-message-personal-back: linear-gradient(120deg, #248A52, #257287);
                <?php break; case '2': ?>
                 --caht-account-back-color: #d2d2d2;
                --chat-account-list-back-color :  #2db742;
                --chat-title-back-color : #2db742;
                --chat-body-back : #e8e8e8;
                --chat-footer-back : #2db742;
                --chat--message-back : #148c25;
                --chat-message-personal-back: #086716;
                <?php break; case '3': ?>
                --caht-account-back-color: <?= $this->chat_setting['customize_color']['account_list_back_color'] ?>;
                --chat-account-list-back-color :  <?= $this->chat_setting['customize_color']['account_back_color'] ?>;
                --chat-title-back-color : <?= $this->chat_setting['customize_color']['title_back_color'] ?>;
                --chat-body-back : <?= $this->chat_setting['customize_color']['chat_back_color'] ?>;
                --chat-footer-back : <?= $this->chat_setting['customize_color']['footer_back_color'] ?>;
                --chat--message-back : <?= $this->chat_setting['customize_color']['message_back_color'] ?>;
                --chat-message-personal-back: <?= $this->chat_setting['customize_color']['person_message_back_color'] ?>;
                <?php endswitch; ?>
            }
        </style>
        <div id="" class="sh_chat_panel " style="right: <?= $this->chat_setting['gap_right'] ?>px; bottom: <?= $this->chat_setting['gap_bot'] ?>px;">
            <div class="sh_chat_panel_container">
                <div class="sh_panel">
                    <div class="sh_chat_account_list">
                        <div class="sh_chat_account_list_box">
			                <?php if($this->chat_setting['account_info']):
				                foreach($this->chat_setting['account_info'] as $id => $account): ?>
                                    <div class="sh_chat_account" data-id="<?= $id ?>">
                                        <img src="<?= $account['img_url'] ?>" alt="<?= $account['account_name'] ?>"/>
                                        <p class="sh_chat_account_name"><?= $account['account_name'] ?></p>
                                        <p class="sh_chat_account_phone_number"><?= $account['account_phone'] ?></p>
                                    </div>
				                <?php endforeach;
			                endif; ?>
                        </div>
                    </div>
                    <div class="sh_chat sh_hide">
                        <div class="sh_chat_title">
                            <div class="sh_chat_avatar">
                                <img src="" alt="">
                            </div>
                            <div class="sh_chat_name">
                                <p>$this->name</p>
                            </div>
                            <div class="sh_chat_back_to_account_list">
                                <span><img src="<?= plugin_dir_url(__FILE__).'assets/svg/icons8-back-50.png'?>" alt=""></span>
                            </div>
                        </div>
                        <div class="sh_chat_body">
                            <div class="sh_chat_messages_container">
                                <div class="sh_chat_message_parent">
                                    <div class="sh_chat_message_scroller">
                                        <div class="sh_chat_messages">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sh_chat_footer">
                            <input type="text" class="sh_chat_message_box" placeholder="لطفا پیام خودتان را اینجا بنویسید">
<!--                            <input type="submit" class="sh_chat_submit" value="ارسال">-->
                            <img class="sh_chat_submit" src="<?= plugin_dir_url(__FILE__).'assets/svg/icons8-send-25.png' ?>" alt="send">
                        </div>
                    </div>
                </div>
                <div class="sh_icon_box" style="width: 50px;height: 50px; background-color: white;">
                    <span class="sh_icon_open" style="max-width: 120px;max-height: 120px;">
                            <img src="<?= $chat_icon ?>" alt="chat_icon">
                    </span>
                </div>
            </div>
        </div>


<?php    }
    private function pop_up()
    {
        $welcome_sentence = get_option('sh_popup_welcome_sentence');
        $question_sentence = get_option('sh_popup_question_sentence');
        $button_text = get_option('sh_popup_button_text');

        $back_color = get_option('sh_popup_back_color');
        $name_color = get_option('sh_popup_name_color');
        $welcome_color = get_option('sh_popup_welcome_color');
        $question_color = get_option('sh_popup_question_color');
        $box_color = get_option('sh_popup_box_color');
        $button_color = get_option('sh_popup_button_color');
        $text_button_color = get_option('sh_popup_text_button_color');

        $main_radius = get_option('sh_popup_main_radius');
        $input_size = get_option('sh_popup_input_size');
        $input_radius = get_option('sh_popup_input_radius');
        $custom_id = get_option('sh_popup_input_custom_id');
        $custom_class = get_option('sh_popup_input_custom_class');
        $html = <<<HTML
        <div class="modal fade" id="sh_modal" aria-labelledby="sh_modal_label" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content sh-modal-content" style="background-color: $back_color; border-radius: {$main_radius}px;">
                    <div class="modal-header sh-modal-header">
                        <div class="sh_img">
                            <img src="{$this->img}" alt />
                        </div>
                        <div class="sh_name">
                            <span style="color: $name_color; ">$this->name</span>
                        </div>
                    </div>
                    <div class="modal-body sh-modal-body">
                      <div class="col-12">
                        <strong style="color: $welcome_color;">$welcome_sentence</strong>
                      </div>
                      <div class="col-12">
                        <p style="color: $question_color; ">$question_sentence</p>
                       </div>
                    </div>
                    <div class="modal-footer sh-modal-footer">
                          <input type="text" id="sh_message" style="background-color: $box_color; width: {$input_size}px; border-radius: {$input_radius}px;">
                          <input type="submit" id="$custom_id" class="$custom_class sh_send_text" value="$button_text" style="background-color: $button_color; color: $text_button_color;">
                    </div>
                </div>
            </div>
        </div>
        <button id="sh_modal_button"  hidden aria-hidden="true"  data-bs-toggle="modal" data-bs-target="#sh_modal"></button>
HTML;
        echo $html;
    }
}