<?php

// See License

use \GoogleUrl\GoogleDOM,
 GoogleUrl\ProxyDefinition;

/**
 * Description of GoogleUrl
 *
 * @author sghzal
 * @license http://www.freebsd.org/copyright/license.html BSD
 */
class GoogleUrl{

    /** SEARCH PARAMS CONSTANTS */
    const  PARAM_NBRESULTS="num";
    /** END SEARCH PARAMS CONSTANTS */


    /** CONSTANTS OF LANG **/
    // french
    const HL_FR="fr";
    const LR_FR="lang_fr";
    const TLD_FR="fr";
    const ACCEPT_FR="fr;q=0.8";
    // english
    const HL_EN="en";
    const LR_EN="lang_en";
    const TLD_EN="com";
    const ACCEPT_EN="en-us,en;q=0.8";
    // GERMAN
    const HL_DE="de";
    const LR_DE="lang_de";
    const TLD_DE="de";
    const ACCEPT_DE="de;q=0.8";
    // DUTCH
    const HL_NL="nl";
    const LR_NL="lang_nl";
    const TLD_NL="nl";
    const ACCEPT_NL="nl;q=0.8";
    // Czech
    const HL_CS="cs";
    const LR_CS="lang_cs";
    const TLD_CS="com";
    const ACCEPT_CS="cs;q=0.8";
    // Danish
    const HL_DK="da";
    const LR_DK="lang_da";
    const TLD_DK="dk";
    const ACCEPT_DK="da;q=0.8";
    // Japan
    const HL_JP="ja";
    const LR_JP="lang_ja";
    const TLD_JP="co.jp";
    const ACCEPT_JP="ja;q=0.8";
    // spain
    const HL_ES="es";
    const LR_ES="lang_es";
    const TLD_ES="es";
    const ACCEPT_ES="es;q=0.8";
    // spain
    const HL_RU="ru";
    const LR_RU="lang_ru";
    const TLD_RU="ru";
    const ACCEPT_RU="ru;q=0.8";
    
    /** END CONSTANTS OF LANG **/


    protected $tld;
    protected $acceptLangage;

    protected $googleParams;
    
    protected $userAgent = "Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2049.0 Safari/537.36";

    protected $enableLr = true;

    public function __construct() {
        $this->init();
    }
    
    /**
     * Reset all params to default :
     *      
     *       "q" => "",                      // Search Query
     * 
     *       "start" => 0,                   // First result number
     * 
     *       "num" => 10,                    // Number of results per pages
     * 
     *       "complete" => 0,                // Suggestion auto
     * 
     *       "pws" => 0,                     // Personnal search
     * 
     *       "hl" => "en",                   // Interface langage
     * 
     *       "lr" => "lang_en",              // Results Langage
     *      
     *       TLD => "com"
     */
    public function init(){
        
        $this->googleParams=array(
            
            "q" => "",                      // Search Query
            "start" => 0,                   // First result number
            "num" => 10,                    // Number of results per pages
            "complete" => 0,                // Suggestion auto
            "pws" => 0,                     // Personnal search
            "hl" =>  self::HL_EN,           // Interface langage
            "lr" =>  self::LR_EN,          // Results Langage


            
        );
        $this->acceptLangage=self::ACCEPT_EN;
        $this->setTld("com");
    }
    
    /**
     * ask if a langage is configured 
     * @param string $iso the iso code of the country. e.g  english : "en" , france : "fr"
     * @return boolean true if available
     */
    public static function langageIsAvailable($iso){
        $hl="HL_".strtoupper($iso);
        
        return defined("self::".$hl);
    }
    
    /**
     * Set the lang to the given (iso formated) lang. This will modify the params hl and lr
     * @param string $iso the iso code of the country. e.g  english : "en" , france : "fr"
     * @param boolean $setTld change the tld to matching with the langage. Default to true
     * @return GoogleUrl this instance
     * @throws Exception
     */
    public function setLang($iso,$setTld=true){
               
        if(self::langageIsAvailable($iso)){
            $hl="HL_".strtoupper($iso);
            $lr="LR_".strtoupper($iso);
            $accept="ACCEPT_".strtoupper($iso);
            
            
            $this->setParam("hl", constant("self::".$hl));
            
            if($this->enableLr){
                $this->setParam('lr', constant("self::".$lr));
            }else{
                $this->setParam('lr', null);
            }
            
            $this->acceptLangage=constant("self::".$accept);
            
            if($setTld){
                $tld="TLD_".strtoupper($iso);
                $this->setTld(constant("self::".$tld));
            }
            
        }else{
            throw new \Exception("Unknown lang '".$iso."'");
        }
            
        return $this;
    }
    
    public function enableLr($enabled = true){
        $this->enableLr = $enabled;
        if(!$this->enableLr){
            $this->setParam('lr', null);
        }
    }

    
    /**
     * 
     * @param string $tld google tld "com","fr","co.uk"
     * @return \GoogleURL\GoogleUrl
     */
    public function setTld($tld){
        $this->tld=trim($tld," .");
        return $this;
    }
    
