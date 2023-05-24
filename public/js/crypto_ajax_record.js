jQuery(document).ready(function () {
//Update primary image
jQuery(".crypto_ajax_record").on("submit", function (e) {
    e.preventDefault();
    var form = jQuery("#crypto-record-form")[0];
    var formData = new FormData(form);
    var i = 0;
    var progress = true;
    console.log(crypto_connectChainAjax.ajaxurl);
    jQuery.ajax({
      type: "post",
      dataType: "json",
      url: crypto_connectChainAjax.ajaxurl,
     // enctype: "multipart/form-data",
      processData: false,
      contentType: false,
      data: formData,
      beforeSend: function () {
     console.log("before send");
      },

      success: function (response) {
        if (response.type == "success") {
 
       console.log(response);
        } else {
          console.log("Blank Response");

        }

      },
      complete: function (data) {
        // Hide image container
        console.log("Submission completed ");
        //console.log(data);

      },
      error: function (jqXHR, textStatus, errorThrown) {
       // console.log(errorThrown);
       console.log('error');

      },
    });

  });
  

});