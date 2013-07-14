<?php

namespace HashtagTodo\Entity;

class Todo {

	private $id;
	private $title;
	private $description;

	public function toArray() {
		return array(
			"id" => $this->getId(),
			"title" => $this->getTitle(),
			"description" => $this->getDescription(),
		);
	}

	public function setId($id) {
		$this->id = $id;
	}

	public function setTitle($title) {
		$this->title = $title;
	}

	public function setDescription($description) {
		$this->description = $description;
	}

	public function getId () {
		return $this->id;
	}

	public function getTitle () {
		return $this->title;
	}

	public function getDescription () {
		return $this->description;
	}

}
