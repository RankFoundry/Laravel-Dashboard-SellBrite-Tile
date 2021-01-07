<?php

namespace RankFoundry\SellBriteTile\Services;

use GuzzleHttp\Client;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

class SellBrite
{
    private static $base_url = 'https://api.sellbrite.com/v1/';

    public static function getFulfillments(string $api_token, string $api_key, string $warehouse): array
    {
        
        $client = new Client();
        $response = $client->request('GET', self::$base_url.'warehouses/fulfillments/'.$warehouse, [
            'auth' => [
                $api_token,
                $api_key
            ],
            'query' => [
                'sb_payment_status' => 'all'
            ]
        ]);
        
        $orders = json_decode($response->getBody()->getContents());
        $products = array();
        
        if(count($orders) > 0){
            foreach ($orders as $order){
                foreach($order->items as $item){
                    if(array_key_exists($item->sku,$products)){
                        $products[$item->sku]['qty'] = $products[$item->sku]['qty'] + $item->quantity;
                    }else{
                        $products[$item->sku] = array(
                            'title' => substr($item->title,0,25),
                            'sku' => $item->inventory_sku,
                            'bin' => self::getBinLocation($item->inventory_sku,$warehouse,$api_token,$api_key),
                            'qty' => $item->quantity,
                            'image' => self::getPrimaryImage($item->inventory_sku,$api_token,$api_key)
                        );
                    }
                }
            }
        }
        

        return $products;
    }
    
    public static function getBinLocation(string $sku, string $warehouse, string $api_token, string $api_key)
    {
        $client = new Client();        
        $response = $client->request('GET', self::$base_url.'inventory', [
            'auth' => [
                $api_token,
                $api_key
            ],
            'query' => [
                'warehouse_uuid' => $warehouse,
                'sku' => $sku
            ]
        ]);
        
        $obj = json_decode($response->getBody()->getContents());
        
        return $obj[0]->bin_location;
    }
    
    public static function getPrimaryImage(string $sku, string $api_token, string $api_key)
    {
        $client = new Client();        
        $response = $client->request('GET', self::$base_url.'products/'.$sku, [
            'auth' => [
                $api_token,
                $api_key
            ]
        ]);
        
        $obj = json_decode($response->getBody()->getContents(),true);
        
        $images = explode('|',$obj['image_list']);
        
        return $images[0];
    }
    
