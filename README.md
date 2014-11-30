WikiRandom
==========

###What is WikiRandom?
WikiRandom is simple and easy to use php lib that help you to get a random Wikipedia article and access to some relative raw data.
Actually the lib is very very simple, you can get basic informations.

###How WikiRandom catch Wikipedia Articles?
WikiRandom use Wikimedia API, you can read more at this link: [Wikimedia API](http://www.mediawiki.org/wiki/API:Main_page)

###Wow, what is useful for?
I use this library for [randpedia](http://www.twitter.com/randpedia), a twitter account that every day tweet (automatically) a random article.

###Usage example:
````php
$wr = new WikiRandom("it");
Instance a new WikiRandom Object, this automatically get a random article

$wr->getApiLanguage();
it

$wr->getId();
812464

$wr->getTitle();
5321 Jagras

$wr->getLink();
http://it.wikipedia.org/wiki/5321_Jagras

$wr->getFirstSentence();
5321 Jagras è un asteroide della fascia principale.

$wr->getNChar(25);
5321 Jagras è un asteroide...

$wr->getCategoriesRelated();
array(3) { 
      [0]=> string(42) "Asteroidi della fascia principale centrale" 
      [1]=> string(31) "Corpi celesti scoperti nel 1985" 
      [2]=> string(16) "Stub - asteroidi" 
}

$wr->getOtherLangLinks();

array(10) 
      { [0]=> array(3) 
        { ["lang"]=> string(2) "en" 
          ["url"]=> string(40) "http://en.wikipedia.org/wiki/5321_Jagras" 
          ["*"]=> string(11) "5321 Jagras" } 
        [1]=> array(3) { 
          ["lang"]=> string(2) "eo" 
          ["url"]=> string(41) "http://eo.wikipedia.org/wiki/5321_Jagraso" 
          ["*"]=> string(12) "5321 Jagraso" } 
        [2]=> array(3) { 
          ["lang"]=> string(2) "fa" 
          ["url"]=> string(84) "http://fa.wikipedia.org/wiki/%D8%B3%DB%8C%D8%A7%D8%B1%DA%A9_%DB%B5%DB%B3%DB%B2%DB%B1"               ["*"]=> string(19) "سیا" }
        [3]=> array(3) { 
          ["lang"]=> string(2) "hu" 
          ["url"]=> string(40) "http://hu.wikipedia.org/wiki/5321_Jagras" 
          ["*"]=> string(11) "5321 Jagras" } 
        [4]=> array(3) { 
          ["lang"]=> string(2) "hy" 
          ["url"]=> string(72) "http://hy.wikipedia.org/wiki/(5321)_%D5%8B%D5%A1%D5%A3%D6%80%D5%A1%D5%BD" 
          ["*"]=> string(19) "(5321) Ջագրաս" } 
        [5]=> array(3) { 
          ["lang"]=> string(2) "la" 
          ["url"]=> string(40) "http://la.wikipedia.org/wiki/5321_Jagras" 
          ["*"]=> string(11) "5321 Jagras" } 
        [6]=> array(3) { 
          ["lang"]=> string(2) "oc" 
          ["url"]=> string(40) "http://oc.wikipedia.org/wiki/5321_Jagras" 
          ["*"]=> string(11) "5321 Jagras" } 
        [7]=> array(3) { 
          ["lang"]=> string(2) "pl" 
          ["url"]=> string(42) "http://pl.wikipedia.org/wiki/(5321)_Jagras" 
          ["*"]=> string(13) "(5321) Jagras" } 
        [8]=> array(3) { 
          ["lang"]=> string(2) "pt" 
          ["url"]=> string(40) "http://pt.wikipedia.org/wiki/5321_Jagras" 
          ["*"]=> string(11) "5321 Jagras" } 
        [9]=> array(3) { 
          ["lang"]=> string(2) "uk" 
          ["url"]=> string(64) "http://uk.wikipedia.org/wiki/5321_%D0%AF%D2%91%D1%80%D0%B0%D1%81" 
          ["*"]=> string(15) "5321 Яґрас" 
        } 
}

$wr->getPlainTextArticle();
5321 Jagras è un asteroide della fascia principale. Scoperto nel 1985, presenta un'orbita caratterizzata da un semiasse maggiore pari a 2,5810209 UA e da un'eccentricità di 0,2213576, inclinata di 13,58746° rispetto all'eclittica. Collegamenti esterni (EN) Jagras - Dati riportati nel database dell'IAU Minor Planet Center (EN) Jagras - Dati riportati nel Jet Propulsion Laboratory - Small-Body Database

$wr->getArticleImages();
array(2) { 
      [0]=> string(63) "http://upload.wikimedia.org/wikipedia/commons/8/83/Celestia.png" 
      [1]=> string(76) "http://upload.wikimedia.org/wikipedia/commons/9/9a/Galileo_Gaspra_Mosaic.jpg" 
}
```
###Bulk usage example:
Previous example is bad in situations, when you need to get more than one random article. And if you are going to get all properties it is becoming worse because of many requests to API (every call make one request).

If you need to get just a couple properties, but load more than one article, you can use this syntax:
````php
$wr = new WikiRandom("ru", 5);
$article_ids = $wr->getIds();
$titles = $wr->getTitle();

or the same:

$wr = new WikiRandom("ru", false);
$article_ids = $wr->getNewRandomArticle(5);
$titles = $wr->getTitle();
```

Or you can use more optimized function, but with limited properties:
````php
$wr = new WikiRandom("ru", false);
$data = $wr->getBulkData(5, 5, 0, true);
// requests: 1 + 5 (list and images for every page)

Array
(
    [462957] => Array
        (
            [page_id] => 462957
            [title] => Краснер
            [length] => 229
            [url] => http://ru.wikipedia.org/wiki/%D0%9A%D1%80%D0%B0%D1%81%D0%BD%D0%B5%D1%80
            [text] => <p><b>Краснер</b> — фамилия.</p>
            [images] => Array
                (
                )

        )

    [14836] => Array
        (
            [page_id] => 14836
            [title] => Выбор России (фракция)
            [length] => 27708
            [url] => http://ru.wikipedia.org/wiki/%D0%92%D1%8B%D0%B1%D0%BE%D1%80_%D0%A0%D0%BE%D1%81%D1%81%D0%B8%D0%B8_(%D1%84%D1%80%D0%B0%D0%BA%D1%86%D0%B8%D1%8F)
            [text] =>
            [images] => Array
                (
                )

        )

)

```