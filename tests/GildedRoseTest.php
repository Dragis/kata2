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
            new Item('+5 Dexterity Vest', $sellIn, 20),
            new Item('Aged Brie', $sellIn, 20),
            new Item('Backstage passes to a TAFKAL80ETC concert', $sellIn, 20),
            new Item('Conjured Mana Cake', $sellIn, 20),
        ];
        $gildedRose = new GildedRose($items);

        for ($i = 0; $i < 3; $i++) {
            $gildedRose->updateQuality();
            $sellIn--;
            foreach ($items as $item) {
                $this->assertSame($sellIn, $item->sell_in);
            }
        }
    }

    public function testSellByDateSulfuras(): void
    {
        $sellIn = 1;
        $items = [
            new Item('Sulfuras, Hand of Ragnaros', $sellIn, 20),
        ];
        $gildedRose = new GildedRose($items);

        for ($i = 0; $i < 3; $i++) {
            $gildedRose->updateQuality();
            $this->assertSame($sellIn, $items[0]->sell_in);
        }
    }

    public function testNormalItemQualityBeforeSellByDate(): void
    {
        $items = [
            new Item('+5 Dexterity Vest', 10, 20),
        ];
        $gildedRose = new GildedRose($items);

        $gildedRose->updateQuality();
        $this->assertSame(19, $items[0]->quality);
    }

    public function testNormalItemQualityOnSellByDate(): void
    {
        $items = [
            new Item('+5 Dexterity Vest', 0, 20),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertSame(18, $items[0]->quality);
    }

    public function testNormalItemQualityAfterSellByDate(): void
    {
        $items = [
            new Item('+5 Dexterity Vest', -5, 20),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertSame(18, $items[0]->quality);
    }

    public function testNormalItemQualityNeverBelow0(): void
    {
        $items = [
            new Item('+5 Dexterity Vest', -5, 0),
            new Item('+5 Dexterity Vest', -5, 1),
            new Item('+5 Dexterity Vest', 0, 1),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        foreach ($items as $item) {
            $this->assertSame(0, $item->quality);
        }
    }

    public function testAgedBrieQualityBeforeSellByDate(): void
    {
        $items = [
            new Item('Aged Brie', 10, 20),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertSame(21, $items[0]->quality);
    }

    public function testAgedBrieOnSellByDate(): void
    {
        $items = [
            new Item('Aged Brie', 0, 20),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertSame(22, $items[0]->quality);
    }

    public function testAgedBrieAfterSellByDate(): void
    {
        $items = [
            new Item('Aged Brie', -5, 20),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertSame(22, $items[0]->quality);
    }

    public function testAgedBrieQualityNeverAbove50(): void
    {
        $items = [
            new Item('Aged Brie', -5, 50),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        foreach ($items as $item) {
            $this->assertSame(50, $item->quality);
        }
    }

    public function testSulfurasQuality(): void
    {
        $items = [
            new Item('Sulfuras, Hand of Ragnaros', -10, 80),
            new Item('Sulfuras, Hand of Ragnaros', 0, 80),
            new Item('Sulfuras, Hand of Ragnaros', 10, 80),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        foreach ($items as $item) {
            $this->assertSame(80, $item->quality);
        }
    }

    public function testConcertTickets15DaysLeft(): void
    {
        $items = [
            new Item('Backstage passes to a TAFKAL80ETC concert', 15, 20),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertSame(21, $items[0]->quality);
    }

    public function testConcertTickets10DaysLeft(): void
    {
        $items = [
            new Item('Backstage passes to a TAFKAL80ETC concert', 10, 20),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertSame(22, $items[0]->quality);
    }

    public function testConcertTickets5DaysLeft(): void
    {
        $items = [
            new Item('Backstage passes to a TAFKAL80ETC concert', 5, 20),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertSame(23, $items[0]->quality);
    }

    public function testConcertTickets0DaysLeft(): void
    {
        $items = [
            new Item('Backstage passes to a TAFKAL80ETC concert', 0, 20),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertSame(0, $items[0]->quality);
    }

    public function testConcertTicketsAfterSellByDate(): void
    {
        $items = [
            new Item('Backstage passes to a TAFKAL80ETC concert', -5, 20),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertSame(0, $items[0]->quality);
    }

    public function testConjuredItemBeforeSellByDate(): void
    {
        $items = [
            new Item('Conjured Mana Cake', 10, 20),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertSame(18, $items[0]->quality);
    }

    public function testConjuredItemOnSellByDate(): void
    {
        $items = [
            new Item('Conjured Mana Cake', 0, 20),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertSame(16, $items[0]->quality);
    }

    public function testConjuredItemAfterSellByDate(): void
    {
        $items = [
            new Item('Conjured Mana Cake', -5, 20),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertSame(16, $items[0]->quality);
    }

    public function testConjuredItemQualityNeverBelow0(): void
    {
        $items = [
            new Item('Conjured Mana Cake', -5, 0),
        ];
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        $this->assertSame(0, $items[0]->quality);
    }

    /**
     * @dataProvider getItemsData
     */
    public function testShouldCalculateCorrectValueForGivenItems(Item $item, int $sellIn, int $quality): void
    {
        $gildedRose = new GildedRose([$item]);
        $gildedRose->updateQuality();

        $this->assertEquals($item->sell_in, $sellIn);
        $this->assertEquals($item->quality, $quality);
    }

    public function getItemsData(): array
    {
        return [
            'Default Vest' => [new Item('+5 Dexterity Vest', 10, 20), 9, 19,],
            'Default Chest' => [new Item('+10 Strength Chest', 10, 20), 9, 19,],
            'Default Necklace' => [new Item('+10 Strength Necklace', 10, 20), 9, 19,],
            'Sulfuras of Ragnaros' => [new Item('Sulfuras, Hand of Ragnaros', 0, 80), 0, 80,],
            'Sulfuras of Verban' => [new Item('Sulfuras, Head of Verban', 0, 80), 0, 80,],
            'Sulfuras of Legendary' => [new Item('Sulfuras, Legendary Rear Vest of Thar', -100, 75), -100, 75,],
            'Sulfuras of Magi' => [new Item('Sulfuras Sword of Magi', -50, 80), -50, 80,],
            'Backstage to TAFKAL80ETC' => [new Item('Backstage passes to a TAFKAL80ETC concert', 5, 49), 4, 50,],
            'Backstage to SEL' => [new Item('Backstage passes to a SEL concert', 5, 49), 4, 50,],
            'Backstage to Dinamika' => [new Item('Backstage passes to a Dinamika concert', 5, 49), 4, 50,],
            'Backstage to Rondo' => [new Item('backstage Passes to a Rondo concert', 5, 49), 4, 50,],
            'Conjured Cake' => [new Item('Conjured Mana Cake', 3, 6), 2, 4,],
            'Same Conjured Cake' => [new Item('Conjured, Mana Cake', 3, 6), 2, 4,],
            'Same Conjured Pie' => [new Item('conjured, Vitality Pake', 3, 6), 2, 4,],
            'Conjured Doughnut' => [new Item('Conjured Mana Doughnut', 3, 6), 2, 4,],
            'Conjured Pie' => [new Item('Conjured, Pie of Honesty', 3, 6), 2, 4,],
        ];
    }
}
