<?php
class Client_Page
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts' , [$this , 'add_files']);
        add_action('wp_footer' , [$this , 'generate_html']);
        if(isset($_GET['info']))
        {
            $this->send_info();
        }
    }

    public function add_files()
    {
        wp_enqueue_style( 'bootstrap' , 'https://lib.arvancloud.com/bootstrap/5.1.3/css/bootstrap.min.css' , false , false ,'all');
        wp_enqueue_script('bootstapjs' , "https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" , false , false , true);
        wp_enqueue_script('client_side' , plugin_dir_url(__FILE__).'/assets/js/client-side.js' , false , false , true);
        wp_enqueue_style('client-side' , plugin_dir_url(__FILE__).'/assets/css/client-side.css' , false , false , 'all');

    }
    public function generate_html()
    {

        $img = get_option('sh_popup_image_url');
        $name = get_option('sh_popup_title');
        $welcome_sentence = get_option('sh_popup_welcome_sentence');
        $question_sentence = get_option('sh_popup_question_sentence');
        $button_text = get_option('sh_popup_button_text');
        $html = <<<HTML
        <div class="modal fade" id="sh_modal" aria-labelledby="sh_modal_label" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="sh_img">
                            <img src="{$img}" alt />
                        </div>
                        <div class="sh_name">
                            <span>$name</span>
                        </div>
                    </div>
                    <div class="modal-body">
                      <div class="col-12">
                        <strong>$welcome_sentence</strong>
                      </div>
                      <div class="col-12">
                        <p>$question_sentence</p>
                       </div>
                    </div>
                    <div class="modal-footer">
                          <input type="text">
                          <input type="submit" value="$button_text">
                    </div>
                </div>
            </div>
        </div>
        <button id="modal_button" data-bs-toggle="modal" data-bs-target="#sh_modal"></button>
HTML;
    echo $html;
    }

    private function send_info()
    {
        $info = [
            'begin_timer' =>  get_option('sh_popup_begin_timer'),
            'repeat_timer' => get_option('sh_popup_repeat_timer')
        ];
        $info = json_encode($info);
        echo $info;
        die();
    }
}