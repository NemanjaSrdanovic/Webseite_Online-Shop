CREATE TABLE Online_Shop(
Steuernummer NUMBER(5)DEFAULT 25694,
Name VARCHAR2(25) NOT NULL,
Domain VARCHAR2(25) NOT NULL,
CONSTRAINT pk_os PRIMARY KEY(Steuernummer),
CONSTRAINT check_stnr CHECK(Steuernummer BETWEEN 10000 AND 99999)
);

CREATE TABLE Support_MA(
Mitarbeiter_ID NUMBER(20),
Steuernummer NUMBER(5)DEFAULT 25694,
Nachname VARCHAR2 (25) NOT NULL,
CONSTRAINT pk_sma PRIMARY KEY(Mitarbeiter_ID),
CONSTRAINT fk_sma FOREIGN KEY(Steuernummer)REFERENCES Online_Shop
);

CREATE TABLE Vorgesetzter(
Mitarbeiter_ID1 NUMBER(20),
Mitarbeiter_ID2 NUMBER(20),
CONSTRAINT pk_vor PRIMARY KEY(Mitarbeiter_ID1,Mitarbeiter_ID2),
CONSTRAINT fk_vor FOREIGN KEY(Mitarbeiter_ID1)REFERENCES Support_MA,
CONSTRAINT fk_vor2 FOREIGN KEY(Mitarbeiter_ID2)REFERENCES Support_MA
);

CREATE TABLE Kunde(
KD_Nr NUMBER(20),
Username VARCHAR2(25)NOT NULL UNIQUE,
IBAN VARCHAR2(20)NOT NULL UNIQUE,
Steuernummer NUMBER(5)DEFAULT 25694,
CONSTRAINT pk_kd PRIMARY KEY(KD_Nr),
CONSTRAINT fk_kd FOREIGN KEY(Steuernummer)REFERENCES Online_Shop,
CONSTRAINT check_kdnr CHECK(KD_Nr BETWEEN 1 AND 5000)
);

CREATE TABLE Bezahlung(
Zahlungs_ID NUMBER(20),
Wert VARCHAR2(3) DEFAULT 'EUR',
Zahlungsart VARCHAR2(20)NOT NULL,
CONSTRAINT pk_bz PRIMARY KEY(Zahlungs_ID)
);

CREATE TABLE Artikel(
Artikel_Nr NUMBER(20),
Preis NUMBER(20)NOT NULL,
Type VARCHAR2(25)NOT NULL,
Bezeichnung VARCHAR2(25)NOT NULL,
Steuernummer NUMBER(5)DEFAULT 25694,
CONSTRAINT pk_ar PRIMARY KEY(Artikel_Nr),
CONSTRAINT fk_ar FOREIGN KEY(Steuernummer)REFERENCES Online_Shop,
CONSTRAINT check_ArNr CHECK(Artikel_Nr BETWEEN 1 AND 1000000)
);

CREATE TABLE Bestellung(
Bestell_Nr NUMBER(20),
Tracking_ID NUMBER(20)NOT NULL UNIQUE,
Datum DATE default CURRENT_TIMESTAMP,
Artikel_Nr NUMBER(20),
CONSTRAINT pk_bg PRIMARY KEY(Bestell_Nr,Artikel_Nr),
CONSTRAINT fk_bg FOREIGN KEY(Artikel_Nr)REFERENCES Artikel ON DELETE CASCADE
);

CREATE TABLE Bearbeitet(
Bestell_Nr NUMBER(20),
Mitarbeiter_ID NUMBER(20),
Artikel_Nr NUMBER(20)NOT NULL,
CONSTRAINT pk_bar PRIMARY KEY(Bestell_Nr,Mitarbeiter_ID,Artikel_Nr),
CONSTRAINT fk_bar2 FOREIGN KEY(Bestell_Nr,Artikel_Nr) REFERENCES Bestellung,
CONSTRAINT fk_bar FOREIGN KEY(Mitarbeiter_ID) REFERENCES Support_MA,
CONSTRAINT check_mID CHECK(Mitarbeiter_ID BETWEEN 0 AND 401)

);

CREATE TABLE Front_Office_MA(
Mitarbeiter_ID NUMBER(20),
Inbound_ID NUMBER(20)NOT NULL UNIQUE,
Soft_Skills NUMBER(2)NOT NULL,
CONSTRAINT pk_fo PRIMARY KEY(Mitarbeiter_ID),
CONSTRAINT fk_fo FOREIGN KEY(Mitarbeiter_ID) REFERENCES Support_MA,
CONSTRAINT check_inid CHECK(Inbound_ID BETWEEN 0 AND 201)
);

CREATE TABLE Back_Office_MA(
Mitarbeiter_ID NUMBER(20),
EMail VARCHAR2(25)NOT NULL UNIQUE,
Techical_Skills NUMBER(2)NOT NULL,
CONSTRAINT pk_bo PRIMARY KEY(Mitarbeiter_ID),
CONSTRAINT fk_bo FOREIGN KEY(Mitarbeiter_ID)REFERENCES Support_MA
);

