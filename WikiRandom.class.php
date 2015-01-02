<?php

class WikiRandom
{
    private $language_supported = array('de', 'en', 'es', 'fr', 'it', 'nl', 'pl', 'ru', 'ceb', 'sv', 'vi', 'war');
    private $base_api;
    private $language;
    private $article_ids = array();

    public function __construct($language, $number = 1)
    {
        $this->setLanguage($language);
        if ($number > 0) {
            $this->getNewRandomArticle($number);
        }
    }

    private function get_list_or_item($data, $field, $i = null, $default = '')
    {
        $i = $i === false || count($this->article_ids) > 1 ? $i : 0;
        if ($i === null || $i === false) {
            $result = array();
            foreach ($data as $page_id => $item) {
                $result[$page_id] = isset($item[$field]) ? $item[$field] : $default;
            }
            return $result;
        }
        return isset($data[$this->article_ids[$i]][$field]) ? $data[$this->article_ids[$i]][$field] : $default;
    }

    public function getApiLanguage()
    {
        return $this->language;
    }

    public function getSupportedLanguages()
    {
        return $this->language_supported;
    }

    public function setLanguage($language)
    {
        if (!in_array($language, $this->getSupportedLanguages())) {
            throw new Exception(sprintf('Language %s is not supported', $language));
        }
        $this->language = $language;
        $this->base_api = sprintf('http://%s.wikipedia.org/w/api.php?format=json&rawcontinue=1&', $language);
        return true;
    }

    public function getNewRandomArticle($number = 1)
    {
        $wiki_api = $this->base_api . 'action=query&list=random&rnnamespace=0&rnlimit=' . $number;
        $json_wapi = json_decode(file_get_contents($wiki_api), true);
        $this->article_ids = array();
        foreach ($json_wapi['query']['random'] as $item) {
            $this->article_ids[] = $item['id'];
        }
        return $this->article_ids;
    }

    public function getId()
    {
        if (empty($this->article_ids)) {
            throw new Exception('Empty article_ids variable');
        }
        return implode('|', $this->article_ids);
    }

    public function getIds()
    {
        return $this->article_ids;
    }

    public function getTitle($i = null)
    {
        $wiki_api = $this->base_api . 'action=query&prop=info&pageids=' . $this->getId();
        $json_wapi = json_decode(file_get_contents($wiki_api), true);
        return $this->get_list_or_item($json_wapi['query']['pages'], 'title', $i);
    }

    public function getLink($i = null)
    {
        $wiki_api = $this->base_api . 'action=query&prop=info&inprop=url&pageids=' . $this->getId();
        $json_wapi = json_decode(file_get_contents($wiki_api), true);
        return $this->get_list_or_item($json_wapi['query']['pages'], 'fullurl', $i);
    }

    public function getFirstSentence($number = 1, $i = null)
    {
        $wiki_api = sprintf($this->base_api . 'action=query&prop=extracts&exsentences=%d&explaintext=&exsectionformat=plain&pageids=%s', intval($number), $this->getId());
        $json_wapi = json_decode(file_get_contents($wiki_api), true);
        return $this->get_list_or_item($json_wapi['query']['pages'], 'extract', $i);
    }

    public function getPlainTextArticle($i = null)
    {
        $wiki_api = $this->base_api . 'action=query&prop=extracts&exlimit=1&explaintext=&exsectionformat=plain&pageids=' . $this->getId();
        $json_wapi = json_decode(file_get_contents($wiki_api), true);
        return $this->get_list_or_item($json_wapi['query']['pages'], 'extract', $i);
    }

    public function getNChar($number = 200, $i = null)
    {
        $wiki_api = sprintf($this->base_api . 'action=query&prop=extracts&exchars=%d&pageids=%s', intval($number), $this->getId());
        $json_wapi = json_decode(file_get_contents($wiki_api), true);
        return $this->get_list_or_item($json_wapi['query']['pages'], 'extract', $i);
    }

