CREATE TABLE `users` (
    `UserId` INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `Email` VARCHAR(128) NOT NULL,
    `Name` VARCHAR(128) NOT NULL,
    `Password` VARCHAR(128) NOT NULL,
    `Status` VARCHAR(128) NOT NULL,
    `Createdon` VARCHAR(128) NOT NULL
);

INSERT INTO `user` (`Email`, `Name`, `Password`, `Status`, `Createdon`) VALUES ('user1@example.com', 'user1', 'a722c63db8ec8625af6cf71cb8c2d939' /*pass1*/, 'stat1', 'createdon');
