<?php
// Define the API URL
$api_url = "https://v6.exchangerate-api.com/v6/f7a455514512fa147d59263e/latest/USD";

// Get the target currency from the request
$target_currency = $_GET['currency'] ?? 'EUR'; // Default to EUR

// Fetch the API response
$response = file_get_contents($api_url);

if ($response) {
    // Decode the JSON response
    $data = json_decode($response, true);

    // Get the conversion rate for the target currency
    $rate = $data['conversion_rates'][$target_currency] ?? null;

    if ($rate) {
        echo $rate; // Return the conversion rate
    } else {
        echo "Error: Invalid target currency.";
    }
} else {
    echo "Error: Unable to fetch conversion rates.";
}
?>
