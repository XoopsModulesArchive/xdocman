#
#
#

CREATE TABLE xdocman_hits (
    id       BIGINT(20) NOT NULL AUTO_INCREMENT,
    doc      CHAR(32)   NOT NULL,
    doctype  CHAR(32)   NOT NULL,
    language CHAR(32)   NOT NULL,
    hits     CHAR(32)   NOT NULL,
    PRIMARY KEY (id)
)
    ENGINE = ISAM;

