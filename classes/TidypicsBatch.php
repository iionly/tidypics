<?php
/**
 * TidypicsBatch class
 *
 */

class TidypicsBatch extends ElggObject {

	/**
	 * A single-word arbitrary string that defines what
	 * kind of object this is
	 *
	 * @var string
	 */
	const SUBTYPE = 'tidypics_batch';

	protected function initializeAttributes() {
		parent::initializeAttributes();

		parent::initializeAttributes();

		$this->attributes['subtype'] = self::SUBTYPE;
	}

	public function __construct($guid = null) {
		parent::__construct($guid);
	}
}
