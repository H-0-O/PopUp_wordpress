(function($){
    //variables
    let popup_active = info.popup_active ,begin_timer = info.begin_timer  , repeat_timer = info.repeat_timer , app = info.app , repeat = info.is_repeated , phone_number = info.phone_number , extra_timer = info.extra_timer ,
        open_aging = info.open_aging , chat_setting = info.chat_setting;
// panel
    if(chat_setting['active'] == "checked") {
        let $chat_messages = $(".sh_chat_messages");
        let account_phone , account_img , account_sentence , account_sentence_time , account_id;
        let sentence_last_index = {};
        $(".sh_icon_box").click(function () {
            $('.sh_icon_open').toggleClass('sh_rotate');
            $('.sh_panel').toggleClass('sh_show_panel');

        });
        if(chat_setting['open_time_chat'])
            setTimeout(()=>{ if (!$('.sh_panel').hasClass('sh_show_panel')) $('.sh_icon_box').click();} , chat_setting['open_time_chat']);
        //go to account and set options
        $(".sh_chat_account").click(function(){
            $($chat_messages).html('');
            account_id = $(this).attr('data-id');
            let src_img = $(this).find('img').attr('src');
            let name = $(this).find('.sh_chat_account_name').text();
            $('.sh_chat_avatar img').attr('src' , src_img);
            $('.sh_chat_name p').text(name);
            account_phone = chat_setting.account_info[account_id].account_phone;
            account_img = chat_setting.account_info[account_id].img_url;
            $('.sh_chat_account_list').toggleClass('sh_hide');
            $('.sh_chat').toggleClass('sh_hide');

            setTimeout(function(){
                send_sentence(chat_setting.account_info[account_id].sentences[0]);
            } , 1000);
            if(chat_setting.account_info[account_id].sentence_time > 1000 ) {
                if (!sentence_last_index[account_id])
                    sentence_last_index[account_id] = 1;
                let sentence_interval = setInterval(function () {
                    if(sentence_last_index[account_id] >= chat_setting.account_info[account_id].sentences.length) {
                        clearInterval(sentence_interval);
                        sentence_last_index[account_id] = 0;
                        return;
                    }
                    if(!$('.sh_panel').hasClass('sh_show_panel'))
                        $('.sh_icon_box').click();
                    setTimeout(function(){
                        send_sentence(chat_setting.account_info[account_id].sentences[sentence_last_index[account_id]]);
                        sentence_last_index[account_id]++;
                        } , 500);

                }, chat_setting.account_info[account_id].sentence_time)

            }
        });

        // send pre sentences
        function send_sentence(sentence){
            $chat_messages.append(generate_message(sentence));
        }

        //back to account list
        $('.sh_chat_back_to_account_list').click(function(){
            $('.sh_chat_account_list').toggleClass('sh_hide');
            $('.sh_chat').toggleClass('sh_hide');
        })

        // send message to whats app
        $(".sh_chat_message_box").keyup(function (key) {
            if (key.keyCode == "13") {
                $(".sh_chat_submit").click();
            }
        })
        $(".sh_chat_submit").click(function (e) {
            e.preventDefault();
            let text = $(".sh_chat_message_box").val();
            if (text == "") {
                return;
            }
            $chat_messages.append(generate_message_personal(text));
            add_to_local_storage(text);
            $(".sh_chat_message_box").val("");
            send_whats_app_message(text);
        });


        function send_whats_app_message(message){
            if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
                open(`https://api.whatsapp.com/send?phone=98${account_phone}&text=${message}`);
            }
            open(`https://web.whatsapp.com/send?phone=98${account_phone}&text=${message}`);
        }
        // (function () {
        //     let text_storage = localStorage.getItem("chat_panel_texts");
        //     if (text_storage) {
        //         text_storage = text_storage.split(';');
        //         for (let i = 0; i < text_storage.length - 1; i++) {
        //             $chat_messages.append(generate_message_personal(text_storage[i]));
        //         }
        //     }
        // })();



        function generate_message(text, img_src) {
            let time = new Date();
            time = time.getHours() + ":" + time.getMinutes();
            let message_box = $(`<div class='sh_chat_message new '>
                                <p>${text}</p>
                                <div class="sh_chat_message_time">${time}</div>
            `);
            return message_box;
        }

        function generate_message_personal(text, img_src) {
            let time = new Date();
            time = time.getHours() + ":" + time.getHours();
            let message_box = $(`<div class='sh_chat_message sh_chat_message_personal new'>
                                <p>${text}</p>
                                <div class="sh_chat_message_time">${time}</div>
            `);
            return message_box;
        }
    }

    //pop up
if(popup_active == null) {
    if (begin_timer == 0 || begin_timer == "" || begin_timer == undefined)
        return;
    if (document.referrer.search(document.location.host) == '-1') {
        setTimeout(open_popup, begin_timer);
    } else {
        setTimeout(open_popup, extra_timer);
    }
    $("#sh_message").keyup(function (key) {
        if (key.keyCode == "13") {
            $(".sh_send_text").click();
        }
    })
    $(".sh_send_text").click(function (e) {
        e.preventDefault();
        let text = $("#sh_message").val();
        document.cookie = "send_message=true";
        $("#sh_modal").click();
        add_to_local_storage(text);
        send_message(text);

    });

    function get_cookie(cookie_name) {
        return document.cookie.split('; ').find((row) => row.startsWith(cookie_name))?.split('=')[1];

    }

    $("#sh_modal").click(function () {
        if (repeat == "" || repeat == undefined) {
            if (document.referrer.search(document.location.host) == -1) {
                return;
            }
        } else if (repeat == "on") {
            if ((get_cookie("send_message") != "true" || get_cookie("send_message") == undefined) || open_aging == "on") {
                setTimeout(function () {
                    if (!$("#sh_modal").hasClass("show")) {
                        setTimeout(open_popup, repeat_timer);
                    }
                }, '500');
            }
        }

    });


    function add_to_local_storage(text) {
        let text_storage = localStorage.getItem("chat_panel_texts");
        if (text_storage) {
            text_storage += text + ";";
            localStorage.setItem('chat_panel_texts', text_storage);
        } else {
            localStorage.setItem('chat_panel_texts', text + ";");
        }
    }

    function send_message(message) {
        switch (app) {
            case 'crisp':
                $crisp.push(["do", "message:send", ["text", message]]);
                $crisp.push(["do", "chat:open"]);
                break;
            case 'whatsapp':
                if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                    open(`https://api.whatsapp.com/send?phone=98${phone_number}&text=${message}`);
                }
                open(`https://web.whatsapp.com/send?phone=98${phone_number}&text=${message}`);
                break;
        }
    }

    function open_popup() {
        $("#sh_modal_button").click();
    }

}


})(jQuery);



