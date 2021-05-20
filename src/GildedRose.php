<?php

declare(strict_types=1);

namespace GildedRose;

final class GildedRose
{
    const NAME_BRIE = 'Aged Brie';
    const NAME_SULFURAS = 'Sulfuras, Hand of Ragnaros';
    const NAME_TICKET = 'Backstage passes to a TAFKAL80ETC concert';
    const NAME_CAKE = 'Conjured Mana Cake';
    const MIN_QUALITY =  0;
    const MAX_QUALITY = 50;

    /**
     * @var Item[]
     */
    private array $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {

            switch ($item->name) {
                case self::NAME_SULFURAS:

                    break;
                case self::NAME_BRIE:
                    $this->updateSellIn($item);
                    $this->updateQualityBrie($item);
                    $this->normalizeItemQuality($item);

                    break;
                case self::NAME_TICKET:
                    $this->updateSellIn($item);
                    $this->updateQualityTicket($item);
                    $this->normalizeItemQuality($item);

                    break;
                case self::NAME_CAKE:
                    $this->updateSellIn($item);
                    $this->updateQualityCake($item);
                    $this->normalizeItemQuality($item);

                    break;
                default:
                    $this->updateSellIn($item);
                    $this->updateQualityDefault($item);
                    $this->normalizeItemQuality($item);

            }
        }
    }

    private function updateQualityBrie(Item $item): void
    {
        $item->quality += 1;
        if ($item->sell_in < 0) {
            $item->quality += 1;
        }
    }

    private function updateQualityTicket(Item $item): void
    {
        if ($item->sell_in < 0) {
            $item->quality = self::MIN_QUALITY;
        } elseif ($item->sell_in < 5) {
            $item->quality += 3;
        } elseif ($item->sell_in < 10) {
            $item->quality += 2;
        } else {
            $item->quality += 1;
        }
    }

    private function updateQualityCake(Item $item): void
    {
        $item->quality -= 2;
        if ($item->sell_in < 0) {
            $item->quality -= 2;
        }
    }

    private function updateQualityDefault(Item $item): void
    {
        $item->quality -= 1;
        if ($item->sell_in < 0) {
            $item->quality -= 1;
        }
    }

    private function updateSellIn(Item $item): void
    {
        $item->sell_in -= 1;
    }

    private function normalizeItemQuality(Item $item): void
    {
        if ($item->quality > self::MAX_QUALITY) {
            $item->quality = self::MAX_QUALITY;
        }
        if ($item->quality < self::MIN_QUALITY) {
            $item->quality = self::MIN_QUALITY;
        }
    }
}
