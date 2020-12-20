<?php

// AGH-Dok-Mailer
// Entwickler: Julian von Bülow
// Lizenz: CC BY-SA 4.0 | https://creativecommons.org/licenses/by-sa/4.0/deed.de

require_once "sendmail.php";
require_once "config.php";

$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);

// Schritt 1: Erhalte __websessionID und __sessionNumber

$url = "https://pardok.parlament-berlin.de/starweb/AHAB/servlet.starweb?path=AHAB/lissh.web";
curl_setopt($ch, CURLOPT_URL, $url);
$response = curl_exec($ch);

$cookie = "";
preg_match("/Set-Cookie: JSESSIONID=.*;/", $response, $cookie);
$cookie = str_replace("Set-Cookie: ", "", $cookie[0]);
$cookie = substr($cookie, 0, strpos($cookie, ';'));

$doc = new DOMDocument();
libxml_use_internal_errors(true);
$doc->loadHTML($response);
$xpath = new DOMXPath($doc);

$nodes = $xpath->query("//form/input");
if($nodes->length > 0)
{
    $__websessionID = $nodes[0]->attributes->getNamedItem("value")->nodeValue;
    $__sessionNumber = $nodes[1]->attributes->getNamedItem("value")->nodeValue;
}


// Schritt 2: POST-Abfrage der Daten

$POST = array();
$POST['__websessionID'] = $__websessionID;
$POST['__sessionNumber'] = $__sessionNumber;
$POST['__pageid'] = "Main";
$POST['__windowid'] = "null";
$POST['__language'] = "";
$POST['__a1'] = "";
$POST['__a2'] = "";
$POST['__a3'] = "";
$POST['__hiddenstyle'] = "A";
$POST['__dateseparator'] = "null";
$POST['__numberstyle'] = "A";
$POST['__dirtyFlag'] = "Clean";
$POST['__SFSRepositoryGroup'] = "";
$POST['__action'] = "19";
$POST['maxtrefferlist1'] = "S99{ITEMS -1:-50}";
$POST['suchewolist'] = "TYP=DOKDBE\\PSEUDOVORGANG";
$POST['LISSH_FreieSuche'] = "";
$POST['wplist'] = "18";
$POST['Suchzeile4'] = "";
$POST['Suchzeile5'] = "";
$POST['Suchzeile6'] = "";
$POST['Suchzeile7'] = "";
$POST['Suchzeile8'] = "";
$POST['suchfeldlist1'] = "/WEBDOK,WEBVOR,1DES2";
$POST['Suchzeile14'] = "";
$POST['Suchzeile9'] = "";
$POST['lissh.operator.list'] = "AND";
$POST['lissh.operator.list2'] = "AND";
$POST['suchfeldlist2'] = "/WEBDOK,WEBVOR,1DES2";
$POST['Suchzeile17'] = "";
$POST['Suchzeile18'] = "";
$POST['Suchzeile19'] = "";
$POST['Suchzeile10'] = "";
$POST['Suchzeile12'] = SCHLAGWORTE;
$POST['Suchzeile20'] = "";
$POST['Suchzeile21'] = "";
$POST['__jsModel'] = "New";

$queryString = http_build_query($POST);
$headers = array(
                "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9",
                "Accept-Encoding: gzip, deflate, br",
                "Accept-Language: de,en-US;q=0.7,en;q=0.3",
                "Connection: keep-alive",
                "Cache-Control: max-age=0",
                "Content-Length: " . strlen($queryString),
                "Content-Type: application/x-www-form-urlencoded",
                "Cookie: " . $cookie,
                "DNT: 1",
                "Host: pardok.parlament-berlin.de",
                "Origin: https://pardok.parlament-berlin.de",
                "Referer: https://pardok.parlament-berlin.de/starweb/AHAB/servlet.starweb",                
                "Sec-GPC: 1",
                "Upgrade-Insecure-Requests: 1",                                
                "UserAgent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:83.0) Gecko/20100101 Firefox/83.0");

curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $queryString);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_HEADER, false);
$response = curl_exec($ch);
curl_close($ch);

$response = utf8_decode($response);
$doc->loadHTML($response, LIBXML_NOWARNING);
$xpath = new DOMXPath($doc);

$nodes = $xpath->query("//div[@name='IfReportGenerated']//span[@name='OFR_WWK4']");
if($nodes->length > 0)
{
    $elements = array();
    foreach ($nodes as $node)
    {
        $element = array();
        $element['title'] = trim($node->childNodes[0]->nodeValue);
        $element['title'] = preg_replace('/\s+/', ' ', $element['title']);
        $element['subtitle'] = $node->childNodes[2]->nodeValue;
        $element['subtitle'] = preg_replace('/\s+/', ' ', $element['subtitle']);
        $element['drucksache'] = trim($node->childNodes[3]->nodeValue);
        $element['url'] = 'https://pardok.parlament-berlin.de' . $node->childNodes[3]->attributes->getNamedItem("href")->nodeValue;
        array_push($elements, $element);
    }    
    
    $lastQuery = false;
    if(file_exists(__DIR__ . "/lastQuery.txt"))
        $lastQuery = file_get_contents(__DIR__ . "/lastQuery.txt");
    
    $template = file_get_contents(__DIR__ . "/mail_template.html");

    foreach ($elements as $elem) 
    {
        if($lastQuery == $elem['title'])
        {
            file_put_contents(__DIR__ . "/lastQuery.txt", $elements[0]['title']);
            return true;
        }

        $message = str_replace("%TITLE%", $elem['title'], $template);
        $message = str_replace("%SUBTITLE%", $elem['subtitle'], $message);
        $message = str_replace("%DRUCKSACHE%", $elem['drucksache'], $message);
        $message = str_replace("%URL%", $elem['url'], $message);

        SendMail($message);

        if(!$lastQuery)
            break;
    }
    file_put_contents(__DIR__ . "/lastQuery.txt", $elements[0]['title']);
}

?>