# Hello World Two-Tier Application

This repository contains a simple "Hello World" web application built using a two-tier architecture. It serves as an introductory example to understand basic web architecture and secure communication between components.

## Architecture Overview

![alt text](aws-three-tier-architecture.png)

The application is divided into two main tiers:

### 1. Frontend Tier
- Built using **HTML**, **CSS**, and **JavaScript** for the user interface.
- **PHP** is used for application logic to:
  - Fetch data from the database.
  - Send messages to the database.
- Handles all communication with the backend using HTTP requests.

### 2. Database Tier
- Stores and manages application data.
- Performs read and write operations as requested by the frontend.
- Configured to accept connections over **SSL/TLS**, ensuring **in-flight encryption** for all data exchanged between the frontend and the database.

## Security Features

- ✅ **SSL/TLS Encrypted Database Connection**: All database traffic is secured with SSL/TLS to protect data during transmission.

## Use Case

This project is ideal for:
- Demonstrating separation of frontend and backend responsibilities.
- Learning to connect PHP-based frontend logic with a secure database.
- Practicing best practices for securing data in web applications.

## Technologies Used

- **Frontend**: HTML, CSS, JavaScript
- **Backend Logic**: PHP
- **Database**: (e.g., MySQL, PostgreSQL) – with SSL/TLS enabled