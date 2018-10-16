UPDATE system_parameter set ingress_pdd_timeout = 60000 WHERE ingress_pdd_timeout IS NULL;
UPDATE system_parameter set egress_pdd_timeout = 6000 WHERE egress_pdd_timeout IS NULL;
UPDATE system_parameter set ring_timeout = 60 WHERE ring_timeout IS NULL;
UPDATE system_parameter set call_timeout = 3600 WHERE call_timeout IS NULL;
