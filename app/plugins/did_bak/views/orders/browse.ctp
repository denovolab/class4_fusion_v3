<div id="title">
    <h1><?php echo __('DID Management', true); ?>&gt;&gt;<?php echo __('DID Search', true); ?></h1>
    <ul id="title-menu">
        <li>
            <a href="<?php echo $this->webroot ?>did/orders/shopping_cart" title="Shopping Cart" class="link_btn">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/shoppingcart.png" alt="">Shopping Cart</a>
        </li>
    </ul>
</div>

<div id="container">
    <?php //echo $this->element("did_client_tab", array('active' => 'orders'))?>
    <!-- Sub Menu Tab -->
    <?php //echo $this->element("did_orders_tab", array('active' => 'browse')); ?>
    <!-- Search Panel -->
    <div class="search_panel">

        <fieldset class="title-block2" id="advsearch" style="margin-left: 1px; width: 100%; display: block;"> 
            <form method="get"> 
                <input type="hidden" class="input in-hidden" name="advsearch"> 
                <table style="width:100%"> 
                    <tbody> 
                        <tr>
                            <td>Country:</td>
                            <td>
                                <select id="countries">
                                    <option>Select...</option>
                                    <?php foreach ($countries as $country): ?>
                                        <option value="<?php echo $country[0]['country']; ?>"><?php echo $country[0]['country']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>Organized By:</td>
                            <td>
                                <select id="triggers">
                                    <option>Select...</option>
                                    <option>By State/Region</option>
                                    <option>By Area Code</option>
                                    <option>By LATA</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>Rate Center:</td>
                            <td><input type="text" id="rate_center" /></td>   
                            <td>State/Province:</td>
                            <td><input type="text" id="state_province" /></td>
                            <td>Area Code:</td>
                            <td><input type="text" id="area_code" /></td>
                            <td>LATA:</td>
                            <td><input type="text" id="lata" /></td>
                            <td>
                                <input class="trigger_btn" type="button" value="Search" />
                                <input type="reset" value="Reset" />
                            </td>
                        </tr>
                    </tbody> 
                </table> 
            </form> 
        </fieldset>

    </div>
    
    <div id="did_display">
        <h1></h1>
        <ul>
        </ul>
    </div>

    <table id="did_listing" class="list">
        <thead>
            <tr>
                <th>DID</th>
                <th>Rate Center</th>
                <th>State</th>
                <th>LATA</th>
                <th>Billing Rule</th>
                <th>Date Assign</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7">
                    <a id="prev-page" href="###">
                        <img src="<?php echo $this->webroot ?>images/previous-page.png" />
                    </a>
                    <a id="next-page" href="###">
                        <img src="<?php echo $this->webroot ?>images/next-page.png" />
                    </a>
                </td>
            </tr>
        </tfoot>
    </table>
</div>

<div id="loading"></div>

