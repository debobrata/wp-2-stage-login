/* 
 * Custom form works
 */

jQuery(function() {
    $("#gl-forgot-pwd").click(function() {
        if ($("#forgot-pwd-container").css("display") == "none") {
            $("#forgot-pwd-container").slideDown("slow");
        } else {
            $("#forgot-pwd-container").slideUp("slow");
        }
    })
});

