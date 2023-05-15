# Kineo Assignment

The task is to refactor url.php file with the task requirements mentioned below:

    1) Using a regex to parse a URL is very flawed, can you refactor the class to parse a URL without using regex?
    2) Can you create functionality that can switch the URL scheme from HTTP to HTTPS and vice versa.
    3) Can you create functionality that can add and remove query parameters.
    4) Can you create functionality to return the URL with any changes made through the previously added functionality.
    5) Can you create functionality to compare 2 URL objects. It should perform 2 different comparisons, firstly it should be able to compare 2 URLs are exactly the same, and second it should be able to compare if 2 URLs are the same while ignoring the query string.

## Code to get Results

```$newUrl = new Url("http://google.com/1/23/42");
echo "URL with reverse scheme is: " . $newUrl->getUrlWithUpdatedScheme() . "\n";
$newUrl->addParamsToUrl(['a'=>'b', 'z' => '2']);
echo "Adding new params to current URL: " . $newUrl->getUrl() . "\n";
$newUrl->removeParamsFromUrl(['a','z']);
echo "Removing params a and z to current URL: " . $newUrl->getUrl() . "\n";
$newUrl->addParamsToUrl(['x'=>'y', 'z' => 21]);
echo "Adding x and y to current URL: " . $newUrl->getUrl() . "\n";
$urlToBeCompared = new Url("https://google.com/1/23/42?x=y&z=21");
echo "Result for comparison:  " . $newUrl->compareUrl($urlToBeCompared, false);