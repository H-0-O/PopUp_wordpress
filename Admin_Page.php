<?php
class Admin_Page
{
	private $title = null  , $welcome_sentence = null , $question_sentence = null , $app = null , $image_url = null , $phone_number;

	public function __construct()
	{
		add_menu_page('تنظیمات پاپ آپ' , 'تنظیمات پاپ آپ' , 'manage_options' , 'pop_up_setting' , [$this , 'render_page_setting']);
		add_action('admin_enqueue_scripts' , [$this , 'add_files']);
	}

	public function add_files()
	{
		wp_enqueue_script('admin_side' , plugin_dir_url(__FILE__).'/assets/js/admin-side.js' , false , false , true);
		wp_enqueue_style('bootstrap_css' , 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css' , false , false , 'all');
		wp_enqueue_style('sh_admin_style' , plugin_dir_url(__FILE__).'/assets/css/admin_style.css' , false , false , 'all');
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

		$crisp_checked = $this->app == "crisp" ? "checked" : "";
		$whatsapp_checked = $this->app == "whatsapp" ? "checked" : "";
		$hidden_or_not = $this->app == "crisp" ? "hidden" : "";
		$html = <<<HTML
			<div class="sh container">
				<form action="" method="post" class="form sh_form">
					<label for="title">عنوان:</label>
					<input type="text" name="title" id="title" /> 
					<label for="welcome">جمله خوش امد گویی:</label>
					<input type="text" name="welcome" id="welcome">
					<label for="question">جمله سوال:</label>
					<input type="text" name="question" id="question">
					<div class="sh_app">
						<label>انتخاب برنامه</label>
						<div class="sh_app_radios">
							<label for="radio_crisp">کریسپ</label>
							<input type="radio" name="app" id="radio_crisp" value="crisp" $crisp_checked>
							<label for="radio_whatsapp">واتس آپ</label>
							<input type="radio" name="app" id="radio_whatsapp" value="crisp" $whatsapp_checked>
						</div>
						<div class="sh_phone_number">
							<label for="phone_number">شماره واتساپ</label>
							<input type="tel" name="phone_number" id="phone_number" $hidden_or_not>
							<label for="pre_phone">پیش شماره</label>
							<input type="text" name="pre_phone" id="pre_phone" maxlength="3" value="+98">
						</div>
					<div>
					<div class="sh_img">
						<img src="" alt="">
						<button class="btn btn-primary">انتخاب عکس</button>
					</div>
				</form>
			</div>
HTML;

		return $html;
	}
}