<?php

namespace core;

if( !defined( 'JS') ){
	die(' Access Denied !!! ');
}

use PDO;

/**
 * Base model
 *
 * PHP version 7.1
 */
abstract class jsModel {

    /**
     * Get the PDO database connection
     *
     * @return mixed
     * @throws \Exception
     */
    protected static function getDB() {

        static $db = null;

        if ($db === null) {
            try{

                $dsn = jsConfig::get('DB_CONNECTION') . ':host=' . jsConfig::get('DB_HOST') . ';dbname=' . jsConfig::get('DB_DATABASE') . ';charset=utf8';
                $db = new PDO( $dsn, jsConfig::get('DB_USERNAME'), jsConfig::get('DB_PASSWORD') );

                // Throw an Exception when an error occurs
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch(PDOException $e){

                // PDO error

            }
        }

        return $db;
    }


    /**
     * select
     *
     * @param string $sql An SQL string
     * @param array $data Parameters to bind
     * @param mixed $fetchMode A PDO Fetch mode
     * @return mixed
     * @throws \Exception
     */
    protected static function select($sql, $data = array(), $fetchMode = PDO::FETCH_ASSOC) {

        $start = microtime(true);

        try {

            // db connection
            $db = self::getDB();

            // making query
            $query = $db->prepare($sql);

            // bind data from data
            foreach ($data as $key => $value) {
                $query->bindValue(":$key", $value);
            }

            // execute query
            $query->execute();

            if(jsConfig::get('PDO_LOG')) {
                $end = microtime(true) - $start;
                jsLog::pdoLog([
                    'function' => 'select',
                    'query' => $sql,
                    'table' => '',
                    'values' => $data,
                    'time' => $end,
                    'slowQuery' => ($end > jsConfig::get('SLOW_QUERY')) ? 'true' : 'false'
                ]);
            }

            // return select rows
            return $query->fetchAll($fetchMode);

        } catch (PDOException $e) {
            // error
        }

    }

    /**
     * selectOne
     *
     * @param string $sql An SQL string
     * @param array $data Parameters to bind
     * @param mixed $fetchMode A PDO Fetch mode
     * @return mixed
     * @throws \Exception
     */
    protected static function selectOne($sql, $data = array(), $fetchMode = PDO::FETCH_ASSOC) {

        $start = microtime(true);

        try {

            // db connection
            $db = self::getDB();

            // making query
            $query = $db->prepare($sql);

            // bind data from data
            foreach ($data as $key => $value) {
                $query->bindValue(":$key", $value);
            }

            // execute query
            $query->execute();

            if(jsConfig::get('PDO_LOG')) {
                $end = microtime(true) - $start;
                jsLog::pdoLog([
                    'function' => 'selectOne',
                    'query' => $sql,
                    'table' => '',
                    'values' => $data,
                    'time' => $end,
                    'slowQuery' => ($end > jsConfig::get('SLOW_QUERY')) ? 'true' : 'false'
                ]);
            }

            // return select rows
            return $query->fetch($fetchMode);

        } catch (PDOException $e) {
            // error
        }

    }

    /**
     * count
     *
     * @param string $sql An SQL string
     * @param array $data Parameters to bind
     * @return mixed
     * @throws \Exception
     */
    protected static function count($sql, $data = array()) {

        $start = microtime(true);

        try {

            // db connection
            $db = self::getDB();

            // making query
            $query = $db->prepare($sql);

            // bind data from data
            foreach ($data as $key => $value) {
                $query->bindValue(":$key", $value);
            }

            // execute query
            $query->execute();

            // return select rows
            $rows = $query->fetchColumn();

            if(jsConfig::get('PDO_LOG')) {
                $end = microtime(true) - $start;
                jsLog::pdoLog([
                    'function' => 'count',
                    'query' => $sql,
                    'table' => '',
                    'values' => $data,
                    'time' => $end,
                    'slowQuery' => ($end > jsConfig::get('SLOW_QUERY')) ? 'true' : 'false'
                ]);
            }

            return $rows;

        } catch (PDOException $e) {
            // error
        }

    }

    /**
     * insert
     *
     * @param string $table A name of table to insert into
     * @param string $data An associative array
     * @param bool lastId
     *
     * @return bool
     * @throws \Exception
     */
    protected static function insert($table, $data, $lastId = false) {

        $start = microtime(true);

        ksort($data);

        $fieldNames = implode('`, `', array_keys($data));
        $fieldValues = ':' . implode(', :', array_keys($data));

        // db connection
        $db = self::getDB();

        // making query
        $query = $db->prepare("INSERT INTO `$table` (`$fieldNames`) VALUES ($fieldValues)");

        try {

            // start transaction
            $db->beginTransaction();

            // bind data from data
            foreach ($data as $key => $value) {
                $query->bindValue(":$key", $value);
            }
            
            // execute query
            $query->execute();

            // commit transaction and query executed
            $db->commit();

            // log query
            if(jsConfig::get('PDO_LOG')) {
                $end = microtime(true) - $start;
                jsLog::pdoLog([
                    'function' => 'insert',
                    'query' => $query,
                    'table' => $table,
                    'values' => $data,
                    'time' => $end,
                    'slowQuery' => ($end > jsConfig::get('SLOW_QUERY')) ? 'true' : 'false'
                ]);
            }

            // check if true return last inser id
            if($lastId){
                return $db->lastInsertId("id");
            }

            return true;

        } catch (PDOException $e) {
           
            // error and rollBack query data
            $db->rollBack();
            return false;
            
        }

    }

    /**
     * update
     *
     * @param string $table A name of table to update
     * @param string $data An associative array
     * @param string $where the WHERE query part
     * @param array $whereData the WHERE data to bind values
     *
     * @return bool
     * @throws \Exception
     */
    protected static function update($table, $data, $where = '', $whereData = array()) {

        $start = microtime(true);

        ksort($data);
        $fieldDetails  = NULL;

        foreach($data as $key=> $value) {
            $fieldDetails .= "`$key` = :$key,";
        }
        
        if(empty($where)){
            return false;
        }

        $fieldDetails  = rtrim($fieldDetails, ',');

        // db connection
        $db = self::getDB();
        
        try {
            // start transaction
            $db->beginTransaction();

            // making query
            $query = $db->prepare("UPDATE $table SET $fieldDetails WHERE $where");

            // bind data from whereData
            foreach ($data as $key => $value) {
                $query->bindValue(":$key", $value);
            }

            foreach ($whereData as $key => $value) {
                $query->bindValue(":$key", $value);
            }

            // execute query
            $query->execute();

            // commit transaction and query executed
            $db->commit();

            // log query
            if(jsConfig::get('PDO_LOG')) {
                $end = microtime(true) - $start;
                jsLog::pdoLog([
                    'function' => 'update',
                    'query' => $query,
                    'table' => $table,
                    'values' => $data,
                    'whereValue' => $whereData,
                    'time' => $end,
                    'slowQuery' => ($end > jsConfig::get('SLOW_QUERY')) ? 'true' : 'false'
                ]);
            }

            return true;

        } catch (PDOException $e) {
            
            // error and rollBack query data
            $db->rollBack();
            return false;
            
        }

    }

    /**
     * delete
     *
     * @param string $table
     * @param string $where
     * @param array $whereData
     *
     * @return bool
     * @throws \Exception
     */
    protected static function delete($table, $where = '', $whereData = array()) {

        $start = microtime(true);

        // db connection
        $db = self::getDB();
        
        try {
            
            // start transaction
            $db->beginTransaction();

            // check where for empty or not and making query
            if(empty($where)){
                $query = $db->prepare("DELETE FROM `$table`");
            }else{
                $query = $db->prepare("DELETE FROM `$table` WHERE $where");
            }
            
            // bind data from whereData
            foreach ($whereData as $key => $value) {
                $query->bindValue(":$key", $value);
            }

            // execute query
            $query->execute();

            // commit transaction and query execute
            $db->commit();

            // log query
            if(jsConfig::get('PDO_LOG')) {
                $end = microtime(true) - $start;
                jsLog::pdoLog([
                    'function' => 'delete',
                    'query' => $query,
                    'table' => $table,
                    'values' => $whereData,
                    'time' => $end,
                    'slowQuery' => ($end > jsConfig::get('SLOW_QUERY')) ? 'true' : 'false'
                ]);
            }

            return true;

        } catch (PDOException $e) {

            // error and rollBack query data 
            $db->rollBack();
            return false;
            
        }

    }
}