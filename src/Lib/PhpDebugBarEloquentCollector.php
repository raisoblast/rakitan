<?php

namespace Lib;

use DebugBar\DataCollector\PDO\PDOCollector;
use DebugBar\DataCollector\PDO\TraceablePDO;

/**
 * Display Eloquent PDO information on php-debugbar
 * source: http://dan.doezema.com/2015/08/php-debugbar-eloquent-collector/
 * modified by Arif Kurniawan
 */
class PhpDebugBarEloquentCollector extends PDOCollector
{
    /**
     *
     * @var \Illuminate\Database\Connection
     */
    protected $db;

    public function __construct(\Illuminate\Database\Connection $db)
    {
        parent::__construct();
        $this->db = $db;
        $this->addConnection($this->getTraceablePdo(), 'Eloquent PDO');
    }

    public function getTraceablePdo()
    {
        return new TraceablePDO($this->db->getPdo());
    }

    public function getName()
    {
        return 'eloquent_pdo';
    }

    public function getWidgets()
    {
        return array(
            "eloquent" => array(
                "icon"    => "inbox",
                "widget"  => "PhpDebugBar.Widgets.SQLQueriesWidget",
                "map"     => "eloquent_pdo",
                "default" => "[]"
            ),
            "eloquent:badge" => array(
                "map"     => "eloquent_pdo.nb_statements",
                "default" => 0
            )
        );
    }
}
