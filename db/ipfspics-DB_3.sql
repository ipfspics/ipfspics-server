CREATE TABLE hash_info (hash VARCHAR(46) UNIQUE,
                        type VARCHAR(5),
                        nsfw TINYINT(1) NOT NULL,
                        sfw TINYINT(1) NOT NULL,
                        backed_up SMALLINT(6) NOT NULL,
                        banned TINYINT(1) NOT NULL,
                        first_seen INT(11),

                        PRIMARY KEY (hash)
);
