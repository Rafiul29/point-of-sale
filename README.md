# Smart Inventory POS & Invoice System - Project Analysis

## 1. Database Relation Analysis (ERD Schema)

Based on the requirements, here is the proposed database structure with relationships.

### Core Entities & Relationships

| Entity | Description | Relationships |
| :--- | :--- | :--- |
| **User** | System users (Admin, Cashier) | `HasMany` Sales, `HasMany` AuditLogs |
| **Category** | Product classifications | `HasMany` Products |
| **Supplier** | Product providers | `HasMany` Products, `HasMany` Purchases |
| **Product** | Inventory items | `BelongsTo` Category, `BelongsTo` Supplier, `HasMany` SaleItems, `HasMany` StockAdjustments |
| **Purchase** | Stock-in transactions | `BelongsTo` Supplier, `HasMany` PurchaseItems |
| **PurchaseItem** | Individual items in a purchase | `BelongsTo` Purchase, `BelongsTo` Product |
| **Customer** | Clients/Buyers | `HasMany` Sales, `HasMany` CustomerLedger |
| **Sale** | POS transactions (Stock-out) | `BelongsTo` Customer (nullable), `BelongsTo` User, `HasMany` SaleItems |
| **SaleItem** | Individual products in a sale | `BelongsTo` Sale, `BelongsTo` Product |
| **CustomerLedger**| Financial tracking (Due/Debit/Credit) | `BelongsTo` Customer |
| **StockAdjustment**| Manual stock corrections | `BelongsTo` Product, `BelongsTo` User |
| **AuditLog** | System activity tracking | `BelongsTo` User |

### Detailed Table Definitions (SQL View)

