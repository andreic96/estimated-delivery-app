CREATE TABLE shipping (
    id BIGINT(11) UNSIGNED AUTO_INCREMENT,
    zip_code VARCHAR(10) NOT NULL,
    shipment_date DATETIME NOT NULL,
    delivered_date DATETIME NOT NULL,
    PRIMARY KEY (id),
    INDEX `shipping_zip_code_shipment_delivered` (`zip_code`, `shipment_date`, `delivered_date`)
);
