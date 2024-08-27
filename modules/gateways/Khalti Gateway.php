<?php

/**
 * Khalti Payment Gateway Module for WHMCS
 * 
 * Copyright © 2024 Nomor Host Pvt. Ltd (https://www.nomor.host/)
 * 
 * This is an unofficial fork of the original Khalti Payment Gateway Module developed by the Khalti team.
 * It has been enhanced with additional features and improved integration with WHMCS.
 * 
 * Licensed under the MIT License. 
 * You may obtain a copy of the License at:
 * 
 * https://opensource.org/licenses/MIT
 * 
 * This software is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and limitations under the License.
 * 
 * For more information, please visit:
 * https://github.com/nomortech/khalti-gateway-whmcs.git
 */

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

require_once __DIR__ . "/khaltigateway/init.php";

function khaltigateway_MetaData()
{
    return [
        'DisplayName' => 'Khalti Payment Gateway (KPG-2)',
        'APIVersion' => '2.0', // Use API Version 1.1
        'DisableLocalCreditCardInput' => true,
        'TokenisedStorage' => false,
    ];
}

function khaltigateway_config()
{
    $sandbox_target = "<a href='https://sandbox.khalti.com' target='_blank'>sandbox.khalti.com</a>";
    $live_target = "<a href='https://admin.khalti.com' target='_blank'>admin.khalti.com</a>";

    return [
        'FriendlyName' => [
            'Type' => 'System',
            'Value' => 'Khalti Payment Gateway (KPG-2)',
        ],
        'test_api_key' => [
            'FriendlyName' => 'TEST API Secret Key for KPG-2',
            'Type' => 'text',
            'Size' => '48',
            'Default' => 'test_key_01234567890123456789012345678901',
            'Description' => "Please visit {$sandbox_target} to get your keys",
        ],
        'live_api_key' => [
            'FriendlyName' => 'LIVE API Secret Key for KPG-2',
            'Type' => 'password',
            'Size' => '48',
            'Default' => 'live_key_01234567890123456789012345678901',
            'Description' => "Please visit {$live_target} to get your keys",
        ],
        'is_debug_mode' => [
            'FriendlyName' => 'Enable Debugging',
            'Type' => 'yesno',
            'Description' => 'Tick to enable debugging mode',
        ],
        'is_test_mode' => [
            'FriendlyName' => 'Enable Test (sandbox) Mode',
            'Type' => 'yesno',
            'Description' => 'Tick to enable sandbox mode of integration',
        ],
    ];
}

/**
 * The function `khaltigateway_link` determines whether to display code for the invoice page or not
 * based on the current page in WHMCS.
 * 
 * @param gateway_params The `gateway_params` parameter in the `khaltigateway_link` function likely
 * contains information related to the payment gateway being used, such as API credentials, transaction
 * details, or any other necessary data for processing payments. This parameter is passed to the
 * function to handle the payment processing logic based on the
 * 
 * If the current page is not the invoice page, the function will return the code for the
 * non-invoice page using `khaltigateway_noinvoicepage_code()`. Otherwise, it will return the code for
 * the invoice page using `khaltigateway_invoicepage_code()`.
 */
function khaltigateway_link($gateway_params)
{
    $currentPage = khaltigateway_whmcs_current_page();
    if ($currentPage !== KHALTIGATEWAY_WHMCS_VIEWINOVICE_PAGE) {
        return khaltigateway_noinvoicepage_code();
    }
    return khaltigateway_invoicepage_code($gateway_params);
}


/**
 * The function `khaltigateway_refund` processes a refund request through the Khalti payment gateway
 * based on the provided parameters.
 * 
 * @param gateway_params The `khaltigateway_refund` function is designed to handle refund requests
 * through the Khalti payment gateway. Let's break down the key components of the function:
 * 
 * @return array is returned with different keys based on the * outcome of the refund process.
 */
function khaltigateway_refund($gateway_params)
{
    // Get the API key from the gateway parameters
    $api_Key = khaltigateway_epay_api_authentication_key($gateway_params);
    // Get the transaction ID to refund from the gateway parameters
    $transactionIdToRefund = $gateway_params['transid'];
    // Get the module name from the gateway parameters
    $moduleName = $gateway_params['paymentmethod'];


    // Log the transaction
    logTransaction(
        $moduleName,
        "Refund Requested for Transaction ID: {$transactionIdToRefund} with Secret Key: {$api_Key}",
        "Debug"
    );
    [
        $responseData,
        $httpCode
    ] = khaltigateway_refund_api_call($api_Key, $transactionIdToRefund);

    if ($responseData['status'] !== 'success' && $httpCode !== 200) {
        // if the status is not success or the http code is not 200, log the error
        logTransaction(
            $moduleName,
            $responseData,
            "Refund Request Failed: " . $httpCode
        );
        // Return failure to WHMCS
        return [
            'status' => 'error',
            'rawdata' => $responseData,
            'transid' => $transactionIdToRefund,
            'error' => 'Refund through gateway failed. ' . $responseData['detail'] . ' HTTP Code: ' . $httpCode,
        ];

    }

    // Return success to WHMCS if the status is success and the http code is 200
    return [
        'status' => 'success',
        'rawdata' => $responseData,
        'transid' => $transactionIdToRefund,
    ];
}
