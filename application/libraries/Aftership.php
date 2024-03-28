<?php
/*
{
    "meta": {
        "code": 200
    },
    "data": {

        }
    }
}
*/

require_once APPPATH . "third_party/guzzlehttp/vendor/autoload.php";

class Aftership
{

  /**
   * Constructor
   *
   * @access public
   * @param array
   */
  public function __construct()
  {
      $this->ci =& get_instance();

      $this->ci->config->load('aftership');
      $this->client = new \GuzzleHttp\Client(
        [
          'base_uri' => $this->ci->config->item('aftership_uri'),
          'headers' => [
              'Content-Type' => 'application/json',
              'Accept'     => 'application/json',
              'as-api-key'      => $this->ci->config->item('aftership_api_key')
          ]
        ]
      );
  }

  public function createTracking($slug, $code)
  {
    try {

        $response = $this->client->request('POST', '/v4/trackings',
          [
            'body' => '{
                        "tracking": {
                          "slug": "' . $slug . '",
                          "tracking_number": "' . $code . '"
                        }
                      }'
          ]
        );

        $statuscode = $response->getStatusCode();
        $array = json_decode($response->getBody()->getContents(), true);
        //print_r($array);
        if ($statuscode == '201' && isset($array["data"]["tracking"]["id"])) {
            return $array["data"]["tracking"]["id"];
        } else {
            return false;
        }
    } catch (GuzzleHttp\Exception\ClientException $e) {
      $response = $e->getResponse();
      $statuscode = $response->getStatusCode();
      //echo $statuscode;
      $array = json_decode($response->getBody()->getContents(), true);
      if ($statuscode == '400' && isset($array["data"]["tracking"]["id"])) {
        return $array["data"]["tracking"]["id"];
      }
    }
    /*
    } catch(Exception $e) {
        echo $e->getMessage();
        return false;
    }*/

  }

  public function getTracking($id, $lang=1)
  {
    try {
      $response = $this->client->request('GET', '/v4/trackings/' . $id . '?lang=' . ($lang == 2 ? 'ru' : 'en'));
      $array = json_decode($response->getBody()->getContents(), true);
      return $array;
    } catch(Exception $e) {
        //echo $e->getMessage();
        return false;
    }
  }


  public function getCouriers()
  {
    try {
      $response = $this->client->request('GET', '/v4/couriers');
      $array = json_decode($response->getBody()->getContents(), true);
      return $array['data']['couriers'];
    } catch(Exception $e) {
        //echo $e->getMessage();
        return [
          ['slug' => 'russian-post', 'name' => 'Russian post'],
          ['slug' => 'dpd', 'name' => 'Dpd']
        ];
        return false;
    }
  }

  /**
   * fixer.io Currency Converter
   *
   * @access private
   */
  private function fixerIoExchangeRates($base, $serviceKey)
  {
      //$ch = curl_init('http://data.fixer.io/api/latest?access_key=' . $serviceKey . '');
      $ch = curl_init('https://www.cbr-xml-daily.ru/latest.js');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $response = curl_exec($ch);
      curl_close($ch);
      return $this->createRatesArray($response, $base);
  }

}
