<td class="label"><?php
__ ( 'code_name' )?>
  :</td>
<td class="value"><input type="text"
	id="query-code_name"    ondblclick="ss_code(undefined, _ss_ids_code_name)"
	value="" name="query[code_name]" class="input in-text"/>
  <!--
  <a href="#"onclick="ss_code(undefined, _ss_ids_code_name)"><img width="9"
	height="9" 
	class="img-button"
	src="<?php
	echo $this->webroot?>images/search-small.png"/></a>
    -->
    <a href="#" onclick="ss_clear('card', _ss_ids_code_name)"> <img
	width="9" height="9"
	class="img-button"
	src="<?php
	echo $this->webroot?>images/delete-small.png"/></a></td>
    
    
<td class="label"><?php echo __('code',true);?>
  :</td>
<td class="value"><input type="text" id="query-code"
	 value=""   	ondblclick="showss_codes(undefined, _ss_ids_code)"
	name="query[code]" class="input in-text"/>
  <!--
  <a href="#"onclick="showss_codes(undefined, _ss_ids_code)" ><img width="9" height="9"
	class="img-button"
	src="<?php
	echo $this->webroot?>images/search-small.png"/></a>
    -->
    <a href="#" onclick="ss_clear('code', _ss_ids_code)"> <img
	width="9" height="9"
	class="img-button"
	src="<?php
	echo $this->webroot?>images/delete-small.png"/></a></td>
