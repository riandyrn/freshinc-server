<?php

/*function debug($data)
{
	echo '<pre>';
	var_dump($data);
	echo '</pre>';
}*/

class Database
{
	
	private $conn;
	
	public function __construct($configs)
	{
		$this->conn = new mysqli($configs['host'], $configs['username'], $configs['password'], $configs['database'], $configs['port']);
		$this->conn->set_charset('utf8');
		if ($this->conn->connect_error) {
			die("Connection failed: " . $this->conn->connect_error);
		}
	}
	
	public function executeSelect($statement)
	{
		$ret = array();
		
		$query = $this->conn->query($statement);
		
		while($row = $query->fetch_assoc())
		{
			array_push($ret, $row);
		}
		
		return $ret;
	}
	
	public function executePreparedSelect($query, $data_array, $type)
	{
		$ret = array();
		
		$params[] = &$type;
		$n = count($data_array);

		for($i = 0; $i < $n; $i++)
		{
			$params[] = &$data_array[$i];
		}
		
		$preparedStatement = $this->conn->prepare($query);
		call_user_func_array(array($preparedStatement, 'bind_param'), $params);
		$preparedStatement->execute();
		
		$result = $preparedStatement->get_result();
		
		while($row = $result->fetch_assoc())
		{
			array_push($ret, $row);
		}
		
		return $ret;
	}
	
	public function executeIncrement($table_name, $fieldname_increment, $value_increment, $where_fieldname, $where_value)
	{
		return $this->executeIncDec($table_name, $fieldname_increment, $value_increment, $where_fieldname, $where_value, "+");
	}
	
	public function executeDecrement($table_name, $fieldname_increment, $value_decrement, $where_fieldname, $where_value)
	{
		return $this->executeIncDec($table_name, $fieldname_increment, $value_increment, $where_fieldname, $where_value, "-");
	}
	
	private function executeIncDec($table_name, $fieldname_increment, $value_increment, $where_fieldname, $where_value, $operator)
	{
		$statement = "UPDATE " . $table_name . " SET " . $fieldname_increment . " = " . $fieldname_increment . " " . $operator . " " . $value_increment;
		$statement = $statement . " WHERE " . $where_fieldname . " = '" . $where_value . "'";
		
		return $this->conn->query($statement);
	}
	
	public function executeUpdate($table_name, $data_array, $where_field, $where_value)
	{
		$statement = "UPDATE " . $table_name . " SET ";
		$keys = array_keys($data_array);
		$i = 0;
		
		foreach($data_array as $value)
		{
			$statement = $statement . $keys[$i] . "='" . $value . "'";
			if($i < count($data_array) - 1)
			{
				$statement = $statement . ", ";
			}
			else 
			{
				$statement = $statement . " ";
			}
			
			$i++;
		}
		
		if($where_field)
		{
			$statement = $statement . "WHERE " . $where_field . "='" . $where_value . "'";
		}
		
		return $this->conn->query($statement);
	}
	
	public function executeDelete($table_name, $arr_conditions = array(), $logic_operator = null)
	{
		/*
		 * $field_name --> where field
		 * $operator --> =, >, <, >=, <=
		 * $value -> nilai
		 * 
		 * $condition = array
		 * (
		 * 		array('field' => $field_name, 'operator' => $operator, 'value' => $value)
		 * );
		 * 
		 * $logic_operator = 'AND' atau 'OR'
		 */
		$statement = "DELETE FROM " . $table_name;
		
		if(count($arr_conditions) > 0)
		{
			$statement = $statement . " WHERE ";
			for($i = 0; $i < count($arr_conditions); $i++)
			{
				$statement = $statement . $arr_conditions[$i]['field'] 
								. $arr_conditions[$i]['operator'] . "'" 
									. $arr_conditions[$i]['value'] . "'";
				
				if($i < count($arr_conditions) - 1)
				{
					$statement = $statement . ' ' . $logic_operator . ' ';
				}
			}		
		}
		
		return $this->conn->query($statement);
	}
	
	public function executeQuery($query)
	{
		return $this->conn->query($query);
	}
	
	public function executeInsert($table_name, $data_array)
	{
		$keys = array_keys($data_array);
		$statement = "INSERT INTO " . $table_name . " (";
		
		for($i = 0; $i < count($keys); $i++)
		{
			$statement = $statement . $keys[$i];
			if($i < count($keys) - 1)
			{
				 $statement = $statement . ", ";
			}
		}
		
		$statement = $statement . ") VALUES (";
		
		$i = 0;
		foreach($data_array as $value)
		{
			$statement = $statement . "'" . $value . "'";
			if($i < count($data_array) - 1)
			{
				$statement = $statement . ', ';
			}
			$i++;
		}
		
		$statement = $statement . ")";
		
		return $this->conn->query($statement);
	}
	
	public function executePreparedInsert($table_name, $data_array, $type)
	{
		$keys = array_keys($data_array);
		$statement = "INSERT INTO " . $table_name . " (";
		
		for($i = 0; $i < count($keys); $i++)
		{
			$statement = $statement . $keys[$i];
			if($i < count($keys) - 1)
			{
				$statement = $statement . ", ";
			}
		}
		
		$statement = $statement . ") VALUES (";
		
		for($i = 0; $i < count($data_array); $i++)
		{
			$statement = $statement . "?";
			if($i < count($data_array) - 1)
			{
				$statement = $statement . ', ';
			}
		}

		$statement = $statement . ")";
		
		$parameters = array();
		$parameters[] = &$type;
		
		foreach($keys as $key)
		{
			$parameters[] = &$data_array[$key];
		}
		
		$preparedStatement = $this->conn->prepare($statement);
		call_user_func_array(array($preparedStatement, 'bind_param'), $parameters);
		
		return $preparedStatement->execute();
	}
	
	public function tryPreparedInsert()
	{
		$table_name = "tagViewLog";
		$data_array = array
		(
			'tag' => 'LX',
			'user_id' => 28,
			'date' => date('Y-m-d', strtotime('now')),	
		);
		$type = 'sis';
		
		return $this->executePreparedInsert($table_name, $data_array, $type);
	}
	
	public function __destruct()
	{
		$this->conn->close();
	}
	
	public function tryPreparedSelect()
	{		
		$query = "SELECT * FROM cityViewSummary WHERE city=?";
		$data_array = array("Bandung");
		$type = "s";
		
		return $this->executePreparedSelect($query, $data_array, $type);
	}
	
}


/*$configs = array
(
	'host' => 'west2-mysql-haraj.cfqzarocxlac.us-west-2.rds.amazonaws.com',
	'username' => 'riandyrn',
	'password' => '08156039704a!',
	'database' => 'db_haraj',
	'port' => 3306
);

$db = new Database($configs);

//debug($db->executeSelect('SELECT * FROM adViewSummary'));
var_dump($db->tryPreparedSelect());
//var_dump($db->executeSelect("SELECT * FROM cityViewSummary WHERE city='Bandung' AND views=15"));
//var_dump($db->tryPreparedInsert());*/