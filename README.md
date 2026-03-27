<img width="1800" height="1025" alt="main-landing-page" src="https://github.com/user-attachments/assets/7f78b551-c172-48ba-a90d-173e29b9d7f3" /># Kitchen Ndeso — Business Order & Installment Management System

> **Note:** I have been building this project since April 2024. At the time, I did not practice version control — something I regret, as it makes it harder to show my development journey. I learned from it, and going forward I now prioritize consistent Git commits on every project.

---

## Overview

Caterflow is a full-stack web application I built to help my parents streamline and manage their business operations — replacing a scattered mix of Excel files and physical books with a single, integrated system for tracking orders, customers, products, vendors, and installment payment schedules.

---

## Features & App Flow

### 1. Authentication
- New users register with a username and a confirmed password.
  <img width="1800" height="1051" alt="regis-page" src="https://github.com/user-attachments/assets/d3eee780-fee4-44e2-aa16-d86f9a01f904" />

- Returning users log in and are redirected to the main dashboard.
  <img width="1794" height="1023" alt="login-page" src="https://github.com/user-attachments/assets/6c35e302-ef38-4ddb-8829-c731d70b2c0d" />
- Each user's data is fully isolated — there won't be any cross-account data leakage.
- Landing Page or main dashboard
<img width="1800" height="1025" alt="main-landing-page" src="https://github.com/user-attachments/assets/c7cfc85e-2252-495b-b811-3dfc18497b0a" />

### 2. Master Data Management
Before placing orders, users set up their business data:

- **Customers** — Name, type (Individual, Hotel, Company, Reseller), customer code (for invoicing), contact person, phone number, and address.
  <img width="1800" height="1024" alt="customer-form-page" src="https://github.com/user-attachments/assets/b988ef41-448b-457f-9f6f-9218d417385f" />

- **Products** — Category, name, product code, type (vendor-sourced or in-house), selling price, and cost of goods.
  **V<img width="1797" height="1024" alt="product-form-page" src="https://github.com/user-attachments/assets/a343812c-2a30-4305-887d-4ef6702abc56" />
endors / Suppliers** — Supplier details, linked to the products sourced from them.
<img width="1800" height="1024" alt="supplier-form-page" src="https://github.com/user-attachments/assets/cdbc4316-9c1d-491a-b1c6-5007aa8c8e1c" />

### 3. Order Management
<img width="1800" height="1025" alt="order-form-page" src="https://github.com/user-attachments/assets/231daf68-fc0d-4178-b0b3-1acffc62a37c" />
The core feature of the app. Users can:
- Select a customer and a delivery date.
- Add multiple products to a single order.
- Configure installment payment schedules — specifying the number of tenures and the interval (daily, weekly, monthly, or yearly) for customers who pay in installments.

### 4. Data Search & Detail Panel
A dynamic search interface that lets users browse and inspect all stored data in real time:
<img width="1800" height="1023" alt="customer-details" src="https://github.com/user-attachments/assets/de481b89-b4a8-41b7-af6a-ada3a382db5d" />
<img width="1800" height="1023" alt="product-details" src="https://github.com/user-attachments/assets/a73e870b-33d6-4855-a742-480d4a496603" />
<img width="1800" height="1023" alt="supplier-details" src="https://github.com/user-attachments/assets/582e4902-e60c-45a8-9650-78cfb37ec302" />
<img width="1800" height="1024" alt="order-details" src="https://github.com/user-attachments/assets/7e08901f-d167-4c5b-ad35-5e62d36c1c4a" />
<img width="1800" height="1023" alt="installment-details" src="https://github.com/user-attachments/assets/bd0bd759-9c6e-4987-97e8-af0c2e279c9a" />

| Table | Summary View | Detail Panel |
|---|---|---|
| **Customers** | Basic customer info | Total orders placed + unpaid installment count |
| **Vendors** | Basic vendor info | Total product cost purchased (useful for profit margin analysis) |
| **Products** | Basic product info | List of customers (hotels, etc.) who recently purchased the product |
| **Orders** | Order summary | Payment status, paid/unpaid tenure breakdown, itemized product list with quantities and subtotals |
| **Payment Schedules** | All tenures per order | Per-tenure status: Paid, Pending, or Overdue — with the ability to mark a tenure as paid upon receiving payment |

---

## Tech Stack

- **Backend:** PHP (no framework), MySQL via PDO with prepared statements
- **Frontend:** Vanilla JavaScript (AJAX), HTML, CSS
- **Server:** Apache via XAMPP
- **Auth:** PHP session-based authentication

---

## Running Locally

1. Install [XAMPP](https://www.apachefriends.org/) and start Apache + MySQL.
2. Clone this repo into your XAMPP `htdocs` directory.
3. Copy `.env.example` to `.env` and fill in your MySQL credentials.
4. Run `composer install` to install PHP dependencies.
5. Import the schema from `sqlQuery.txt` into your MySQL database.
6. Visit `http://localhost/AWS` in your browser.
