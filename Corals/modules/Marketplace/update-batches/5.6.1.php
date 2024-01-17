<?php

use Corals\Modules\Utility\Facades\ListOfValue\ListOfValues;
use Corals\Modules\Utility\Models\ListOfValue\ListOfValue;

$preferred_providers = [
    "apc_postal" => "APC Postal",
    "aramex" => "Aramex",
    "asendia_us" => "Asendia US",
    "australia_post" => "Australia Post (also used for Startrack)",
    "axlehire" => "Axlehire",
    "borderguru" => "BorderGuru",
    "boxberry" => "Boxberry",
    "bring" => "Bring (also used for Posten Norge)",
    "canada_post" => "Canada Post",
    "cdl" => "CDL",
    "collect_plus" => "CollectPlus",
    "correios_br" => "CorreiosBR",
    "correos_espana" => "Correos Espana",
    "couriersplease" => "Couriers Please",
    "deutsche_post" => "Deutsche Post",
    "dhl_benelux" => "DHL Benelux",
    "dhl_ecommerce" => "DHL eCommerce",
    "dhl_express" => "DHL Express",
    "dhl_germany_c2c" => "DHL Germany C2C",
    "dhl_germany" => "DHL Germany",
    "dpd_germany" => "DPD GERMANY",
    "dpd" => "DPD",
    "dpd_uk" => "DPD UK",
    "estafeta" => "Estafeta",
    "fastway_australia" => "Fastway Australia",
    "fedex" => "FedEx",
    "globegistics" => "Globegistics",
    "gls_deutschland" => "GLS Deutschland",
    "gls_france" => "GLS France",
    "gls_us" => "GLS US",
    "gophr" => "Gophr",
    "gso" => "GSO",
    "hermes_germany_b2c" => "Hermes Germany B2C",
    "hermes_uk" => "Hermes UK",
    "hongkong_post" => "Hongkong Post",
    "lasership" => "LaserShip",
    "lso" => "LSO",
    "mondial_relay" => "Mondial Relay",
    "newgistics" => "Newgistics",
    "new_zealand_post" => "New Zealand Post (also used for Pace and CourierPost)",
    "nippon_express" => "Nippon Express",
    "ontrac" => "OnTrac",
    "orangeds" => "OrangeDS",
    "parcelforce" => "Parcelforce",
    "parcel" => "Parcel",
    "passport" => "Passport",
    "pcf" => "PCF",
    "posti" => "Posti",
    "purolator" => "Purolator",
    "royal_mail" => "Royal Mail",
    "rr_donnelley" => "RR Donnelley",
    "russian_post" => "Russian Post",
    "sendle" => "Sendle",
    "skypostal" => "SkyPostal",
    "stuart" => "Stuart",
    "ups" => "UPS",
    "usps" => "USPS",
    "yodel" => "Yodel",
];

$ppParent = ListOfValue::query()->where('code', 'marketplace_shipping_providers')->first();

if ($ppParent) {
    $ppParent->children()->delete();

    cache()->flush();

    ListOfValues::insertListOfValuesChildren($ppParent, $preferred_providers);
}

