# Crossref Fetcher / DOIMerge
This extension adds a CrossrefFetcher as Cronjob and adds a BackendModule, where existing publications can be enhanced
 with additional information retrieved via their [DOI](https://en.wikipedia.org/wiki/Digital_object_identifier).
 
## Fetcher
The Fetcher module adds a database called `tl_crossref_keywords` with the keywords that should be searched. 
It is always the entry selected, where `last_finished` does not exist or is the oldest.

The Cronjob `CrossrefFetcherCron` runs every minute and fetches some entries. It uses the class `CrossrefAPI` which uses the official, open [Crossref API](https://github.com/CrossRef/rest-api-doc/blob/master/rest_api.md).
To decide, if a publication is relevant for us, it uses a Class that implements the `PublicationDecider` interface, currently `CfaedPublicationDecider` which just checks if some terms appear.
If there is a positive decision, it is added to the Publication Matcher Lifecycle as explained in the Publication Module.

## DOIMerge
DOIMerge adds a database called `tl_doi_merge` as a log about merged bibtexs. It retrieves the Bibtex directly from the publisher via DOI as explained on [Stackoverflow](http://stackoverflow.com/questions/10507049/get-metadata-from-doi).

It provides a BackendModule (`DOIMerge.php`), where unmerged publications are shown and a computed, "merged" Bibtex is provided.