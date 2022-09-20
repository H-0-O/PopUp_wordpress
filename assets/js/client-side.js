(function($){
    //variables
    let begin_timer = info.begin_timer  , repeat_timer = info.repeat_timer , app = info.app , repeat = info.is_repeated , phone_number = info.phone_number , extra_timer = info.extra_timer ,
        open_aging = info.open_aging;

    if(app == "whatsapp") {
        // panel
        let $chat_messages = $(".sh_chat_messages");

        $(".sh_icon_box").click(function () {
            $('.sh_icon_open').toggleClass('sh_rotate');
            $('.sh_panel').toggleClass('sh_show_chat');

        });

        (function () {
            let text_storage = localStorage.getItem("chat_panel_texts");
            if (text_storage) {
                text_storage = text_storage.split(';');
                for (let i = 0; i < text_storage.length - 1; i++) {
                    $chat_messages.append(generate_message_personal(text_storage[i]));
                }
            }
        })();


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
            send_message(text);
        });

        function generate_message(text, img_src) {
            let time = new Date();
            time = time.getHours() + ":" + time.getMinutes();
            let message_box = $(`<div class='sh_chat_message new '>
                                <p>${text}</p>
                                <img src="${img_src}" />
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

    if(begin_timer == 0 || begin_timer == "" || begin_timer == undefined )
        return;
    if(document.referrer.search(document.location.host) == '-1') {
        setTimeout(open_popup, begin_timer);
    }else{
        setTimeout(open_popup, extra_timer);
    }
    $("#sh_message").keyup(function(key){
        if(key.keyCode == "13")
        {
            $(".sh_send_text").click();
        }
    })
    $(".sh_send_text").click(function(e)
    {
        e.preventDefault();
        let text = $("#sh_message").val();
        document.cookie = "send_message=true";
        $("#sh_modal").click();
        add_to_local_storage(text);
        send_message(text);

    });

    function get_cookie(cookie_name)
    {
        return document.cookie.split('; ').find((row) => row.startsWith(cookie_name)) ?.split('=')[1];

    }

    $("#sh_modal").click(function(){
        if(repeat == "" || repeat == undefined)
        {
            if(document.referrer.search(document.location.host) == -1)
            {
                return;
            }
        }else if(repeat == "on")
        {
            if( (get_cookie("send_message") != "true" || get_cookie("send_message") == undefined) || open_aging == "on") {
                setTimeout(function () {
                    if (!$("#sh_modal").hasClass("show")) {
                        setTimeout(open_popup, repeat_timer);
                    }
                }, '500');
            }
        }

    });


    function add_to_local_storage(text)
    {
        let text_storage = localStorage.getItem("chat_panel_texts");
        if (text_storage) {
            text_storage += text + ";";
            localStorage.setItem('chat_panel_texts', text_storage);
        } else {
            localStorage.setItem('chat_panel_texts', text + ";");
        }
    }
    function send_message(message)
    {
        switch (app){
            case 'crisp':
                $crisp.push(["do", "message:send", ["text", message]]);
                $crisp.push(["do", "chat:open"]);
                break;
            case 'whatsapp':
                if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
                    open(`https://api.whatsapp.com/send?phone=98${phone_number}&text=${message}`);
                }
                open(`https://web.whatsapp.com/send?phone=98${phone_number}&text=${message}`);
                break;
        }
    }
    function open_popup()
    {
        $("#sh_modal_button").click();
    }




})(jQuery);



