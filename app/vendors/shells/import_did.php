<?php 

class ImportDidShell extends Shell
{
    var $uses = array('did.DidRepos');
    
    public function main()
    {
        $file_path = $this->args[0];
        $ingress_id = $this->args[1];
        
        
    }
}

?>
