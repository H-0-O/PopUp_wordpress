<?php
class Admin_Page
{
	private $title = null  , $welcome_sentence = null , $question_sentence = null , $app = null , $image_url = null , $phone_number ;
    private $repeat = null , $begin_timer = null , $repeat_timer = null , $sh_button = null;

	public function __construct()
	{
		add_action('wp_ajax_nopriv_save_chat_panel' , function () {
			echo "gfiogjoifdjg";
			die();
		});
		add_action('wp_ajax_save_chat_panel' , function () {
            echo "gfiogjoifdjg";
            die();
        });
		add_menu_page('تنظیمات پاپ آپ' , 'تنظیمات پاپ آپ' , 'manage_options' , 'pop_up_setting' , [$this , 'render_page_setting']);
		add_action('admin_enqueue_scripts' , [$this , 'add_files']);
        if(isset($_GET['save_option']))
        {
            $this->save_options();
        }

	}

	public function add_files()
	{
		wp_enqueue_script('admin_side' , plugin_dir_url(__FILE__).'/assets/js/admin-side.js' , ["wp-color-picker"] , false , true);
		wp_enqueue_script('admin_side' , "https://code.jquery.com/ui/1.13.2/jquery-ui.js" , false , true);
		wp_enqueue_style('bootstrap_css' , 'https://lib.arvancloud.com/bootstrap/5.1.3/css/bootstrap.min.css' , false , false , 'all');
		wp_enqueue_style('sh_admin_style' , plugin_dir_url(__FILE__).'/assets/css/admin_style.css' , false , false , 'all');
        wp_enqueue_media();
        wp_enqueue_style('wp-color-picker');
	}
	public function render_page_setting()
	{
			if($_GET['panel'] == 2)
			{
				echo $this->html_chat_setting();
				return;
			}
			echo $this->html_main_setting();
			echo $this->html_sub_setting();

	}

	private function html_main_setting()
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
        $extra_timer = get_option('sh_popup_extra_timer');
        $open_again_after_send = get_option('sh_popup_open_again_after_send');
		$checked = [
            'crisp' => $this->app == "crisp" ? "checked" : "",
            'whatsapp' => $this->app == "whatsapp" ? "checked" : "",
            'repeat' => $this->repeat == "on" ? "checked" : "" ,
            'open_again' => $open_again_after_send == "on" ? "checked" : "",
        ];
		$hiddens = [
            'phone' => $this->app == "crisp" ? "hidden" : ""  ,
            'img' => (empty($this->image_url) or $this->image_url == false ) ? "hidden" : "" ,
            'repeat_timer' => empty($this->repeat) ? "hidden" : ""
        ];
        $requires = [
            'phone_number' => $this->app == "whatsapp" ? "required" : ""
        ];
		$html = <<<HTML
			<div class="sh container">
				<form action="//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}&&save_option" method="post" class="sh_container" id="settings">
				<div class="form sh_main_setting">
					<label for="title">نام :</label>
					<input type="text" name="title" id="title" value="{$this->title}"/> 
					<label for="welcome">جمله اول :</label>
					<input type="text" name="welcome" id="welcome" value="{$this->welcome_sentence}">
					<label for="question">جمله دوم:</label>
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
                        <div class="sh_open_again">
                            <label for="open_again">بعد از ارسال پیام باز شود ؟</label>
                            <input type="checkbox" id="open_again" name="open_again" {$checked['open_again']}>
                        </div>
                        <div class="sh_timer">
                             <div class="sh_begin_timer">
                                <label for="begin_timer">زمان باز شدن در صفحه اول</label>
                                <input type="number" name="begin_timer" min="1000" required id="begin_timer" value="{$this->begin_timer}" >
                            </div>
                            <div class="sh_extra-time" {$hiddens['repeat_timer']}>
                                <label for="extra_time">زمان باز شدن در صفحه های بعدی</label>
                                <input type="number" name="extra_timer" min="5000" id="extra_timer" value="$extra_timer">
                            </div>
                            <div class="sh_repeat_timer" {$hiddens['repeat_timer']}>
                                <label for="repeat_timer">زمان تکرار</label>
                                <input type="number" name="repeat_timer" min="1000" id="repeat_timer" value="{$this->repeat_timer}" >
                            </div>
                        </div>
						<div class="sh_phone_number" {$hiddens['phone']}>
							<label for="phone_number">شماره واتساپ</label>
							<input type="tel" name="phone_number" id="phone_number" {$requires['phone_number']} value="{$this->phone_number}" >
							<label for="pre_phone">پیش شماره</label>
							<input type="text" name="pre_phone" id="pre_phone" maxlength="3" value="+98" disabled>
						</div>
					</div>
					<div class="sh_img">
						<img src="{$this->image_url}" alt="" {$hiddens['img']} id="uploaded">
						<button class="btn btn-primary" id="uploader">انتخاب عکس</button>
						<input type="text" hidden name="img-url" value="{$this->image_url}" id="uploaded-url">
					</div>
					
			</div>
				
			
HTML;

