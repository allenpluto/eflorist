<?php
// Include Class Object
// Name: db
// Description: database functions

// Database Related Functions


class db
{
    private static $_conn = null;

    function db_get_connection()
    {
        if (!self::$_conn)
        {
            $dbLocation = 'mysql:dbname='.DATABASE_NAME.';host='.DATABASE_HOST;
            $dbUser = DATABASE_USER;
            $dbPass = DATABASE_PASSWORD;
            $db = new PDO($dbLocation, $dbUser, $dbPass, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'utf8\''));

            self::$_conn = $db;
        }

        return self::$_conn;
    }

    function db_get_columns($table)
    {
        $table = str_replace("'","\'", $table);
        $sql = 'DESCRIBE '.$table;
        $pdo_statement_obj = self::$_conn->query($sql);

        if (self::$_conn->errorCode() == '00000')
        {
            $column = array();
            foreach ($pdo_statement_obj as $column_description_index=>$column_description_value)
            {
                $column[$column_description_value['Field']] = $column_description_value['Field'];
            }
            return $column;
        }
        else
        {
            $query_errorInfo = self::$_conn->errorInfo();
            $GLOBALS['global_message']->error = __FILE__.'(line '.__LINE__.'): SQL Error - '.$query_errorInfo[2];
            return false;
        }        
    }

    function db_get_primary_key($table)
    {
        $table = str_replace("'","\'", $table);
        $sql = 'SHOW INDEX FROM `'.$table.'` WHERE Key_name = "PRIMARY"';
        $pdo_statement_obj = self::$_conn->query($sql);

        if (self::$_conn->errorCode() == '00000')
        {
            $column = array();
            foreach ($pdo_statement_obj as $column_description_index=>$column_description_value)
            {
                $column[] = $column_description_value['Column_name'];
            }
            return $column;
        }
        else
        {
            $query_errorInfo = self::$_conn->errorInfo();
            $GLOBALS['global_message']->error = __FILE__.'(line '.__LINE__.'): SQL Error - '.$query_errorInfo[2];
            return false;
        }        
    }

    function db_get_fulltext_index($table)
    {
        $table = str_replace("'","\'", $table);
        $sql = 'SHOW INDEX FROM `'.$table.'` WHERE index_type = "FullTEXT"';
        $pdo_statement_obj = self::$_conn->query($sql);

        if (self::$_conn->errorCode() == '00000')
        {
            $column = array();
            foreach ($pdo_statement_obj as $column_description_index=>$column_description_value)
            {
                if (!isset($column[$column_description_value['Key_name']])) $column[$column_description_value['Key_name']] = array('Seq_in_index'=>array(),'Column_name'=>array());
                $column[$column_description_value['Key_name']]['Seq_in_index'][] = $column_description_value['Seq_in_index'];
                $column[$column_description_value['Key_name']]['Column_name'][] = $column_description_value['Column_name'];
            }
            $result = array();
            foreach ($column as $key_name=>$row)
            {
                array_multisort($row['Seq_in_index'], SORT_NUMERIC, SORT_ASC, $row['Column_name']);
                $result[$key_name] = $row['Column_name'];
            }
            return $result;
        }
        else
        {
            $query_errorInfo = self::$_conn->errorInfo();
            $GLOBALS['global_message']->error = __FILE__.'(line '.__LINE__.'): SQL Error - '.$query_errorInfo[2];
            return false;
        }
    }

    function db_table_exists($table)
    {
        $table = trim($table,'`');
        $sql = 'SHOW TABLES LIKE "'.$table.'"';
        $pdo_statement_obj = self::$_conn->query($sql);

        if (self::$_conn->errorCode() == '00000')
        {
            $result = $pdo_statement_obj->fetchAll(PDO::FETCH_ASSOC);
            if (count($result) == 0)
            {
                $GLOBALS['global_message']->notice = $sql;
                $GLOBALS['global_message']->notice = __FILE__.'(line '.__LINE__.'): '.$table.' does not exist';
                return false;
            }
            else
            {
                return true;
            }
        }
        else
        {
            $query_errorInfo = self::$_conn->errorInfo();
            $GLOBALS['global_message']->error = __FILE__.'(line '.__LINE__.'): SQL Error - '.$query_errorInfo[2];
            return false;
        }
    }

    // compare records of two tables with same id (e.g. tb_entity_thing & tb_index_thing), return the ids listed in source_table but not in target_table
    // if $parameter['include_update'] === true, return ids in source_table with newer update_time as well
    function db_compare_records($parameter)
    {
        $result = array();
        $sql = 'SELECT ' . $parameter['source_primary_key'] . '
        FROM ' . $parameter['source_table'] . '
        WHERE 1';
        if (isset($parameter['id_group']))
        {
            $sql .= ' AND ' . $parameter['source_primary_key'] . ' IN ('.implode(',',$parameter['id_group']).') ';
        }
        $sql .= ' AND ' . $parameter['source_primary_key'] . ' NOT IN
        (SELECT ' . $parameter['target_primary_key'] .' FROM ' . $parameter['target_table'] . ' WHERE 1';
        if (isset($parameter['id_group']))
        {
            $sql .= ' AND ' . $parameter['target_primary_key'] . ' IN ('.implode(',',$parameter['id_group']).') ';
        }
        $sql .= ')';
        if (!empty($parameter['where'])) $sql .= ' AND (' . implode(' AND ', $parameter['where']) . ')';
        $pdo_statement_obj = self::$_conn->query($sql);
        if (self::$_conn->errorCode() == '00000')
        {
            $id_group = array();
            foreach ($pdo_statement_obj as $row_index => $row_value)
            {
                $id_group[] = $row_value[$parameter['source_primary_key']];
            }
            $result['insert'] = $id_group;
            unset($id_group);
        }
        else
        {
            $query_errorInfo = self::$_conn->errorInfo();
            $GLOBALS['global_message']->error = __FILE__.'(line '.__LINE__.'): SQL Error - '.$query_errorInfo[2];
            return false;
        }

        $sql = 'SELECT ' . $parameter['target_primary_key'] . '
        FROM ' . $parameter['target_table'] . '
        WHERE 1';
        if (isset($parameter['id_group']))
        {
            $sql .= ' AND ' . $parameter['target_primary_key'] . ' IN ('.implode(',',$parameter['id_group']).') ';
        }
        $sql .= ' AND ' . $parameter['target_primary_key'] . ' NOT IN
        (SELECT ' . $parameter['source_primary_key'] .' FROM ' . $parameter['source_table'] . ' WHERE 1';
        if (isset($parameter['id_group']))
        {
            $sql .= ' AND ' . $parameter['source_primary_key'] . ' IN ('.implode(',',$parameter['id_group']).') ';
        }
        if (!empty($parameter['where']))
        {
            $sql .= ' AND (' . implode(' AND ', $parameter['where']) . ')';
        }
        $sql .= ')';
        $pdo_statement_obj = self::$_conn->query($sql);
        if (self::$_conn->errorCode() == '00000')
        {
            $id_group = array();
            foreach ($pdo_statement_obj as $row_index => $row_value)
            {
                $id_group[] = $row_value[$parameter['source_primary_key']];
            }
            $result['delete'] = $id_group;
            unset($id_group);
        }
        else
        {
            $query_errorInfo = self::$_conn->errorInfo();
            $GLOBALS['global_message']->error = __FILE__.'(line '.__LINE__.'): SQL Error - '.$query_errorInfo[2];
            return false;
        }

        $sql = 'SELECT ' . $parameter['source_table'] . '.' . $parameter['source_primary_key'] . '
        FROM ' . $parameter['source_table'] . '
        JOIN ' . $parameter['target_table'] . ' ON ' . $parameter['source_table'] . '.' . $parameter['source_primary_key'] . '=' . $parameter['target_table'] . '.' . $parameter['target_primary_key'] . '
        WHERE '.$parameter['source_table'].'.update_time > '.$parameter['target_table'].'.update_time';
        if (isset($parameter['id_group']))
        {
            $sql .= ' AND ' . $parameter['source_table'] . '.' . $parameter['source_primary_key'] . ' IN ('.implode(',',$parameter['id_group']).') ';
        }
        if (!empty($parameter['where'])) $sql .= ' AND (' . implode(' AND ', $parameter['where']) . ')';
        $pdo_statement_obj = self::$_conn->query($sql);
        if (self::$_conn->errorCode() == '00000')
        {
            $id_group = array();
            foreach ($pdo_statement_obj as $row_index => $row_value)
            {
                $id_group[] = $row_value[$parameter['source_primary_key']];
            }
            $result['update'] = $id_group;
            unset($id_group);
        }
        else
        {
            $query_errorInfo = self::$_conn->errorInfo();
            $GLOBALS['global_message']->error = __FILE__.'(line '.__LINE__.'): SQL Error - '.$query_errorInfo[2];
            return false;
        }
        return $result;
    }
}
?>