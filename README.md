# Geoip HL Component (test-assignment)

## Task

Create a GeoIP lookup form with caching in a Highload Block.
- User enters a valid IP.
- If the IP exists in the HL block, show data from the database.
- If not, request data from a public GeoIP service (sypexgeo.net, geoip.top, ipstack.com).
- Show the data to the user and store it in the database.

## Installation

1. Copy the component to:
2. In the admin panel, create a **Highload Block** with the following settings:
    - **Entity Name (NAME):** `GeoipCache`
    - **Table Name:** `geoip_cache`

3. Add fields to the Highload Block:
    - `UF_IP` (string, required)
    - `UF_DATA` (text)
    - `UF_SOURCE` (string)
    - `UF_CREATED_AT` (date/time)

## Usage

Connect the component on the page:

```php
<?$APPLICATION->IncludeComponent("custom:geoip.hl", ".default", []);?>
