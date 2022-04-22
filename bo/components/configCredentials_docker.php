<?php
namespace bo\components;

/*
 *  MYSQL Datenbank 
 */
define("SQL_HOST",'mysql:host=db;dbname=' . getenv('MARIADB_DATABASE'));
define("SQL_PW",getenv('MARIADB_PASSWORD'));
define("SQL_USER",getenv('MARIADB_USER'));

/*
 *  Email Konto
 */
define("SMTP_HOST",'mail');
define("SMTP_USER",'XXX');
define("SMTP_SECRET",'XXX');
define("SMTP_SENDER",'localhost');
define("SMTP_SENDER_ADRESS",'noreply@example.localhost');
define("SMTP_NO_TLS",true); // DEAKTIVIERT TLS!

/*
 *  Telegram Bot
 */
define("TELEGRAM_TOKEN",'XXX');

/*
 *  ÖZG
 */
define("OZG_TOKEN",'XXX');
?>