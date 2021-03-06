<?php

declare(strict_types=1);

namespace Vityaz\Listeners;

use pocketmine\event\block\BlockBurnEvent;
use pocketmine\event\block\LeavesDecayEvent;
use pocketmine\event\entity\ExplosionPrimeEvent;
use pocketmine\event\Listener;
use pocketmine\event\server\QueryRegenerateEvent;
use Vityaz\Main;

class ServerListener implements Listener {

    private $core;
    public $count;

    public function __construct(Main $core) {
        $this->core = $core;
    }

    public function onQueryRegenerate(QueryRegenerateEvent $event): void {
        $cachedCount = $this->core->getVityazManager()->getPlayerCountManager()->getCachedPlayers();
        $hubCount = $this->core->getVityazManager()->getQueryUtil()->getHubCountAsInteger();
        $total = $cachedCount + $hubCount;
        $event->setPlayerCount($total);
        $event->setMaxPlayerCount(100);
    }

    public function onLeaveDecay(LeavesDecayEvent $event) {
        $block = $event->getBlock();
        $level = $block->getLevel();
        $event->setCancelled();
    }

    public function onBurn(BlockBurnEvent $event) {
        $block = $event->getBlock();
        $level = $block->getLevel()->getName();
        $event->setCancelled();
    }

    public function onPrimedExplosion(ExplosionPrimeEvent $event) {
        $event->setBlockBreaking(false);
    }
}
