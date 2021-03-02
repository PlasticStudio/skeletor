<?php

namespace Skeletor\Extensions;

use SilverStripe\Core\ClassInfo;
use SilverStripe\ORM\DataExtension;

class BaseElementExtension extends DataExtension {

	public function ElementCacheKey()
	{
		$fragments = [
			sprintf(
                '%s_%s',
                ClassInfo::ShortName($this->owner->getClassName()),
                $this->owner->ID
            ),
			$this->owner->LastEdited
		];
		return implode('-_-', $fragments);
	}

}