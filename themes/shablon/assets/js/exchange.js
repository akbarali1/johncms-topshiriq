
var pagelink = window.location.href;
var fadeTimeout = 5000;
function round(num, dec) {
   const [sv, ev] = num.toString().split('e');
   return Number(Number(Math.round(parseFloat(sv + 'e' + dec)) + 'e-' + dec) + 'e' + (ev || 0));
}
var $xabarmatni = $("#xabarmatni"), $liveToast = $("#liveToast"), $timeselect = $("#timeselect"), $timeoff = $("#timeoff"), $errors = $("#errors");

      $(document).on('click', '#errors', function () {
          var $this = $(this), qaysi = $this.attr('qiymat')
          $.ajax({
              url: "../api/exchange/",
              type: "POST",
              data: {yulduzcha:qaysi},
              dataType: "JSON",
              beforeSend: function() {
                $xabarmatni.text("");
                $this.attr("disabled", true);
              },
              success: function (a) {
                 if (a.success){
                $xabarmatni.text("Almashinuv amalga oshirildi");
                $liveToast.show();
                 setTimeout(function() {$liveToast.fadeOut();}, fadeTimeout);
             //   reload(1000, pagelink);
                 }else{
                $xabarmatni.text(a.error.answer);
                $liveToast.show();
                setTimeout(function() {$liveToast.fadeOut();}, fadeTimeout);
                 }
                $this.attr("disabled", false);
              }
          });
      });
      $(document).on('click', '#timeoff', function () {
          var qaysi = $timeselect.find(":selected").val();
          $.ajax({
              url: "../api/exchange/",
              type: "POST",
              data: {timeoff:qaysi},
              dataType: "JSON",
              beforeSend: function() {
                $timeselect.attr("disabled", true);
                $timeoff.attr("disabled", true);
                $xabarmatni.text("");
              },
              success: function (a) {
                 if (a.success){
                $xabarmatni.text("Test vaqti o`chirib qo`yildi");
                $liveToast.show();
                 setTimeout(function() {$liveToast.fadeOut();}, fadeTimeout);
                 }else{
                $xabarmatni.text(a.error.answer);
                $liveToast.show();
                 setTimeout(function() {$liveToast.fadeOut();}, fadeTimeout);
                 $timeselect.attr("disabled", false);
                 $timeoff.attr("disabled", false);
                 }
              }
          });
      });
     var $balladd = $("#balladd");
     var $inputball = $("#inputball");
     $(document).on('click', '#balladd', function () {
          var qaysi = $inputball.val();
          $.ajax({
              url: "../api/exchange/",
              type: "POST",
              data: {balladd:qaysi},
              dataType: "JSON",
              beforeSend: function() {
                $xabarmatni.text("");
                $balladd.attr("disabled", true);
                $inputball.attr("disabled", true);
              },
              success: function (a) {
                 if (a.success){
                $xabarmatni.text("Ball sotib olindi");
                $liveToast.show();
                 setTimeout(function() {$liveToast.fadeOut();}, fadeTimeout);
                 }else{
                $xabarmatni.text(a.error.answer);
                $liveToast.show();
                 setTimeout(function() {$liveToast.fadeOut();}, fadeTimeout);
                 $inputball.val("");
                }
                $inputball.attr("disabled", false);
                $balladd.attr("disabled", false);
              }
          });
      });
var $output = $("#divball");
$inputball.keyup(function() {
    var value = parseFloat($(this).val());
    $output.text(round(value*0.33, 4)+' soʻm');
});
