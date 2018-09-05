<?php

/*
 * SendPulse REST API Usage Example
 *
 * Documentation
 * https://login.sendpulse.com/manual/rest-api/
 * https://sendpulse.com/api
 *
 * Settings
 * https://login.sendpulse.com/settings/#api
 */


require("src/ApiInterface.php");
require("src/ApiClient.php");
require("src/Storage/TokenStorageInterface.php");
require("src/Storage/FileStorage.php");
require("src/Storage/SessionStorage.php");
require("src/Storage/MemcachedStorage.php");
require("src/Storage/MemcacheStorage.php");

use Sendpulse\RestApi\ApiClient;
use Sendpulse\RestApi\Storage\FileStorage;

define('API_USER_ID', 'ea3e1fc5ea013c5eb8be57c2a72ae5b5');
define('API_SECRET', 'e1ef536c867bf346d087688cf9895a3c');
define('PATH_TO_ATTACH_FILE', __FILE__);

$SPApiClient = new ApiClient(API_USER_ID, API_SECRET, new FileStorage());

/*
 * Example: Add new email to mailing lists
 */
 $bookID = 1755360;
 $emails = array(
    array(
        'email' => 'wainstan@ya.ru',
        'variables' => array(
            'phone' => '+79633507266',
            'name' => 'User Name',
        )
    )
);
var_dump($SPApiClient->addEmails($bookID, $emails));

// Get Mailing Lists list example
//var_dump($SPApiClient->listAddressBooks());

// Send mail using SMTP
$email = array(
    'html' => '<p>Hello!</p>',
    'text' => 'text',
    'subject' => 'Mail subject',
    'from' => array(
        'name' => 'John',
        'email' => 'John@domain.com',
    ),
    'to' => array(
        array(
            'name' => 'Client',
            'email' => 'client@domain.com',
        ),
    ),
    'bcc' => array(
        array(
            'name' => 'Manager',
            'email' => 'manager@domain.com',
        ),
    ),
    'attachments' => array(
        'file.txt' => file_get_contents(PATH_TO_ATTACH_FILE),
    ),
);
//var_dump($SPApiClient->smtpSendMail($email));


/*
 * Example: create new push
 */

$task = array(
    'title' => 'Hello!',
    'body' => 'This is my first push message',
    'website_id' => 1,
    'ttl' => 20,
    'stretch_time' => 10,
);
// This is optional
$additionalParams = array(
    'link' => 'http://yoursite.com',
    'filter_browsers' => 'Chrome,Safari',
    'filter_lang' => 'en',
    'filter' => '{"variable_name":"some","operator":"or","conditions":[{"condition":"likewith","value":"a"},{"condition":"notequal","value":"b"}]}',
);
//var_dump($SPApiClient->createPushTask($task, $additionalParams));
