<?php

class Content_Model_Model extends Itvina_Model_Abstract
{
	protected $model_id;
	protected $model_name;
	protected $model_category_id;
	protected $model_content;

	
	public function toArrayList() {
		$all = $this->fetchAllBase();
		$model = array();
		foreach ($all as $row) {
			$model["$row->model_id"]= trim($row->model_name);
		}
		return $model;
	}
	
	/**
	 * @return the $model_id
	 */
	public function getModel_id() {
		return $this->model_id;
	}

	/**
	 * @return the $model_name
	 */
	public function getModel_name() {
		return $this->model_name;
	}

	/**
	 * @return the $model_category
	 */
	public function getModel_category_id() {
		return $this->model_category_id;
	}

	/**
	 * @return the $model_content
	 */
	public function getModel_content() {
		return $this->model_content;
	}
	
	/**
	 * @param field_type $model_id
	 */
	public function setModel_id($model_id) {
		$this->model_id = $model_id;
		return $this;
	}
	
	/**
	 * @param field_type $model_id
	 */
	public function setModel_name($model_name) {
		$this->model_name = $model_name;
		return $this;
	}
	
	/**
	 * @param field_type $model_id
	 */
	public function setModel_category_id($model_category_id) {
		$this->model_category_id = $model_category_id;
		return $this;
	}
	
	/**
	 * @param field_type $model_content
	 */
	
	public function setModel_content($model_content) {
		$this->model_content = $model_content;
		return $this;
	}
}