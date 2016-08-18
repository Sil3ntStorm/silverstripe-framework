<?php

namespace SilverStripe\Forms\GridField;

use SilverStripe\Core\Convert;
use SilverStripe\Forms\HiddenField;
use SilverStripe\ORM\DataList;

/**
 * This class is a snapshot of the current status of a {@link GridField}.
 *
 * It's designed to be inserted into a Form as a HiddenField and passed through
 * to actions such as the {@link GridField_FormAction}.
 *
 * @see GridField
 */
class GridState extends HiddenField {

	/**
	 * @var GridField
	 */
	protected $grid;

	/**
	 * @var GridState_Data
	 */
	protected $data = null;

	/**
	 * @param GridField $grid
	 * @param string $value json encoded string
	 */
	public function __construct($grid, $value = null) {
		$this->grid = $grid;

		if ($value) $this->setValue($value);

		parent::__construct($grid->getName() . '[GridState]');
	}

	/**
	 * @param mixed $d
	 * @return object
	 */
	public static function array_to_object($d) {
		if(is_array($d)) {
			return (object) array_map(function($item) {
				return GridState::array_to_object($item);
			}, $d);
		}

		return $d;
	}

	/**
	 * @param mixed $value
	 * @return $this
	 */
	public function setValue($value) {
		if (is_string($value)) {
			$this->data = new GridState_Data(json_decode($value, true));
		}

		parent::setValue($value);
		return $this;
	}

	/**
	 * @return GridState_Data
	 */
	public function getData() {
		if(!$this->data) {
			$this->data = new GridState_Data();
		}

		return $this->data;
	}

	/**
	 * @return DataList
	 */
	public function getList() {
		return $this->grid->getList();
	}

	/**
	 * Returns a json encoded string representation of this state.
	 *
	 * @return string
	 */
	public function Value() {
		if(!$this->data) {
			return json_encode(array());
		}

		return json_encode($this->data->toArray());
	}

	/**
	 * Returns a json encoded string representation of this state.
	 *
	 * @return string
	 */
	public function dataValue() {
		return $this->Value();
	}

	/**
	 *
	 * @return string
	 */
	public function attrValue() {
		return Convert::raw2att($this->Value());
	}

	/**
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->Value();
	}
}
