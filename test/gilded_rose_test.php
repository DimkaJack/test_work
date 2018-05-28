<?php

use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase {

    function testGildedRoseUpdateQuality() {
        $items = [
            'Dexterity' => new Item('+5 Dexterity Vest', 4, 4),
            'Aged' => new Item('Aged Brie', 4, 4),
            'Elixir' => new Item('Elixir of the Mongoose', 4, 4),
            'Sulfuras' => new Item('Sulfuras, Hand of Ragnaros', 0, 4),
            'Sulfuras2' => new Item('Sulfuras, Hand of Ragnaros', -1, 4),
            'Backstage1' => new Item('Backstage passes to a TAFKAL80ETC concert', 15, 4),
            'Backstage2' => new Item('Backstage passes to a TAFKAL80ETC concert', 10, 4),
            'Backstage3' => new Item('Backstage passes to a TAFKAL80ETC concert', 5, 4),
            'Conjured' => new Item('Conjured Mana Cake', 4, 4)
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->update_quality();

        $this->assertEquals(3, $items['Dexterity']->sell_in);
        $this->assertEquals(3, $items['Dexterity']->quality);

        $this->assertEquals(3, $items['Aged']->sell_in);
        $this->assertEquals(5, $items['Aged']->quality);

        $this->assertEquals(3, $items['Elixir']->sell_in);
        $this->assertEquals(3, $items['Elixir']->quality);

        $this->assertEquals(0, $items['Sulfuras']->sell_in);
        $this->assertEquals(4, $items['Sulfuras']->quality);

        $this->assertEquals(-1, $items['Sulfuras2']->sell_in);
        $this->assertEquals(4, $items['Sulfuras2']->quality);

        $this->assertEquals(14, $items['Backstage1']->sell_in);
        $this->assertEquals(5, $items['Backstage1']->quality);

        $this->assertEquals(9, $items['Backstage2']->sell_in);
        $this->assertEquals(6, $items['Backstage2']->quality);

        $this->assertEquals(4, $items['Backstage3']->sell_in);
        $this->assertEquals(7, $items['Backstage3']->quality);

        $this->assertEquals(3, $items['Conjured']->sell_in);
        $this->assertEquals(2, $items['Conjured']->quality);
    }

    function testGildedRoseUpdateQualityPassed() {
        $items = [
            'Dexterity' => new Item('+5 Dexterity Vest', -1, 4),
            'Aged' => new Item('Aged Brie', -1, 4),
            'Elixir' => new Item('Elixir of the Mongoose', -1, 4),
            'Backstage' => new Item('Backstage passes to a TAFKAL80ETC concert', -1, 4),
            'Conjured' => new Item('Conjured Mana Cake', -1, 4)
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->update_quality();

        $this->assertEquals(-2, $items['Dexterity']->sell_in);
        $this->assertEquals(2, $items['Dexterity']->quality);

        $this->assertEquals(-2, $items['Aged']->sell_in);
        $this->assertEquals(5, $items['Aged']->quality);

        $this->assertEquals(-2, $items['Elixir']->sell_in);
        $this->assertEquals(2, $items['Elixir']->quality);

        $this->assertEquals(-2, $items['Backstage']->sell_in);
        $this->assertEquals(4, $items['Backstage']->quality);


        $this->assertEquals(-2, $items['Conjured']->sell_in);
        $this->assertEquals(0, $items['Conjured']->quality);
    }

}
