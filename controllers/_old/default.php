<?
if(!defined('ALLOW_RUN')) { // ��������� ������ ��������� � �����
  header('Location: /'); 
  exit();
}

$tpl = new Templator("home.html");
$tpl->run();