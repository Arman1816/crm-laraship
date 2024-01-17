<?php

namespace Corals\Modules\Marketplace\database\seeds;

use Corals\Modules\Utility\Facades\ListOfValue\ListOfValues;
use Corals\Modules\Utility\Models\ListOfValue\ListOfValue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class MarketplacePackagesDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $packages = [
            "FedEx_Box_10kg,FedEx® 10kg Box,15.81 x 12.94 x 10.19 in",
            "FedEx_Box_25kg,FedEx® 25kg Box,54.80 x 42.10 x 33.50 in",
            "FedEx_Box_Extra_Large_1,FedEx® Extra Large Box (X1),11.88 x 11.00 x 10.75 in",
            "FedEx_Box_Extra_Large_2,FedEx® Extra Large Box (X2),15.75 x 14.13 x 6.00 in",
            "FedEx_Box_Large_1,FedEx® Large Box (L1),17.50 x 12.38 x 3.00 in",
            "FedEx_Box_Large_2,FedEx® Large Box (L2),11.25 x 8.75 x 7.75 in",
            "FedEx_Box_Medium_1,FedEx® Medium Box (M1),13.25 x 11.50 x 2.38 in",
            "FedEx_Box_Medium_2,FedEx® Medium Box (M2),11.25 x 8.75 x 4.38 in",
            "FedEx_Box_Small_1,FedEx® Small Box (S1),12.38 x 10.88 x 1.50 in",
            "FedEx_Box_Small_2,FedEx® Small Box (S2),11.25 x 8.75 x 4.38 in",
            "FedEx_Envelope,FedEx® Envelope,12.50 x 9.50 x 0.80 in",
            "FedEx_Padded_Pak,FedEx® Padded Pak,11.75 x 14.75 x 2.00 in",
            "FedEx_Pak_1,FedEx® Large Pak,15.50 x 12.00 x 0.80 in",
            "FedEx_Pak_2,FedEx® Small Pak,12.75 x 10.25 x 0.80 in",
            "FedEx_Tube,FedEx® Tube,38.00 x 6.00 x 6.00 in",
            "FedEx_XL_Pak,FedEx® Extra Large Pak,17.50 x 20.75 x 2.00 in",
            "Fastway_Australia_Satchel_A2,Satchel A2,594.00 x 420.00 x 48.00 mm",
            "Fastway_Australia_Satchel_A3,Satchel A3,420.00 x 297.00 x 64.00 mm",
            "Fastway_Australia_Satchel_A4,Satchel A4,297.00 x 210.00 x 64.00 mm",
            "Fastway_Australia_Satchel_A5,Satchel A5,210.00 x 148.00 x 64.00 mm",
            "couriersplease_500g_satchel,500g Satchel,22.00 x 33.50 x 0.10 cm",
            "couriersplease_1kg_satchel,1kg Satchel,28.00 x 35.00 x 0.10 cm",
            "couriersplease_3kg_satchel,3kg Satchel,34.00 x 42.00 x 0.10 cm",
            "couriersplease_5kg_satchel,5kg Satchel,43.70 x 59.70 x 0.10 cm",
            "DHLeC_Irregular,Irregular Shipment,10.00 x 10.00 x 10.00 in",
            "DHLeC_SM_Flats,Flats,27.00 x 17.00 x 17.00 in",
            "USPS_FlatRateCardboardEnvelope,Flat Rate Cardboard Envelope,12.50 x 9.50 x 0.75 in",
            "USPS_FlatRateEnvelope,Flat Rate Envelope,12.50 x 9.50 x 0.75 in",
            "USPS_FlatRateGiftCardEnvelope,Flat Rate Gift Card Envelope,10.00 x 7.00 x 0.75 in",
            "USPS_FlatRateLegalEnvelope,Flat Rate Legal Envelope,15.00 x 9.50 x 0.75 in",
            "USPS_FlatRatePaddedEnvelope,Flat Rate Padded Envelope,12.50 x 9.50 x 1.00 in",
            "USPS_FlatRateWindowEnvelope,Flat Rate Window Envelope,10.00 x 5.00 x 0.75 in",
            "USPS_IrregularParcel,Irregular Parcel,0.00 x 0.00 x 0.00 in",
            "USPS_LargeFlatRateBoardGameBox,Large Flat Rate Board Game Box,24.06 x 11.88 x 3.13 in",
            "USPS_LargeFlatRateBox,Large Flat Rate Box,12.25 x 12.25 x 6.00 in",
            "USPS_APOFlatRateBox,APO/FPO/DPO Large Flat Rate Box,12.25 x 12.25 x 6.00 in",
            "USPS_LargeVideoFlatRateBox,Flat Rate Large Video Box (Int'l only),9.60 x 6.40 x 2.20 in",
            "USPS_MediumFlatRateBox1,Medium Flat Rate Box 1,11.25 x 8.75 x 6.00 in",
            "USPS_MediumFlatRateBox2,Medium Flat Rate Box 2,14.00 x 12.00 x 3.50 in",
            "USPS_RegionalRateBoxA1,Regional Rate Box A1,10.13 x 7.13 x 5.00 in",
            "USPS_RegionalRateBoxA2,Regional Rate Box A2,13.06 x 11.06 x 2.50 in",
            "USPS_RegionalRateBoxB1,Regional Rate Box B1,12.25 x 10.50 x 5.50 in",
            "USPS_RegionalRateBoxB2,Regional Rate Box B2,16.25 x 14.50 x 3.00 in",
            "USPS_SmallFlatRateBox,Small Flat Rate Box,8.69 x 5.44 x 1.75 in",
            "USPS_SmallFlatRateEnvelope,Small Flat Rate Envelope,10.00 x 6.00 x 4.00 in",
            "USPS_SoftPack,Soft Pack Padded Envelope,Length and width defined in the Parcel",
            "UPS_Box_10kg,Box 10kg,410.00 x 335.00 x 265.00 mm",
            "UPS_Box_25kg,Box 25kg,484.00 x 433.00 x 350.00 mm",
            "UPS_Express_Box,Express Box,460.00 x 315.00 x 95.00 mm",
            "UPS_Express_Box_Large,Express Box Large,18.00 x 13.00 x 3.00 in",
            "UPS_Express_Box_Medium,Express Box Medium,15.00 x 11.00 x 3.00 in",
            "UPS_Express_Box_Small,Express Box Small,13.00 x 11.00 x 2.00 in",
            "UPS_Express_Envelope,Express Envelope,12.50 x 9.50 x 2.00 in",
            "UPS_Express_Hard_Pak,Express Hard Pak,14.75 x 11.50 x 2.00 in",
            "UPS_Express_Legal_Envelope,Express Legal Envelope,15.00 x 9.50 x 2.00 in",
            "UPS_Express_Pak,Express Pak,16.00 x 12.75 x 2.00 in",
            "UPS_Express_Tube,Express Tube,970.00 x 190.00 x 165.00 mm",
            "UPS_Laboratory_Pak,Laboratory Pak,17.25 x 12.75 x 2.00 in",
            "UPS_MI_BPM,BPM (Mail Innovations - Domestic & International),0.00 x 0.00 x 0.00 in",
            "UPS_MI_BPM_Flat,BPM Flat (Mail Innovations - Domestic & International),0.00 x 0.00 x 0.00 in",
            "UPS_MI_BPM_Parcel,BPM Parcel (Mail Innovations - Domestic & International),0.00 x 0.00 x 0.00 in",
            "UPS_MI_First_Class,First Class (Mail Innovations - Domestic only),0.00 x 0.00 x 0.00 in",
            "UPS_MI_Flat,Flat (Mail Innovations - Domestic only),0.00 x 0.00 x 0.00 in",
            "UPS_MI_Irregular,Irregular (Mail Innovations - Domestic only),0.00 x 0.00 x 0.00 in",
            "UPS_MI_Machinable,Machinable (Mail Innovations - Domestic only),0.00 x 0.00 x 0.00 in",
            "UPS_MI_MEDIA_MAIL,Media Mail (Mail Innovations - Domestic only),0.00 x 0.00 x 0.00 in",
            "UPS_MI_Parcel_Post,Parcel Post (Mail Innovations - Domestic only),0.00 x 0.00 x 0.00 in",
            "UPS_MI_Priority,Priority (Mail Innovations - Domestic only),0.00 x 0.00 x 0.00 in",
            "UPS_MI_Standard_Flat,Standard Flat (Mail Innovations - Domestic only),0.00 x 0.00 x 0.00 in",
            "UPS_Pad_Pak,Pad Pak,14.75 x 11.00 x 2.00 in",
            "UPS_Pallet,Pallet,120.00 x 80.00 x 200.00 cm",
        ];

        $packagesLOVParent = ListOfValue::query()
            ->updateOrCreate(['code' => 'marketplace_packages_templates'], [
                'value' => 'Packages Templates',
                'module' => 'Marketplace'
            ]);

        $listOfValuesPackages = [];

        foreach ($packages as $rawData) {
            $package = $this->parsePackageRawData($rawData);

            $package['module'] = 'Marketplace';
            $package['parent_id'] = $packagesLOVParent->id;
            $package['properties'] = json_encode(Arr::pull($package, 'dimensions'));
            $package['created_at'] = now();
            $package['updated_at'] = now();

            $listOfValuesPackages[] = $package;
        }

        ListOfValue::query()->where('parent_id', $packagesLOVParent->id)->delete();

        ListOfValue::query()->insert($listOfValuesPackages);

        $shippingProvidersParent = ListOfValue::query()
            ->updateOrCreate(['code' => 'marketplace_shipping_providers'], [
                'value' => 'Shipping Providers',
                'module' => 'Marketplace'
            ]);

        $shippingProviders = [
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

        ListOfValue::query()->where('parent_id', $shippingProvidersParent->id)->delete();

        ListOfValues::insertListOfValuesChildren($shippingProvidersParent, $shippingProviders);
    }

    protected function parsePackageRawData($rawData): array
    {
        [$code, $label, $rawDimensions] = explode(',', $rawData);

        $value = sprintf("%s (%s)", $label, $rawDimensions);

        $rawDimensions = explode(' ', $rawDimensions);

        $dimensions = [
            'length' => $rawDimensions[0],
            'width' => $rawDimensions[2],
            'height' => $rawDimensions[4],
            'distance_unit' => $rawDimensions[5]
        ];

        return compact('code', 'value', 'label', 'dimensions');
    }
}