    public function getCategoriesRelated($i = null)
    {
        $wiki_api = $this->base_api . 'action=query&prop=categories&pageids=' . $this->getId();
        $json_wapi = json_decode(file_get_contents($wiki_api), true);
        $result = $this->get_list_or_item($json_wapi['query']['pages'], 'categories', false, array());
        $categories = array();
        foreach ($result as $page_id => $items) {
            $categories[$page_id] = array();
            foreach ($items as $item) {
                $categories[$page_id][] = ltrim(strstr($item['title'], ':'), ':');
            }
        }
        return $i === null ? $categories : $categories[$this->article_ids[$i]];
    }

    public function getArticleImages($i = null, $image_min_size = 0)
    {
        $result = array();
        $i = count($this->article_ids) > 1 ? $i : 0;
        $article_ids = $i == null ? $this->article_ids : array($this->article_ids[$i]);
        foreach ($article_ids as $page_id) {
            $result[$page_id] = array();
            $wiki_api = $this->base_api . 'action=query&generator=images&prop=imageinfo&iiprop=url|size&pageids=' . $page_id;
            $json_wapi = json_decode(file_get_contents($wiki_api), true);
            if (!$json_wapi) {
                return array();
            }
            foreach ($json_wapi['query']['pages'] as $item) {
                $imageinfo = $item['imageinfo'][0];
                if ($image_min_size > 0 && $imageinfo['size'] < $image_min_size) {
                    continue;
                }
                $result[$page_id][] = $imageinfo['url'];
            }
        }
        return $i === null ? $result : $result[$this->article_ids[$i]];
    }

    public function getOtherLangLinks($i = null)
    {
        $wiki_api = $this->base_api . 'action=query&prop=langlinks&llprop=url&pageids=' . $this->getId();
        $json_wapi = json_decode(file_get_contents($wiki_api), true);
        //return {lang => 'es',url => 'es.wikipedia....', * => 'Titolo'}
        return $this->get_list_or_item($json_wapi['query']['pages'], 'langlinks', $i, array());
    }

    public function getBulkData($limit = 1, $sentences = 5, $chars = 200, $with_images = false, $image_min_size = 102400)
    {
        $wiki_api = $this->base_api . 'action=query&generator=random&grnnamespace=0&grnlimit=' . $limit . '&prop=info|extracts&inprop=url&explaintext=&exsectionformat=plain';
        if ($sentences > 0) {
            $wiki_api .= '&exsentences=' . $sentences;
        } elseif ($chars) {
            $wiki_api .= '&exchars=' . $chars;
        }
        $json_wapi = json_decode(file_get_contents($wiki_api), true);
        $result = array();
        if (!$json_wapi) {
            return $result;
        }
        foreach ($json_wapi['query']['pages'] as $page_id => $item) {
            $result[$page_id] = array(
                'page_id' => $page_id,
                'title'   => $item['title'],
                'length'  => $item['length'],
                'url'     => $item['fullurl'],
                'text'    => isset($item['extract']) ? $item['extract'] : '',
            );
        }
        $this->article_ids = array_keys($result);
        if ($with_images) {
            foreach ($this->article_ids as $page_id) {
                $wiki_api = $this->base_api . 'action=query&generator=images&prop=imageinfo&iiprop=url|size&pageids=' . $page_id;
                $json_wapi = json_decode(file_get_contents($wiki_api), true);
                $images = array();
                if ($json_wapi) {
                    foreach ($json_wapi['query']['pages'] as $item) {
                        $imageinfo = $item['imageinfo'][0];
                        if ($image_min_size > 0 && $imageinfo['size'] < $image_min_size) {
                            continue;
                        }
                        $images[] = $imageinfo['url'];
                    }
                }
                $result[$page_id]['images'] = $images;
            }
        }
        return array_values($result);
    }
}
