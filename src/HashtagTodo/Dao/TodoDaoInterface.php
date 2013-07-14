<?php

namespace HashtagTodo\Dao;

interface TodoDaoInterface {
	function findOne($id);
	function findAll();
	function save($todo);
	function delete($todo);
}
