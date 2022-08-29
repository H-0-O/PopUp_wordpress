(function($){

    let begin_timer , repeat_timer;
    (function get_info()
    {
        var timer;
        $.ajax({
            url: window.location.href+"?info",
            success:(respond)=>{
                timer =  JSON.parse(respond);
                console.log(timer);
                begin_timer = timer.begin_timer;
                repeat_timer = timer.repeat_timer;
            }
        });
        return timer;
    })();

    setTimeout(function (){

        $("#modal_button").click();
    } , begin_timer);




})(jQuery);



