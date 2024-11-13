-- create and select the database
DROP DATABASE IF EXISTS cafe;
CREATE DATABASE cafe;
USE cafe;  -- MySQL command

-- create the tables
CREATE TABLE categories (
  categoryID       INT(11)        NOT NULL   AUTO_INCREMENT,
  categoryName     VARCHAR(255)   NOT NULL,
  PRIMARY KEY (categoryID)
);

CREATE TABLE products (
  productID        INT(11)        NOT NULL   AUTO_INCREMENT,
  categoryID       VARCHAR(11)        NOT NULL,
  productCode      VARCHAR(10)    NOT NULL   UNIQUE,
  productName      VARCHAR(255)   NOT NULL,
  listPrice        DECIMAL(10,2)  NOT NULL,
  PRIMARY KEY (productID)
);

-- insert data into the database
INSERT INTO categories VALUES
(1, 'Regular'),
(2, 'Zero Sugar'),
(3, 'Energy');

INSERT INTO products VALUES
(1, 1, 'a', 'CocaCola', 10.00),
(2, 1, 'b', 'Sprite', 10.00),
(3, 1, 'c', 'Solo Original Lemonade', 8.00),
(4, 1, 'd', 'Fanta', 7.99),
(5, 1, 'e', 'Sunkist', 8.00),
(6, 2, 'f', 'Schweppes', 11.00),
(7, 2, 'g', 'Pepsi', 10.99),
(8, 3, 'h', 'Red Bull', 7.99),
(9, 3, 'i', 'Gatorade', 10.99),
(10, 3, 'j', 'Monster', 10.99);