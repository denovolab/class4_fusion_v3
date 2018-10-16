<?php

class ActiveCallServer
{
    private static $ip = "192.168.112.76";
    private static $port = 4320;

    private function __construct()
    {
        // do something
    }

    public static function request($method, $arrayResult = true)
    {
        $result = false;

        if ($method) {

            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            $socketFlag = socket_connect($socket, self::$ip, self::$port);

            if ($socketFlag) {

                $command = "login\r\n";
                socket_write($socket, $command, strlen($command));
                $out = socket_read($socket, 2046);

                if (strcmp($out, 'Welcome') !== FALSE) {
                    $command = $method . "\r\n";

                    socket_write($socket, $command, strlen($command));
                    $out = socket_read($socket, 2048);

                    if (!empty($out)) {
                        $result = $out;
                    }

                    $command = "logout\r\n";
                    socket_write($socket, $command, strlen($command));

                }
            }

            socket_close($socket);
        }

        if ($arrayResult && $result !== false) {
            $result = explode("\n", trim($result));
            $tempResult = array();

            foreach ($result as $item) {
                $temp = explode('=', $item);
                $tempResult[$temp[0]] = $temp[1];
            }

            $result = $tempResult;
        }

        return $result;
    }
}