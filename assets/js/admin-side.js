(function ($) {
    const params = new Proxy(new URLSearchParams(window.location.search), {
        get: (searchParams, prop) => searchParams.get(prop),
    });
        // Get the value of "some_key" in eg "https://example.com/?some_key=some_value"
    let panel = params.panel;


    //media uploader
    let mediaUploader = wp.media({
        title: 'Choose Image',
        button: {
            text: 'Choose Image'
        },
        multiple: false
    });
    $('.my-color-field').wpColorPicker();
if(panel != 2) {
    // page one

    $("#uploader").click(function (e) {
        e.preventDefault();
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }
    });

    mediaUploader.on('select', function () {
        let $img = $("#uploaded");
        var attachment = mediaUploader.state().get('selection').first().toJSON();
        $img.attr('src', attachment.url);
        $("#uploaded-url").val(attachment.url);
        if ($img.attr('hidden')) {
            $img.attr('hidden', false);
        }
    });
    // phone number edit
    $(".sh_form").submit(function (e) {
        let phone_number = $("#phone_number").val();
        if (phone_number[0] == 0) {
            phone_number = phone_number.substring(1);
        }
        $("#phone_number").val(phone_number);
    })
    $("#radio_whatsapp").click(function () {
        $('.sh_phone_number').attr('hidden', false);
        $('.sh_phone_number #phone_number').attr('required', true);
    });
    $("#radio_crisp").click(function () {
        $(".sh_phone_number").attr('hidden', true).attr('required', false);
        $('.sh_phone_number #phone_number').attr('required', true);
    })
    $("#repeat").click(function () {
        let $repeat_timer = $(".sh_repeat_timer");
        if ($repeat_timer.attr('hidden')) {
            $repeat_timer.attr('hidden', false);
        } else {
            $repeat_timer.attr('hidden', true);
        }

        let $extra_timer = $('.sh_extra-time');
        if ($extra_timer.attr('hidden'))
            $extra_timer.attr('hidden', false);
        else
            $extra_timer.attr('hidden', true);
    })

    //color picker

}

//chat setting

if(panel == 2) {
    // global vars
    let current_img_filed , max_id;

    // run functions
    defaults();

    //cansel submit on enter
    $('#settings').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }
    });

    $("#settings").submit(function (e) {
        e.preventDefault();
        grab_tables_information();
        let settings = {
            active: $("input[name='sh_popup_chat_active']").prop('checked') == true ? "checked" : "" ,
            theme: $('input[name="sh_popup_chat_theme"]:checked').val(),
            gap_bot: $('input[name="sh_chat_gap_from_bottom"]').val(),
            gap_right: $('input[name="sh_chat_gap_from_right"]').val(),
            customize_color: grab_tables_for_color(),
            open_time_chat : $(".sh_chat_open_time").val() == '' ? 0 : $(".sh_chat_open_time").val(),
            chat_icon: $(".chat_icon").attr('src'),
            account_info : grab_tables_information(),
        }

        $.ajax({
            url: ajaxurl ,
            data:{
                action: 'save_chat_panel',
                settings
            },
            method: "POST",
            statusCode:{
                200:()=>{alert("تنظیمات با موفقیت ذخیره شد.")},
                500:()=>{alert("مقادیر وارد شده اشتباه است.")}
            }
        });
    });

    $(".ui-sortable").sortable();

    //add sentence
    $("body").on("click", '.add_to_sentence', function () {
            let text = $(this).parent().find('.sh_chat_input_sentence').val();
            $(this).parent().find(".ui-sortable").append(generate_sentence(text))
            $(this).parent().find('.sh_chat_input_sentence').val('');
    });

    //remove sentence
    $(".sh_chat_sentence").dblclick(function(){
       $(this).remove();
    });

    // add account
    $(".sh_chat_add_account").click(function () {
        $(".sh_chat_accounts").append(generate_account());
        $(".ui-sortable").sortable();
        defaults();
    });
    //remove account
    $("body").on( 'click' , '.remove_account' , function(){
        let id =$(this).parent().parent().parent().parent().attr('data-id');
        console.log(id)
        $('.sh_chat_accounts').find(`th[data-id="${id}"]`).remove();
        $('.sh_chat_accounts').find(`td[data-id="${id}"]`).remove();
        defaults();
    })
    //add img
    $("body").on("click", '.uploader-img', function (e) {
        current_img_filed = $(this).parent().find(".img-uploaded");
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }

    });
    //active customize color
    $("input[name='sh_popup_chat_theme']").click(function(){
        if($('input[name="sh_popup_chat_theme"]:checked').val() == 3){
            $(".sh_chat_customize").show();
        }else{
            $(".sh_chat_customize").hide();
        }
    })
    mediaUploader.on('select', function () {
        let $img = current_img_filed;
        var attachment = mediaUploader.state().get('selection').first().toJSON();
        $img.attr('src', attachment.url);
        $("#uploaded-url").val(attachment.url);
        if ($img.attr('hidden')) {
            $img.attr('hidden', false);
        }
        defaults();
    });

    function generate_sentence(text) {
        return `<li class='ui-state-default sh_chat_sentence'>${text}</li>`;
    }

    function generate_account(account_number) {
        max_id++;
        let message = "اکانت جدید"
        return ` <th data-id="${max_id}" >${message}</th>
                                <td data-id="${max_id}">
                                    <table class="sh_chat_child_table" data-id="${max_id}">
                                            <tr>
                                                <th scope="row"><input type="text" name="sh_popup_chat_account_name" data-id="" value="" placeholder="نام اکانت" ></th>
                                                <td>
                                                    <input type="number" name="sh_popup_account_phone_number" data-id="" placeholder="تلفن"/>
                                                    <p class="btn btn-danger remove_account">حذف کردن اکانت</p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>عکس اکانت</th>
                                                <td>
                                                    <p class="btn btn-primary uploader-img">انتخاب عکس</p>
                                                    <img class="img-uploaded" src="" alt="">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>فاصله زمانی بین ارسال جملات</th>
                                                <td><input type="number" placeholder="ms" name="sh_chat_input_sentence_time" style="width: 80px;"></td>
                                            </tr>
                                            <tr>
                                                <th><label for="sh_chat_input_sentence">جملات اماده</th>
                                                <td class="sh_chat_sentence_box">
                                                    <input type="text" class="sh_chat_input_sentence" />
                                                      <p class="add_to_sentence btn btn-primary">اضافه کردن</p>
                                                    <ul class="sh_chat_sentences ui-sortable"></ul>
                                                </td>
                                            </tr>
                                    </table >
                                </td>`;
    }

    function defaults(){
        if($(".img-uploaded").attr('src') == ''){
            $(".img-uploaded").css('display' , 'none');
        }else{
            $(".img-uploaded").css('display' , 'inline-block');
        }
        let data_ids = $('.sh_chat_accounts > th').map(function(){
            return $(this).attr('data-id');
        }).get();
        if(data_ids.length != 0 )
            max_id = Math.max.apply(Math, data_ids);
        else
            max_id = 0;
    }
    function grab_tables_for_color(){
        let colors = $(".sh_chat_customize .my-color-field");
        let final_result = {};
        $.each(colors , function(key , val){
            final_result[$(val).attr('name')] = $(val).val() == '' ? '#000' :  $(val).val();
        });
        return final_result;
    }
    function grab_tables_information(){
        let tables = $(".sh_chat_accounts").find("table");
        let final_result = {};
        $.each(tables , function(key , val){
            final_result[key] = {};
            final_result[key].account_name = $(val).find("input[name='sh_popup_chat_account_name']").val();
            final_result[key].account_phone = $(val).find("input[name='sh_popup_account_phone_number']").val();
            final_result[key].img_url = $(val).find(".img-uploaded").attr('src');
            final_result[key].sentence_time =  $(val).find("input[name='sh_chat_input_sentence_time']").val();
            final_result[key].sentences = grab_tables_sentence(val);
        })
        return final_result;
    }

    function grab_tables_sentence(el){

        if($(el).find(".sh_chat_sentences").children().length != 0){
            let sentences = [];
            $.each($(el).find(".sh_chat_sentences").children()  , function(key , val){
              sentences.push($(val).text());
            })
            return sentences;
        }else{
            return false;
        }
    }

}

})(jQuery)