    /**
     * Set terms to search but doesnt launch the search
     * @param string $search set the string to search
     * @return GoogleUrl
     */
    public function searchTerm($search){
        return $this->setParam("q",$search);
    }
    
    /**
     * 
     * @param string $name name of the param
     * @param string $value value of the param
     * @return \GoogleUrl
     */
    private function setParam($name,$value){
        if(null === $value){
            if($this->googleParams[$name]){
                unset($this->googleParams[$name]);
            }
        }else
            $this->googleParams[$name]=$value;
        
        return $this;
    }

    /**
     * get a param by its name
     * @param string $name the param to get
     * @return string
     */
    private function param($name){
        return $this->googleParams[$name];
    }


    /**
     * check if param isset
     * @param string $name the param to get
     * @return string
     */
    private function hasParam($name){
        return isset($this->googleParams[$name]);
    }




    /**
     * Set which page to query. Between 0 and 100
     * @param int $n the number of the page. Begins to 0
     * @return GoogleUrl this instance
     */
    public function setPage($n){
        $this->setParam("start", $this->param("num")*$n);
        return $this;
    }

    public function getPage(){
        return $this->param("start")/$this->param("num");
    }

    /**
     * Set how many results per page between 1 and 100
     * Will also update the start param to match the page number
     * @param int $n the number of the page. Begins to 0
     * @return GoogleUrl this instance
     */
    public function setNumberResults($n){

        $page=$this->getPage();

        $this->setParam(self::PARAM_NBRESULTS, $n);
        
        $this->setPage($page);
        
        return $this;
    }
    
    public function setUserAgent($userAgent) {
        $this->userAgent = $userAgent;
    }

        
    /**
     * Launch a google Search
     * @param string $searchTerm the string to search. Or if not specified will take the given with ->searchTerm($search)
     * @param array $options Options for the query . available options :
     *                       + proxy : a proxyDefinition item to proxyfy the request
     *                       + 
     *                       + 
     *                       
     * @return GoogleDOM the Google DOMDocument
     * @throws Exception
     * @throws \GoogleUrl\CaptachaException google detected us as a bot
     */
    public function search($searchTerm=null, \GoogleUrl\SimpleProxyInterface $proxy = null){
        
    
        /**======================
         * CHANGE SEARCH IF NEEDED
          ========================*/
        if(null !== $searchTerm)
            $this->searchTerm($searchTerm);
        else
            if( ! strlen($this->param("q"))>0 )
                throw new Exception ("Nothing to Search");


        /**=========
         * INIT CURL
          =========*/
        $c = new \GoogleUrl\Curl();
        $c->url=$this->__toString();


        /**==========
         * DO HEADERS
          ===========*/
        // let's be redirected if needed
        $c->followLocation();
        // use a true user agent, maybe better for true results
        $c->useragent = $this->userAgent;

        // use other headers

        // accept-langage to make sure google use the same language as asked
        $header[]="Accept-Language: ".$this->acceptLangage;

        $c->HTTPHEADER=$header;

        
        /**=========
         * SET PROXY
           =========*/
        if($proxy){
            $c->proxy = $proxy->getIp();
            $c->proxyport = $proxy->getPort();
            
            $login = $proxy->getLogin();
            if($login){
                $auth = $login;
                $psw = $proxy->getPassword();
                if($psw){
                    $auth .= ":" . $psw;
                }
                $c->proxyuserpwd = $auth;
            }
            
            
            $proxyType = $proxy->getProxyType();
            $c->proxytype = $proxyType ? $proxyType : "http";
  
        }
        

        /**========
         * EXECUTE
          =========*/
        $r=$c->exec();
        
        if(false === $r){
            
            $errno = $c->errno();
            
            if(CURLE_COULDNT_RESOLVE_PROXY == $errno){
                throw new \GoogleUrl\Exception\ProxyException("HTTP query failled [curl-error : $errno - " . $c->error() . " ] for the following URL : ".$this);
            }else{
                throw new \GoogleUrl\Exception\CurlException ("HTTP query failled [curl-error : $errno - " . $c->error() . " ] for the following URL : ".$this);
            }
            
        }
        
        /**===============
         * CREATE DOCUMENT
          ================*/
        $doc=new GoogleDOM($this->param("q"),$this->getUrl(),$this->getPage(),$this->param(self::PARAM_NBRESULTS));
        libxml_use_internal_errors(TRUE);
        $doc->loadHTML($r);
        libxml_use_internal_errors(FALSE);
        libxml_clear_errors();
        
        if($doc->isCaptcha())
            throw new \GoogleUrl\Exception\CaptachaException();

        return $doc;
    }
    
    /**
     * get the generated url
     * @return string the generated url
     */
    public function getUrl(){
        return $this->__toString();
    }
    
    /**
     * Same as gerUrl
     * @return string the generated url
     */
    public function __toString() {

        $url="https://www.google.".$this->tld."/search?".http_build_query($this->googleParams);
        
        return $url;
        
    }

}