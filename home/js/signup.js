$('document').ready(function () {
    /* validation */
    $("#user-form").validate({
        rules:
            {
                username: {
                    required: true,
                    minlength: 3
                }
            },
        messages:
            {
                username: "<p class='erorr'>user name must have al least 3 char</p>",

            },
        submitHandler: submitForm
    });

    function submitForm() {
        $.ajax({
            type: 'POST',
            url: 'ajax/chat.php',
            // data: {method: "signup", message: $("#user-form").serialize()},
            data: $("#user-form").serialize() + '&method=signup',
            beforeSend: function () {
                $("#error").fadeOut();
                $("#btn-submit").html('Processing');
            },
            success: function (data) {

                $("#btn-submit").html('You have Entered');
                // $.cookie('userName', $('#username').val());

                if (data === "registered") {
                    $("#error").hide();
                    $("#join").delay(2000).fadeOut(1000);
                }
                if (data === "error") {
                    $("#error").hide();
                    $("#join").delay(2000).fadeOut(1000);
                }

            }
        });

        return false;
    }

});