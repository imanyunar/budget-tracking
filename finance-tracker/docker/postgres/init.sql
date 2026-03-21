-- Create the test database if it doesn't exist
SELECT 'CREATE DATABASE finance_test'
WHERE NOT EXISTS (SELECT FROM pg_database WHERE datname = 'finance_test')\gexec
