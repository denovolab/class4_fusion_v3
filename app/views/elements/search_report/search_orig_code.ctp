
<td class="label">
  <?php __ ('code');?>
  :</td>
<td class="value"><input type="text" id="query-code"
	 value=""	name="query[code]" class="input in-text"/>
  <!--
  <a href="#" onclick="ss_code(undefined, _ss_ids_code)"><img width="9" height="9"
	 class="img-button"
	src="<?php
	echo $this->webroot?>images/search-small.png"/></a>-->
    <a href="#" onclick="ss_clear('code', _ss_ids_code)" > <img
	width="9" height="9"
	class="img-button"
	src="<?php
	echo $this->webroot?>images/delete-small.png"/></a></td>
 