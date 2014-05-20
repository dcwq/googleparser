<?php

class GoogleParser
{
    private $urlBase = 'https://www.google.pl/search?hl=pl&safe=active&tbo=d&site=&source=hp&q=';
    
    private $interface = null;
    private $timeout = 60;

    /**
     * Run proxy
     *
     * @param $search
     */

    public function run($search, $offset = 0)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,       $this->getUrl($search, $offset));
        curl_setopt($ch, CURLOPT_USERAGENT, $this->getUserAgent());
        curl_setopt($ch, CURLOPT_INTERFACE, $this->getInterface());
        curl_setopt($ch, CURLOPT_TIMEOUT,   $this->getTimeout());
        
	curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response['data'] = curl_exec($ch);
        $response['error'] = curl_error($ch);
        $response['info'] = curl_getinfo($ch);

        curl_close($ch);

        $html = str_get_html($response['data']);
		
	//find all anchors in h3 paragraph with class r
        $linkObjs = $html->find('h3.r a');

        $links = array();

        foreach ($linkObjs as $linkObj)
        {
            $title = trim($linkObj->plaintext);
            $link  = trim($linkObj->href);

            if (!preg_match('/^https?/', $link) && preg_match('/q=(.+)&amp;sa=/U', $link, $matches) && preg_match('/^https?/', $matches[1])) 
            {
                $link = $matches[1];
            } 
            else if (!preg_match('/^https?/', $link)) 
            { 
            	// not valid link
                continue;
            }

            $links[] = array(
                'title' => $title,
                'link'  => $link
            );
        }

        return $links;
    }
	
    /**
     * Get search url
     *
     * @param $search
     * @return string
     */

    private function getUrl($search, $offset = 0)
    {
        $phrase = str_replace(" ","%20", $search);

        return $this->urlBase . $phrase . '&oq=' . $phrase . ( $offset ? ( '&start=' . $offset ) : '');
    }

    /**
     * Get timeout (sec)
     *
     * @return int
     */

    private function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * Get user agent
     *
     * @return string
     */

    private function getUserAgent()
    {
        return "Mozilla/5.0 (Windows; U; Windows NT 6.0; ja-JP) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.4 Safari/533.20.27";
    }

    private function getInterface()
    {
        return $this->interface;
    }
	
}
