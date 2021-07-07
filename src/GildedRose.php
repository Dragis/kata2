<?php

declare(strict_types=1);

namespace GildedRose;

final class GildedRose
{
    public const NAME_BRIE = 'Aged Brie';

    public const NAME_SULFURAS = 'Sulfuras';

    public const NAME_TICKET = 'Backstage passes';

    public const NAME_CAKE = 'Conjured';

    public const MIN_QUALITY = 0;

    public const MAX_QUALITY = 50;

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

        $agedBrieHandler = new ItemBrieHandler();

        foreach ($this->items as $item) {
            switch (true) {
                case stripos($item->name, self::NAME_SULFURAS) !== false:

                    break;
                case $agedBrieHandler->supports($item) !== false:
                    $agedBrieHandler->updateQuality($item);

                    break;
                case stripos($item->name, self::NAME_TICKET) !== false:
                    $this->updateSellIn($item);
                    $this->updateQualityTicket($item);
                    $this->normalizeItemQuality($item);

                    break;
                case stripos($item->name, self::NAME_CAKE) !== false:
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
        ++$item->quality;
        if ($item->sell_in < 0) {
            ++$item->quality;
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
            ++$item->quality;
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
        --$item->quality;
        if ($item->sell_in < 0) {
            --$item->quality;
        }
    }

    private function updateSellIn(Item $item): void
    {
        --$item->sell_in;
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
