<?php

class DATABASE_CONFIG
{

    var $default = array(
        'driver' => 'postgres',
        'persistent' => false,
        'host' => '',
        'login' => '',
        'password' => '',
        'database' => '',
        'prefix' => '',
        'port' => ''
    );

    /**
     * DATABASE_CONFIG constructor.
     *
     * Get login data from INI file.
     */
    public function __construct()
    {
        $sections = parse_ini_file(ROOT . '/etc/dnl_softswitch.ini', TRUE);

        $this->default['host'] = $sections['db']['hostaddr'];
        $this->default['port'] = $sections['db']['port'];
        $this->default['login'] = $sections['db']['user'];
        $this->default['database'] = $sections['db']['dbname'];
        $this->default['password'] = $sections['db']['password'];
    }

}

?>
