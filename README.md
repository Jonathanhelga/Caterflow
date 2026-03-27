# Kitchen Ndeso — Business Order & Installment Management System

> **Note:** I have been building this project since April 2024. At the time, I did not practice version control — something I regret, as it makes it harder to show my development journey. I learned from it, and going forward I now prioritize consistent Git commits on every project.

---

## Overview

Caterflow is a full-stack web application I built to help my parents streamline and manage their business operations — replacing a scattered mix of Excel files and physical books with a single, integrated system for tracking orders, customers, products, vendors, and installment payment schedules.

---

## Features & App Flow

### 1. Authentication
- New users register with a username and a confirmed password.
- Returning users log in and are redirected to the main dashboard.
- Each user's data is fully isolated — there won't be any cross-account data leakage.

### 2. Master Data Management
Before placing orders, users set up their business data:

- **Customers** — Name, type (Individual, Hotel, Company, Reseller), customer code (for invoicing), contact person, phone number, and address.
- **Products** — Category, name, product code, type (vendor-sourced or in-house), selling price, and cost of goods.
- **Vendors / Suppliers** — Supplier details, linked to the products sourced from them.

### 3. Order Management
The core feature of the app. Users can:
- Select a customer and a delivery date.
- Add multiple products to a single order.
- Configure installment payment schedules — specifying the number of tenures and the interval (daily, weekly, monthly, or yearly) for customers who pay in installments.

### 4. Data Search & Detail Panel
A dynamic search interface that lets users browse and inspect all stored data in real time:

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