![Smart Inventory POS ER Diagram](file:///home/ubuntu/Desktop/code/pos/pos_er_diagram.png)

```mermaid
erDiagram
    USERS ||--o{ SALES : "processes"
    USERS ||--o{ AUDIT_LOGS : "performs"
    CATEGORIES ||--o{ PRODUCTS : "contains"
    SUPPLIERS ||--o{ PRODUCTS : "supplies"
    SUPPLIERS ||--o{ PURCHASES : "provides"
    PURCHASES ||--o{ PURCHASE_ITEMS : "includes"
    PRODUCTS ||--o{ PURCHASE_ITEMS : "bought_in"
    CUSTOMERS ||--o{ SALES : "makes"
    CUSTOMERS ||--o{ CUSTOMER_LEDGER : "has_history"
    SALES ||--o{ SALE_ITEMS : "contains"
    PRODUCTS ||--o{ SALE_ITEMS : "sold_in"
    PRODUCTS ||--o{ STOCK_ADJUSTMENTS : "adjusted"
    USERS ||--o{ STOCK_ADJUSTMENTS : "authorized"

    USERS {
        bigint id PK
        string name
        string username "unique"
        string password
        enum role "Admin, Cashier"
        datetime created_at
    }
    CATEGORIES {
        bigint id PK
        string name
        string description
    }
    SUPPLIERS {
        bigint id PK
        string name
        string contact_person
        string phone
        string email
        text address
    }
    PRODUCTS {
        bigint id PK
        bigint category_id FK
        bigint supplier_id FK
        string name
        string barcode "unique"
        decimal cost_price
        decimal selling_price
        integer stock_quantity
        integer reorder_level
        boolean is_active
    }
    CUSTOMERS {
        bigint id PK
        string name
        string phone "unique"
        string email
        text address
    }
    PURCHASES {
        bigint id PK
        bigint supplier_id FK
        bigint user_id FK "Recorded By"
        string purchase_number "unique"
        decimal total_amount
        enum status "Pending, Received"
        date purchase_date
    }
    PURCHASE_ITEMS {
        bigint id PK
        bigint purchase_id FK
        bigint product_id FK
        integer quantity
        decimal unit_cost
        decimal total_cost
    }
    SALES {
        bigint id PK
        bigint customer_id FK "nullable"
        bigint user_id FK "Cashier"
        string invoice_number "unique"
        decimal total_amount
        decimal discount_amount
        decimal vat_amount
        decimal net_amount
        enum payment_method "Cash, Card, Mobile"
        enum status "Paid, Partial, Due"
        datetime sale_date
    }
    SALE_ITEMS {
        bigint id PK
        bigint sale_id FK
        bigint product_id FK
        integer quantity
        decimal unit_price
        decimal subtotal
    }
    CUSTOMER_LEDGER {
        bigint id PK
        bigint customer_id FK
        bigint sale_id FK "nullable"
        enum type "Debit, Credit"
        decimal amount
        decimal balance_after
        string note
    }
    STOCK_ADJUSTMENTS {
        bigint id PK
        bigint product_id FK
        bigint user_id FK
        integer quantity_change "signed"
        enum type "Addition, Subtraction, Damage, Return"
        text reason
    }
    AUDIT_LOGS {
        bigint id PK
        bigint user_id FK
        string event "e.g. product.created"
        string auditable_type
        bigint auditable_id
        json old_values
        json new_values
        string ip_address
    }
    SETTINGS {
        bigint id PK
        string key "unique"
        text value
    }
```

---

## 2. System Design Analysis

### Architectural Pattern: Laravel Repository + Service Layer
To ensure the system is scalable and maintainable (as per non-functional requirements), I recommend the following architecture:

1.  **Models (Eloquent)**: Represent the database tables and define relationships (e.g., `Product hasMany SaleItems`).
2.  **Repositories**: Abstraction layer for data fetching. For example, `ProductRepository` handles finding products by barcode or category.
3.  **Service Layer**: Where the "Business Logic" lives.
    *   `SaleService`: Handles the complex logic of creating a sale, updating stock, and updating customer ledger in a single **Transaction**.
    *   `StockService`: Manages inventory levels and reorder alerts.
    *   `InvoiceService`: Generates PDF invoices using a library like `barryvdh/laravel-dompdf`.
4.  **Controllers**: Keep them thin. They should only receive requests and call the appropriate Service.
5.  **Audit Observers**: Use Laravel Observers to automatically record `AuditLog` entries whenever a `Product` or `Sale` is updated.

### Key Module Interactions

*   **POS Module**: 
    1. Cashier scans barcode (handled by **Barcode Management Module**).
    2. Frontend (React) fetches product details via API.
    3. Sale is finalized -> **SaleService** updates stock quantity -> **CustomerLedger** is updated if credit -> **InvoiceService** returns PDF link.
*   **Inventory Module**:
    1. Stock falls below `reorder_level`.
    2. **StockService** triggers a system notification or marks as "Low Stock" for the **Reporting Module**.

---

## 3. Implementation Recommendations (Add-ons)

1.  **Barcode Scanning**: Use a keyboard-wedge scanner. Implement a global listener in the React frontend to capture input into the POS search field automatically.
2.  **Excel Import**: Use `maatwebsite/excel` for Laravel to handle product bulk uploads.
3.  **PDF Generation**: Use Tailwind CSS for the invoice styling to ensure the printed version looks premium and modern.

---

## 4. Requirement Mapping (Traceability Matrix)

This section confirms how each of your specific requirements is addressed in the technical design.

### Must-have features
| Feature | Implementation Detail (Database / System) |
| :--- | :--- |
| **Login + roles** | `USERS` table with `role` enum (Admin, Cashier). |
| **Products (CRUD)** | `PRODUCTS`, `CATEGORIES`, `SUPPLIERS` tables + `ProductController`. |
| **Stock in/out** | `PURCHASES` (Stock In), `SALE_ITEMS` (Stock Out), `STOCK_ADJUSTMENTS`. |
| **POS sales screen** | `SALES` (Invoice tracking), `SALE_ITEMS` (Cart), fast search via barcode. |
| **VAT & Discounts** | `SALES.discount_amount`, `SALES.vat_amount` fields (Net calculation). |
| **Invoice PDF / Print** | `InvoiceService` using Laravel DOMPDF; `sales.invoice_number`. |
| **Customer ledger** | `CUSTOMERS` table + `CUSTOMER_LEDGER` (Debit/Credit history). |
| **Reports** | `ReportController` querying `SALES` (Daily/Weekly) and `PRODUCTS` (Top products, Low stock). |
| **Audit log** | `AUDIT_LOGS` table with `user_id` to track who changed stock or sales. |

### Add-ons
| Feature | Implementation Detail (Database / System) |
| :--- | :--- |
| **Barcode scanner** | `PRODUCTS.barcode` field; JavaScript global listener for keyboard-wedge scanners. |
| **Barcode labels PDF**| `BarcodeService` to generate standard labels from product list to PDF. |
| **Excel Import/Export**| `Maatwebsite/Excel` for both importing and exporting `Product` and `Stock` records. |




Changes
1. Doller sing change to bdt taka hole project 
2. admin don't acess pos page
3. cachier when sell product customtomer informatin get name,email,phone,address
4. categories subcategory needed (pic optional)
5. product model image filed use
6. /suppliers pages use bangladeshi frienly text use 
7. create and update product image field  and category and subcategory id added
8. /products page stock and stock sync option exclude total sku change (total Product)
9. purchases/create page stock update  status wise (Pending, Received, Cancelled)
10. /reports/sales page  word use friendly text
11. /reports/inventory page filter use start and end date  
12. dashboard header fixed navbar use  
13. /audit-logs page filter option model App%5CModels%5CProduc opion exlcude


Cachier Dashboard chnages
1. terminal pos search product search name category sku barcode
2. terminal pos sell product  customer search (name phone email address)

demeshounajahan@gmail.com
