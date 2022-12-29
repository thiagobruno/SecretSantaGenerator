CREATE TABLE SecretSanta (
    Id bigint AUTO_INCREMENT NOT NULL,
    Person varchar(255) NOT NULL,
    Email varchar(255) NOT NULL,
    Selected boolean DEFAULT false,
    PRIMARY KEY (Id)
);
