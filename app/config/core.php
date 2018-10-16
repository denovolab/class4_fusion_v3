<?php

Configure::write('debug',0);
Configure::write('project_name','partition');
Configure::write('App.encoding', 'UTF-8');
define('LOG_ERROR', 2);
Configure::write('Session.save', 'php');
Configure::write('Session.cookie', 'CAKEPHP_PHP');
Configure::write('Session.timeout', '100');//设置session超时时间
Configure::write('Session.start', true);
Configure::write('Session.checkAgent', true);
Configure::write('Security.level', 'medium');
Configure::write('Security.salt', 'DYhG93b0qy7878JfIxWw0FgaC9mi');
Configure::write('Acl.classname', 'DbAcl');
Configure::write('Acl.database', 'default');
Cache::config('default', array('engine' => 'File'));
define('PRI', true);
define('PROJECT', 'class4');



?>
