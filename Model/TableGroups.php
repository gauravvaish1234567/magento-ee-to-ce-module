<?php

namespace Vendor\M2Migration\Model;

class TableGroups
{
    public function base()
    {
        return [
            'store_website',
            'store_group',
            'store'
        ];
    }

    public function config()
    {
        return [
            'core_config_data'
        ];
    }

    public function eav()
    {
        return [
            'eav_entity_type', // ✅ FIXED (CRITICAL)
            'eav_attribute',
            'eav_attribute_set',
            'eav_attribute_group',
            'eav_entity_attribute'
        ];
    }

    public function category()
    {
        return [
            'catalog_category_entity',
            'catalog_category_entity_varchar',
            'catalog_category_entity_int',
            'catalog_category_entity_text',
            'catalog_category_entity_decimal',
            'catalog_category_entity_datetime',
            'catalog_category_product'
        ];
    }

    public function product()
    {
        return [
            'catalog_product_entity',
            'catalog_product_entity_varchar',
            'catalog_product_entity_int',
            'catalog_product_entity_text',
            'catalog_product_entity_decimal',
            'catalog_product_entity_datetime'
        ];
    }

    public function productRelations()
    {
        return [
            'catalog_product_website', // ✅ added
            'catalog_product_relation',
            'catalog_product_link',
            'catalog_product_super_link',
            'catalog_product_super_attribute'
        ];
    }

    public function customer()
    {
        return [
            'customer_entity',
            'customer_entity_varchar',
            'customer_entity_int',
            'customer_entity_text',
            'customer_entity_decimal',
            'customer_entity_datetime'
        ];
    }

    public function address()
    {
        return [
            'customer_address_entity',
            'customer_address_entity_varchar',
            'customer_address_entity_int',
            'customer_address_entity_text',
            'customer_address_entity_decimal', // ✅ added
            'customer_address_entity_datetime' // ✅ added
        ];
    }

    public function inventory()
    {
        return [
            'cataloginventory_stock',
            'cataloginventory_stock_item',
            'cataloginventory_stock_status' // ✅ added
        ];
    }

    public function tax()
    {
        return [
            'tax_class',
            'tax_calculation',
            'tax_calculation_rate',
            'tax_calculation_rule'
        ];
    }

    public function cart()
    {
        return [
            'quote',
            'quote_item',
            'quote_address',
            'quote_payment',
            'quote_shipping_rate' // ✅ added
        ];
    }

    public function orders()
    {
        return [
            'sales_order',
            'sales_order_item',
            'sales_order_address',
            'sales_order_payment',
            'sales_order_status_history',

            'sales_invoice',
            'sales_invoice_item',

            'sales_shipment',
            'sales_shipment_item',

            'sales_creditmemo',
            'sales_creditmemo_item'
        ];
    }

    public function rules()
    {
        return [
            'catalogrule',
            'catalogrule_product',
            'catalogrule_product_price',
            'salesrule',
            'salesrule_coupon'
        ];
    }

    public function cms()
    {
        return [
            'cms_page',
            'cms_block'
        ];
    }

    public function url()
    {
        return [
            'url_rewrite',
            'catalog_url_rewrite_product_category'
        ];
    }

    public function sequence()
    {
        return [
            'sequence_order_0',
            'sequence_order_1',
            'sequence_product_0',
            'sequence_product_1'
        ];
    }

    public function admin()
    {
        return [
            'admin_user',
            'authorization_role',
            'authorization_rule'
        ];
    }
}