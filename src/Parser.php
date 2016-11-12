<?php
namespace JonathanKowalski\Omg;


class Parser
{

    protected $ns;

    public function __construct($ns='og')
    {
        $this->ns = $ns.':';
    }

    /**
     * @param $url
     * @return array
     * @throws CantFetchException
     */
    public function parse($url)
    {
        $htmlContent = $this->fetchUrl($url);
        return $this->extract($htmlContent);
    }

    public function parseContent($content){
        return $this->extract($content);
    }

    protected function fetchUrl($url)
    {
        if(!$url){
            throw new CantFetchException;
        }

        $ch = curl_init($url);

        $agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $response = curl_exec($ch);

        curl_close($ch);

        if(!!$response){
            return $response;
        }

        throw new CantFetchException;
    }

    protected function extract($content)
    {
        $data = [];
        $i=0;
        while( false !== ($i = strpos($content, $this->ns, $i)) ){
            $endPos = strpos($content, '>', $i) + 1;
            $tag = $this->getTag($content, $endPos);
            $property = $this->getProperty($tag);
            $value = $this->getValue($tag);
            $i = $endPos;

            if(!!$property) {
                $data[$property] = $value;
            }
        }

        return $data;
    }

    protected function getTag($content, $offset){
        $part = substr($content, 0, $offset);
        $startTag = strrpos($part, '<');
        return substr($part, $startTag);
    }

    protected function getProperty($tag)
    {
        $clue = 'property="';
        return $this->cut($clue, $tag);
    }

    protected function getValue($tag){
        $clue = 'content="';
        return $this->cut($clue, $tag);
    }

    protected function cut($clue, $content)
    {
        $i = strpos($content, $clue);
        if(false === $i){
            return false;
        }
        $i += strlen($clue);
        $end = strpos($content, '"', $i);
        return substr($content, $i, $end-$i);
    }
}