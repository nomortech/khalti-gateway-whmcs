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

function khaltigateway_acknowledge_whmcs_for_payment($post_data)
{
    global $gateway_module;

    try {
        $khalti_transaction_id = $post_data["wh_transactionId"];

        $wh_payload = $post_data['wh_payload'];
        $wh_response = $post_data['wh_response'];
        $wh_invoiceId = $post_data['wh_invoiceId'];
        $wh_gatewayModule = $post_data['wh_gatewayModule'];
        $wh_transactionId = $post_data['wh_transactionId'];
        $wh_paymentAmount = $post_data['wh_paymentAmount'];
        $wh_paymentFee = $post_data['wh_paymentFee'];
        $wh_paymentSuccess = $post_data['wh_paymentSuccess'];

        // Log the details before processing
        logTransaction($wh_gatewayModule, "Processing payment for Invoice ID: $wh_invoiceId, Transaction ID: $wh_transactionId, Amount: $wh_paymentAmount", "Debug");

        // Check Callback Transaction ID
        checkCbTransID($khalti_transaction_id);

        // Log the transaction
        $debugData = json_encode(array(
            'payload' => $wh_payload,
            'khalti_response' => $wh_response,
            'invoiceId' => $wh_invoiceId
        ));
        logTransaction($wh_gatewayModule, $debugData, "Success");

        addInvoicePayment(
            $wh_invoiceId,
            $wh_transactionId,
            $wh_paymentAmount,
            $wh_paymentFee,
            $wh_gatewayModule
        );

        callback3DSecureRedirect($wh_invoiceId, $wh_paymentSuccess);

    } catch (Exception $e) {
        logTransaction($wh_gatewayModule, "Error in khaltigateway_acknowledge_whmcs_for_payment: " . $e->getMessage(), "Failure");
        // Handle the exception or terminate script
        die("Error processing payment: " . $e->getMessage());
    }
}