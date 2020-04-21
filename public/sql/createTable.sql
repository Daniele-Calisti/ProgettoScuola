CREATE TABLE `progettoweb`.`credenziali` ( `id` INT NULL AUTO_INCREMENT ,
                                            `utente` VARCHAR(255) NULL ,
                                            `password` VARCHAR(255) NULL, 
                                            `email` VARCHAR(255) NULL , 
                                            `verificato` TINYINT(1) NULL COMMENT 'assume valore 1 o 0' , 
                                            `token` VARCHAR(255) NULL , 
                                            PRIMARY KEY (`id`)) ENGINE = InnoDB;
                                          
