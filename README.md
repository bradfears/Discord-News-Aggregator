# Discord-News-Aggregator
PHP script (to be used with cron) for posting news stories to a Discord channel.

## Requirements
1. Discord server (you can create your own)
2. Web host with PHP cli cron support
3. Knowledge of PHP

## Preparation

1. Edit your php.ini file  
  a. enable _allow_url_fopen_  
  b. uncomment _extension=curl_  
  c. uncomment _extension=openssl_  
2. Enable Discord server webhooks  
  a. Right-click on your server in Discord and go to _Server Settings_ -> _Integrations_  
  b. Under Webhooks, create a new webhook (it will default to "Captain Hook")  
  c. Click the _Copy Webhook URL_ button (you will need to add this url to your code)  
3. Get your channel ID  
  a. Click the _User Settings_ link next to your name in Discord (lower left)  
  b. Scroll down and click _Advanced_  
  c. Toggle _Developer Mode_  
  d. Hit Esc and go back to your list of channels  
  e. Right-click on your desired channel and click _Copy ID_ (you will need to add this to your code)  
4. Get your Discord authorization token by following the instructions on [this page](https://linuxhint.com/get-discord-token/)  
  a. You will need to add the authorization token to your code  
5. Open webhook.php and change the first three variables ($channel_id, $webhook_url, and $auth_code)  
  a. You should already have these values from previous preparation steps
  
## Test the code
1. Run the webhook.php from the command line or terminal
2. This should post a new message to the channel
3. Run the webhook.php file again and make sure you do not see duplicate news stories

## Schedule a job to run the script
1. Using cron (provided by your web host), create a job similar to the following  
`*/10  *  *  *  *  /usr/local/bin/php /path/to/webhook.php >/dev/null 2>&1`
2. **Important Note:** Schedule the jobs to run at least 10 minutes apart so new stories will create a new thread in the channel
  a. Otherwise, Discord will just treat each news story like a reply to the previous story
