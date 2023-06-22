async function crypto_is_metamask_Connected() {
  var result = new Array();


  if (typeof ethereum == 'undefined') {
    alert("MetaMask is not installed");
    return result;
  }
  const accounts = await ethereum.request({
    method: "eth_accounts",
  });
  const networkId = await ethereum.request({
    method: "net_version",
  });

  if (accounts.length) {
    // console.log(`Connected to: ${accounts[0]}`);
    result["addr"] = accounts[0];
    result["network"] = networkId;
  } else {
    result["addr"] = "";
  }

  return result;
}

function crypto_init() {
  // Create a Web3 instance
  web3 = new Web3(window.ethereum);
  connectWallet();
  connectContract(contractAbi, contractAddress);
  //getId('web3');
}

// Connect to the MetaMast wallet
const connectWallet = async () => {
  const accounts = await ethereum.request({ method: "eth_requestAccounts" });
  account = accounts[0];
 // console.log(`Connected account...........: ${account}`);
  // getBalance(account);
  //getId('web3');
};

// Helper function to get JSON (in order to read ABI in our case)
const getJson = async (path) => {
  const response = await fetch(path);
  const data = await response.json();
  return data;
};

// Connect to the contract
const connectContract = async (contractAbi, contractAddress) => {
  const data = await getJson(contractAbi);
  const contractABI = data.abi;
  contract = new web3.eth.Contract(contractABI, contractAddress);
  // return contract;
  //console.log(contractAddress);
};

// Get Balance of ether
const getBalance = async (address) => {
  //  printResult(`getBalance() requested.`);
  const balance = await web3.eth.getBalance(address);
 // console.log("Token balance: " + web3.utils.fromWei(balance));
  // balanceOf(address);
  //printResult(`Account ${readableAddress(account)} has ${web3.utils.fromWei(balance)} currency`);
};

//Get ID of the domain
const getId = async (name) => {
  try {
    const did = await contract.methods.getID(name).call();
    //console.log("Domain: " + name + " - ID: " + did);
    return did;
  } catch (error) {
    console.log(error.message);
  }
};

//Get title of a domain with ID
const titleOf = async (id) => {
  try {
    const did = await contract.methods.titleOf(id).call();
   // console.log("Title of " + id + " - Domain: " + did);
    return did;
  } catch (error) {
    console.log(error.message);
  }
};

//Get title of a domain with ID
const getOwner = async (id) => {
  try {
    const did = await contract.methods.getOwner(id).call();
    //console.log("Owner of " + id + " - address: " + did);
    return did;
  } catch (error) {
    console.log(error.message);
  }
};

//get Web3Domain balance of user
const balanceOf = async (address) => {
  //console.log("Counting NFTs");
  try {
    const did = await contract.methods.balanceOf(address).call();
    console.log("Total NFTs: " + did);
    return did;
  } catch (error) {
    console.log(error.message);
  }
};

const claim = async (id, title, uri, to,amount) => {
  try {
    const transferAmount = web3.utils.toWei(amount,"ether"); // This is a necessary conversion, contract methods use Wei, we want a readable
console.log(transferAmount);
    const result = await contract.methods
      .claim(id, title, uri, to)
      .send({ from: account, value: transferAmount, maxPriorityFeePerGas: null,  maxFeePerGas: null,  });
   // console.log("Domain: " + title + " -- " + result.status);
    return result.status;
  } catch (error) {
    console.log(error.message);
    return error.message;
  }
};

const setNftPrice = async (price) => {
  console.log("Set NFT Price executed");
  try {
    const result = await contract.methods
      .setNftPrice(price)
      .send({ from: account });
    console.log("setNftPrice : " + result.status);
    console.log(result);
  } catch (error) {
    console.log(error.message);
  }
};

const transferFrom = async (to, id) => {
  console.log("Transfer processing...");
  try {
    const result = await contract.methods
      .transferFrom(account, to, id)
      .send({ from: account, maxPriorityFeePerGas: null,  maxFeePerGas: null });
    console.log("transferFrom : " + result.status);
    return true;
  } catch (error) {
    console.log(error.message);
    return error.message;
  }
};

const setTokenURI = async (id,url) => {
  console.log("Set Token URI");
  var fee = '0.09';
  try {
    const result = await contract.methods
      .setTokenURI(id,url)
      .send({ from: account, value: Web3.utils.toWei(fee, 'ether'), maxPriorityFeePerGas: null,  maxFeePerGas: null, });
    console.log(result);
    return result.status;
  } catch (error) {
    console.log(error.message);
    return error.message;
  }
};

const setAllow = async (id,price) => {
  console.log("Set domain ID:"+id+" Price: "+price);
  try {
    const result = await contract.methods
      .setAllow(id,price)
      .send({ from: account,maxPriorityFeePerGas: null,  maxFeePerGas: null, });
    console.log(result);
    return result.status;
  } catch (error) {
    console.log(error.message);
  }
};

const setReverse = async (id) => {
  console.log("Set reverse ID:"+id);
  try {
    const result = await contract.methods
      .setReverse(id)
      .send({ from: account,maxPriorityFeePerGas: null,  maxFeePerGas: null, });
    console.log(result);
    return result.status;
  } catch (error) {
    console.log(error.message);
  }
};

const getReverse = async (addr) => {
  try {
    const did = await contract.methods.getReverse(addr).call();
    console.log("Reverse address to " + did);
    return did;
  } catch (error) {
    console.log(error.message);
  }
};

//Get title of a domain with ID
const getAllow = async (id) => {
  try {
    const did = await contract.methods.getAllow(id).call();
    return did;
  } catch (error) {
    console.log(error.message);
  }
};


function crypto_sleep(ms) {
  return new Promise((resolve) => setTimeout(resolve, ms));
}
