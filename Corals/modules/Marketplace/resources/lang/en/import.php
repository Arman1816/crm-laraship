<?php

return [
    'labels' => [
        'import' => '<i class="fa fa-th fa-th"></i> Import',
        'download_sample' => '<i class="fa fa-download fa-th"></i> Download Import Sample',
        'column' => 'Column',
        'description' => 'Description',
        'column_description' => 'Import columns description',
        'file' => 'Import File (csv)',
        'upload_file' => '<i class="fa fa-upload fa-th"></i> Upload',
        'images_root' => 'Images Root Path',
        'images_root_help' => 'full path from app root. <br/>e.g. storage/app/marketplace, public/marketplace or /',
        'clear_images' => 'Clear existing images and upload new images from import.'
    ],
    'messages' => [
        'file_uploaded' => 'File has been uploaded successfully and a job dispatched to handle the import process.'
    ],
    'exceptions' => [
        'invalid_headers' => 'Invalid import file columns. Please check the sample import file.',
        'path_not_exist' => 'path not exist.',
    ],
    'product-headers' => [
        'SKU' => '<sup class="required-asterisk">*</sup> The product will be added or updated based on SKU.',
        'Parent SKU' => 'The parent product sku. Required in case of Type is <b>variable</b>.',
        'Type' => '<sup class="required-asterisk">*</sup> Valid values: <b>simple</b>, <b>variable</b>',
        'Name' => '<sup class="required-asterisk">*</sup> The product name.',
        'Short Description' => '<sup class="required-asterisk">*</sup> The product short description',
        'Description' => 'The product description',
        'Status' => '<sup class="required-asterisk">*</sup> Valid values: <b>active</b>, <b>inactive</b>',
        'Attribute Sets' => 'The product attribute sets code. Pipe concatenated for multiple
                        <br/> <b>e.g. first-set|second-set</b>',
        'Product Attributes' => 'The product level attributes code. Pipe concatenated for multiple
                        <br/> <b>e.g. dimension:10cm,15cm,20cm|cover:plastic</b>',
        'Attributes' => 'The product variation attributes code. Pipe concatenated for multiple
                        <br/> <b>e.g. color:red|size:small</b>',
        'Brand Name' => 'The product brand name',
        'Categories' => '<sup class="required-asterisk">*</sup> The product categories. Pipe concatenated for multiple categories e.g. <b>Software|Themes|Scripts</b>',
        'Featured Image' => 'The product featured image.
                          <br/> File location in the selected <u>[Images Root Path]</u>
                          <br/> e.g. <b>products/x-product/featured.png</b>',
        'Images' => 'The product images.
                          <br/> File location in the selected <u>[Images Root Path]</u>. Pipe concatenated for multiple
                          <br/> e.g. <b>products/x-product/image-0.png|products/x-product/image-1.png</b>',
        'Regular Price' => '<sup class="required-asterisk">*</sup> The product regular Price',
        'Sale Price' => 'The product sale Price',
        'Inventory' => '<sup class="required-asterisk">*</sup> Valid values: <b>finite</b>, <b>bucket</b>, <b>infinite</b>',
        'Inventory Value' => 'Required when inventory is finite or bucket. 
                                <br/> In case of <b>finite</b> value is the inventory quantity as <b>integer</b> value.
                                <br/> In case of <b>bucket</b> valid values: <b>in_stock</b>, <b>out_of_stock</b>, <b>limited</b>',
        'Shippable' => 'Determine if the product shippable or not. Valid values: <b>1</b>, <b>0</b>',
        'Width' => 'Package width',
        'Height' => 'Package height',
        'Length' => 'Package length',
        'Weight' => 'Package weight',
    ],
    'category-headers' => [
        'Name' => 'The category name',
        'Status' => 'The category status',
        'Slug' => 'The category slug. auto generated when empty',
        'Parent Category' => 'The parent category name.',
        'Featured' => 'Determine if the category is featured or not. Valid values: <b>1</b>, <b>0</b>',
        'Image' => 'Category image.
                          <br/> File location in the selected <u>[Images Root Path]</u>
                          <br/> e.g. <b>categories/software/logo.png</b>',
    ],
    'brand-headers' => [
        'Name' => 'The brand name',
        'Status' => 'The brand status',
        'Slug' => 'The brand slug. auto generated when empty',
        'Featured' => 'Determine if the brand is featured or not. Valid values: <b>1</b>, <b>0</b>',
        'Image' => 'Brand image.
                          <br/> File location in the selected <u>[Images Root Path]</u>
                          <br/> e.g. <b>brands/dell/logo.png</b>',
    ]
];
