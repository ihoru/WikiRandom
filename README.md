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

$wr->getArticleTitle();
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
