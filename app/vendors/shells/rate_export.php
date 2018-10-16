<?php

class RateExportShell extends Shell
{
    var $uses = array('ImportExportLog', 'RateTable', 'Rate');

    public function main()
    {
        $logId = $this->args[0];
        $record = $this->ImportExportLog->find('first', array(
            'conditions' => array(
                'id' => $logId
            )
        ));
        $rateTableId = $record['ImportExportLog']['foreign_id'];
        $this->ImportExportLog->save(array(
            'id' => $record['ImportExportLog']['id'],
            'status' => 1
        ));

        $limit = 5000000;
        $offset = 0;

        do {
//            $count = $this->RateTable->query("SELECT count(*) FROM rate where rate_table_id = {$rateTableId} LIMIT {$limit} OFFSET {$offset}");
            $count = $this->RateTable->query("SELECT COUNT(*) FROM ({$record['ImportExportLog']['sql']} LIMIT {$limit} OFFSET {$offset}) as c");
            $count = $count[0][0]['count'];

            if ($count > 0) {
                $sql = "\COPY ({$record['ImportExportLog']['sql']} LIMIT {$count} OFFSET {$offset}) TO '{$record['ImportExportLog']['file_path']}' delimiter as ',' csv header";
//                $sql = "\COPY ({$record['ImportExportLog']['sql']} LIMIT {$count} OFFSET {$offset}) TO STDOUT delimiter as ',' csv header";
                $this->ImportExportLog->_get_psql_cmd($sql);
            }
            $offset += $count;
            $this->ImportExportLog->save(array(
                'id' => $record['ImportExportLog']['id'],
                'success_numbers' => $offset
            ));
        } while ($count != 0);

        $this->ImportExportLog->save(array(
            'id' => $record['ImportExportLog']['id'],
            'finished_time' => date('Y-m-d H:i:s'),
            'status' => 2
        ));
    }
}

?>
