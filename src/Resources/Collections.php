<?php
/**
 * Copyright (c) 2017 PreFiXAUT
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Kamui\Resources;

use Kamui\API;
use Kamui\BaseResource;

class Collections extends BaseResource
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
        
        $this->api->scope = 'collections_edit';
        return $this->api->sendPostJson("channels/{$id}/collections", $args, array(), true);
    }
    
    public function update($collection, $args = array())
    {
        $id = $this->api->getCollectionID($collection);
        if (!$id || !is_string($title))
            return false;
        
        $this->api->scope = 'collections_edit';
        return $this->api->sendPutJson("collections/{$id}", $args, array(), true);
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
        
        $this->api->scope = 'collections_edit';
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
        
        $this->api->scope = 'collections_edit';
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
        
        $this->api->scope = 'collections_edit';
        $this->sendPutJson("collections/{$collection_id}/items/{$item_id}", $data, array(), true);
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
        
        $this->api->scope = 'collections_edit';
        return $this->sendPutJson("collections/{$collection_id}/thumbnail", $data, array(), true);
    }
}
