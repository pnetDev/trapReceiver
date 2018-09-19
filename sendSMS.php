 #!/usr/bin/php
<?php
// CM 180820 This is a PHP script which is called by /opt/parseTrap.sh and does the following.
// Sends a text message to each number in "/opt/sendModeAPI/numbers.txt" using sendmode.com API
// The SMS text is passed to this script from bash.

$message = $argv[1];
print "$message\n";
$myfile = fopen("/var/log/snmptrapd.Log1", "a") or die("Unable to open file!");
fwrite($myfile, "PHP SMS TEXT,");
fwrite($myfile, $message);
fclose($myfile);

$currTime = date('Y-m-d H:i:s');
$logFile = '/var/log/sms.log';
$sms_username = "c.maverley@permanet.ie";
$sms_password = "perma0108";
$senderid = "permaNET.ie";
// $message = file_get_contents("message.txt");
$message = str_replace("\r", "\n", $message);

// CM Iterate through $numbers
$handle = fopen("/opt/sendModeAPI/numbers.txt", "r");
if ($handle) {
        while (($mobilenumber = fgets($handle)) !== false) {
                $mobilenumber = trim(preg_replace('/\s\s+/', ' ', $mobilenumber));   // Removes \n from the string
                //echo json_encode(htmlspecialchars($mobilenumber));
                $logMessage = $currTime . "\t" . $mobilenumber . "\t" . $message;
                echo $logMessage;

                // CM This code was supplied by sendmode.com
                $url = "https://api.sendmode.com/httppost.aspx?Type=sendparam&username=".urlencode($sms_username)."&password=".urlencode($sms_password)."&numto=".urlencode($mobilenumber)."&data1=".urlencode($message)."&senderid=".urlencode($senderid);
                //pr($url) ; die;
                $reply = file_get_contents($url);
                $reply_data = simplexml_load_string($reply);
                $val=$reply_data->call_result->result;
                //echo $val;
                // CM End of code supplied by sendmode.com

                // Write to log
                echo file_put_contents("/var/log/sms.log",$logMessage,FILE_APPEND);
        }
        fclose($handle);
} else {
    // error opening the file.
}
?>
