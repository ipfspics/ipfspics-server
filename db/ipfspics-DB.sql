DELIMITER ||
CREATE DATABASE hashes;
USE hashes;
CREATE TABLE hash_info (hash VARCHAR(46) UNIQUE,
                        type VARCHAR(5),
                        nsfw TINYINT(1) NOT NULL,
                        sfw TINYINT(1) NOT NULL,
                        backed_up SMALLINT(6) NOT NULL,
                        banned TINYINT(1) NOT NULL,
			nb_views BIGINT UNSIGNED NOT NULL,
                        first_seen INT(11),

                        PRIMARY KEY (hash)
);
CREATE TABLE votes (hash VARCHAR(46),
                    vote_type TINYTEXT,
                    ip VARCHAR(45) NOT NULL,
                    timestamp INT(11) NOT NULL
);
CREATE INDEX hash_index ON votes (hash);
CREATE TRIGGER correct_hash BEFORE INSERT ON hash_info
        FOR EACH ROW
                BEGIN
                        IF NEW.hash NOT REGEXP '(Q)[a-zA-Z0-9_]{45}' THEN
                                SIGNAL SQLSTATE '99999'
                                        SET MESSAGE_TEXT = 'Wrong hash';
                        END IF;
                END;
||
DELIMITER ;
