<?php

namespace HashtagTodo\Dao;

use Sigh\SimpleDB\Database;

use HashtagTodo\Entity\Todo;

class TodoSimpledbDao implements TodoDaoInterface {

	private $sdb;
	private $tablename = 'todos';

	public function __construct(Database $sdb, $tablename) {
		$this->sdb = $sdb;
		$this->tablename = $tablename;
	}

	public function findOne($id) {
		return $this->sdb[$this->tablename][$id] == null ? null : static::hashToTodo($this->sdb[$this->tablename][$id]);
	}

	public function findAll() {
		$todos = array();
		foreach ($this->sdb[$this->tablename]->selectAll() as $k => $v) {
			$todos[] = static::hashToTodo($v);
		}
		return $todos;
	}

	public function save($todo) {
		if (is_null($todo->getId())) {
			$hash = $this->sdb[$this->tablename]->insert(static::todoToHash($todo));
			$todo->setId($hash['id']);
		} else {
			$this->sdb[$this->tablename]->update(static::todoToHash($todo), $todo->getId());
		}
		return $todo;
	}

	public function delete($todo) {
		return $this->deleteById($todo->getId());
	}

	public function deleteById($id) {
		return $this->sdb[$this->tablename]->delete($id);
	}

	protected function todoToHash($todo) {
		return array(
			"id" => $todo->getId(),
			"title" => $todo->getTitle(),
			"description" => $todo->getDescription(),
		);
	}

	protected function hashToTodo($hash, Todo $todo = null) {
		if ($todo == null) {
			$todo = new Todo();
		}
		$todo->setId($hash['id']);
		$todo->setTitle($hash['title']);
		$todo->setDescription($hash['description']);
		return $todo;
	}

}
