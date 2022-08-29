$(document).ready(function (){
    let mediaUploader = wp.media({
        title: 'Choose Image',
        button: {
            text: 'Choose Image'
        },
        multiple: false
    });

    $("#uploader").click(function(e){
        e.preventDefault();
        if(mediaUploader)
        {
            mediaUploader.open();
            return;
        }

    });

    mediaUploader.on('select' , function(){
        let $img = $("#uploaded");
        var attachment = mediaUploader.state().get('selection').first().toJSON();
        $img.attr('src' , attachment.url);
        $("#uploaded-url").val( attachment.url);
        if($img.attr('hidden'))
        {
            $img.attr('hidden' , false);
        }
    });
    $(".sh_form").submit(function(e){
       let phone_number = $("#phone_number").val();
       if(phone_number[0] == 0)
       {
           phone_number = phone_number.substring(1);
       }
        $("#phone_number").val(phone_number);
    })
   $("#radio_whatsapp").click(function(){
      $('.sh_phone_number').attr('hidden' , false);
   });
   $("#radio_crisp").click(function (){
       $(".sh_phone_number").attr('hidden' , true)
   })
   $("#repeat").click(function(){
       let $repeat_timer = $(".sh_repeat_timer");
       if($repeat_timer.attr('hidden'))
       {
           $repeat_timer.attr('hidden' , false);
       }else{
           $repeat_timer.attr('hidden' , true);
       }
   })
})