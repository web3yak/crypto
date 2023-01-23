(function ($) {
  "use strict";

  
})(jQuery);

function crypto_wallet_short(str, keep) {
  var len = str.length,
    re = new RegExp(
      "(.{" + keep + "})(.{" + (len - keep * 2) + "})(.{" + keep + "})",
      "g"
    );
  // console.log(re)
  return str.replace(re, function (match, a, b, c) {
    var xx = a + ("" + b).replace(/./g, "*") + c;
    return xx.replace("**********************************", "***");
  });
}

/** add a parameter at the end of the URL. Manage '?'/'&', but not the existing parameters.
 *  does escape the value (but not the key)
 */

const crypto_uniqueId = (length = 16) => {
  return parseInt(
    Math.ceil(Math.random() * Date.now())
      .toPrecision(length)
      .toString()
      .replace(".", "")
  );
};

function crypto_is_valid_domain_name(username) {
  var count = (username.match(/\./g) || []).length;

  if (count > 1) return false;

  if (
    username.match(/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i) &&
    username.match(/^.{1,253}$/) &&
    username.match(/^[^\.]{1,63}(\.[^\.]{1,63})*$/)
  ) {
    return true;
  } else {
    return false;
  }
}

const crypto_plugin_url = crypto_connectChainAjax.crypto_plugin_url;
const contractAbi = crypto_plugin_url + "/public/js/web3domain.json?p0"; // Update with an ABI file, for example "./sampleAbi.json"
const contractAddress = crypto_connectChainAjax.crypto_contract;

var crypto_network_arr = new Array(); // OR var  arr  = [];
crypto_network_arr["137"] = "Polygon - MATIC";
crypto_network_arr["19"] = "Filecoin - tFIL";
crypto_network_arr["0"] = "Global - EVM";
crypto_network_arr["80001"] = "Mumbai - Testnet";

let web3; // Web3 instance
let contract; // Contract instance
let account; // Your account as will be reported by Metamask
