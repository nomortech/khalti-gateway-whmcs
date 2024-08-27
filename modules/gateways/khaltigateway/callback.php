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

header("Content-Type: application/json");

// Enable detailed error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Require libraries needed for gateway module functions.
$WHMCS_ROOT = dirname(dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME']))));

// Load WHMCS
require_once $WHMCS_ROOT . "/init.php";

$whmcs->load_function('gateway');
$whmcs->load_function('invoice');

// Load the Khalti gateway init file
require_once __DIR__ . "/init.php";
require_once __DIR__ . "/whmcs.php";

// Retrieve callback parameters
$callback_args = $_GET;
$pidx = $callback_args['pidx'];
$khalti_transaction_id = $callback_args['transaction_id'] ? $callback_args['transaction_id'] : $callback_args['txnId'];
$amount_paisa = intval($callback_args['amount']);
$amount_rs = $amount_paisa / 100;

$purchase_order_id = $callback_args['purchase_order_id'];
// Explode the string by the underscore (_) to separate Invoice ID and NPR amount
$parts = explode('_', $purchase_order_id);
// Now explode the first part to get the ID after "Invoice:"
$invoiceParts = explode(':', $parts[0]);
// The invoice ID should be the second element after splitting by ':'
$invoice_id = $invoiceParts[1];

$gateway_module = $khaltigateway_gateway_params['paymentmethod'];

// Function to handle errors and stop execution
function error_resp($msg)
{
    global $gateway_module;
    logTransaction($gateway_module, $msg, "Error");
    header("HTTP/1.1 400 Bad Request");
    die(json_encode(['error' => $msg]));
}

// Log the initial callback request
logTransaction($gateway_module, "Callback initiated with data: " . json_encode($_GET), "Debug");

// Validate necessary parameters
if (!$khalti_transaction_id || !$amount_paisa) {
    error_resp("Insufficient Data to proceed: Missing transaction ID or amount.");
}

// Perform the payment lookup with Khalti
$response = khaltigateway_epay_lookup($khaltigateway_gateway_params, $pidx);

if (!$response) {
    error_resp("Confirmation with Khalti failed.");
}

// Log the response from Khalti
logTransaction($gateway_module, "Khalti response received: " . json_encode($response), "Debug");

// Handle various payment statuses
if ($response["status"] == "Refuded") {
    error_resp("ERROR !! Payment already refunded.");
}

if ($response["status"] == "Expired") {
    error_resp("ERROR !! Payment Request already expired.");
}

if ($response["status"] == "Pending") {
    error_resp("Payment is still pending.");
}

if ($response["status"] !== "Completed") {
    error_resp("ERROR !! Payment status is NOT COMPLETE.");
}

// Log that the payment is marked as completed
logTransaction($gateway_module, "Payment completed successfully", "Success");

// Prepare data for WHMCS processing
$wh_response = $response;
$wh_invoiceId = $invoice_id;
$wh_paymentAmount = $amount_rs;
$wh_payload = $callback_args;
$wh_transactionId = $khalti_transaction_id;
$wh_paymentSuccess = true;
$wh_paymentFee = 0.0;
$wh_gatewayModule = $gateway_module;


// Convert the amount from NPR to base currency
$wh_amount_in_base_currency = khaltigateway_convert_from_npr_to_basecurrency($amount_rs);

// Fetch the actual invoice details from WHMCS
$invoice = localAPI("GetInvoice", array("invoiceid" => $invoice_id));

if (!$invoice || isset($invoice['result']) && $invoice['result'] !== 'success') {
    error_resp("Failed to retrieve invoice with ID: " . $invoice_id);
}


// Prepare data to submit to WHMCS for payment processing
$khaltigateway_whmcs_submit_data = array(
    'wh_payload' => $wh_payload,
    'wh_response' => $wh_response,
    'wh_invoiceId' => $wh_invoiceId,
    'wh_gatewayModule' => $wh_gatewayModule,
    'wh_transactionId' => $wh_transactionId,
    'wh_paymentAmount' => $wh_amount_in_base_currency,
    'wh_paymentFee' => $wh_paymentFee,
    'wh_paymentSuccess' => $wh_paymentSuccess
);

// Log the data being submitted to WHMCS
logTransaction($gateway_module, "Submitting data to WHMCS: " . json_encode($khaltigateway_whmcs_submit_data), "Debug");

// Submit the data to WHMCS
khaltigateway_acknowledge_whmcs_for_payment($khaltigateway_whmcs_submit_data);

// Log the completion of the callback processing
logTransaction($gateway_module, "Callback processing complete", "Success");

?>
