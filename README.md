googleparser
=====================

Google Search Results Parser for PHP


How to use?
---------

```PHP
/*
* Require simple html dom parser
* Download from http://sourceforge.net/projects/simplehtmldom/
*/
require_once 'simple_html_dom.php';

$gp = new GoogleParser();

$results = $gp->run('pozycjonowanie'); //results from 0-10
var_dump($results);

//set offset
$results = $gp->run('pozycjonowanie', 1); //results from 10-20

var_dump($results);
```
