<?php


namespace GildedRose;


class ItemBrieHandler implements ItemHandlerInterface
{
    public const NAME_BRIE = 'Aged Brie';
    public const MIN_QUALITY = 0;

    public const MAX_QUALITY = 50;

    public function updateQuality(Item $item): void
    {
        $this->updateSellIn($item);
        $this->updateQualityBrie($item);
        $this->normalizeItemQuality($item);
    }

    public function supports(Item $item): bool
    {
        return stripos($item->name, self::NAME_BRIE) !== false;
    }

    private function updateSellIn(Item $item): void
    {
        --$item->sell_in;
    }

    private function updateQualityBrie(Item $item): void
    {
        ++$item->quality;
        if ($item->sell_in < 0) {
            ++$item->quality;
        }
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