		<p class="form-row" style="width:200px;">
		    <label>Card Number <span class="required">*</span></label>

		    <input class="input-text" style="width:180px;" type="text" size="16" maxlength="16" name="billing_credircard" />
		</p>
		<div class="clear"></div>
		<p class="form-row form-row-first" style="width:200px;">
		    <label>Expiration Month <span class="required">*</span></label>
		    <select name="billing_expdatemonth">
		        <option value=01> 1 - January</option>
		        <option value=02> 2 - February</option>
		        <option value=03> 3 - March</option>
		        <option value=04> 4 - April</option>
		        <option value=05> 5 - May</option>
		        <option value=06> 6 - June</option>
		        <option value=07> 7 - July</option>
		        <option value=08> 8 - August</option>
		        <option value=09> 9 - September</option>
		        <option value=10>10 - October</option>
		        <option value=11>11 - November</option>
		        <option value=12>12 - December</option>
		    </select>
		</p>
		<p class="form-row form-row-second" style="width:150px;">
		    <label>Expiration Year  <span class="required">*</span></label>
		    <select name="billing_expdateyear">
		<?php
		    $today = (int)date('y', time());
			$today1 = (int)date('Y', time());
		    for($i = 0; $i < 20; $i++)
		    {
		?>
		        <option value="<?php echo $today; ?>"><?php echo $today1; ?></option>
		<?php
		        $today++;
				$today1++;
		    }
		?>
		    </select>
		</p>
		<div class="clear"></div>
		<p class="form-row" style="width:200px;">
		    <label>Card CVV <span class="required">*</span></label>

		    <input class="input-text" style="width:100px;" type="text" size="5" maxlength="5" name="billing_cvv" />
		</p>
		<div class="clear"></div>