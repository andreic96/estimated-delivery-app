CREATE TABLE shipping (
    id BIGINT(11) UNSIGNED AUTO_INCREMENT,
    zip_code VARCHAR(10) NOT NULL,
    shipment_date DATE NOT NULL,
    delivered_date DATE NOT NULL,
    PRIMARY KEY (id)
);
