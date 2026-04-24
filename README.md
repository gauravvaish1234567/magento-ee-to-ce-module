# Magento 2 Commerce → Open Source Migration Tool

## 📌 Overview

This module provides a **custom data migration framework** to migrate data from **Magento 2 Commerce (EE)** to **Magento 2 Open Source (CE)**.

It is designed as a **modular, CLI-driven, chunk-based migration system** with support for:

* Multi-database connections (source & target)
* EAV transformation (row_id → entity_id)
* Batch processing for large datasets
* Configurable mapping via XML
* Table grouping & dependency-based execution
* Error logging & recovery support

---

## 🧠 Architecture

```
CLI Command
   ↓
AbstractMigrateCommand
   ↓
Migration Engine
   ↓
Source DB → Transformer → Destination DB
                  ↓
           RowIdResolver
                  ↓
              MapReader
```

---

## ⚙️ Key Components

### 1. Console Commands

Each migration step is executed via CLI:

```bash
bin/magento m2:migrate:<group>
```

Example:

```bash
bin/magento m2:migrate:products
```

---

### 2. Migration Engine (`Model/Migration.php`)

Responsible for:

* Fetching tables
* Applying mapping rules
* Batch processing (chunk-based)
* Calling transformer
* Inserting into target DB

---

### 3. Source (`ResourceModel/Source.php`)

* Connects to **Commerce DB**
* Fetches data using batch queries
* Supports dynamic ordering

---

### 4. Destination (`ResourceModel/Destination.php`)

* Inserts data into **Open Source DB**
* Uses bulk insert (`insertMultiple`)
* Falls back to row-level insert on failure
* Logs failed rows to:

```
var/log/migration_errors.log
```

---

### 5. Transformer (`Model/Transformer.php`)

Handles:

* Removing staging fields (`created_in`, `updated_in`)
* Converting `row_id → entity_id`
* Preparing data for CE compatibility

---

### 6. RowIdResolver

* Maps EE `row_id` to CE `entity_id`
* Critical for:

  * products
  * categories
  * rules

---

### 7. MapReader (`m2map.xml`)

Controls:

* Table inclusion
* Ignore rules
* Row ID transformation
* Migration priority

---

### 8. TableGroups (`Model/TableGroups.php`)

Defines logical migration groups:

* base
* config
* eav
* categories
* products
* customers
* orders
* etc.

---

## 🚀 Migration Commands

### Base & Configuration

```bash
bin/magento m2:migrate:base
bin/magento m2:migrate:config
```

---

### EAV (CRITICAL)

```bash
bin/magento m2:migrate:eav
```

---

### Catalog

```bash
bin/magento m2:migrate:categories
bin/magento m2:migrate:products
bin/magento m2:migrate:product-relations
```

---

### Customer

```bash
bin/magento m2:migrate:customers
bin/magento m2:migrate:addresses
```

---

### Sales

```bash
bin/magento m2:migrate:cart
bin/magento m2:migrate:orders
```

---

### Other

```bash
bin/magento m2:migrate:inventory
bin/magento m2:migrate:tax
bin/magento m2:migrate:catalog-rules
bin/magento m2:migrate:cms
bin/magento m2:migrate:url-rewrites
bin/magento m2:migrate:sequences
bin/magento m2:migrate:admin
```

---

## 🔄 Correct Execution Flow

Run commands in this order:

```
1. base
2. config
3. eav
4. categories
5. products
6. product-relations
7. customers
8. addresses
9. inventory
10. tax
11. cart
12. orders
13. catalog-rules
14. cms
15. url-rewrites
16. sequences
17. admin
```

---

## ⚠️ Important Notes

### 1. Always Clean Target Tables

Before migration:

```sql
SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE <tables>;

SET FOREIGN_KEY_CHECKS = 1;
```

---

### 2. Do NOT Use Wildcards in SQL

❌ Invalid:

```sql
TRUNCATE catalog_product_entity_*;
```

✔ Valid:

```sql
TRUNCATE catalog_product_entity;
TRUNCATE catalog_product_entity_varchar;
```

---

### 3. Row ID Handling

Magento Commerce uses:

```
row_id (internal)
entity_id (logical)
```

Open Source uses:

```
entity_id only
```

👉 Must transform correctly using `RowIdResolver`

---

### 4. Batch Processing

Data is migrated in chunks:

```php
LIMIT 200 OFFSET X
```

---

### 5. Ordering (Critical)

Always use:

```php
ORDER BY primary_key ASC
```

---

## 🧪 Debugging

### Check Failed Rows

```bash
cat var/log/migration_errors.log
```

---

### Verify EAV

```sql
SELECT * FROM eav_entity_type;
```

Must include:

* catalog_product
* catalog_category
* customer

---

### Reindex After Migration

```bash
bin/magento setup:upgrade
bin/magento indexer:reindex
bin/magento cache:flush
```

---

## ❗ Common Issues

### Invalid entity_type

Cause:

* Missing `eav_entity_type`

Fix:

```bash
bin/magento m2:migrate:eav
```

---

### Duplicate entity_id

Cause:

* Re-running migration
* Dirty target DB

Fix:

* Truncate tables
* Ensure proper row_id mapping

---

### FK Constraint Errors

Cause:

* Wrong execution order
* Missing parent records

Fix:

* Follow correct migration sequence

---

## 🚀 Future Improvements

* Cursor-based pagination (no OFFSET)
* Retry mechanism for failed rows
* Resume migration support
* Data validation layer
* Parallel processing

---

## 👨‍💻 Author Notes

This tool replicates core ideas from Magento’s official Data Migration Tool but is:

* Lightweight
* Customizable
* Debug-friendly
* Modular

---

## ✅ Status

✔ Functional prototype
✔ Large dataset support
✔ Production-ready base (with improvements possible)

---
Please check the module if you have any suggestions do let me know on devgauravvaish@gmail.com