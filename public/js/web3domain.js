async function w3d_getDomain_byID(id,web3)
{ 
    const ABI_ID = [
    {
        "inputs": [
          {
            "internalType": "uint256",
            "name": "tokenId",
            "type": "uint256"
          }
        ],
        "name": "titleOf",
        "outputs": [
          {
            "internalType": "string",
            "name": "",
            "type": "string"
          }
        ],
        "stateMutability": "view",
        "type": "function",
        "constant": true
      }
  ];
    const contractAddress = '0x3bA26d4d5250E82936F281805423A1ABEaEfC3B5';
    const myContract = new web3.eth.Contract(ABI_ID, contractAddress);

    myContract.methods.titleOf(id).call().then(function(domain) {

       console.log(domain);

    });
   
}