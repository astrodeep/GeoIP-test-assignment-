# Geoip HL Component(test-assignment)

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