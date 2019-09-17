<?php

//$GLOBALS['BE_MOD']['Publication System']['Crossref Fetcher'] = array("callback" => "CrossrefFetcher\CrossrefBackend");
$GLOBALS['BE_MOD']['Publication System']['DOI Merge'] = array("callback" => "CrossrefFetcher\DOIMerge");
$GLOBALS['BE_MOD']['Publication System']['Crossref Keywords'] = array("tables" => array('tl_crossref_keywords'));

$GLOBALS['TL_CRON']['minutely'][] = array("CrossrefFetcher\CrossrefFetcherCron", "fetchCrossref");