		return $html;
	}
    private function html_sub_setting()
    {
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
        $html =<<<HTML
        <div class="sh_sub_setting">
                 <div>
                <label for="back_color">:رنگ بک گراند</label>
                <input class="my-color-field" type="text" value="$back_color" data-default-color="$back_color" name="back_color" id="back_color"/>
                </div>
                <div>
                <label for="name_color">رنگ نام</label>
                <input class="my-color-field" type="text" value="$name_color" data-default-color="$name_color" name="name_color"/>
                </div>
                <div>
                <label for="welcome_color">رنگ جمله اول </label>
                <input class="my-color-field" type="text" value="$welcome_color" data-default-color="$welcome_color" name="welcome_color"/>
                </div>
                <div>
                <label for="question_color">رنگ جمله دوم </label>
                <input class="my-color-field" type="text" value="$question_color" data-default-color="$question_color" name="question_color"/>
                </div>
                <div>
                <label for="box_color">رنگ فیلد ورودی</label>
                <input class="my-color-field" type="text" value="$box_color" data-default-color="$box_color" name="box_color"/>
                </div>
                <div>
                <label for="button_color">رنگ دکمه</label>
                <input class="my-color-field" type="text" value="$button_color" data-default-color="$button_color" name="button_color"/>
                </div>
                <div>
                <label for="text_button_color">رنگ نوشته دکمه</label>
                <input class="my-color-field" type="text" value="$text_button_color" data-default-color="$text_button_color" name="text_button_color"/>
                </div>
                <div>
                <label for="main_radios">گوشه فرم اصلی</label>
                <input type="number" name="main_radius" value="$main_radius" />
                </div>
                <div>
                        <label>فیلد ورودی</label>
                        <div>
                        <label for="input_size">اندازه :</label>
                        <input type="number" min="0" name="input_size" value="$input_size" />
                        </div>
                        <div>
                        <label for="input_size">گوشه :</label>
                        <input type="number" min="0" name="input_radius" value="$input_radius" />
                        </div>
                </div>        
                <div>
                    <label for="cutom_id">ایدی اختصاصی</label>        
                    <input type="text" name="custom_id" value="$custom_id" />
                </div>
                <div>
                    <label for="cutom_class">کلاس اختصاصی</label>        
                    <input type="text" name="custom_class" value="$custom_class" />
                </div>
                
                </div>
            <div class="sh_save_options">
            <input type="submit" id="save-options" class="btn btn-primary" value="ذخیره تغییرات" >
        </div>
    </form>
</div>
HTML;
        return $html;
    }

	private function html_chat_setting(){
		$active = get_option('sh_popup_chat_active') == "active" ? "checked" : "" ;
		?>
			<form action='//<?= "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}&&save_option" ?>' method="post" class="sh_container" id="settings">
					<table class="form-table" role="presentation">
						<tbody>	
							<tr>
								<th scope="row">فعال بودن چت : </th>
								<td><input type="checkbox" name="sh_popup_chat_active" value="active" <?= $active ?> /></td>
							</tr>
                            <tr>
                                <th>باز شدن پنل چت :</th>
                                <td><input type="number" class="sh_chat_open_time"  placeholder="ms" style=" width:80px;"></td>
                            </tr>
                            <tr>
                                <th scope="row"><strong>اکانت ها</strong></th>
                                <td><p class="sh_chat_add_account btn btn-primary">اضافه کردن اکانت</p></td>
                            </tr>
							<tr class="sh_chat_accounts"></tr>
						</tbody>
					</table>
					<div class="sh_save_options">
            <input type="submit" id="save-options" class="btn btn-primary" value="ذخیره تغییرات" >
        </div>
			</form>

<?php }
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
            'sh_popup_button_text' => esc_html($_POST['sh_button']),
            'sh_popup_back_color' => esc_attr($_POST['back_color']),
            'sh_popup_name_color' => esc_attr($_POST['name_color']),
            'sh_popup_welcome_color' => esc_attr($_POST['welcome_color']),
            'sh_popup_question_color' => esc_attr($_POST['question_color']),
            'sh_popup_box_color' => esc_attr($_POST['box_color']),
            'sh_popup_button_color' => esc_attr($_POST['button_color']),
            'sh_popup_text_button_color' => esc_attr($_POST['text_button_color']),
            'sh_popup_main_radius' => esc_html($_POST['main_radius']),
            'sh_popup_input_size' => esc_html($_POST['input_size']),
            'sh_popup_input_radius' => esc_html($_POST['input_radius']),
            'sh_popup_input_custom_id' => esc_html($_POST['custom_id']),
            'sh_popup_input_custom_class' => esc_html($_POST['custom_class']),
            'sh_popup_extra_timer' => esc_html($_POST['extra_timer']),
            'sh_popup_open_again_after_send' => esc_html($_POST['open_again']),
	        'sh_popup_chat_active' => esc_html($_POST['sh_popup_chat_active'])
        ];

        foreach ($options as $option => $val)
        {
            update_option($option , $val);
        }
        header("Location: {$_SERVER['HTTP_REFERER']}");
    }

    public function save_chat_panel(){
        echo "foigjofijg";
        die();
    }
}