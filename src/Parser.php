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

        $ch = curl_init();

        $agents = [
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1',
            'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1.9) Gecko/20100508 SeaMonkey/2.0.4',
            'Mozilla/5.0 (Windows; U; MSIE 7.0; Windows NT 6.0; en-US)',
            'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_7; da-dk) AppleWebKit/533.21.1 (KHTML, like Gecko) Version/5.0.5 Safari/533.21.1'

        ];
        curl_setopt($ch,CURLOPT_USERAGENT,$agents[array_rand($agents)]);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        //set the header params
        $header = [
            "Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5",
            "Cache-Control: max-age=0",
            "Connection: keep-alive",
            "Keep-Alive: 300",
            "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7",
            "Accept-Language: en-us,en,fr;q=0.5",
            "Pragma: "
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

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