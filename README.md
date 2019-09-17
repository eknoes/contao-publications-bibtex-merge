# DOIMerge
This extension adds a BackendModule, where existing publications can be enhanced
 with additional information retrieved via their [DOI](https://en.wikipedia.org/wiki/Digital_object_identifier).
 
## DOIMerge
DOIMerge adds a database called `tl_doi_merge` as a log about merged bibtexs. It retrieves the Bibtex directly from the publisher via DOI as explained on [Stackoverflow](http://stackoverflow.com/questions/10507049/get-metadata-from-doi).

It provides a BackendModule (`DOIMerge.php`), where unmerged publications are shown and a computed, "merged" Bibtex is provided.