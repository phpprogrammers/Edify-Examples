<?php

// get the time right now
$now = new Datetime();


// the next line outputs audit information to the error_log.
\Edify\Utils\Log::setDebugLevel("ALL");
// you can add a comma seperated list
// of modules to only show those ones
// for example "Example1,Edify\Utils\Loader,Edify\Cache\Factory"
\Edify\Utils\Log::debugLog("[Example1]", "\n\n\n\n\n\n\n\n\n
-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
Running Exmaple 1 at " . $now->format("H:i:s") . "
-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-");

//  Display the home page of the site.

$cacheDynamicCTRL = new \Edify\Cache\Factory(\Edify\Cache\Factory::DYNAMIC, "../cache", "EN");
$cacheStaticsCTRL = new \Edify\Cache\Factory(\Edify\Cache\Factory::STATICS, "../cache", "EN");

$cacheBuffer = $cacheDynamicCTRL->load(
    Array(
        "url" => "index.html",
        "age" => 300
    )
);

if (is_null($cacheBuffer)) {
    \Edify\Utils\Log::debugLog("[Example1]", "\n\n
-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
Cached Version not found or too old
-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-");
    $staticPartsOfThePage = $cacheStaticsCTRL->load(
        Array(
            "list" => Array(
                "layout.html",
                "header.html",
                "leftnav.html",
                "farright.html"
            )
        )
    );
    // Since I developed this I know that there will be specific cached content
    // retrieved from the cache.  I know for example that the buffer holding
    // layout.html actually specifys where the other buffers go.

    $cacheBuffer = $staticPartsOfThePage["layout.html"];
    foreach ($staticPartsOfThePage as $key => $buffer) {
        if ($key != "layout.html") {
            if (is_null($buffer)) {
                $cacheBuffer = str_replace("[[[$key]]]", "", $cacheBuffer);
            } else {
                $cacheBuffer = str_replace("[[[$key]]]", $buffer, $cacheBuffer);
            }
        }
    }


    // I want to also change a variable that tells us when this was cached
    $cacheBuffer = str_replace("[TIMESTAMP]", $now->format("H:i:s"), $cacheBuffer);

    // save the cache the file extension doesnt matter as long as it is the same
    // as the Load variable above.
    $cacheDynamicCTRL->save(Array("url" => "index.html", "buffer" => $cacheBuffer));
} else {

    \Edify\Utils\Log::debugLog("[Example1]", "\n\n
-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
Cached Version young enough to use
-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-");
}
// this allows you to see that the cache actually works as on quick reloads the
// cache will have the previous name.  Wait five minutes and it will change.
print str_replace(Array("[YOURNAME]", "[CURRENT_TIME]"), Array((isset($_GET["name"]) ? $_GET["name"] : "IrishAdo"), $now->format("H:i:s")), $cacheBuffer);
?>
