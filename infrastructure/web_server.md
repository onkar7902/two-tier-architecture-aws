# Frontend and PHP Application Logic Setup Guide

This guide provides detailed step-by-step instructions to configure the web server and PHP-based application logic on an EC2 instance. It assumes you're using a Linux-based AMI with `dnf` package manager (e.g., Amazon Linux 2023).

---

## Step 1: Install and Configure Nginx (web Server)

```
# Install Nginx and Git
sudo dnf install nginx -y
sudo dnf install git -y
```

Replace the server block in /etc/nginx/nginx.conf with the content from the nginx_config file.

# Update the nginx configuration file
```
sudo sed -i 's/update-me/localhost:8080/g' /etc/nginx/nginx.conf
```

# Start and enable the Nginx service
```
sudo systemctl start nginx
sudo systemctl enable nginx
```

# Navigate to Nginx's root directory
```
cd /usr/share/nginx/html
```

# Clone the frontend files from the GitHub repository
```
sudo git clone https://github.com/onkar7902/two-tier-architecture-aws.git
```

# Move frontend files to the nginx HTML root
```
sudo mv two-tier-architecture-aws/frontend/* /usr/share/nginx/html
```

# Clean up the cloned repository
```
sudo rm -rf /usr/share/nginx/html/two-tier-architecture-aws
```

---

## Step 2: Install PHP, Apache, and MySQL client and Dependencies (Backend Logic)
https://docs.aws.amazon.com/linux/al2023/ug/ec2-lamp-amazon-linux-2023.html


# Update Apache config to listen on port 8080
```
sudo sed -i 's/^Listen 80$/Listen 8080/' /etc/httpd/conf/httpd.conf
```

# Start and enable Apache
```
sudo systemctl start httpd
sudo systemctl enable httpd
```

# Assign Apache group permissions to ec2-user for file management
```
sudo usermod -a -G apache ec2-user
sudo chown -R ec2-user:apache /var/www
```

---

## Step 3: Install Additional PHP Modules and Composer

```
# Install additional PHP packages for complete functionality
sudo yum install php-cli php-json php-mbstring php-pdo php-mysqlnd php-gd php-curl -y

# Install Composer (PHP dependency manager)
cd /tmp
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
php -r "unlink('composer-setup.php');"
```

---

## Step 4: Deploy PHP Backend Logic and Install AWS SDK

```
# Clone the backend application code
cd /var/www/html
sudo git clone https://github.com/onkar7902/two-tier-architecture-aws.git

# Move API scripts to appropriate folder
sudo mkdir api
sudo mv /var/www/html/two-tier-architecture-aws/backend/api/* api/

# Remove the cloned repo
sudo rm -rf /var/www/html/two-tier-architecture-aws

# Install AWS SDK for PHP
cd /var/www/html
composer require aws/aws-sdk-php
```

---

## Step 5: Configure SSL for RDS Connection

To ensure encrypted in-transit communication with Amazon RDS, you need to download the appropriate **Amazon RDS root certificate** (`bundle.pem`) and configure it on your EC2 instance.

🔗 **Download the Certificate**  
Follow the official AWS documentation to download the latest RDS SSL certificate:

👉 [Download Amazon RDS Root Certificates](https://docs.aws.amazon.com/AmazonRDS/latest/UserGuide/UsingWithRDS.SSL.html#UsingWithRDS.SSL.CertificatesAllRegions)

Once you’ve downloaded the certificate file (typically named `region_name-bundle.pem`), upload it to your EC2 instance and move it to the appropriate directory with secure permissions:

```bash
# Move the SSL bundle (Amazon RDS root certificate) to proper location
sudo mv /home/ec2-user/bundle.pem /etc/pki/tls/certs/

# Set proper ownership and permissions for the certificate
sudo chown apache:apache /etc/pki/tls/certs/bundle.pem
sudo chmod 600 /etc/pki/tls/certs/bundle.pem
```

---

> ✅ This completes the full setup of your frontend and backend logic for the two-tier application, secured with RDS SSL connectivity and AWS SDK integration.

---