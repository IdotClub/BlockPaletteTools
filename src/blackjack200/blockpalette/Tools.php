<?php


namespace blackjack200\blockpalette;


use pocketmine\network\mcpe\convert\RuntimeBlockMapping;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\plugin\PluginBase;
use ReflectionClass;
use ReflectionProperty;

class Tools extends PluginBase {
	public function onLoad() : void {
		RuntimeBlockMapping::init();
		$klass = new ReflectionClass(RuntimeBlockMapping::class);
		$l = $klass->getProperty('legacyToRuntimeMap');
		$l->setAccessible(true);
		$r = $klass->getProperty('runtimeToLegacyMap');
		$r->setAccessible(true);
		$path = $this->getDataFolder();
		$this->dump($path, $l);
		$this->dump($path, $r);
	}

	private function dump(string $path, ReflectionProperty $l) : void {
		$name = sprintf("%s_%s.json", ProtocolInfo::CURRENT_PROTOCOL, $l->getName());
		file_put_contents($path . $name, json_encode($l->getValue(), JSON_THROW_ON_ERROR));
		$this->getLogger()->notice("DUMP: $name");
	}
}