<script type="text/javascript" src="<?php echo $this->webroot ?>js/sprintf.js"></script>
<script type="text/javascript">
    $(function() {
        var $triggers = $('#triggers');
        var $pager = $('#prev-page, #next-page');
        var $trigger_button = $('.trigger_btn');
        var $rate_center = $('#rate_center');
        var $locality = $('#locality');
        var $state_province = $('#state_province');
        var $area_code = $('#area_code');
        var $lata = $('#lata');
        var $loading = $('#loading');
        var $did_display = $('#did_display');
        var $did_item = $('.did_item');
        var $did_listing = $('#did_listing');
        var $put_into_cart = $('.put_into_cart');
        var type = 0;
        var option = null;
        var $countries = $('#countries');
    
        $countries.change(function() {
            var country = $(this).val();
            if ('US' == $.trim(country)) {
                $triggers.show();
            } else {
                $triggers.hide();
            }
        }).trigger('change');
    
        function put_data(data)
        {
            var $tbody = $did_listing.find('> tbody');
            $tbody.empty();
            $.each(data, function(index, value) {
                var $tr = $('<tr />');
                $tr.append('<td>' + value[0]['number'] + '</td>');
                $tr.append('<td>' + value[0]['rate_center'] + '</td>');
                $tr.append('<td>' + value[0]['state'] + '</td>');
                $tr.append('<td>' + value[0]['lata'] + '</td>');
                $tr.append('<td>' + value[0]['lata'] + '</td>');
                $tr.append('<td>' + value[0]['created_time'] + '</td>');
                $tr.append('<td><a href="###" title="Put into your car" class="put_into_cart" control="' + value[0]['number'] + '"><img src="<?php echo $this->webroot ?>images/add_to_cart.png" /></a></td>');
                $tbody.append($tr);
            });
            $did_listing.show();
        }
    
        $trigger_button.click(getData);
    
        var page = 1;
    
        function getData(event) {
            $did_listing.hide();
            var btn_val = $(event.target).val();
            
            option = new Object();
            var header = '';
            
            var $target = $(event.currentTarget);
            var idName = $target.attr('id');
        
            if(idName == 'next-page') {
                btn_val = 'Search';
                page++;
            } else if (idName == 'prev-page') {
                btn_val = 'Search';
                page--;
                if (page < 1) {
                    page = 1;
                }
            } else {
                page = 1;
                $pager.unbind('click');
                $pager.click(getData);
            }
        
            switch (btn_val)
            {
                case 'By State/Region':
                    type = 1;
                    header = 'DID Availability by State';
                    break;
                case 'By Area Code':
                    type = 2;
                    header = 'DID Availability by Area Code';
                    break;
                case 'By LATA':
                    type = 3;
                    header = 'DID Availability by LATA';
                    break;
                case 'Search':
                    type = 4;
                    option.rate_center = $rate_center.val();
                    option.locality = $locality.val();
                    option.state_province = $state_province.val();
                    option.area_code = $area_code.val();
                    option.lata = $lata.val();
                    header = 'DID Availability by Search';
                    break;
                default:
                    return;
            }
                   
        
            option.page = page;
            
            $.ajax({
                'url':'<?php echo $this->webroot ?>did/orders/search/' + type,
                'type':'POST',
                'dataType':'json',
                'data':option,
                'success': function(data) {
                    $loading.hide();
                    $did_display.find('> h1').text(header);
                    var $ul = $did_display.find('> ul');
                    $ul.empty();
                    if (type != 4)
                    {
                        $.each(data, function(index, value) {
                            $ul.append('<li><a href="###" class="did_item">' + value[0]['name']+'</a>(' + value[0]['count'] + ')</li>');
                        });
                    }
                    else
                    {
                        put_data(data);
                        $('tfoot', $did_listing).show();
                    }
                },
                'beforeSend' : function() {
                    $loading.show();
                }
            });
        
            return false;
        
        }
    
        $triggers.change(getData);
        
    
    
        function get_data_group(event) {
            //var $this = $(this);
            var text = $('.item_clicked').text();

            option.text = text;
            
            var $target = $(event.currentTarget);
            var idName = $target.attr('id');
        
            if(idName == 'next-page') {
                page++;
            } else if (idName == 'prev-page') {
                page--;
                if (page < 1) {
                    page = 1;
                }
            } else {
                page = 1;
            }
            
            option.page = page;

            $.ajax({
                'url':'<?php echo $this->webroot ?>did/orders/search_listing/' + type,
                'type':'POST',
                'dataType':'json',
                'data':option,
                'success': function(data) {
                    $loading.hide();
                    put_data(data);
                },
                'beforeSend' : function() {
                    $loading.show();
                }
            });

            return false;
        }
    
        $did_item.live('click', function (event) {
            var $this = $(this);
            $('a', '#did_display').removeClass('item_clicked');
            $this.addClass('item_clicked');
            get_data_group(event);
            $pager.unbind('click');
            $pager.click(get_data_group);
        });


        $put_into_cart.live('click', function(event) {
            var $this = $(this);
            var number = $this.attr('control');

            $.ajax({
                'url':'<?php echo $this->webroot ?>did/orders/put_into_cart',
                'type':'POST',
                'dataType':'json',
                'data': {'number' : number},
                'success': function(data) {
                    $loading.hide();

                    if (data.result)
                        jQuery.jGrowl(sprintf("<?php __('The DID number [%s] is put into your cart successfully'); ?>", number),{theme:'jmsg-success'});
                },
                'beforeSend' : function() {
                    $loading.show();
                }
            });

            return false;
        });
    });    
</script>