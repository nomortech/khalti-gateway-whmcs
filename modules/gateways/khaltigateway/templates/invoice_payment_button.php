<div class='row' id='khaltigateway-button-wrapper'>
    <div class='col-sm-12'>
        <div class='col' id='khaltigateway-button-content'>
            <div class='col'>
                <div class='thumbnail' style='border:0px;box-shadow:none;'>
                    <!-- <img src='<?php echo $_inc_vars['khalti_logo_url']; ?>' alt='khalti digital wallet' /> -->
                    <img src="/modules/gateways/khaltigateway/assets/Khalti_Logo_white.png"
                        alt='khalti digital wallet' />
                </div>
            </div>
            <div class='col text-left'>
                <!-- <small>Pay with Khalti Payment Wallet</small> -->
                <a id='khalti-payment-button' href='<?php echo $_inc_vars['pidx_url']; ?>'
                    class='btn btn-primary btn-large' style='<?php echo $_inc_vars['button_css']; ?>'>
                    <?php echo $_inc_vars['gateway_params']['langpaynow']; ?>
                </a>
                <br />
                <!-- <small>NPR <?php echo $_inc_vars['npr_amount']; ?></small> -->
            </div>
        </div>
    </div>
</div>