CREATE TABLE Bewertet(
Artikel_Nr NUMBER(20),
KD_Nr NUMBER(20),
CONSTRAINT pk_bw PRIMARY KEY (Artikel_Nr,KD_Nr),
CONSTRAINT fk_bw FOREIGN KEY (KD_Nr)REFERENCES Kunde,
CONSTRAINT fk_bw2 FOREIGN KEY (Artikel_Nr)REFERENCES Artikel
);


CREATE TABLE Taetigt(
Artikel_Nr NUMBER(20),
KD_Nr NUMBER(20),
Bestell_Nr NUMBER(20),
Zahlungs_ID NUMBER(20),
CONSTRAINT pk_tae PRIMARY KEY (Artikel_Nr,KD_Nr,Bestell_Nr),
CONSTRAINT fk_tae FOREIGN KEY (KD_Nr)REFERENCES Kunde,
CONSTRAINT fk_tae2 FOREIGN KEY (Bestell_Nr,Artikel_Nr)REFERENCES Bestellung,
CONSTRAINT fk_tae3 FOREIGN KEY (Zahlungs_ID)REFERENCES Bezahlung
);


CREATE TABLE Loginform(

User_ID NUMBER(20),
Userr VARCHAR2(25)NOT NULL,
Pass VARCHAR2(25)NOT NULL,

CONSTRAINT pk_lf PRIMARY KEY (User_ID)
);

/*PROCEDURE*/
create or replace PROCEDURE bestell_mid (bestellnr IN NUMBER, mid OUT NUMBER) IS
BEGIN
  Select b.mitarbeiter_id INTO mid from bearbeitet b
  where b.bestell_nr=bestellnr; 
  END;
  /

/* AUTO INCREMENT -> SEQUENCE samt dazu passenden TRIGGER verwenden */ 
CREATE SEQUENCE kunde_seq START WITH 1;
CREATE SEQUENCE mitar_seq START WITH 1;
CREATE SEQUENCE login_seq START WITH 1;
CREATE SEQUENCE track_seq START WITH 170000;
CREATE SEQUENCE bestell_seq START WITH 5000;
CREATE SEQUENCE zahl_seq START WITH 3000;
CREATE SEQUENCE taetigt_bseq START WITH 5000;
CREATE SEQUENCE taetigt_zseq START WITH 3000;
CREATE SEQUENCE bearbeitet_bseq START WITH 5000;
CREATE SEQUENCE frontoffice_id START WITH 1;


/*TRIGGER*/
CREATE OR REPLACE TRIGGER frontoffice_insert
 BEFORE INSERT ON Front_Office_MA
 FOR EACH ROW
BEGIN
 SELECT frontoffice_id.nextval
 INTO :new.Inbound_ID
 FROM dual;
END;

CREATE OR REPLACE TRIGGER bearbeitetb_insert
 BEFORE INSERT ON Bearbeitet
 FOR EACH ROW
BEGIN
 SELECT bearbeitet_bseq.nextval
 INTO :new.Bestell_Nr
 FROM dual;
END;

CREATE OR REPLACE TRIGGER taetigtb_insert
 BEFORE INSERT ON Taetigt
 FOR EACH ROW
BEGIN
 SELECT taetigt_bseq.nextval
 INTO :new.Bestell_Nr
 FROM dual;
END;

CREATE OR REPLACE TRIGGER taetigtz_insert
 BEFORE INSERT ON Taetigt
 FOR EACH ROW
BEGIN
 SELECT taetigt_zseq.nextval
 INTO :new.Zahlungs_ID
 FROM dual;
END;

CREATE OR REPLACE TRIGGER Kunde_insert
 BEFORE INSERT ON Kunde
 FOR EACH ROW
BEGIN
 SELECT kunde_seq.nextval
 INTO :new.KD_Nr
 FROM dual;
END;

CREATE OR REPLACE TRIGGER Mitarbeiter_insert
 BEFORE INSERT ON Support_MA
 FOR EACH ROW
BEGIN
 SELECT mitar_seq.nextval
 INTO :new.Mitarbeiter_ID
 FROM dual;
END;

CREATE OR REPLACE TRIGGER Loginform_insert
 BEFORE INSERT ON Loginform
 FOR EACH ROW
BEGIN
 SELECT login_seq.nextval
 INTO :new.User_ID
 FROM dual;
END;

CREATE OR REPLACE TRIGGER Bestellund_insert
 BEFORE INSERT ON Bestellung
 FOR EACH ROW
BEGIN
 SELECT bestell_seq.nextval
 INTO :new.Bestell_Nr
 FROM dual;
END;

CREATE OR REPLACE TRIGGER Tracking_insert
 BEFORE INSERT ON Bestellung
 FOR EACH ROW
BEGIN
 SELECT track_seq.nextval
 INTO :new.Tracking_ID
 FROM dual;
END;

CREATE OR REPLACE TRIGGER Bezahlung_insert
 BEFORE INSERT ON Bezahlung
 FOR EACH ROW
BEGIN
 SELECT zahl_seq.nextval
 INTO :new.Zahlungs_ID
 FROM dual;
END;
