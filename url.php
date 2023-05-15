<?php

final class Url {

    private string $url;
    
    public function __construct(string $url)
    {
        $this->url = $url;
    }
    
    /**
     * Function to get url scheme eg http or https
     * @return string
     */
    public function getUrlScheme(): string
    {
        return parse_url($this->url, PHP_URL_SCHEME);
    }
    
    /**
     * Function to get the Host
     * @return string
     */
    private function getUrlHost(): string
    {
        return parse_url($this->url, PHP_URL_HOST);
    }
    
    /**
     * Function to get the url path
     * @return string|null
     */
    private function getUrlFullPath(): string|null
    {
        return parse_url($this->url, PHP_URL_PATH);
    }
    
    /**
     * Function to get query params from the url
     * @return string|null
     */
    private function getUrlQueryParams(): string|null
    {
        return parse_url($this->url, PHP_URL_QUERY);
    }
    
    /**
     * Function to return the url scheme from HTTP to HTTPS and vice versa
     * @return string
     */
    private function switchUrlScheme(): string
    {
        $parsedUrl = $this->getUrlScheme();
        return $parsedUrl === 'http' ? 'https' : 'http';
    }
    
    /**
     * Prepare URL from Scheme, Host, and path
     * @param bool $paramsToBeAddedInUrl
     * @param bool $switchScheme
     * @return string
     */
    private function prepareUrl(bool $paramsToBeAddedInUrl = true, bool $switchScheme = false): string
    {
        $urlString = '';
        $urlString = $switchScheme === true ? $this->switchUrlScheme() : $this->getUrlScheme();
        $urlString .= '://';
        $urlString .= $this->getUrlHost();
        $urlString .= $this->getUrlFullPath() !== null ? $this->getUrlFullPath() : '';
        if($paramsToBeAddedInUrl) {
            $currentUrlParams = $this->getUrlQueryParams();
            if($currentUrlParams) {
                $urlString .= '?';
                $urlString .= $currentUrlParams;
            }
        }
        return $urlString;
    }
    
    /**
     * Function to get the url with new scheme
     * @return string
     */
    public function getUrlWithUpdatedScheme(): string
    {
        $this->url = $this->prepareUrl(true, true);
        return $this->url;
    }
    
    /**
     * Function to remove the url params from url
     * @retu
     * 0rn string
     */
    public function removeAllParamsFromUrl(): string
    {
        $this->url = $this->prepareUrl(false, false);
        return $this->url;
    }
    
    /**
     * Function to add the query parameters to the url
     * @param array $paramsArray
     * @return string
     */
    public function addParamsToUrl(array $paramsArray = []): string
    {
        $originalQueryParams = [];
        if($this->getUrlQueryParams()) {
            parse_str($this->getUrlQueryParams(), $originalQueryParams);
        }
        
        $updatedUrlString = $this->removeAllParamsFromUrl();
        if(count($paramsArray)) {
            $paramsArray = array_merge($originalQueryParams, $paramsArray);
            $paramString = http_build_query($paramsArray);
            $updatedUrlString .= '?' . $paramString;
        }
        $this->url = $updatedUrlString;
        return $this->url;
    }
    
    /**
     * Function to remove the params from the url
     * @param array $paramsArray
     * @return string
     */
    public function removeParamsFromUrl(array $paramsArray = []): string
    {
        $originalQueryParams = [];
        if($this->getUrlQueryParams()) {
            parse_str($this->getUrlQueryParams(), $originalQueryParams);
        }
        
        $newUrlString = $this->removeAllParamsFromUrl();
        if(count($paramsArray) && count($originalQueryParams)){
            foreach ($paramsArray as $paramsValue) {
                if(array_key_exists($paramsValue, $originalQueryParams)) {
                    unset($originalQueryParams[$paramsValue]);
                }
            }
            
            if(count($originalQueryParams)) {
                $newParamString = http_build_query($originalQueryParams);
                $newUrlString .= '?' . $newParamString;
            }
            
        }
        
        $this->url = $newUrlString;
        return $this->url;
    }
    
    /**
     * Function to return the URL with any changes made through the previous functionality
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }
    
    /**
     * Function to compare two urls and return result
     * @param Url $urlForComparison
     * @param bool $parameterizedComparison
     * @return string
     */
    public function compareUrl(Url $urlForComparison, bool $parameterizedComparison = false): string
    {
        if($parameterizedComparison) {

            return $urlForComparison->getUrl() === $this->url ? 'true': 'false';
        }
        return $urlForComparison->removeAllParamsFromUrl() === $this->removeAllParamsFromUrl() ? 'true' : 'false';
    }
}

$newUrl = new Url("http://google.com/1/23/42");
echo "URL with reverse scheme is: " . $newUrl->getUrlWithUpdatedScheme() . "\n";
$newUrl->addParamsToUrl(['a'=>'b', 'z' => '2']);
echo "Adding new params to current URL: " . $newUrl->getUrl() . "\n";
$newUrl->removeParamsFromUrl(['a','z']);
echo "Removing params a and z to current URL: " . $newUrl->getUrl() . "\n";
$newUrl->addParamsToUrl(['x'=>'y', 'z' => 21]);
echo "Adding x and y to current URL: " . $newUrl->getUrl() . "\n";
$urlToBeCompared = new Url("https://google.com/1/23/42?x=y&z=21");
echo "Result for comparison:  " . $newUrl->compareUrl($urlToBeCompared, false);
