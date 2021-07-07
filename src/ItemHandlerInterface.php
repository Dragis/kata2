<?php


namespace GildedRose;


interface ItemHandlerInterface
{
    public function updateQuality(Item $item): void;

    public function supports(Item $item): bool;
}