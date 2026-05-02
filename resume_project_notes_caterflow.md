=== PROJECT 1: Caterflow ===
Status: Actively building — deployed and functional, with invoice generation and analytics dashboard in progress
Stack: PHP · MySQL · HTML/CSS/Vanilla JS · AWS EC2 + RDS · Apache


--- RESUME BULLETS (pick 4–5 for the resume) ---

STRONGEST BULLETS:

- Built a full-stack business management system for a catering operation in PHP and MySQL, deployed on AWS EC2 with the database hosted on AWS RDS — covering customers, products, vendors, orders, and payment installments across 9 relational tables

- Engineered an installment payment engine that splits any order total evenly across a configurable number of tenures (daily / weekly / monthly / yearly intervals), auto-computes each due date from the delivery date, and absorbs rounding remainders in the final installment

- Implemented atomic 3-table PDO transactions for order creation — inserting into orders, order_items, and order_tenures in a single commit, with full rollback on failure, ensuring the database never lands in a partial state

- Designed a multi-user data isolation model: every query for user-owned data is scoped by session user_id at the SQL level, preventing cross-account data leakage without relying on application-layer guards

- Built a JSON API endpoint serving 5 search categories (customers, vendors, products, orders, installments) consumed by a Vanilla JS frontend that renders dynamic tables and a slide-in detail panel — all without page reload

- Wrote real-time overdue detection that auto-updates installment statuses at query time and propagates the overdue flag up to the parent order, giving the business accurate payment visibility at all times


SUPPORTING BULLETS (use if you have space or for a longer project description):

- Deployed via a Git-based pipeline: local development → GitHub → SSH pull to EC2, with database migrations handled manually via mysqldump + scp → RDS import

- Designed a dynamic multi-item order form where rows are added client-side, subtotals update live via JS (quantity × price), and the form serializes as parallel arrays for backend processing

- Secured all database queries with PDO prepared statements (ATTR_EMULATE_PREPARES = false) and hashed passwords with bcrypt, following OWASP fundamentals throughout

- Used a GENERATED column (subtotal = quantity × unit_price) in order_items to keep calculated data consistent at the database level rather than relying on application code


--- NUMBERS (concrete figures to drop into bullets) ---

- 9 relational tables (User_List, customers, customer_contacts, products, categories, vendors, orders, order_items, order_tenures)
- ~5,800 lines of code across 40 source files (PHP, JS, CSS)
- 5 AJAX search categories rendered without page reload
- 3-table atomic transaction on every order creation
- 4 installment period types (daily, weekly, monthly, yearly)
- 4 customer types (individual, hotel, company, reseller)
- 3 contact roles per customer (purchasing, payment, receiving)
- 2 AWS services in production (EC2 for compute, RDS for database)
- 7 feature modules (Auth, Customer, Product, Supplier, Order, Search, CustomerContact)


--- WHAT YOU ARE PROUD OF (raw, keep for interview stories) ---

- The installment engine: user sets the number of tenures and period — backend does the rest. Proud of how cleanly the remainder math works.
- The search panel: 5 categories, dynamic tables, slide-in detail views that join related data across multiple tables on a single row click.
- The order form UX: add/remove item rows dynamically, live subtotal updates, clean layout even for first-time users.
- No framework used intentionally — wanted to understand the DOM and HTTP at a fundamental level before abstracting it away.


--- HARD PROBLEMS (use these as STAR stories in interviews) ---

- Schema design for multi-user isolation: had to ensure every table had a user_id FK and every query filtered by it — missing even one would be a data leak.
- Atomic order creation: figuring out the right transaction boundary across 3 tables, and the invoice number trick (insert TEMP-uuid first, get the auto-increment ID, then update to the real formatted invoice number).
- Dynamic form serialization: the order form sends products[] and quantities[] as parallel arrays — had to write backend logic that safely zips and validates them regardless of how many rows the user adds.
- Installment date calculation: chose delivery_date (not order_date) as the starting point after thinking through the business logic — you don't owe money before you receive the goods.


--- IN PROGRESS (mention these to show momentum) ---

- Invoice PDF generator with digital signature / print support
- Key stats dashboard: business performance over customizable date ranges (daily, weekly, monthly, yearly)
- Smarter search column filtering per data category
