var x = document.getElementsByClassName('test')
for (var i = 0; i < x.length; i++) {
    x[i].addEventListener("click", function() {
        var selectedEl = document.querySelector(".selected");
        if (selectedEl) {
            selectedEl.classList.remove("selected");
            $("#test_javob").val("");
        }
        this.classList.add("selected");

        var javob = $(this).text(),
            id = $("#questionid").val();
        $("#test_javob").val(javob);
        $.ajax({
            url: "../api/demotest/",
            type: "POST",
            data: {
                id: id,
                answer: javob
            },
            beforeSend: function() {
                $(".test").addClass("disabledbutton");
            },
            success: function(a) {
                var question = JSON.parse(a);
                if (question.success) {
                    $(".selected").removeClass("disabledbutton");
                    $(".selected").css("background-color", "#00ca00!important");
                    $('.selected').css('cssText', function(i, v) {
                        return this.style.cssText + ';background-color: #00ca00 !important;';
                    });
                } else {
                    $(".selected").removeClass("disabledbutton");
                    $('.selected').css('cssText', function(i, v) {
                        return this.style.cssText + ';background-color: #f37373 !important;';
                    });
                }
                reload(1000, pagelink);
            }
        })
    }, false);
}
var t = 20;
var m = 0;

function refr_time() {
    if (t > 0) {
        t--;
        if (m > 0 && t == 0) {
            t = 59;
            m--;
        }
        if (t < 10) {
            t = "0" + t;
        }
        document.getElementById('timer').innerHTML = t + " soniya";
    }
    if (m == 0 && t == "00") {
        clearInterval(tm);
        $("#redall").css('cssText', function(i, v) {
            return this.style.cssText + ';background-color: #dc3545!important;';
        });
        document.getElementById('timer').innerHTML = "Vaqt tugadi";
       reload(500, pagelink);
    }
}
var tm = setInterval('refr_time();', 1000);