    public static function getSales(string $api_token, string $api_key): array
    {
        $today = CarbonImmutable::now('America/Los_Angeles')->locale('en_US');
        $now = CarbonImmutable::now('America/Los_Angeles')->locale('en_US');
        $yesterday = CarbonImmutable::yesterday('America/Los_Angeles')->locale('en_US');
        $daybefore = CarbonImmutable::yesterday('America/Los_Angeles')->locale('en_US')->subDay();
        $thisweek = $today->startOfWeek();
        $lastweek = $thisweek->subWeek();
        $thismonth = $today->startOfMonth();
        $lastmonth = $thismonth->subMonth();
        
        $todayStart = $today->startOfDay()->toIso8601String();
        $todayEnd = $today->endOfDay()->toIso8601String();
        $yesterdayStart = $yesterday->startOfDay()->toIso8601String();
        $yesterdayEnd = $yesterday->endOfDay()->toIso8601String();
        $daybeforeStart = $daybefore->startOfDay()->toIso8601String();
        $daybeforeEnd = $daybefore->endOfDay()->toIso8601String();
        $thisWeekStart = $thisweek->toIso8601String();
        $thisWeekEnd = $thisweek->endOfWeek()->toIso8601String();
        $lastWeekStart = $lastweek->toIso8601String();
        $lastWeekEnd = $lastweek->endOfWeek()->toIso8601String();
        $thisMonthStart = $thismonth->toIso8601String();
        $thisMonthEnd = $thismonth->endOfMonth()->toIso8601String();
        $lastMonthStart = $lastmonth->toIso8601String();
        $lastMonthEnd = $lastmonth->endOfMonth()->toIso8601String();
        
        $todayStats = self::getOrderStats($api_token,$api_key,$todayStart,$todayEnd);
        $yesterdayStats = self::getOrderStats($api_token,$api_key,$yesterdayStart,$yesterdayEnd);
        $daybeforeStats = self::getOrderStats($api_token,$api_key,$daybeforeStart,$daybeforeEnd);
        $thisWeekStats = self::getOrderStats($api_token,$api_key,$thisWeekStart,$thisWeekEnd);
        $lastWeekStats = self::getOrderStats($api_token,$api_key,$lastWeekStart,$lastWeekEnd);
        $thisMonthStats = self::getOrderStats($api_token,$api_key,$thisMonthStart,$thisMonthEnd);
        $lastMonthStats = self::getOrderStats($api_token,$api_key,$lastMonthStart,$lastMonthEnd);
        
        $data = array(
            '0' => array(
                'title' => 'Today',
                'date' => $today->toFormattedDateString(),
                'orders' => $todayStats['orders'],
                'items' => $todayStats['items'],
                'pending' => $todayStats['pending'],
                'gross' => $todayStats['gross'],
                'gross_diff' => ($todayStats['gross'] - $yesterdayStats['gross']),
                'cost' => '-',
                'profit' => '-',
                'margin' => '-'
            ),
            '1' => array(
                'title' => 'Yesterday',
                'date' => $yesterday->toFormattedDateString(),
                'orders' => $yesterdayStats['orders'],
                'items' => $yesterdayStats['items'],
                'pending' => $yesterdayStats['pending'],
                'gross' => $yesterdayStats['gross'],
                'gross_diff' => ($yesterdayStats['gross'] - $daybeforeStats['gross']),
                'cost' => '-',
                'profit' => '-',
                'margin' => '-'
            ),
            '2' => array(
                'title' => 'Week',
                'date' => $now->startOfWeek()->toFormattedDateString() .' - '. $now->endOfWeek()->toFormattedDateString(),
                'orders' => $thisWeekStats['orders'],
                'items' => $thisWeekStats['items'],
                'pending' => $thisWeekStats['pending'],
                'gross' => $thisWeekStats['gross'],
                'gross_diff' => ($thisWeekStats['gross'] - $lastWeekStats['gross']),
                'cost' => '-',
                'profit' => '-',
                'margin' => '-'
            ),
            '3' => array(
                'title' => 'Month',
                'date' => $now->startOfMonth()->toFormattedDateString() .' - '. $now->endOfMonth()->toFormattedDateString(),
                'orders' => $thisMonthStats['orders'],
                'items' => $thisMonthStats['items'],
                'pending' => $thisMonthStats['pending'],
                'gross' => $thisMonthStats['gross'],
                'gross_diff' => ($thisMonthStats['gross'] - $lastMonthStats['gross']),
                'cost' => '-',
                'profit' => '-',
                'margin' => '-'
            )
        );
        
        return $data;
    }
    
    public static function getOrderStats($api_token,$api_key,$start,$end): array
    {
        $data = array();
        $data['pending'] = 0;
        $data['gross'] = 0;
        $data['orders'] = 0;
        $data['items'] = 0;
        $data['cost'] = 0;
        
        $orders = TRUE;
        $page = 1;
        $limit = 100;
        while($orders){
            $curOrders = self::getOrders($api_token,$api_key,$limit,$page,$start,$end);
            
            if(count($curOrders) == $limit){
                $page++;
            }else{
                $orders = FALSE;
            }
            
            foreach($curOrders as $curOrder){
                if($curOrder->sb_status != 'canceled'){
                    if($curOrder->sb_payment_status == 'none'){
                        $data['pending'] = $data['pending'] + 1;
                    }else{
                        $items = 0;
                        foreach($curOrder->items as $item){
                            $items = $items + $item->quantity;
                            $itemCost = getItemCost($api_token,$api_key,$item->inventory_sku);
                            $data['cost'] = $data['cost'] + ($itemCost * $item->quantity);
                        }
                        
                        $data['gross'] = $data['gross'] + $curOrder->total;
                        $data['orders'] = $data['orders'] + 1;
                        $data['items'] = $data['items'] + $items;
                    }
                }
            }
        }
        
        return $data;
    }
    
