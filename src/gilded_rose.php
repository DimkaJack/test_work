<?php

/**
 * Class ProductFactory
 *
 * Имеет смысл для таких задач использовать базу данных, но в задаче не было это указано.
 * Первоначально хотел использовать массивы, но вышло много кривого кода.
 * Поэтому использовал фабрику, создавать абстракции не стал, т.к. кажутся здесь избыточными
 */
class ProductFactory{

    /**
     * Создание нужного класса на основе имени продукта.
     *
     * @param Item $item
     * @return AgedBrie|Backstage|Conjured|BaseQualityUpdater|Sulfuras
     */
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
                return new BaseQualityUpdater($item);
        }
    }
}


/**
 * Базовый класс для изменения качества и срока хранения
 *
 * Class Product
 */
class BaseQualityUpdater{

    const QUALITY_ITERATOR = 1,
        SELL_IN_ITERATOR = 1,
        MAX_QUALITY = 50,
        MIN_QUALITY = 0;

    protected $item;

    /**
     * Множитель на который увеличиваем QUALITY_ITERATOR
     * @var int
     */
    public $quality_iterator = 1;

    /**
     * Множитель на который увеличиваем SELL_IN_ITERATOR
     * @var int
     */
    public $sell_in_iterator = 1;

    /**
     * Если true - увеличиваем качество, если false - уменьшаем
     * @var bool
     */
    public $iterate = false;

    /**
     * Множитель для просроченных товаров
     * @var int
     */
    public $passed_quality_iterator = 2;

    /**
     * Множитель для просроченных товаров
     * @var int
     */
    public $passed_sell_in_iterator = 1;

    /**
     * Аналогично $iterate, но для для просроченных товаров
     * @var bool
     */
    public $passed_iterate = false;

    public function __construct(Item $item)
    {
        $this->item = $item;
    }

    /**
     * Определяем просрок и получаем необходимые множители
     *
     * @param $sell_in
     * @return array
     */
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

    /**
     * Обновляем качество и срок
     */
    public function update()
    {
        list($quality_iterator, $sell_in_iterator, $is_iterate) = $this->getIterators($this->item->sell_in);

        $iterator = $quality_iterator * self::QUALITY_ITERATOR;
        if( $is_iterate ){
            $this->item->quality += $iterator;
        }else{
            $this->item->quality -= $iterator;
        }

        $iterator = $sell_in_iterator * self::SELL_IN_ITERATOR;
        $this->item->sell_in -= $iterator;

        //Качество не должно быть меньше минимума
        if( $this->item->quality < self::MIN_QUALITY ){
            $this->item->quality = self::MIN_QUALITY;
        }

        //Качество не должно быть больше максимума
        if( $this->item->quality > self::MAX_QUALITY ){
            $this->item->quality = self::MAX_QUALITY;
        }
    }
}


class AgedBrie extends BaseQualityUpdater {
    public $iterate = true;
    public $passed_quality_iterator = 1;
    public $passed_iterate = true;
}


class Backstage extends BaseQualityUpdater {
    public $iterate = true;

    /**
     * Переопределяем родительский метод, т.к. у Backstage дополнительные условия
     */
    public function update()
    {
        parent::update();

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


class Sulfuras extends BaseQualityUpdater {
    public $quality_iterator = 0;
    public $sell_in_iterator = 0;
    public $passed_quality_iterator = 0;
    public $passed_sell_in_iterator = 0;
}


class Conjured extends BaseQualityUpdater {
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

            $quality_updater = (new ProductFactory())->create($item);

            $quality_updater->update();

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

