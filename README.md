# Khalti Payment Gateway Module for WHMCS

Welcome to the **Khalti Payment Gateway Module for WHMCS**. This module integrates the Khalti Wallet with the WHMCS platform, allowing secure and seamless payment processing. This version is an unofficial fork of the original Khalti Payment Gateway Module, enhanced with additional features and improved integration with WHMCS.

![Khalti Payment Gateway Module for WHMCS](/screenshots/banner_2.png)

## Features

-   **Seamless WHMCS Integration**: Easy to set up and fully compatible with your existing WHMCS installation.
-   **Secure Payments via Khalti Wallet**: Accept payments directly through the Khalti Wallet, providing a trusted payment option.
-   **Direct Refund Processing**: Process refunds directly from within WHMCS.
-   **Gateway Logs**: Logs and Error will be displayed in the Gateway Logs of WHMCS for easy troubleshooting and debugging.
-   **Enhanced Module**: A forked and improved version of the official Khalti Plugin, with added functionality.

## Requirements

-   WHMCS 7.0 or later
-   PHP 7.2 or later
-   Khalti API credentials

## Installation

1. **Download the Module**: Download the latest version of the module from the [GitHub releases page](https://github.com/nomortech/khalti-gateway-whmcs/releases).

2. **Upload Files**: Unzip the downloaded file and upload the contents to the `/modules/gateways/` directory of your WHMCS installation.

3. **Activate the Module**:

    - Log in to your WHMCS admin panel.
    - Navigate to `Configuration` > `Apps & Integrations` > `Go to Browse Tab` > `Payments in Left Menu`.
    - Under `Available Gateways`, find "Khalti Payment Gateway" and click `Activate`.

4. **Configure the Module**:
    - Enter your Khalti API credentials, including the `Public Key`, `Secret Key`, and any other required settings.
    - Save the changes.

## Usage

Once installed and activated, the Khalti Payment Gateway will be available for your clients as a payment option during checkout. You can manage and monitor transactions directly through the WHMCS admin panel.

## Support

For support or questions regarding this module, you can:

-   Visit our [Support Portal](https://myaccount.nomor.host/supporttickets.php).
-   Email us at [support@nomor.host](mailto:support@nomor.host).

## Screenshots

![Khalti Payment Gateway Module for WHMCS Refund Screenshot](/screenshots/refund_screen.png)

## Contributing

We welcome contributions! If you would like to contribute to this project, please fork the repository and submit a pull request. For major changes, please open an issue first to discuss what you would like to change.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Acknowledgements

This module was initially developed by the Khalti team. This version is an unofficial fork that has been enhanced and maintained by NOMOR HOST Pvt. Ltd.

---

**Keywords**: WHMCS, Khalti, Khalti Payment Gateway, WHMCS Khalti, Web Hosting Billing, WHMCS Payment Module, Open Source WHMCS Module
