=== Crypto ===
Contributors: odude
Donate link: https://w3d.name/
Tags: crypto, login, metamask, NFT, Blockchain, Token
Requires at least: 3.0.1
Requires PHP: 5.5
Tested up to: 6.1.1
Stable tag: 2.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Cryptocurrency wallet login, donation, price display, content restriction, and more.

== Description ==

Enable your users to log in via Metamask.
Automatic registration eliminates the need for remembering passwords for the website.

**[crypto-connect]** -  Use this shortcode on any of your pages to add a login button that connects to Metamask, allowing users to register on the site without any additional steps.
**Secure** - All transactions on your site have no connection with our server and are solely dependent on your server. If you are using any server's API, it only facilitates the connection of the wallet but has no control over transactions.

== Sign in/Register using a cryptocurrency wallet ==

example: `[crypto-connect label="Connect Wallet" class="fl-button fl-is-info fl-is-light"]`


== Donation Widget ==

* Receive cryptocurrency donations in your preferred wallet.
* The option to set a fixed amount in a specific token is available.

== Cryptocurrency or Token Price ==

* Display the latest price of a token in the selected currency.
* Use shortcode to add it to your website.
* Show multiple token prices at once.
* Use a caching system to limit API calls.
* Data is obtained from CoinMarketCap's free API
* Example shortcode: `[crypto-price symbol="MATIC,BTC,ETH" style="style1" currency="USD"]`

== Limit Access to Content/Page ==
​

* Show or hide content based on the availability of a specific Web3Domain.
* Limit access to a full specific page.
* Limit access to certain parts of the content using shortcode.
* Example shortcode: `[crypto-block] Private article [/crypto-block]`

= Option 1: Restrict by Web3Domain =
* Users must have a specific NFT web3domain name from web3domain.org in their wallet.
* Use the shortcode `[crypto-access-domain]` to limit access to a page.

= Option 2: Restrict by NFT or Cryptocurrency =
* Users must have a specific NFT and/or number of tokens in their wallet.
* Select the network chain (Ethereum Mainnet, Binance BNB Chain, Polygon Chain)
* Compatible with any smart contract address.
* Use the shortcode [crypto-access-nft] to limit access to a page.

== Marketplace of Web3Domain ==
             
Become a Web3Domain provider on your own website by selling subdomains. We will integrate the domain into our ecosystem, providing every domain user access to all of the features that Web3Domain offers.

* It is best option to earn for membership by letting user to obtain subdomain of your web3 primary domain.
* You earn as soon as domain minted.
* You can set the price for your subdomain yourself.
* You can also restrict not to be minted by public. Only you can mint it and transfer. Hence you can save commission fees too. 
* All Web3Domains are NFTs. Which you can sell at opensea.io


[Live Demo](https://web3domain.org/user-dashboard/)

> If any suggestion, contact at admin@web3domain.org

[GitHub](https://github.com/web3yak/crypto/)

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `crypto.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use shortcode [crypto-connect]

== Frequently Asked Questions ==

= What is Metamask? =

MetaMask is a software cryptocurrency wallet used to interact with the Ethereum blockchain. It allows users to access their Ethereum wallet through a browser extension or mobile app, which can then be used to interact with decentralized applications.

= What is Web3Domain? =

Web3Domain is a platform that allows you to register and sell your own web3 domain names, which are minted on a blockchain network. These domain names can be used to create subdomains, which can be sold to visitors. The Web3Domain platform also provides features such as connecting to a crypto wallet, automatic login, and the ability to earn money by selling subdomains. Additionally, all Web3Domains are Non-Fungible Tokens (NFTs) which can be sold on platforms such as opensea.io.

== Screenshots ==

1. Simple Login Interface with multiple wallet
2. Donation Widget
3. Crypto Price

== Changelog ==

= 2.2 = 
Updated languages

= 2.1 =
All starts from scratch

== Upgrade Notice ==

= 2.0 =
Web3Connect has been removed and now only Metamask is supported. You need to save configuration again. 