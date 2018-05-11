<?php
/**
 * Created by PhpStorm.
 * User: spac3-monkey
 * Date: 10/6/15
 * Time: 12:58 PM
 */

$config['mandrill_mail_data'] = array(
    'protocol' => 'smtp',
    'smtp_host' => 'smtp.mandrillapp.com',
    'smtp_user' => 'DealPlay.Purchase@gmail.com',
    'smtp_pass' => 'Xp5FYoA4tMJqZhe7QQe3xw',
    'smtp_port' => 587,
    'mailtype' => 'html',
    'crlf' => "rn",
    'charset' => 'utf-8', // or iso-8859-1
    'newline' => "rn"
);

$config['mandrill_check_mail'] = "https://mandrillapp.com/api/1.0/messages/info.json";
$config['mandrill_api_key'] = "Xp5FYoA4tMJqZhe7QQe3xw";

$config['mandrill_api_base'] = "https://mandrillapp.com/api/1.0/";

$config['unsubscribe_url'] = 'contacts/(:num)/unsubscriptions';

$config['mandrill_unsub'] = "<br /><a href=\"*|UNSUB:[unsubscribe_url]|*\">Click here if you want to stop receiving these emails.</a>";

$config['mandrill_endpoints'] = array(
    'ping'      => "users/ping2.json",
    'user_info' => "users/info.json",
    'mail_info' => "messages/info.json"
);


/**
 * Post body for message check
 *
 * {
 *   "id": "ea022f6165824a7cac56e21d90090bd7",
 *   "key": "Xp5FYoA4tMJqZhe7QQe3xw"
 * }
 */