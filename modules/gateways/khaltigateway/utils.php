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


/**
 * Print_r inside a preformatted tag <pre>
 */
function ndie($data, $style = "")
{
    echo "<pre style='{$style}'>";
    print_r($data);
    echo "</pre>";
}

/**
 * Print_r inside a preformatted tag <pre> and Die
 */
function mdie($data)
{
    ndie($data);
    die();
}

/**
 * JSON Encode and Die
 */
function jdie()
{
    die(json_encode(array("idx" => null)));
}

/**
 * A simple template like function to include php file with incjected variables
 * @param  string $file     File to include
 * @param  array  $vars     Variables to inject into the file
 */
function file_include_contents($filename, $_inc_vars = array())
{
    if (is_file($filename)) {
        ob_start();
        include $filename;
        return ob_get_clean();
    }
    return false;
}