<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace GCG\Core;
/**
 * Description of Connection
 *
 * @author james
 */
abstract class Connection {
    protected $dbmap;
    protected $dbconfig;
    protected $attributes;
    public function getConnection($name) {
        if(!isset($this->dbmap)) {
            $this->dbmap = [];
        }
        if(!isset($this->dbmap[$name])) {
            $this->dbmap[$name] = $this->createConnection($name);
            $this->dbmap[$name]->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        }
        return $this->dbmap[$name];
    }
    public function createConnection($name) {
        if(!isset($this->dbconfig)) {
            //Access configuration values from default location (/usr/local/etc/gcg/default)
            $this->dbconfig = (new \Configula\Config('/usr/local/etc/gcg/default'))->getItem('gcgDatabase', []);
        }
        if(!isset($this->dbconfig[$name])) {
            throw new \Exception("No config for named option");
        }
        if(isset($this->attributes)) {
            return new \Medoo(\array_merge($this->dbconfig[$name],[
                "option" => $this->attributes
            ]));
        }
        return new \Medoo($this->dbconfig[$name]); // medoo does not use namespaces
    }
    public function setConfigFolder($folder) {
        $this->dbconfig = (new \Configula\Config($folder))->getItem('gcgDatabase', []);        
    }
    public function setAttributes($attributes) {
        $this->attributes = $attributes;
    }
}
