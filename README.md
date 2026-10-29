# Uniques Printing Press Ltd.

A responsive, multi-page PHP website for a fictional printing business. The project demonstrates reusable PHP includes, data-driven service and team pages, safe form handling, and a simple local contact-submission workflow.

## Features

- Shared site configuration, header, navigation, and footer
- Four main pages: Home, About, Services, and Contact
- Service catalog with individual service detail pages
- Team profiles rendered from an array
- Contact form with server-side validation, sticky values, CSRF protection, and JSONL storage
- Private local messages page with password form, newest-first table, and submission count
- Responsive styling with accessibility-friendly labels, focus states, and reduced-motion support

## Tech stack

- PHP 8.2 (compatible with PHP 8)
- HTML5
- CSS3
- Apache through XAMPP
- JSON Lines (`.jsonl`) for local contact-submission storage

## Run with XAMPP

1. Install and start **Apache** in the XAMPP Control Panel.
2. Place the project folder at:

   ```text
   C:\xampp\htdocs\swd\04-php-site
   ```

3. Open this URL in your browser:

   ```text
   http://localhost/swd/04-php-site/
   ```

4. Submit a test message from the Contact page. The application creates `storage/messages.jsonl` when it saves the first submission.
5. To view saved submissions locally, open:

   ```text
   http://localhost/swd/04-php-site/messages.php
   ```

   The local-learning password is `printing-local-only`, configured in `includes/config.php`.

The `storage/.htaccess` file denies direct web access to the saved messages. Keep the Apache configuration enabled so this access rule is applied.

## Project structure

```text
04-php-site/
├── about.php
├── contact.php
├── index.php
├── messages.php
├── README.md
├── service.php
├── services.php
├── assets/
│   └── css/
│       └── style.css
├── includes/
│   ├── config.php
│   ├── data.php
│   ├── footer.php
│   ├── functions.php
│   └── header.php
└── storage/
    ├── .htaccess
    └── messages.jsonl  (created after the first successful contact submission)
```

## What I learned

- I learned how to use PHP includes to share configuration and page layout: **[write your notes here]**
- I learned how to render services and team members from arrays: **[write your notes here]**
- I learned how to validate and safely display form input: **[write your notes here]**
- I learned how the contact form stores submissions and how the messages page reads them: **[write your notes here]**
- One thing I want to improve next is: **[write your notes here]**

## Local-learning security note

The messages page password is hard-coded in `includes/config.php` for convenience while learning locally. This is not production-grade authentication: the password is stored as plain text in the source code, there is no user management or rate limiting, and session security needs additional production configuration. Do not deploy this password or use this page with real private submissions on a public server. A production application should use secure password hashing and verification, environment-based secrets, HTTPS, hardened sessions, authorization controls, rate limiting, and a suitable protected data store.
