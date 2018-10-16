<?php
$login_type = $session->read('login_type');
if ($login_type == 3) {
    $project_name = Configure::read('project_name');

    if ($project_name == 'partition') {

        if (isset($_SESSION['carrier_panel'])) {
            $post = $_SESSION['carrier_panel']['Client'];
            ?>

            <ul id="topmenu-menu" class="topmenu-left">

                <?php if (!empty($post['is_client_info']) || !empty($post['is_mutualsettlements']) || !empty($post['is_invoices']) || !empty($post['is_changepassword']) ||
                        !empty($post['is_rateslist'])) {
                    ?>
                    <li><span><?php __('Management') ?></span>
                        <ul style="display: none;">

                            <?php if (!empty($post['is_client_info'])) { ?>
                                <li><a href="<?php echo $this->webroot ?>clients/view/">Account Summary</a></li>
                            <?php } ?>



                            <?php if (!empty($post['is_changepassword'])) { ?>
                                <li><a href="<?php echo $this->webroot ?>users/changepassword"> <?php __('ChangePassword') ?> </a></li>
                            <?php } ?>
                                
                            <?php if (!Configure::read('did.enable')): ?>    
                            <?php if (!empty($post['is_rateslist'])) { ?>
                                <li><a href="<?php echo $this->webroot ?>clientrates/view_rate"> <?php __('RateTable') ?> </a></li>
                            <?php } ?>
                            <?php endif; ?>
                                
                            <?php if (Configure::read('did.enable')): ?>
                                <li><a href="<?php echo $this->webroot ?>did/orders/ingress_trunk"> <?php __('Trunks') ?> </a></li>
                            <?php endif; ?>
                                
                            <?php if (false): ?>
                            <?php if ($project_name == 'exchange') { ?>
                                <li><a href="<?php echo $this->webroot ?>gatewaygroups/view_egress"><?php __('Trunks') ?> </a></li>
                            <?php } else { ?>
                                <li><a href="<?php echo $this->webroot ?>prresource/gatewaygroups/view_egress"><?php __('Trunks') ?> </a></li>
                            <?php } ?>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php } ?>

                <li>
                    <span>Billing</span>
                    <ul style="display:none">
                        <li><a href="<?php echo $this->webroot ?>finances/get_actual_ingress_egress_detail/">Balance</a></li>
                        <li><a href="<?php echo $this->webroot ?>clients/clients_payment/">Payment History</a></li>
                        <?php if (Configure::read('payline.enable_paypal')): ?>
                        <li><a target="_blank" href="<?php echo $this->webroot ?>clients/client_pay">Online Payment</a></li>
                        <?php endif; ?>
                        <?php if (!empty($post['is_invoices'])) { ?>
                            <li><a  href="<?php echo $this->webroot ?>pr/pr_invoices/view">	<?php __('Invoices') ?></a></li>
                        <?php } ?>
                        <?php if (!empty($post['is_mutualsettlements']) && !Configure::read('did.enable')) { ?>
                            <li><a  href="<?php echo $this->webroot ?>clientmutualsettlements/summary_reports">	<?php __('MutualSettlements') ?></a></li>
                        <?php } ?>
                    </ul>
                </li>

                <li>
                    <?php if (!empty($post['is_spam']) || !empty($post['is_location']) || !empty($post['is_orig_term']) || !empty($post['is_summaryreport']) || !empty($post['is_usage'])
                            || $post['is_cdrslist']) {
                        ?>
                        <span><?php __('Reports') ?></span>
                        <ul style="display: none;">
			    <li><a href="<?php echo $this->webroot ?>clients/clients_usage_report/">Usage Report</a></li>
                            <?php if(!Configure::read('did.enable')): ?>
                           <!-- <li><a href="<?php echo $this->webroot ?>clients/clients_usage_report/">Usage Report</a></li>-->
                            <?php if (!empty($post['is_spam'])) { ?>
                            <li><a href="<?php echo $this->webroot ?>cdrreports/summary_reports/spam/">	<?php echo __('Spam Report', true); ?></a></li>
                            <?php } ?>
                            <?php if (!empty($post['is_location'])) { ?><li><a	href="<?php echo $this->webroot ?>Locationreports/summary_reports"><?php __('LocationReport') ?></a></li><?php } ?>
                            <?php if (!empty($post['is_orig_term'])) { ?><li><a href="<?php echo $this->webroot ?>origtermstatis/summary_reports"><?php __('Orig-TermReport') ?></a></li><?php } ?>
                            <?php if (!empty($post['is_summaryreport'])) { ?><li><a href="<?php echo $this->webroot ?>clientsummarystatis/summary_reports"><?php __('SummaryReport') ?></a></li><?php } ?>
                            
                            <?php endif; ?>
                             <?php if (!empty($post['is_usage'])) { ?><li><a href="<?php echo $this->webroot ?>ratereports/summary_reports"><?php __('UsageReport') ?></a></li><?php } ?>
                            <?php if (!empty($post['is_cdrslist'])) { ?>
                             <li><a href="<?php echo $this->webroot ?>cdrreports/summary_reports"><?php __('CDRsList') ?></a></li>
<!--                             <li><a href="<?php echo $this->webroot ?>quickcdr"><?php __('Simple CDR Export') ?></a></li>-->
                             <?php } ?>
                            <?php if (!empty($post['is_cdrslist']) && false) { ?><li><a href="<?php echo $this->webroot ?>reports/user_summary">	<?php __('Summary Report') ?></a></li><?php } ?>

                            <?php if (!empty($post['is_qos'])) { ?><li><a href="<?php echo $this->webroot ?>monitorsreports/carrier2/ingress"><?php __('QoSReport') ?></a></li><?php } ?>
                            <?php if (!empty($post['is_discon'])) { ?><li><a href="<?php echo $this->webroot ?>disconnectreports/summary_reports"><?php __('DisconnectCauses') ?></a></li><?php } ?>
                            <?php if (!empty($post['is_bill_mismatch'])) { ?><li><a href="<?php echo $this->webroot ?>mismatchesreports/mismatches_report"><?php __('MismatchesReport') ?></a></li><?php } ?>
                            <?php if (!empty($post['is_active_call'])) { ?><li><a href="<?php echo $this->webroot ?>realcdrreports/summary_reports"><?php __('Active Call Report ') ?></a></li><?php } ?>
                <?php if (!empty($post['is_termin'])) { ?><li><a href="<?php echo $this->webroot ?>gatewaygroups/egress_report"><?php __('TerminationReport') ?>	</a></li><?php } ?>
                        </ul>

            <?php } ?>
                </li>

            <?php if (Configure::read('did.enable')): ?>
                    <li>
                        <span>DID Management</span>
                        <ul style="display:none">
                            <li><a href="<?php echo $this->webroot ?>did/orders/browse">Buy DID</a></li>
                            <li><a href="<?php echo $this->webroot ?>did/did_assign/listing">My DID</a></li>
                            <li><a href="<?php echo $this->webroot ?>did/did_request/index">DID Request</a></li>
                        </ul>
                    </li>
            <?php endif; ?>



            <?php if (!empty($post['is_route'])) { ?>
                    <li><span><?php __('Routing') ?></span>
                        <ul style="display: none;">
                            <li><a href="<?php echo $this->webroot ?>digits/view"><?php __('DigitTranslation') ?>	</a></li>

                            <li><a href="<?php echo $this->webroot ?>dynamicroutes/view"><?php __('DynamicRoute') ?> </a></li>
                            <li><a href="<?php echo $this->webroot ?>products/product_list"> <?php __('StaticRoute') ?></a></li>
                            <li><a href="<?php echo $this->webroot ?>blocklists/index"> <?php __('BlockList') ?> </a></li>
                            <li><a href="<?php echo $this->webroot ?>routestrategys/strategy_list"><?php __('RoutingStrategies') ?></a></li>
                        </ul>
                    </li>
                    <?php
                }
            }
        }
    }
    ?>
