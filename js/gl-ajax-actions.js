jQuery(document).ready( function() {
    
    /* ================= GravityForms setting - SS Extension ================= */
    $("#sub_g_frgt_pwd").live("click", function() {
        var emailId = $("input[name=g_frgt_pwd]").val();
        if (emailId) {
                jQuery.ajax({
                    type: 'post',
                    url: myAjax.ajaxurl,
                    dataType: 'json',
                    data: {
                        action: 'do_pwd_reset',
                        reset_email: emailId
                    },
                    beforeSend: function() {
                        var imgUrl = pluginBaseUrl+"/templates/forms/login/images/gl-loading.gif";
                        var loadHtml = '<img style="height:100px; display:block; margin:0 auto;" src="'+imgUrl+'" />';
                        $(".notification-container").html(loadHtml);
                    },
                    success: function(data, textStatus, XMLHttpRequest) {
                        $("input[name=g_frgt_pwd]").val('');
                        var msgHtml = '<div class="success_pan">';
                        msgHtml += '<img src="'+pluginBaseUrl+'/templates/forms/login/images/success_icon_64px.png">';
                        msgHtml += '<div class="success_content">Your password has been reset. Please check your email to get the new password.</div>';
                        msgHtml += '</div>';
                        
                        var errorMsgHtml = '<div class="error_pan">';
                        errorMsgHtml += '<img src="'+pluginBaseUrl+'/templates/forms/login/images/error_icon_64px.png">';
                        if (data == "f") {
                            errorMsgHtml += '<div class="error_content">Some error occured. Please try again.</div>';
                        }
                        if (data == "nv") {
                            errorMsgHtml += '<div class="error_content">Enter a valid email id</div>';
                        }
                        if (data == "nr") {
                            errorMsgHtml += '<div class="error_content"><u>'+emailId+'</u> is not a registered email id</div>';
                        }
                        errorMsgHtml += '</div>';

                        //$(".notification-container").html(msgHtml);
                        if (data == "s") {
                            $(".notification-container").html(msgHtml);
                        }
                        if (data == "f" || data == "nv" || data == "nr") {
                            $(".notification-container").html(errorMsgHtml);
                        }
                    },
                    error: function (MLHttpRequest, textStatus, errorThrown) {
                        alert('ERROR - '+errorThrown);
                    }
                })
            }
        
    })
    
    jQuery(document).ready(function($){
 
 
    var custom_uploader;
 
    $('#upload_image_button').click(function(e) {
 
        e.preventDefault();
 
        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
 
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
 
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            attachment = custom_uploader.state().get('selection').first().toJSON();
            $('#upload_image').val(attachment.url);
        });
 
        //Open the uploader dialog
        custom_uploader.open();
 
    });
 
 
    });
})
