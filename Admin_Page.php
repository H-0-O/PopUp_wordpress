<?php
class Admin_Page
{
	private $title = null  , $welcome_sentence = null , $question_sentence = null , $app = null , $image_url = null , $phone_number ;
    private $repeat = null , $begin_timer = null , $repeat_timer = null , $sh_button = null;

	public function __construct()
	{
		add_menu_page('تنظیمات پاپ آپ' , 'تنظیمات پاپ آپ' , 'manage_options' , 'pop_up_setting' , [$this , 'render_page_setting']);
		add_action('admin_enqueue_scripts' , [$this , 'add_files']);
        if(isset($_GET['save_option']))
        {
            $this->save_options();
        }
	}

	public function add_files()
	{
		wp_enqueue_script('admin_side' , plugin_dir_url(__FILE__).'/assets/js/admin-side.js' , false , false , true);
        //wp_localize_script('admin_side' , 'ajax'  , ['url' => admin_url('admin-ajax.php')]);
		wp_enqueue_style('bootstrap_css' , 'https://lib.arvancloud.com/bootstrap/5.1.3/css/bootstrap.min.css' , false , false , 'all');
		wp_enqueue_style('sh_admin_style' , plugin_dir_url(__FILE__).'/assets/css/admin_style.css' , false , false , 'all');
        wp_enqueue_media();
        wp_enqueue_style('wp-color-picker');
	}
	public function render_page_setting()
	{
		echo $this->html_setting();
	}

	private function html_setting():string
	{
		$this->title = get_option('sh_popup_title');
		$this->welcome_sentence = get_option('sh_popup_welcome_sentence');
		$this->question_sentence = get_option('sh_popup_question_sentence');
		$this->app = get_option('sh_popup_app');
		$this->image_url = get_option('sh_popup_image_url');
		$this->phone_number = get_option('sh_popup_phone_number');
        $this->repeat = get_option('sh_popup_repeat');
        $this->begin_timer = get_option('sh_popup_begin_timer');
        $this->repeat_timer = get_option('sh_popup_repeat_timer');
        $this->sh_button = get_option('sh_popup_button_text');
		$checked = [
            'crisp' => $this->app == "crisp" ? "checked" : "",
            'whatsapp' => $this->app == "whatsapp" ? "checked" : "",
            'repeat' => $this->repeat == "on" ? "checked" : ""
        ];
		$hiddens = [
            'phone' => $this->app == "crisp" ? "hidden" : ""  ,
            'img' => (empty($this->image_url) or $this->image_url == false ) ? "hidden" : "" ,
            'repeat_timer' => empty($this->repeat) ? "hidden" : ""
        ];
		$html = <<<HTML
			<div class="sh container">
				<form action="//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}&&save_option" method="post" class="form sh_form">
					<label for="title">عنوان:</label>
					<input type="text" name="title" id="title" value="{$this->title}"/> 
					<label for="welcome">جمله خوش امد گویی:</label>
					<input type="text" name="welcome" id="welcome" value="{$this->welcome_sentence}">
					<label for="question">جمله سوال:</label>
					<input type="text" name="question" id="question" value="{$this->question_sentence}">
					<label for="sh_button">متن دکمه</label>
					<input type="text" name="sh_button" id="sh_button" value="{$this->sh_button}"/>
					<div class="sh_app">
						<label class="advance_setting">تنظیمات بیشتر</label>
						<div class="sh_app_radios">
							<label for="radio_crisp">کریسپ</label>
							<input type="radio" name="app" id="radio_crisp" value="crisp" {$checked['crisp']}>
							<label for="radio_whatsapp">واتس آپ</label>
							<input type="radio" name="app" id="radio_whatsapp" value="whatsapp" {$checked['whatsapp']}>
						</div>
						<div class="sh_repeat">
						    <label for="repeat">تکرار شود ؟</label>
						    <input type="checkbox" id="repeat" name="repeat"  {$checked['repeat']}>
                        </div>
                        <div class="sh_timer">
                             <div class="sh_begin_timer">
                                <label for="begin_timer">زمان بعد از ورد کابر</label>
                                <input type="number" name="begin_timer" id="begin_timer" value="{$this->begin_timer}" >
                            </div>
                            <div class="sh_repeat_timer" {$hiddens['repeat_timer']}>
                                <label for="repeat_timer">زمان ماندن کاربر</label>
                                <input type="number" name="repeat_timer" id="repeat_timer" value="{$this->repeat_timer}" >
                            </div>
                        </div>
						<div class="sh_phone_number" {$hiddens['phone']}>
							<label for="phone_number">شماره واتساپ</label>
							<input type="tel" name="phone_number" id="phone_number" value="{$this->phone_number}" >
							<label for="pre_phone">پیش شماره</label>
							<input type="text" name="pre_phone" id="pre_phone" maxlength="3" value="+98">
						</div>
					</div>
					<div class="sh_img">
						<img src="{$this->image_url}" alt="" {$hiddens['img']} id="uploaded">
						<button class="btn btn-primary" id="uploader">انتخاب عکس</button>
						<input type="text" hidden name="img-url" value="{$this->image_url}" id="uploaded-url">
					</div>
					<input type="submit" id="save-options" class="btn btn-primary" value="ذخیره تغییرات" >
				</form>
				
			</div>
HTML;

		return $html;
	}

    public function save_options()
    {

        $options = [
            'sh_popup_title' => esc_html($_POST['title'])  ,
            'sh_popup_welcome_sentence' => esc_html($_POST['welcome']),
            'sh_popup_question_sentence' => esc_html($_POST['question']) ,
            'sh_popup_app' => esc_html($_POST['app']),
            'sh_popup_image_url' => esc_url($_POST['img-url']) ,
            'sh_popup_phone_number' => esc_html($_POST['phone_number']) ,
            'sh_popup_repeat' => esc_html($_POST['repeat']),
            'sh_popup_begin_timer' => esc_html($_POST['begin_timer']),
            'sh_popup_repeat_timer' => esc_html($_POST['repeat_timer']) ,
            'sh_popup_button_text' => esc_html($_POST['sh_button'])
        ];

        foreach ($options as $option => $val)
        {
            update_option($option , $val);
        }
        header("Location: {$_SERVER['HTTP_REFERER']}");
    }
}