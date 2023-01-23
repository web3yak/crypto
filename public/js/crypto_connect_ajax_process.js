//Load more button
jQuery(document).ready(function () {
  var paged = 1;
  var count = 0;

  jQuery(document).on("click", "#crypto_connect_ajax_process", function (e) {
    e.preventDefault();
    id = jQuery(this).attr("data-id");
    method_name = jQuery(this).attr("data-method_name");
    nonce = jQuery(this).attr("data-nonce");
    param1 = jQuery(this).attr("data-param1");
    param2 = jQuery(this).attr("data-param2");
    param3 = jQuery(this).attr("data-param3");
   // alert("am ready");

    jQuery.ajax({
      type: "post",
      dataType: "json",
      url: crypto_connectChainAjax.ajaxurl,
      data: {
        action: "crypto_connect_ajax_process",
        id: id,
        method_name: method_name,
        nonce: nonce,
        param1: param1,
        param2: param2,
        param3: param3,
      },
      beforeSend: function () {
        //  alert("about to send");
        //jQuery("#crypto_connect_ajax_process_loader").show();
      },
      success: function (response) {
        //  jQuery("#crypto_connect_ajax_process_content").empty();
        // jQuery("#crypto_connect_ajax_process_content").append(response.msg).fadeIn("normal");
       // alert(response.msg);
        if (response.msg == "success") {
          location.reload();
        }
      },
      complete: function (data) {
        jQuery("#crypto_connect_ajax_process_loader").hide();
      },
    });
  });
});
