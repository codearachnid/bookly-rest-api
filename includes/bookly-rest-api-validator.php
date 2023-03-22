<?php

/**
 * Used to validate the WP plugins against Envato.
 *
 * @since      1.0.0
 * @package    bookly
 * @subpackage Bookly_Rest_Api/includes
 * @author     PDG Solutions <info@pdg.solutions>
 */
class Bookly_Rest_Api_Validator
{
    /**
     * Envato API base URL
     * @var string
     */
    protected $apiUrl = 'https://api.envato.com/v3/market';

    /**
     * Envato item ID
     * @var string
     */
    protected $itemId;

    /**
     * Initialize Bookly validator
     * @param string $apiKey
     * @param string $itemId
     */
    public function __construct($apiKey, $itemId)
    {
        $this->apiKey = $apiKey;
        $this->itemId = $itemId;
    }

    /**
     * General purpose function to query the Envato API.
     *
     * @since   1.0.0
     * @param   string $url     The url to access, via curl.
     * @return  array           Request result.
     */
    protected function curl($url)
    {
        // Send request to envato to verify purchase
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; Envato API Wrapper PHP)');

        $header   = [];
        $header[] = 'Content-length: 0';
        $header[] = 'Content-type: application/json';
        $header[] = 'Authorization: Bearer '. $this->apiKey;

        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        $data = curl_exec($ch);

        if (curl_errno($ch) > 0) {
            return ['error' => "Failed to query Envato API: ".curl_error($ch)];
        }

        curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return json_decode($data, true);
    }

    /**
     * Verify purchase code against Envato API.
     *
     * @since   1.0.0
     * @param   string  $purchaseCode
     * @return  bool
     */
    public function isValid($purchaseCode)
    {
        if (file_exists(BOOKLY_REST_API_PLUGIN_PATH.'.development')) {
            return true;
        }

        // Gets author data & prepare verification vars
        $purchaseCode = urlencode(trim($purchaseCode));

        $url = $this->apiUrl.'/author/sale?code='.$purchaseCode;

        $response = $this->curl($url);

        /*
        On success response
        ===
        {
          "amount": "19.84",
          "sold_at": "2016-09-07T10:54:28+10:00",
          "license": "Regular License",
          "support_amount": "0.00",
          "supported_until": "2017-03-09T01:54:28+11:00",
          "item": {
            "id": 17022701,
            "name": "SEO Studio - Professional Tools for SEO",
            "author_username": "baileyherbert",
            "updated_at": "2017-11-02T15:57:41+11:00",
            "site": "codecanyon.net",
            "price_cents": 2000,
            "published_at": "2016-07-13T19:07:03+10:00"
          },
          "buyer": "some_buyer_name",
          "purchase_count": 1
        }

        On error response
        ===
        {
            "error": 404,
            "description": "No sale belonging to the current user found with that code"
        }
        */

        return (
            !isset($response['error']) &&
            (isset($response['item']) && $response['item']['id'] === $this->itemId)
        );
    }
}
