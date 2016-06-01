<?php
namespace Task;

use Symfony\Component\Console\Command\Command;
use Jenssegers\Optimus\Optimus;

class BaseTask extends Command
{
    /**
     *
     * @var \Illuminate\Database\Connection
     */
    //protected $db;

    public function __construct(
            //\Illuminate\Database\Connection $db,
            )
    {
        parent::__construct();
        //$this->db = $db;
    }
}
