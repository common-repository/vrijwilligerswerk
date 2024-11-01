<?php


    echo '<div class="wrap">';

    // header

    echo "<h2>" . __( 'Vacatures Settings', 'vrijwilligersvacaturebank_nl' ) . "</h2>";

    // settings form
    
    ?>

<form name="form1" method="post" action="">

<p><?php _e("Consumer Key:", 'vrijwilligersvacaturebank_nl' ); ?> 
<input type="text" name="consumerkey" value="<?php echo $consumerkey; ?>" size="50">
</p>

<p><?php _e("Consumer Secret:", 'vrijwilligersvacaturebank_nl' ); ?> 
<input type="text" name="consumersecret" value="<?php echo $consumersecret; ?>" size="50">
</p>

<p><?php _e("Root Route:", 'vrijwilligersvacaturebank_nl' ); ?> 
<input type="text" name="consumerroute" value="<?php echo $consumerroute; ?>" size="50">
</p>

<p><?php _e("Disable frontend:", 'vrijwilligersvacaturebank_nl' ); ?> 
<input type="checkbox" name="disablefront" value="1" <?php echo (empty($disablefront)?'':'checked="checked"'); ?> size="50">
</p>
<hr />

<p class="submit">
<input type="submit" name="reset" id="set_ev_options_reset" class="button-secondary" value="<?php esc_attr_e('Reset') ?>"/>

<input type="submit" name="submit" id="set_ev_options" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" data-api-url="<?php echo $apiurl; ?>" />
</p>

</form>
</div>
