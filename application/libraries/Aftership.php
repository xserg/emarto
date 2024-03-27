<?php
/*
{
    "meta": {
        "code": 200
    },
    "data": {
        "tracking": {
            "id": "vwwk3x5m0gwfalt5yvwhp02i",
            "created_at": "2024-02-28T15:44:20+00:00",
            "updated_at": "2024-02-28T15:44:30+00:00",
            "last_updated_at": "2024-02-28T15:44:30+00:00",
            "tracking_number": "RLJ06333216",
            "slug": "cainiao",
            "active": true,
            "android": [],
            "custom_fields": null,
            "customer_name": null,
            "delivery_time": 8,
            "destination_country_iso3": "RUS",
            "courier_destination_country_iso3": "RUS",
            "emails": [],
            "expected_delivery": null,
            "ios": [],
            "note": null,
            "order_id": null,
            "order_id_path": null,
            "order_date": null,
            "origin_country_iso3": "CHN",
            "shipment_package_count": null,
            "shipment_pickup_date": "2024-02-21T12:57:26+08:00",
            "shipment_delivery_date": null,
            "shipment_type": null,
            "shipment_weight": null,
            "shipment_weight_unit": null,
            "signed_by": null,
            "smses": [],
            "source": "api",
            "tag": "InTransit",
            "subtag": "InTransit_005",
            "subtag_message": "Customs clearance completed",
            "title": "RLJ06333216",
            "tracked_count": 1,
            "last_mile_tracking_supported": true,
            "language": null,
            "unique_token": "deprecated",
            "checkpoints": [
                {
                    "slug": "cainiao",
                    "city": null,
                    "created_at": "2024-02-28T15:44:30+00:00",
                    "location": null,
                    "country_name": null,
                    "message": "Покинуть склад",
                    "country_iso3": null,
                    "tag": "InTransit",
                    "subtag": "InTransit_007",
                    "subtag_message": "Departure Scan",
                    "checkpoint_time": "2024-02-21T12:57:26+08:00",
                    "coordinates": [],
                    "state": null,
                    "zip": null,
                    "raw_tag": "GWMS_OUTBOUND"
                },
                {
                    "slug": "cainiao",
                    "city": null,
                    "created_at": "2024-02-28T15:44:30+00:00",
                    "location": null,
                    "country_name": null,
                    "message": "Заказ получен успешно",
                    "country_iso3": null,
                    "tag": "InTransit",
                    "subtag": "InTransit_002",
                    "subtag_message": "Acceptance scan",
                    "checkpoint_time": "2024-02-21T07:11:46+08:00",
                    "coordinates": [],
                    "state": null,
                    "zip": null,
                    "raw_tag": "GWMS_ACCEPT"
                },
                {
                    "slug": "cainiao",
                    "city": null,
                    "created_at": "2024-02-28T15:44:30+00:00",
                    "location": null,
                    "country_name": null,
                    "message": "Принято перевозчиком",
                    "country_iso3": null,
                    "tag": "InTransit",
                    "subtag": "InTransit_002",
                    "subtag_message": "Acceptance scan",
                    "checkpoint_time": "2024-02-21T18:29:23+08:00",
                    "coordinates": [],
                    "state": null,
                    "zip": null,
                    "raw_tag": "PU_PICKUP_SUCCESS"
                },
                {
                    "slug": "cainiao",
                    "city": null,
                    "created_at": "2024-02-28T15:44:30+00:00",
                    "location": null,
                    "country_name": null,
                    "message": "Пакет закончен",
                    "country_iso3": null,
                    "tag": "InTransit",
                    "subtag": "InTransit_001",
                    "subtag_message": "In Transit",
                    "checkpoint_time": "2024-02-21T11:58:43+08:00",
                    "coordinates": [],
                    "state": null,
                    "zip": null,
                    "raw_tag": "GWMS_PACKAGE"
                },
                {
                    "slug": "cainiao",
                    "city": null,
                    "created_at": "2024-02-28T15:44:30+00:00",
                    "location": null,
                    "country_name": null,
                    "message": "Входящий в сортинг-центр",
                    "country_iso3": null,
                    "tag": "InTransit",
                    "subtag": "InTransit_003",
                    "subtag_message": "Arrival scan",
                    "checkpoint_time": "2024-02-21T21:05:11+08:00",
                    "coordinates": [],
                    "state": null,
                    "zip": null,
                    "raw_tag": "SC_INBOUND_SUCCESS"
                },
                {
                    "slug": "cainiao",
                    "city": null,
                    "created_at": "2024-02-28T15:44:30+00:00",
                    "location": null,
                    "country_name": null,
                    "message": "Исходящий в сортинговом центре",
                    "country_iso3": null,
                    "tag": "InTransit",
                    "subtag": "InTransit_007",
                    "subtag_message": "Departure Scan",
                    "checkpoint_time": "2024-02-21T21:36:09+08:00",
                    "coordinates": [],
                    "state": null,
                    "zip": null,
                    "raw_tag": "SC_OUTBOUND_SUCCESS"
                },
                {
                    "slug": "cainiao",
                    "city": null,
                    "created_at": "2024-02-28T15:44:30+00:00",
                    "location": null,
                    "country_name": null,
                    "message": "Прибыл на выезд транспортного узла",
                    "country_iso3": null,
                    "tag": "InTransit",
                    "subtag": "InTransit_003",
                    "subtag_message": "Arrival scan",
                    "checkpoint_time": "2024-02-23T05:16:33+08:00",
                    "coordinates": [],
                    "state": null,
                    "zip": null,
                    "raw_tag": "LH_HO_IN_SUCCESS"
                },
                {
                    "slug": "cainiao",
                    "city": null,
                    "created_at": "2024-02-28T15:44:30+00:00",
                    "location": null,
                    "country_name": null,
                    "message": "Начато экспортное таможенное оформление",
                    "country_iso3": null,
                    "tag": "InTransit",
                    "subtag": "InTransit_006",
                    "subtag_message": "Customs clearance started",
                    "checkpoint_time": "2024-02-24T21:37:22+08:00",
                    "coordinates": [],
                    "state": null,
                    "zip": null,
                    "raw_tag": "CC_EX_START"
                },
                {
                    "slug": "cainiao",
                    "city": null,
                    "created_at": "2024-02-28T15:44:30+00:00",
                    "location": null,
                    "country_name": null,
                    "message": "Успех расчистки экспорта",
                    "country_iso3": null,
                    "tag": "InTransit",
                    "subtag": "InTransit_005",
                    "subtag_message": "Customs clearance completed",
                    "checkpoint_time": "2024-02-24T22:37:27+08:00",
                    "coordinates": [],
                    "state": null,
                    "zip": null,
                    "raw_tag": "CC_EX_SUCCESS"
                },
                {
                    "slug": "cainiao",
                    "city": null,
                    "created_at": "2024-02-28T15:44:30+00:00",
                    "location": null,
                    "country_name": null,
                    "message": "Выезд из страны/региона вылета",
                    "country_iso3": null,
                    "tag": "InTransit",
                    "subtag": "InTransit_007",
                    "subtag_message": "Departure Scan",
                    "checkpoint_time": "2024-02-25T20:00:00+08:00",
                    "coordinates": [],
                    "state": null,
                    "zip": null,
                    "raw_tag": "LH_HO_AIRLINE"
                },
                {
                    "slug": "cainiao",
                    "city": null,
                    "created_at": "2024-02-28T15:44:30+00:00",
                    "location": null,
                    "country_name": null,
                    "message": "Слева от страны/региона отправления",
                    "country_iso3": null,
                    "tag": "InTransit",
                    "subtag": "InTransit_007",
                    "subtag_message": "Departure Scan",
                    "checkpoint_time": "2024-02-26T13:32:00+08:00",
                    "coordinates": [],
                    "state": null,
                    "zip": null,
                    "raw_tag": "LH_DEPART"
                },
                {
                    "slug": "cainiao",
                    "city": null,
                    "created_at": "2024-02-28T15:44:30+00:00",
                    "location": null,
                    "country_name": null,
                    "message": "Прибыл в офис linehual",
                    "country_iso3": null,
                    "tag": "InTransit",
                    "subtag": "InTransit_003",
                    "subtag_message": "Arrival scan",
                    "checkpoint_time": "2024-02-26T16:41:00+03:00",
                    "coordinates": [],
                    "state": null,
                    "zip": null,
                    "raw_tag": "LH_ARRIVE"
                },
                {
                    "slug": "cainiao",
                    "city": null,
                    "created_at": "2024-02-28T15:44:30+00:00",
                    "location": null,
                    "country_name": null,
                    "message": "Прибыл на таможню",
                    "country_iso3": null,
                    "tag": "InTransit",
                    "subtag": "InTransit_003",
                    "subtag_message": "Arrival scan",
                    "checkpoint_time": "2024-02-27T18:18:14+03:00",
                    "coordinates": [],
                    "state": null,
                    "zip": null,
                    "raw_tag": "CC_HO_IN_SUCCESS"
                },
                {
                    "slug": "cainiao",
                    "city": null,
                    "created_at": "2024-02-28T15:44:30+00:00",
                    "location": null,
                    "country_name": null,
                    "message": "Начало расчистки импорта",
                    "country_iso3": null,
                    "tag": "InTransit",
                    "subtag": "InTransit_005",
                    "subtag_message": "Customs clearance completed",
                    "checkpoint_time": "2024-02-28T00:11:46+03:00",
                    "coordinates": [],
                    "state": null,
                    "zip": null,
                    "raw_tag": "CC_IM_START"
                }
            ],
            "subscribed_smses": [],
            "subscribed_emails": [],
            "return_to_sender": false,
            "order_promised_delivery_date": null,
            "delivery_type": null,
            "pickup_location": null,
            "pickup_note": null,
            "courier_tracking_link": "https://global.cainiao.com/",
            "first_attempted_at": null,
            "courier_redirect_link": null,
            "order_tags": [],
            "order_number": null,
            "first_estimated_delivery": null,
            "custom_estimated_delivery_date": null,
            "origin_state": null,
            "origin_city": null,
            "origin_postal_code": null,
            "origin_raw_location": "China",
            "destination_state": null,
            "destination_city": null,
            "destination_postal_code": null,
            "aftership_estimated_delivery_date": null,
            "destination_raw_location": "Russia, Russian Federation",
            "latest_estimated_delivery": null,
            "courier_connection_id": null,
            "shipment_tags": [],
            "next_couriers": [],
            "on_time_status": null,
            "on_time_difference": null,
            "tracking_account_number": null,
            "tracking_origin_country": null,
            "tracking_destination_country": null,
            "tracking_key": null,
            "tracking_postal_code": null,
            "tracking_ship_date": null,
            "tracking_state": null
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
