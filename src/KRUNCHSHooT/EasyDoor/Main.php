<?php

declare(strict_types=1);

namespace KRUNCHSHooT\EasyDoor;

use pocketmine\block\Air;
use pocketmine\block\Door;
use pocketmine\event\EventPriority;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\math\Facing;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase { 

    public function onEnable() : void {
        $this->getServer()->getPluginManager()->registerEvent(
            PlayerInteractEvent::class,
            function (PlayerInteractEvent $event) : void {
                $block = $event->getBlock();
                $player = $event->getPlayer();
                $item = $event->getItem();

                if($block instanceof Door){
                    if(!$player->isSneaking() || $item->isNull()){
                        if ($block->isHingeRight()) {
                            $next = $block->getSide(Facing::rotateY($block->getFacing(), false));
                            if ($next instanceof Door) {
                                $other = $next->getSide($next->isTop() ? Facing::DOWN : Facing::UP);
                                $world = $next->getPosition()->getWorld();
                                $next->setOpen(!$block->isOpen());
                                if ($other instanceof Door && $other->isSameType($next)) {
                                    $other->setOpen(!$block->isOpen());
                                    $world->setBlock($next->getPosition(), $next);
                                    $world->setBlock($other->getPosition(), $other);
                                }
                            }
                        } else {
                            $next2 = $block->getSide(Facing::rotateY($block->getFacing(), true));
                            if ($next2 instanceof Door) {
                                $other = $next2->getSide($next2->isTop() ? Facing::DOWN : Facing::UP);
                                $world = $next2->getPosition()->getWorld();
                                $next2->setOpen(!$block->isOpen());
                                if ($other instanceof Door && $other->isSameType($next2)) {
                                    $other->setOpen(!$block->isOpen());
                                    $world->setBlock($next2->getPosition(), $next2);
                                    $world->setBlock($other->getPosition(), $other);
                                }
                            }
                        }
                    } 
                }
            },
            EventPriority::HIGH,
            $this,
            true
        );
    }

}
