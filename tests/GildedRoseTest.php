<?php

declare(strict_types=1);

namespace Tests;

use GildedRose\GildedRose;
use GildedRose\Item;
use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{

    public function testSellByDateDecrease(): void
    {
        $sellIn = 1;
        $items = [
            new Item("+5 Dexterity Vest", $sellIn, 20),
            new Item("Aged Brie", $sellIn, 20),
            new Item("Backstage passes to a TAFKAL80ETC concert", $sellIn, 20),
        ];
        $gildedRose = new GildedRose($items);

        for ($i = 0; $i < 3; $i++) {
            $gildedRose->updateQuality();
            $sellIn--;
            foreach ($items as $item) {
                $this->assertEquals($sellIn, $item->sell_in);
            }
        }
    }

    public function testSellByDateSulfuras(): void
    {
        $sellIn = 1;
        $items = [
            new Item("Sulfuras, Hand of Ragnaros", $sellIn, 20),
        ];
        $gildedRose = new GildedRose($items);

        for ($i = 0; $i < 3; $i++) {
            $gildedRose->updateQuality();
            $this->assertEquals($sellIn, $items[0]->sell_in);
        }
    }

    public function testNormalItemQualityBeforeSellByDate(): void
    {
        $items = [
            new Item("+5 Dexterity Vest", 10, 20),
        ];
        $gildedRose = new GildedRose($items);

        $gildedRose->updateQuality();
        $this->assertEquals(19, $items[0]->quality);
    }

    public function testNormalItemQualityOnSellByDate(): void
    {
        $items = [
            new Item("+5 Dexterity Vest", 0, 20),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(18, $items[0]->quality);
    }

    public function testNormalItemQualityAfterSellByDate(): void
    {
        $items = [
            new Item("+5 Dexterity Vest", -5, 20),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(18, $items[0]->quality);
    }

    public function testNormalItemQualityNeverBelow0(): void
    {
        $items = [
            new Item("+5 Dexterity Vest", -5, 0),
            new Item("+5 Dexterity Vest", -5, 1),
            new Item("+5 Dexterity Vest", 0, 1),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        foreach ($items as $item) {
            $this->assertEquals(0, $item->quality);
        }
    }

    public function testAgedBrieQualityBeforeSellByDate(): void
    {
        $items = [
            new Item("Aged Brie", 10, 20),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(21, $items[0]->quality);
    }

    public function testAgedBrieOnSellByDate(): void
    {
        $items = [
            new Item("Aged Brie", 0, 20),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(22, $items[0]->quality);
    }

    public function testAgedBrieAfterSellByDate(): void
    {
        $items = [
            new Item("Aged Brie", -5, 20),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(22, $items[0]->quality);
    }

    public function testAgedBrieQualityNeverAbove50(): void
    {
        $items = [
            new Item('Aged Brie', -5, 50),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        foreach ($items as $item) {
            $this->assertEquals(50, $item->quality);
        }
    }

    public function testSulfurasQuality(): void
    {
        $items = [
            new Item("Sulfuras, Hand of Ragnaros", -10, 80),
            new Item("Sulfuras, Hand of Ragnaros", 0, 80),
            new Item("Sulfuras, Hand of Ragnaros", 10, 80),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        foreach ($items as $item) {
            $this->assertEquals(80, $item->quality);
        }
    }

    public function testConcertTickets15DaysLeft(): void
    {
        $items = [
            new Item('Backstage passes to a TAFKAL80ETC concert', 15, 20),
            ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(21, $items[0]->quality);
    }

    public function testConcertTickets10DaysLeft(): void
    {
        $items = [
            new Item('Backstage passes to a TAFKAL80ETC concert', 10, 20),
            ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(22, $items[0]->quality);
    }

    public function testConcertTickets5DaysLeft(): void
    {
        $items = [
            new Item('Backstage passes to a TAFKAL80ETC concert', 5, 20),
            ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(23, $items[0]->quality);
    }

    public function testConcertTickets0DaysLeft(): void
    {
        $items = [
            new Item('Backstage passes to a TAFKAL80ETC concert', 0, 20),
            ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(0, $items[0]->quality);
    }

    public function testConcertTicketsAfterSellByDate(): void
    {
        $items = [
            new Item('Backstage passes to a TAFKAL80ETC concert', -5, 20),
            ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertEquals(0, $items[0]->quality);
    }

}
