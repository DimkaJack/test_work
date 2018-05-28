<?php

class ProductFactory{
    public function create(Item $item)
    {
        switch ($item->name){
            case 'Aged Brie':
                return new AgedBrie($item);

            case 'Backstage passes to a TAFKAL80ETC concert':
                return new Backstage($item);

            case 'Sulfuras, Hand of Ragnaros':
                return new Sulfuras($item);

            case 'Conjured Mana Cake':
                return new Conjured($item);

            default:
                return new Product($item);
        }
    }
}

class Product{
    const QUALITY_ITERATOR = 1,
        SELL_IN_ITERATOR = 1,
        MAX_QUALITY = 50,
        MIN_QUALITY = 0;

    protected $item;
    public $quality_iterator = 1;
    public $sell_in_iterator = 1;
    public $iterate = false;
    public $passed_quality_iterator = 2;
    public $passed_sell_in_iterator = 1;
    public $passed_iterate = false;

    public function __construct(Item $item)
    {
        $this->item = $item;
    }

    public function getIterators($sell_in)
    {
        if( $sell_in >= 0){
            return [
                $this->quality_iterator,
                $this->sell_in_iterator,
                $this->iterate,
            ];
        }

        return [
            $this->passed_quality_iterator,
            $this->passed_sell_in_iterator,
            $this->passed_iterate,
        ];
    }

    public function update_quality()
    {
        list($quality_iterator, $sell_in_iterator, $iterate) = $this->getIterators($this->item->sell_in);

        $iterator = $quality_iterator * self::QUALITY_ITERATOR;
        if( $iterate ){
            $this->item->quality += $iterator;
        }else{
            $this->item->quality -= $iterator;
        }

        $iterator = $sell_in_iterator * self::SELL_IN_ITERATOR;

        $this->item->sell_in -= $iterator;

        if( $this->item->quality < self::MIN_QUALITY ){
            $this->item->quality = self::MIN_QUALITY;
        }

        if( $this->item->quality > self::MAX_QUALITY ){
            $this->item->quality = self::MAX_QUALITY;
        }
    }
}

class AgedBrie extends Product {
    public $iterate = true;
    public $passed_quality_iterator = 1;
    public $passed_iterate = true;
}

class Backstage extends Product {
    public $iterate = true;

    public function update_quality()
    {
        parent::update_quality();

        if ($this->item->sell_in < 11) {
            if ($this->item->quality < self::MAX_QUALITY) {
                $this->item->quality = $this->item->quality + 1;
            }
        }
        if ($this->item->sell_in < 6) {
            if ($this->item->quality < self::MAX_QUALITY) {
                $this->item->quality = $this->item->quality + 1;
            }
        }
    }
}

class Sulfuras extends Product {
    public $quality_iterator = 0;
    public $sell_in_iterator = 0;
    public $passed_quality_iterator = 0;
    public $passed_sell_in_iterator = 0;
}

class Conjured extends Product {
    public $quality_iterator = 2;
    public $passed_quality_iterator = 4;
}

class GildedRose {

    private $items;

    function __construct($items) {
        $this->items = $items;
    }

    function update_quality() {
        foreach ($this->items as $item) {

            $product = (new ProductFactory())->create($item);

            $product->update_quality();

        }
    }
}



class Item {

    public $name;
    public $sell_in;
    public $quality;

    function __construct($name, $sell_in, $quality) {
        $this->name = $name;
        $this->sell_in = $sell_in;
        $this->quality = $quality;
    }

    public function __toString() {
        return "{$this->name}, {$this->sell_in}, {$this->quality}";
    }

}

