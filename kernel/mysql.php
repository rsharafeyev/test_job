<?
$_CONF['DB_CONN'] = mysql_connect($_CONF['DB_CONF']['HOST'], $_CONF['DB_CONF']['USER'], $_CONF['DB_CONF']['PASS']) or die('Can\'t connect to DB');
$_CONF['DB_CODE'] = mysql_query('set names utf8');
$_CONF['DB_SELE'] = mysql_select_db($_CONF['DB_CONF']['BASE'], $_CONF['DB_CONN']) or die('Can\'t select DB!');

function mysql_insert($table,$data)
{
	global $MYSQL_SELECT_LIST;
	$keys = array();
	$vals = array();
	$sql = '';
	$i = 0;
	foreach ($data as $key => $val)
	{
		$keys[$i] = '`'.$key.'`';
		$vals[$i] = '\''.mysql_safeval($val).'\'';
        $i++;
	}
	$sql = 'INSERT INTO `'.$table.'` ('.implode(',',$keys).') VALUES ('.implode(',',$vals).')';
	mysql_query($sql);
	return(mysql_insert_id());
}

function mysql_update($table,$data,$where=false)
{
	global $MYSQL_SELECT_LIST;
	$set = array();
	$sql = '';
	$i = 0;
	foreach ($data as $key => $val)
	{
		$set[$i] = '`'.$key.'` = \''.mysql_safeval($val).'\'';
        $i++;
	}
	$sql = 'UPDATE `'.$table.'` SET '.implode(',',$set);
	if ($where){$sql .= ' WHERE '.$where;}
	mysql_query($sql);
	return($sql);
}

function mysql_delete($table,$where = '1=1')
{
	global $MYSQL_SELECT_LIST;
	$sql = 'DELETE FROM `'.$table.'` WHERE '.$where;
	mysql_query($sql);
	return($sql);
}

function mysql_select($table,$fields='*',$where=false,$order=false,$limit=false)
{
	global $MYSQL_SELECT_LIST;
	$data = array();
	if (is_array($fields)){$fields = implode(',',$fields);}
	$sql = 'SELECT '.$fields.' FROM '.$table;
	if ($where){$sql .= ' WHERE '.$where;}
	if ($order){$sql .= ' ORDER BY '.$order;}
	if ($limit){$sql .= ' LIMIT '.$limit;}
	$DB_DATA = mysql_query($sql);
	if ($DB_DATA)
	{
		while ($line = mysql_fetch_assoc($DB_DATA))
		{
			$data[] = $line;
		}
	} else
	{
		$data = false;
	}
	return($data);
}

function mysql_exec($sql)
{
	global $MYSQL_SELECT_LIST;
	$data = array();
	$DB_DATA = mysql_query($sql);
	if ($DB_DATA)
	{
		while ($line = @mysql_fetch_assoc($DB_DATA))
		{
			$data[] = $line;
		}
	} else
	{
		$data = false;
	}
	return($data);
}

function mysql_safeval($val)
{
	return(mysql_real_escape_string($val));
}
?>