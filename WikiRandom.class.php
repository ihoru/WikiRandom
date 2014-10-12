<?php

class WikiRandom{
	
	/* ATTRIBUTI */		
	private $language_supported = array("de", "en", "es", "fr", "it", "nl", "pl" , "ru" , "ceb", "sv", "vi" , "war");
	private $base_api;
	private $language;
        private $article_id;


	public function __construct($language){
                    if (in_array($language, $this->language_supported)) {
                        $this->language = $language;
                        
                        $this->set_base_api($this->language);
                                                
                        $this->getNewRandomArticle();
                    } else {
                        throw new Exception("Language " . $language . " is not supported");
                    }                      
        }
        
        private function set_base_api($language){
            $this->base_api = "http://$language.wikipedia.org/w/api.php?";
        }
        
        public function getApiLanguage(){
            return $this->language;
        }
        
        public function getNewRandomArticle()
        {
            $wiki_api = $this->base_api."action=query&list=random&rnnamespace=0&format=json";
            $json_wapi = json_decode(file_get_contents($wiki_api),TRUE);
            
            $this->article_id = $json_wapi['query']['random'][0]['id'];                                                                
        }
        
        public function getId()
        {                                
            return $this->article_id;
        }
        
        public function getTitle()
        {
            $wiki_api = $this->base_api."action=query&prop=info&format=json&pageids=$this->article_id";
            $json_wapi = json_decode(file_get_contents($wiki_api),TRUE);

            return $json_wapi['query']['pages'][$this->article_id]['title']; 
        }
               
        public function getLink()
        {                
            $wiki_api = $this->base_api."action=query&prop=info&pageids=$this->article_id&inprop=url&format=json";
            $json_wapi = json_decode(file_get_contents($wiki_api),TRUE);

            return trim($json_wapi['query']['pages'][$this->article_id]['fullurl']);            
        }
        
        public function getFirstSentence()
        {               
            $wiki_api = $this->base_api."action=query&pageids=$this->article_id&prop=extracts&exsentences=1&explaintext=&exsectionformat=plain&format=json";
            $json_wapi = json_decode(file_get_contents($wiki_api),TRUE);

            return $json_wapi['query']['pages'][$this->article_id]['extract'];                                
        }
        
        public function getPlainTextArticle()
        {
            $wiki_api = $this->base_api."action=query&prop=extracts&exlimit=1&explaintext=&exsectionformat=plain&format=json&pageids=$this->article_id";
            $json_wapi = json_decode(file_get_contents($wiki_api),TRUE);

            return $json_wapi['query']['pages'][$this->article_id]['extract'];                                
        }
                
        public function getNChar($number_char)
        {                
            $wiki_api = $this->base_api."action=query&pageids=$this->article_id&prop=extracts&explaintext=&exchars=$number_char&format=json";
            $json_wapi = json_decode(file_get_contents($wiki_api),TRUE);

            return $json_wapi['query']['pages'][$this->article_id]['extract'];
        }
        
        public function getCategoriesRelated()
        {                
            $wiki_api = $this->base_api."action=query&pageids=$this->article_id&prop=categories&format=json";         
            $json_wapi = json_decode(file_get_contents($wiki_api),TRUE);

            $all_categories = $json_wapi['query']['pages'][$this->article_id]['categories'];

            $category = array();

            foreach ($all_categories as $key => $value) {                                                      
                    foreach ($value as $v => $val) {
                        if ($v != "ns"){                                
                            $category[] = ltrim(strstr ($val, ":"), ":");                                
                        }                                
                    }
            }

            return $category;
        }
        
        public function getArticleImages()
        {             
                $wiki_api = $this->base_api."action=query&prop=imageinfo&pageids=$this->article_id&generator=images&iiprop=url&format=json";
                $json_wapi = json_decode(file_get_contents($wiki_api),TRUE);                                
                                                
                $url_image = array();
                
                foreach ($json_wapi["query"]["pages"] as $key => $value){                    
                    if ($key < 0)
                        $url_image[] =  $json_wapi["query"]["pages"][$key]["imageinfo"][0]["url"];
                }
                
                return $url_image;
                                                                                                              
        }
        
        public function getOtherLangLinks()
        {                            
            $wiki_api = $this->base_api."action=query&prop=langlinks&llprop=url&pageids=$this->article_id&format=json";         
            $json_wapi = json_decode(file_get_contents($wiki_api),TRUE);
            
            //return {lang => "es",url => "es.wikipedia....", * => "Titolo"} 
            if (array_key_exists("langlinks",$json_wapi['query']['pages'][$this->article_id])){
                    return $json_wapi['query']['pages'][$this->article_id]['langlinks'];                                                                                   
            } else {
                    return NULL;
            }
            
        }
                        
}      