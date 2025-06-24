# Simple Ecommerce App - Product Requirements Document (PRD)

## Overview

This document outlines the requirements for a simple ecommerce CRUD API. The app allows clients to manage products, users, and orders via RESTful endpoints. There is no UI; all interactions are through API requests.

## Features

### API Endpoints

-   User registration and authentication (API token-based)
-   CRUD operations for products
-   CRUD operations for orders
-   CRUD operations for users (admin only)
-   List all orders for a user
-   List all products

## Database Models

### User

-   id (bigint, primary key)
-   name (string)
-   email (string, unique)
-   password (string)
-   is_admin (boolean, default: false)
-   timestamps

### Product

-   id (bigint, primary key)
-   name (string)
-   description (text)
-   price (decimal)
-   stock (integer)
-   timestamps

### Order

-   id (bigint, primary key)
-   user_id (foreign key to users)
-   status (string: pending, paid, shipped, completed, cancelled)
-   total (decimal)
-   timestamps

### OrderItem

-   id (bigint, primary key)
-   order_id (foreign key to orders)
-   product_id (foreign key to products)
-   quantity (integer)
-   price (decimal)
-   timestamps

## API Routes (example)

| Method | Endpoint                | Description                      |
| ------ | ----------------------- | -------------------------------- |
| POST   | /api/register           | Register a new user              |
| POST   | /api/logout             | logout (has token)               |
| POST   | /api/login              | User login (get token)           |
| GET    | /api/products           | List all products                |
| POST   | /api/products           | Create product (admin)           |
| GET    | /api/products/{id}      | Get product details              |
| PUT    | /api/products/{id}      | Update product (admin)           |
| DELETE | /api/products/{id}      | Delete product (admin)           |
| GET    | /api/orders             | List user orders                 |
| POST   | /api/orders             | Create order                     |
| GET    | /api/orders/{id}        | Get order details                |
| PUT    | /api/orders/{id}/status | Update order status (admin only) |
| DELETE | /api/orders/{id}        | Delete order (admin)             |
| GET    | /api/users              | List users (admin)               |
| GET    | /api/users/{id}         | Get user details (admin)         |
| PUT    | /api/users/{id}         | Update user (admin)              |
| DELETE | /api/users/{id}         | Delete user (admin)              |

## Technology Stack

-   Backend: Laravel 12.x (PHP)
-   Database: MySQL or SQLite

## Success Criteria

-   All CRUD endpoints function as described
-   API is stateless and token-authenticated
-   Database models match requirements

## Notes

-   Only admins can update the status of an order using the `/api/orders/{id}/status` endpoint.
-   The general order update endpoint (`PUT /api/orders/{id}`) should not allow status changes.

---

_This PRD serves as a starting point for development. Features can be expanded as needed._
