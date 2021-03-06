<?php
$path = "./";
require_once($path."functions.php");
require_once $path.'db/pdo.php';
require_once $path.'vendor/autoload.php';

$mode = $_REQUEST["mode"];
unset($_REQUEST["mode"]);
unset($_REQUEST["submit"]);

switch($mode) {
    case 'addFeed':
        $feed = new SimplePie();
        $feed->enable_cache(false);
        $feed->set_feed_url($_REQUEST["feed"]);
        $feed->init();
        if($feed->error()) {
            throw new InvalidArgumentException($feed->error());
        }
        $query = $fpdo->insertInto("feeds")->values(["feed"=>$_REQUEST["feed"]]);
        $query->execute();
        break;

    case 'deleteFeed':
        $query = $fpdo->deleteFrom("feeds")->where("ID", $_REQUEST["feedID"]);
        $query->execute();
        break;

    case 'addAggregateFeed':
        $urls = explode("\n", $_REQUEST["feeds"]);
        foreach($urls as $url) {
            $feed = new SimplePie();
            $feed->enable_cache(false);
            $feed->set_feed_url($url);
            $feed->init();
            if($feed->error()) {
                throw new InvalidArgumentException($feed->error());
            }
        }
        $query = $fpdo->insertInto("aggregateFeeds")->values(["feeds"=>$_REQUEST["feeds"]]);
        $query->execute();
        break;


    case 'updateAggregateFeed':
        $urls = explode("\n", $_REQUEST["feeds"]);
        foreach($urls as $url) {
            $feed = new SimplePie();
            $feed->enable_cache(false);
            $feed->set_feed_url($url);
            $feed->init();
            if($feed->error()) {
                throw new InvalidArgumentException($feed->error());
            }
        }
        $id = $_REQUEST["id"];
        $query = $fpdo->update("aggregateFeeds")->set(["feeds"=>$_REQUEST["feeds"]])->where("ID", $id);
        $query->execute();
        break;

    case 'addRegex':
        $regex = "/".$_REQUEST["regex"]."/s";
        if($_REQUEST["caseInsensitive"]) {
            $regex .= "i";
        }
        if(preg_match($regex, "") === false) {
            throw new InvalidArgumentException($regex."<br>".preg_last_error());
        }
        $query = $fpdo->insertInto("filters")->values(["feedID"=>$_REQUEST["feedID"], "field"=>$_REQUEST["field"], "regex"=>$regex]);
        $query->execute();
        break;

    case 'deleteRegex':
        $query = $fpdo->deleteFrom("filters")->where("ID", $_REQUEST["filterID"]);
        $query->execute();
        break;

    case 'setMaxItems':
        $query = $fpdo->update("feeds")->set(["maxItems"=>$_REQUEST["maxItems"]])->where("ID", $_REQUEST["feedID"]);
        $query->execute();
        break;
}

header("location:admin.php");
