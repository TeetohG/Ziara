<?php
/**
 * Created by PhpStorm.
 * User: UniQue
 * Date: 4/5/2018
 * Time: 11:58 AM
 */

namespace App\Services;

class AmadeusConfig
{
    public $name = 'Amadeus';
    public $system = 'Test';
    public $userId = '';
    public $password = '';
    public $pcc = '';
    public $isoCurrency = '';
    public $requestorIdType = '';
    public $requestorId = '';

    public static function iataCode($string)
    {
        if (strlen($string) == 3) {
            return $string;
        }
        return substr($string, 0, 3);
    }

    public static function cityImage($cityCode)
    {
        return 'https://photo.hotellook.com/static/cities/960x720/' . $cityCode . '.jpg';
    }

    public function mungXmlToArray($xml)
    {
        $obj = SimpleXML_Load_String($xml);
        if ($obj === FALSE) return $xml;
        $nss = $obj->getNamespaces(TRUE);
        if (empty($nss)) return $xml;

        $nsm = array_keys($nss);
        foreach ($nsm as $key) {
            $rgx = '#' . '(' . '\<' . '/?' . preg_quote($key) . ')' . '(:{1})' . '#';
            $rep = '$1' . '_';
            $xml = preg_replace($rgx, $rep, $xml);
        }
        return json_decode(json_encode(SimpleXML_Load_String($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }

    public function bookingReference()
    {
        return strtoupper(str_random('8'));
    }

    public function paymentReference()
    {
        return strtoupper(str_random('5'));
    }

    public function amadeusDate($date)
    {
        return date('y-m-d', strtotime($date));
    }

    public function callAmadeus($headers, $json_post_string, $requestUrl)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $requestUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_post_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_VERBOSE, false);

        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function createXMlFile($response, $name)
    {
        $file = fopen($name . '.xml', 'w');
        fwrite($file, $response);
        return fclose($file);
    }

    public function createTXTFile($response, $name)
    {
        $file = fopen($name . '.txt', 'w');
        fwrite($file, $response);
        return fclose($file);
    }

    public function lowFareRequestHeader($json_post_string)
    {
        return [
            "POST /v2/shopping/flight-offers HTTP/1.1",
            "Host: test.api.amadeus.com",
            "Content-Type: application/json",
            "Content-Length: " . strlen($json_post_string)
        ];
    }

    public function lowFareMatrixRequestHeader($json_post_string)
    {
        return [
            "POST /v2/shopping/flight-offers/pricing HTTP/1.1",
            "Host: test.api.amadeus.com",
            "Content-Type: application/json",
            "Content-Length: " . strlen($json_post_string)
        ];
    }

    public function lowFareScheduleRequestHeader($json_post_string)
    {
        return [
            "POST /v2/shopping/flight-dates HTTP/1.1",
            "Host: test.api.amadeus.com",
            "Content-Type: application/json",
            "Content-Length: " . strlen($json_post_string)
        ];
    }

    public function airInfoRequestHeader($json_post_string)
    {
        return [
            "POST /v1/airports HTTP/1.1",
            "Host: test.api.amadeus.com",
            "Content-Type: application/json",
            "Content-Length: " . strlen($json_post_string)
        ];
    }

    public function airPriceRequestHeader($json_post_string)
    {
        return [
            "POST /v1/pricing/flight-offers HTTP/1.1",
            "Host: test.api.amadeus.com",
            "Content-Type: application/json",
            "Content-Length: " . strlen($json_post_string)
        ];
    }

    public function airSeatMapRequestHeader($json_post_string)
    {
        return [
            "POST /v1/shopping/seatmaps HTTP/1.1",
            "Host: test.api.amadeus.com",
            "Content-Type: application/json",
            "Content-Length: " . strlen($json_post_string)
        ];
    }

    public function travelBuildRequestHeader($json_post_string)
    {
        return [
            "POST /v1/booking/flight-orders HTTP/1.1",
            "Host: test.api.amadeus.com",
            "Content-Type: application/json",
            "Content-Length: " . strlen($json_post_string)
        ];
    }

    public function cancelPnrRequestHeader($json_post_string)
    {
        return [
            "POST /v1/booking/flight-orders/{orderId}/cancel HTTP/1.1",
            "Host: test.api.amadeus.com",
            "Content-Type: application/json",
            "Content-Length: " . strlen($json_post_string)
        ];
    }

    public function issueTicketRequestHeader($json_post_string)
    {
        return [
            "POST /v1/booking/flight-orders HTTP/1.1",
            "Host: test.api.amadeus.com",
            "Content-Type: application/json",
            "Content-Length: " . strlen($json_post_string)
        ];
    }

    public function voidTicketRequestHeader($json_post_string)
    {
        return [
            "POST /v1/booking/flight-orders/{orderId}/cancel HTTP/1.1",
            "Host: test.api.amadeus.com",
            "Content-Type: application/json",
            "Content-Length: " . strlen($json_post_string)
        ];
    }

    public function pnrReadRequestHeader($json_post_string)
    {
        return [
            "POST /v1/booking/flight-orders/{orderId} HTTP/1.1",
            "Host: test.api.amadeus.com",
            "Content-Type: application/json",
            "Content-Length: " . strlen($json_post_string)
        ];
    }

    public function updatePnrRequestHeader($json_post_string)
    {
        return [
            "POST /v1/booking/flight-orders/{orderId} HTTP/1.1",
            "Host: test.api.amadeus.com",
            "Content-Type: application/json",
            "Content-Length: " . strlen($json_post_string)
        ];
    }
}