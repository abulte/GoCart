<?php include('header.php'); ?>

<div class="page-header">
    <h1><?php echo  $page_title; ?></h1>
</div>

<div class="row" style="margin-top:10px;">
    <div class="span3 company-box">
        <h3><?php echo $this->config->item('company_name'); ?></h3>
        <p>
        <?php echo $this->config->item('address1');?><br/>
        <?php echo $this->config->item('address2');?><br/>
        <?php echo $this->config->item('zip') . ' ' . $this->config->item('city');?><br/>
        <?php echo $this->config->item('country');?><br/>
        </p>
    </div>
	<div class="span4">
		<h3><?php echo lang('billing_address');?></h3>
		<?php echo (!empty($order->bill_company))?$order->bill_company.'<br/>':'';?>
		<?php echo $order->bill_firstname.' '.$order->bill_lastname;?> <br/>
		<?php echo $order->bill_address1;?><br>
		<?php echo (!empty($order->bill_address2))?$order->bill_address2.'<br/>':'';?>
		<?php echo $order->bill_city.', '.$order->bill_zone.' '.$order->bill_zip;?><br/>
		<?php echo $order->bill_country;?><br/>
		
		<?php echo $order->bill_email;?><br/>
		<?php echo $order->bill_phone;?>
	</div>
	<div class="span4">
		<h3><?php echo lang('shipping_address');?></h3>
		<?php echo (!empty($order->ship_company))?$order->ship_company.'<br/>':'';?>
		<?php echo $order->ship_firstname.' '.$order->ship_lastname;?> <br/>
		<?php echo $order->ship_address1;?><br>
		<?php echo (!empty($order->ship_address2))?$order->ship_address2.'<br/>':'';?>
		<?php echo $order->ship_city.', '.$order->ship_zone.' '.$order->ship_zip;?><br/>
		<?php echo $order->ship_country;?><br/>
		
		<?php echo $order->ship_email;?><br/>
		<?php echo $order->ship_phone;?>
	</div>
</div>

<div class="row" style="margin-top:20px;">
	<div class="span4">
		<h3><?php echo lang('order_details');?></h3>
		<p>
		<?php if(!empty($order->referral)):?>
			<strong><?php echo lang('referral');?>: </strong><?php echo $order->referral;?><br/>
		<?php endif;?>
		<?php if(!empty($order->is_gift)):?>
			<strong><?php echo lang('is_gift');?></strong>
		<?php endif;?>
		
		<?php if(!empty($order->gift_message)):?>
			<strong><?php echo lang('gift_note');?></strong><br/>
			<?php echo $order->gift_message;?>
		<?php endif;?>
		</p>
	</div>
	<div class="span4">
		<h3><?php echo lang('payment_method');?></h3>
		<p><?php echo $order->payment_info; ?></p>
	</div>
	<div class="span4">
		<h3><?php echo lang('shipping_details');?></h3>
		<?php echo $order->shipping_method; ?>
		<?php if(!empty($order->shipping_notes)):?><div style="margin-top:10px;"><?php echo $order->shipping_notes;?></div><?php endif;?>
	</div>
</div>

<p style="min-height: 50px"></p>
</a><table class="table table-striped">
	<thead>
		<tr>
			<th><?php echo lang('name');?></th>
			<th><?php echo lang('description');?></th>
			<th><?php echo lang('price');?></th>
			<th><?php echo lang('quantity');?></th>
			<th><?php echo lang('total');?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($order->contents as $orderkey=>$product):?>
		<tr>
			<td>
				<?php echo $product['name'];?>
				<?php echo (trim($product['sku']) != '')?'<br/><small>'.lang('sku').': '.$product['sku'].'</small>':'';?>
				
			</td>
			<td>
				<?php //echo $product['excerpt'];?>
				<?php
				
				// Print options
				if(isset($product['options']))
				{
					foreach($product['options'] as $name=>$value)
					{
						$name = explode('-', $name);
						$name = trim($name[0]);
						if(is_array($value))
						{
							echo '<div>'.$name.':<br/>';
							foreach($value as $item)
							{
								echo '- '.$item.'<br/>';
							}	
							echo "</div>";
						}
						else
						{
							echo '<div>'.$name.': '.$value.'</div>';
						}
					}
				}
				
				if(isset($product['gc_status'])) echo $product['gc_status'];
				?>
			</td>
			<td><?php echo format_currency($product['price']);?></td>
			<td><?php echo $product['quantity'];?></td>
			<td><?php echo format_currency($product['price']*$product['quantity']);?></td>
		</tr>
		<?php endforeach;?>
		</tbody>
		<tfoot>
		<?php if($order->coupon_discount > 0):?>
		<tr>
			<td><strong><?php echo lang('coupon_discount');?></strong></td>
			<td colspan="3"></td>
			<td><?php echo format_currency(0-$order->coupon_discount); ?></td>
		</tr>
		<?php endif;?>
		
		<tr>
			<td><strong><?php echo lang('subtotal');?></strong></td>
			<td colspan="3"></td>
			<td><?php echo format_currency($order->subtotal); ?></td>
		</tr>
		
		<?php 
		$charges = @$order->custom_charges;
		if(!empty($charges))
		{
			foreach($charges as $name=>$price) : ?>
				
		<tr>
			<td><strong><?php echo $name?></strong></td>
			<td colspan="3"></td>
			<td><?php echo format_currency($price); ?></td>
		</tr>	
				
		<?php endforeach;
		}
		?>
		<tr>
			<td><strong><?php echo lang('shipping');?></strong></td>
			<td colspan="3"><?php echo $order->shipping_method; ?></td>
			<td><?php echo format_currency($order->shipping); ?></td>
		</tr>
		
		<tr>
			<td><strong><?php echo lang('tax');?></strong></td>
			<td colspan="3"></td>
			<td><?php echo format_currency($order->tax); ?></td>
		</tr>
		<?php if($order->gift_card_discount > 0):?>
		<tr>
			<td><strong><?php echo lang('giftcard_discount');?></strong></td>
			<td colspan="3"></td>
			<td><?php echo format_currency(0-$order->gift_card_discount); ?></td>
		</tr>
		<?php endif;?>
		<tr>
			<td><h3><?php echo lang('total');?></h3></td>
			<td colspan="3"></td>
			<td><strong><?php echo format_currency($order->total); ?></strong></td>
		</tr>
	</tfoot>
</table>

<div style="text-align: center"><?php echo $this->config->item('invoice_legal_disclaimer'); ?></div>

<?php include('footer.php');