<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @package   CrossrefFetcher
 * @author    Sönke Huster &#40;me@eknoes.de&#41;
 * @license   MIT
 * @copyright cfaed 2017
 */


/**
 * Namespace
 */

    namespace Eknoes\ContaoPublicationsBibtexMerge\Controller;

use Contao\BackendModule;
use Contao\BackendTemplate;
use Contao\Input;
use Eknoes\ContaoPublications\Utils\BibtexParser;
use Eknoes\ContaoPublications\Utils\BibtexWriter;
use Eknoes\ContaoPublications\Utils\PublicationsModifier;
use Exception;


/**
 * Class CrossrefBackend
 *
 * @copyright  cfaed 2017
 * @author     Sönke Huster &#40;me@eknoes.de&#41;
 * @package    Devtools
 */
class DOIMerge extends BackendModule
{

    protected $strTemplate = "doi_merge";

    private function checkInput() {
        if(empty(Input::post("submit")) || empty(Input::post("publicationId"))) {
            return;
        }
        $modifier = new PublicationsModifier();
        $this->Database->prepare("INSERT INTO tl_doi_merge (tstamp, publication_id, last_merge, old_bib) VALUES (now(), ?, now(), ?)")->execute(Input::post("publicationId"), html_entity_decode(Input::post("current_bib")));
        $this->Database->prepare("UPDATE tl_publications SET bibtex=? WHERE id=?")->execute(html_entity_decode(Input::post("bibtex")), Input::post("publicationId"));
        $modifier->autogenerateFields(Input::post("publicationId"), html_entity_decode(Input::post("bibtex")));
    }

    /**
     * Compile the current element
     */
    protected function compile()
    {
        $this->checkInput();

        $template = new BackendTemplate($this->strTemplate);

        $query = $this->Database->query("SELECT tl_publications.id, bibtex, tl_doi_merge.last_merge FROM tl_publications LEFT JOIN tl_doi_merge ON tl_publications.id = tl_doi_merge.publication_id WHERE bibtex LIKE '%doi=%' ORDER BY tl_doi_merge.last_merge LIMIT 1");
        $pubs = [];

        while($query->next()) {
            $current = BibtexParser::parse_string($query->bibtex)[0];

            if(!key_exists("doi", $current)) {
                $pubs[] = ["no doi", $query->bibtex];
                continue;
            }

            try {
                $doiBib = BibtexParser::parse_string(PublicationsModifier::fetchBibtex($current['doi']))[0];
            } catch (Exception $exception) {
                $this->log("Could not fetch Bibtex from DOI: " . $exception->getMessage(), "compile", TL_GENERAL);
                continue;
            }
            ksort($doiBib);
            ksort($current);
            $pubs[] = ["doi" => BibtexWriter::writeBibtex($doiBib), "current" => BibtexWriter::writeBibtex($current), "computed" => $this->computeMergedBib($current, $doiBib), "pubId" => $query->id];
        }

        $template->pubs = $pubs;

        $this->Template = $template;
        $this->Template->action = $this->Environment->request;
        $this->Template->token = REQUEST_TOKEN;


    }

    /**
     * Adds New Elements to existing bibtex, marks new and changed keys
     * @param $current Bibtex Array, where new keys should be added
     * @param $new Bibtex Array which could hold additional information
     * @return string HTML-Computed Bibtex
     */
    public function computeMergedBib($current, $new)
    {
        if(empty($new)) {
            return $current;
        }
        $computed = array_merge(array_diff_key($new, $current), $current);
        ksort($computed);
        $raw = "@" . $computed['type'] . "{" . $computed['reference'] . ",\n";
        foreach ($computed as $key => $value) {
            if ($key == "raw" || $key == "type" || $key == "reference" || $key == "lines") {
                continue;
            }
            if(!key_exists($key, $current)) {
                $raw .= "<span class=\"new-key\">";
            } else if (key_exists($key, $new) && $current[$key] != $new[$key]) {
                $raw .= "<span class=\"different-key\">";
            }

            if (is_array($value)) {
                $raw .= $key . "={" . implode(" and ", $value) . "}\n";
            } else {
                $raw .= $key . "={" . $value . "},\n";
            }

            if(!key_exists($key, $current) || key_exists($key, $new) && $current[$key] != $new[$key]) {
                $raw .= "</span>";
            }

        }
        $raw .= "}\n";
        return $raw;
    }

}

?>