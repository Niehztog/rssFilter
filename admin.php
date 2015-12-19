<?php
$path = "./";
require_once($path."functions.php");
require_once $path.'db/pdo.php';
require_once $path.'vendor/autoload.php';

$fields = ["title", "summary", "category", "contributor", "author", "content", "url"];

$feeds = $fpdo->from("feeds")->fetchAll();

$aggregates = $fpdo->from("aggregateFeeds")->fetchAll();

foreach($feeds as &$feed) {
    $feed["patterns"] = $fpdo->from("filters")->where("feedID", $feed["ID"])->fetchAll();
}

//DISPLAY
$smarty = new Smarty();
$smarty->auto_literal = true;
$data = new Smarty_Data();
$data->assign("fields", $fields);
$data->assign("feeds", $feeds);
$data->assign("aggregates", $aggregates);
$data->assign("base_url", "//".$_SERVER['HTTP_HOST'].substr($_SERVER['REQUEST_URI'],0,strrpos($_SERVER['REQUEST_URI'],"/")));
$smarty->display("admin.tpl", $data);
