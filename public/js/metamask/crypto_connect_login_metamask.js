jQuery(document).ready(function() {
    jQuery("[id=flexi_notification_box]").hide();
    jQuery("[id=delete_notification]").click(function() {
        jQuery("[id=flexi_notification_box]").fadeOut("slow");
    });

    jQuery("[id=btn-login]").click(function() {
        //alert("Login");

        login();
    });

});


if (typeof window.ethereum !== 'undefined') {
    console.log('MetaMask is installed!');
    jQuery("[id=flexi_notification_box]").hide();


} else {
    console.log("MetaMask is not installed");
    jQuery("[id=wallet_msg]").empty();
   // jQuery("#flexi_notification_box").fadeIn("slow");
 //   jQuery("[id=wallet_msg]").append("Metamask not installed").fadeIn("normal");
    jQuery.toast({
        heading: 'Notice',
        text: 'Metamask not installed',
        icon: 'warning',
        loader: true,
        loaderBg: '#fff',
        showHideTransition: 'fade',
        hideAfter: 3000,
        allowToastClose: false,
        position: {
            left: 100,
            top: 30
        }
    });
}

async function login() {
    if (typeof window.ethereum !== 'undefined') {
        // Instance web3 with the provided information
        web3 = new Web3(window.ethereum);
        try {
            // Request account access
            await window.ethereum.enable();
            onInit();
            return true
        } catch (error) {
            // User denied access
          //  console.log("ooo");
           // jQuery("[id=wallet_msg]").empty();
          //  jQuery("#flexi_notification_box").fadeIn("slow");
           // jQuery("[id=wallet_msg]").append(error.message).fadeIn("normal");
            jQuery.toast({
                heading: 'Error',
                text: error.message,
                icon: 'error',
                loader: true,
                loaderBg: '#fff',
                showHideTransition: 'fade',
                hideAfter: 3000,
                allowToastClose: false,
                position: {
                    left: 100,
                    top: 30
                }
            });
            return false
        }
    }
    else
    {
        jQuery("[id=wallet_msg]").empty();
       // jQuery("#flexi_notification_box").fadeIn("slow");
       // jQuery("[id=wallet_msg]").append("Metamask not installed").fadeIn("normal");
        jQuery.toast({
            heading: 'Notice',
            text: 'Metamask not installed',
            icon: 'warning',
            loader: true,
            loaderBg: '#fff',
            showHideTransition: 'fade',
            hideAfter: 3000,
            allowToastClose: false,
            position: {
                left: 100,
                top: 30
            }
        });
    }
}
async function onInit() {
    await window.ethereum.enable();
    const accounts = await window.ethereum.request({
        method: 'eth_requestAccounts'
    });
    const account = accounts[0];
    console.log(account);
    process_login_register(account);
    window.ethereum.on('accountsChanged', function(accounts) {
        // Time to reload your interface with accounts[0]!
        console.log(accounts[0])
    });
}

function create_link_crypto_connect_login(nonce, postid, method, param1, param2, param3) {

    newlink = document.createElement('a');
    newlink.innerHTML = '';
    newlink.setAttribute('id', 'crypto_connect_ajax_process');
    // newlink.setAttribute('class', 'xxx');
    newlink.setAttribute('data-nonce', nonce);
    newlink.setAttribute('data-id', postid);
    newlink.setAttribute('data-method_name', method);
    newlink.setAttribute('data-param1', param1);
    newlink.setAttribute('data-param2', param2);
    newlink.setAttribute('data-param3', param3);
    document.body.appendChild(newlink);
}

function process_login_register(curr_user) {
    //alert("register " + curr_user);
    //Javascript version to check is_user_logged_in()
    if (jQuery('body').hasClass('logged-in')) {
        // console.log("check after login");
        create_link_crypto_connect_login('<?php echo sanitize_key($nonce); ?>', '', 'check', curr_user, '', '');
        //jQuery("#crypto_connect_ajax_process").click();
        setTimeout(function() {
            jQuery('#crypto_connect_ajax_process').trigger('click');
        }, 1000);


    } else {
        // console.log("register new");
        create_link_crypto_connect_login('<?php echo sanitize_key($nonce); ?>', '', 'register', curr_user, '', '');
        //jQuery("#crypto_connect_ajax_process").click();
        setTimeout(function() {
            jQuery('#crypto_connect_ajax_process').trigger('click');
        }, 1000);

    }
}