    public static function getOrders(string $api_token, string $api_key, $limit, $page, $min_ordered_at = null, $max_ordered_at = null){
        $client = new Client();        
        $response = $client->request('GET', self::$base_url.'orders', [
            'auth' => [
                $api_token,
                $api_key
            ],
            'query' => [
                'limit' => $limit,
                'page' => $page,
                'min_ordered_at' => $min_ordered_at,
                'max_ordered_at' => $max_ordered_at
            ]
        ]);
        
        $obj = json_decode($response->getBody()->getContents());
        
        return $obj;
    }
    
    public static function getProducts(string $api_token, string $api_key, $limit, $page, $min_modified_at = null, $max_modified_at = null){
        $client = new Client();        
        $response = $client->request('GET', self::$base_url.'products', [
            'auth' => [
                $api_token,
                $api_key
            ],
            'query' => [
                'limit' => $limit,
                'page' => $page,
                'min_modified_at' => $min_modified_at,
                'max_modified_at' => $max_modified_at
            ]
        ]);
        
        $obj = json_decode($response->getBody()->getContents());
        
        return $obj;
    }
    
    public static function getInventory(string $api_token, string $api_key, $limit, $page, $warehouse = null){
        $client = new Client();        
        $response = $client->request('GET', self::$base_url.'inventory', [
            'auth' => [
                $api_token,
                $api_key
            ],
            'query' => [
                'limit' => $limit,
                'page' => $page,
                'warehouse_uuid' => $warehouse
            ]
        ]);
        
        $obj = json_decode($response->getBody()->getContents());
        
        return $obj;
    }
    
    
    
    public static function getItemCost(string $api_token, string $api_key, string $sku){
        $client = new Client();        
        $response = $client->request('GET', self::$base_url.'inventory', [
            'auth' => [
                $api_token,
                $api_key
            ],
            'query' => [
                'sku' => $sku
            ]
        ]);
        
        $obj = json_decode($response->getBody()->getContents());
        
        return $obj[0]->cost;
    }
    
    public static function getErrors(string $api_token, string $api_key, string $warehouse): array
    {
        $data = array();
        $data['missing_description'] = 0;
        $data['missing_images'] = 0;
        $data['missing_category'] = 0;
        $data['missing_cost'] = 0;
        $data['missing_location'] = 0;
        $data['total_products'] = 0;
        $data['total_active'] = 0;
        
        $products = TRUE;
        $page = 1;
        $limit = 100;
        while($products){
            $curProducts = self::getProducts($api_token,$api_key,$limit,$page);
            
            if(count($curProducts) == $limit){
                $page++;
            }else{
                $products = FALSE;
            }
            
            $data['total_products'] = $data['total_products'] + count($curProducts);
            
            foreach($curProducts as $curProduct){
                if($curProduct->quantity > 0){
                    $data['total_active'] = $data['total_active'] + 1;
                    
                    if($curProduct->description == null){
                        $data['missing_description'] = $data['missing_description'] + 1;
                    }

                    if($curProduct->image_list == ""){
                        $data['missing_images'] = $data['missing_images'] + 1;
                    }

                    if($curProduct->category_name == ""){
                        $data['missing_category'] = $data['missing_category'] + 1;
                    }
                }
            }
        }
        
        $inventory = TRUE;
        $invPage = 1;
        $invLimit = 100;
        while($inventory){
            $curItems = self::getInventory($api_token, $api_key, $invLimit, $invPage, $warehouse);
            
            if(count($curItems) == $invLimit){
                $invPage++;
            }else{
                $inventory = FALSE;
            }
            
            foreach($curItems as $curItem){
                if($curItem->on_hand > 0){
                    if($curItem->cost == ""){
                        $data['missing_cost'] = $data['missing_cost'] + 1;
                    }

                    if($curItem->bin_location == ""){
                        $data['missing_location'] = $data['missing_location'] + 1;
                    }
                }
            }
        }
        
        return $data;
    }
  
}