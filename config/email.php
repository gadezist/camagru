<?php

$encoding = "utf-8";
$from_name = 'Andrii';
$from_mail = 'admin_camagru@ukr.net';
$mail_message = "Go to URL for registration";


$subject_preferences = array(
	"input-charset" => $encoding,
	"output-charset" => $encoding,
	"line-length" => 76,
	"line-break-chars" => "\r\n"
);

$header = "Content-type: text/html; charset=".$encoding."\r\n";
$header .= "From: ".$from_name." <".$from_mail."> \r\n";
$header .= "MIME-Version: 1.0 \r\n";
$header .= "Content-Transfer-Encoding: 8bit \r\n";
$header .= iconv_mime_encode("Subject", $mail_message, $subject_preferences);

return $header;