<?php

namespace Kamui\Resources;

use Kamui\API;
use Kamui\Resource;

class Collections extends Resource
{
    public function __construct(API $api)
    {
        parent::__construct($api);
    }
    
    public function get($collection)
    {
        $id = $this->api->getCollectionID($collection);
        if (!$id)
            return false;
        
        return $this->api->sendGet("collections/{$id}");
    }
    
    public function items($collection, $all = false)
    {
        $id = $this->api->getCollectionID($collection);
        if (!$id)
            return false;
        
        $args = array(
            'include_all_items' => (bool) $all,
        );
        
        return $this->api->sendGet("collections/{$id}/items", $args);
    }
    
    public function create($channel, $args = array())
    {
        $id = $this->api->getUserID($channel);
        if (!$id || !is_string($title))
            return false;
        
        return $this->api->sendPostJson("channels/{$id}/collections", $args, array(), true);
    }
    
    public function update($collection, $args = array())
    {
        $id = $this->api->getCollectionID($collection);
        if (!$id || !is_string($title))
            return false;
        
        return $this->api->sendPutJson("collections/{$id}", $args, array(), true);
    }
    
    public function thumbnail($collection, $item)
    {
        $collection_id = $this->api->getCollectionID($collection);
        $item_id = $this->api->getCollectionItemID($item);
        if (!$collection_id || !$item_id)
            return false;
        
        $data = array(
            'item_id'   => $item_id,
        );
        
        return $this->sendPutJson("collections/{$collection_id}/thumbnail", $data, array(), true);
    }
    
    public function delete($collection, $item = null)
    {
        $collection_id = $this->api->getCollectionID($collection);
        if (!$collection_id)
            return false;
        
        if (is_null($item)) {
            return $this->sendDelete("collections/{$collection_id}", array(), true);
        }
        
        $item_id = $this->api->getCollectionItemID($item);
        if (!$item_id)
            return false;
        
        return $this->sendDelete("collections/{$collection_id}/items/{$video_id}", array(), true);
    }
    
    public function add($collection, $item, $type = null)
    {
        $collection_id = $this->api->getCollectionID($collection);
        $item_id = $this->api->getCollectionItemID($item);
        if (!$collection_id || !$item_id)
            return false;
        
        if (is_null($type)) {
            $type = $this->api->getCollectionItemType($item);
        }
        
        $data = array(
            'id'    => $item_id,
            'type'  => $type,
        );
        
        return $this->sendPostJson("collections/{$collection_id}/items", $data, array(), true);
    }
    
    public function move($collection, $item, $position)
    {
        $collection_id = $this->api->getCollectionID($collection);
        $item_id = $this->api->getCollectionItemID($item);
        if (!$collection_id || !$item_id || !is_numeric($position))
            return false;
        
        $data = array(
            'position'  => intval($position),
        );
        
        $this->sendPutJson("collections/{$collection_id}/items/{$item_id}", $data, array(), true);
    }
}
