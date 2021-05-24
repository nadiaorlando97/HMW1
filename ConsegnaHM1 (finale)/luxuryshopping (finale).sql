-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 24, 2021 alle 23:45
-- Versione del server: 10.4.18-MariaDB
-- Versione PHP: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `luxuryshopping`
--

DELIMITER $$
--
-- Procedure
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `GetAcquistiNoBuonoOver` (IN `SpesaMassimaSpedizioni` INT)  BEGIN 
SELECT * 
FROM acquisto
WHERE id IN (
    SELECT id 
    FROM acquisto as a, utenti as u
    WHERE a.utente = u.CodUtente AND u.spesaTotSpedizioni > SpesaMassimaSpedizioni AND a.conBuono = FALSE
    );
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetAcquistiSpedizioniNonRecenti` (IN `CodiceUtente` INT)  BEGIN 
SELECT * 
FROM acquisto 
WHERE utente = CodiceUtente AND id IN (
	SELECT sc.acquisto 
    FROM spedizioneavvenuta as sa, spedizioniincorso as sc
    WHERE DATEDIFF(CURRENT_DATE(), sa.dataConsegna) > 30 OR DATEDIFF(CURRENT_DATE(), sc.dataInvio) > 40
);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetInzioSaldi` ()  BEGIN 
UPDATE articoli SET Sconto = CASE  
WHEN Prezzo >= 500 THEN 50 
WHEN Prezzo >= 300 AND Prezzo < 500 THEN 30
WHEN Prezzo >= 100 AND Prezzo < 300 THEN 10
ELSE 0
END;
UPDATE articoli SET PrezzoScontato = Prezzo * (1 - Sconto/100);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `GetMediaAcquisti` (IN `CodiceUtente` INT)  BEGIN 
SELECT conBuono, AVG(importo) FROM `acquisto` WHERE utente = CodiceUtente GROUP BY conBuono;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `acquisto`
--

CREATE TABLE `acquisto` (
  `id` int(11) NOT NULL,
  `conBuono` tinyint(1) NOT NULL,
  `importo` int(11) NOT NULL,
  `utente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `acquisto`
--

INSERT INTO `acquisto` (`id`, `conBuono`, `importo`, `utente`) VALUES
(3, 0, 500, 2),
(4, 0, 1000, 2),
(5, 1, 300, 2),
(6, 0, 450, 7),
(7, 0, 600, 7),
(8, 1, 700, 7);

-- --------------------------------------------------------

--
-- Struttura della tabella `admin`
--

CREATE TABLE `admin` (
  `codAdmin` int(11) NOT NULL,
  `utente` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `Sale` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `admin`
--

INSERT INTO `admin` (`codAdmin`, `utente`, `password`, `Sale`) VALUES
(1, 'nadiaorlando97@gmail.com', 'e36000d449c8fbd73966b0ce7d9f86a5', '9652b811bacf6c6cf24f'),
(4, 'nadia@libero.it', '574124c85909d48fd9ee24da8b544121', '626fe61eb00d64b0c019');

-- --------------------------------------------------------

--
-- Struttura della tabella `articoli`
--

CREATE TABLE `articoli` (
  `CodProdotto` int(8) NOT NULL,
  `NomeProdotto` varchar(256) COLLATE armscii8_bin NOT NULL,
  `Descrizione` varchar(256) COLLATE armscii8_bin NOT NULL,
  `Prezzo` float NOT NULL,
  `PercorsoImg` varchar(256) COLLATE armscii8_bin NOT NULL,
  `PrezzoScontato` float NOT NULL,
  `Sconto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin;

--
-- Dump dei dati per la tabella `articoli`
--

INSERT INTO `articoli` (`CodProdotto`, `NomeProdotto`, `Descrizione`, `Prezzo`, `PercorsoImg`, `PrezzoScontato`, `Sconto`) VALUES
(1, 'Jacquemus', 'Borsa media in pelle bianca', 525, './Immagini/jaquemus.jpg', 262.5, 50),
(2, 'Gucci', 'Borsa GG con catena', 1500, './Immagini/gucci1.jpg', 750, 50),
(3, 'Louis Vuitton', 'Borsetta piccola con logo', 1200, './Immagini/LV8.jpg', 600, 50),
(4, 'Chanel', 'Borsa piccola con tracolla', 2000, './Immagini/chanel.jpg', 1000, 50),
(5, 'Michael Kors', 'Zaino con doppie cerniere', 350, './Immagini/zaino.jpg', 245, 30),
(6, 'Moschino', 'Borsa grande LOVE a spalla', 180, './Immagini/moschino.jpg', 162, 10),
(7, 'Pinko', 'Mini Love Bag Icon Simply', 250, './Immagini/pinko.jpg', 225, 10),
(8, 'Dior', 'Borsa nera Saddle in pelle', 400, './Immagini/dior.jpg', 280, 30),
(9, 'Prada', 'Mini Borsa in nylon rossa', 500, './Immagini/prada.jpg', 250, 50);

-- --------------------------------------------------------

--
-- Struttura della tabella `articoliacquistati`
--

CREATE TABLE `articoliacquistati` (
  `CodProdotto` int(11) NOT NULL,
  `acquisto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `carrello`
--

CREATE TABLE `carrello` (
  `codCarrello` int(11) NOT NULL,
  `codProdotto` int(11) NOT NULL,
  `utente` varchar(256) NOT NULL,
  `nomeProdotto` varchar(256) NOT NULL,
  `Descrizione` varchar(256) NOT NULL,
  `Prezzo` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `preferiti`
--

CREATE TABLE `preferiti` (
  `RefCodProdotto` int(8) NOT NULL,
  `RefCodUtente` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struttura della tabella `recensioni`
--

CREATE TABLE `recensioni` (
  `codRecensione` int(11) NOT NULL,
  `TestoRecensione` varchar(256) NOT NULL,
  `CodProdotto` int(11) NOT NULL,
  `CodUtente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `recensioni`
--

INSERT INTO `recensioni` (`codRecensione`, `TestoRecensione`, `CodProdotto`, `CodUtente`) VALUES
(4, 'Adoro questa borsetta', 4, 2),
(6, 'Troppo carina questa borsa', 6, 2),
(7, 'Meravigliosa', 7, 4),
(10, 'La adoro', 8, 2),
(11, 'Soddisfatta dell\'acquisto', 9, 2),
(14, 'Bellissima', 1, 2),
(23, 'Molto capiente!', 5, 7),
(24, 'Ottima qualità', 2, 7),
(25, 'Mi piace tantissimo', 3, 7);

-- --------------------------------------------------------

--
-- Struttura della tabella `spedizioneavvenuta`
--

CREATE TABLE `spedizioneavvenuta` (
  `dataInvio` date NOT NULL,
  `acquisto` int(11) NOT NULL,
  `utente` int(11) NOT NULL,
  `costo` float NOT NULL,
  `dataConsegna` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `spedizioneavvenuta`
--

INSERT INTO `spedizioneavvenuta` (`dataInvio`, `acquisto`, `utente`, `costo`, `dataConsegna`) VALUES
('2020-04-03', 4, 2, 150, '2020-04-10'),
('2020-02-05', 5, 7, 120, '2020-02-10');

-- --------------------------------------------------------

--
-- Struttura della tabella `spedizioniincorso`
--

CREATE TABLE `spedizioniincorso` (
  `dataInvio` date NOT NULL,
  `acquisto` int(11) NOT NULL,
  `utente` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `spedizioniincorso`
--

INSERT INTO `spedizioniincorso` (`dataInvio`, `acquisto`, `utente`) VALUES
('2020-05-01', 3, 2),
('2020-04-03', 4, 2),
('2021-01-20', 6, 7),
('2021-02-01', 7, 7);

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `CodUtente` int(8) NOT NULL,
  `Utente` varchar(256) NOT NULL,
  `Password` varchar(256) NOT NULL,
  `Sale` varchar(256) NOT NULL,
  `spesaTotSpedizioni` float NOT NULL,
  `dataIscrizione` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`CodUtente`, `Utente`, `Password`, `Sale`, `spesaTotSpedizioni`, `dataIscrizione`) VALUES
(2, 'nadiaorlando97@gmail.com', '2694834590e19fd711925f50f17f4446', 'e1da813e83efe42eea44', 150, '2021-05-07'),
(3, 'pippo@hotmail.com', '2147395256c6ab834126e5400e79de6f', '2d83ce421eee424141be', 0, '2021-05-07'),
(6, 'mariello@hotmail.com', 'ee4e7b493013cc19e73a453c90fd50ee', 'f4bece3d5dcf91360552', 0, '2021-05-23'),
(7, 'maria@hotmail.com', '92560afe60581204aefb2a669094acf8', '536c2f0e8bb7a71d5919', 120, '2021-05-24');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `acquisto`
--
ALTER TABLE `acquisto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `acquisto_ibfk_1` (`utente`);

--
-- Indici per le tabelle `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`codAdmin`);

--
-- Indici per le tabelle `articoli`
--
ALTER TABLE `articoli`
  ADD PRIMARY KEY (`CodProdotto`);

--
-- Indici per le tabelle `articoliacquistati`
--
ALTER TABLE `articoliacquistati`
  ADD PRIMARY KEY (`CodProdotto`,`acquisto`),
  ADD KEY `articoliacquistati_ibfk_1` (`acquisto`);

--
-- Indici per le tabelle `carrello`
--
ALTER TABLE `carrello`
  ADD PRIMARY KEY (`codCarrello`);

--
-- Indici per le tabelle `preferiti`
--
ALTER TABLE `preferiti`
  ADD PRIMARY KEY (`RefCodProdotto`,`RefCodUtente`) USING BTREE,
  ADD KEY `RefCodUtente` (`RefCodUtente`);

--
-- Indici per le tabelle `recensioni`
--
ALTER TABLE `recensioni`
  ADD PRIMARY KEY (`codRecensione`);

--
-- Indici per le tabelle `spedizioneavvenuta`
--
ALTER TABLE `spedizioneavvenuta`
  ADD PRIMARY KEY (`acquisto`,`utente`),
  ADD KEY `spedizioneavvenuta_ibfk_2` (`utente`);

--
-- Indici per le tabelle `spedizioniincorso`
--
ALTER TABLE `spedizioniincorso`
  ADD PRIMARY KEY (`acquisto`,`utente`),
  ADD KEY `spedizioniincorso_ibfk_1` (`utente`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`CodUtente`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `acquisto`
--
ALTER TABLE `acquisto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT per la tabella `admin`
--
ALTER TABLE `admin`
  MODIFY `codAdmin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT per la tabella `articoli`
--
ALTER TABLE `articoli`
  MODIFY `CodProdotto` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT per la tabella `carrello`
--
ALTER TABLE `carrello`
  MODIFY `codCarrello` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT per la tabella `recensioni`
--
ALTER TABLE `recensioni`
  MODIFY `codRecensione` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `CodUtente` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `acquisto`
--
ALTER TABLE `acquisto`
  ADD CONSTRAINT `acquisto_ibfk_1` FOREIGN KEY (`utente`) REFERENCES `utenti` (`CodUtente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `articoliacquistati`
--
ALTER TABLE `articoliacquistati`
  ADD CONSTRAINT `articoliacquistati_ibfk_1` FOREIGN KEY (`acquisto`) REFERENCES `acquisto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `articoliacquistati_ibfk_2` FOREIGN KEY (`CodProdotto`) REFERENCES `articoli` (`CodProdotto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `preferiti`
--
ALTER TABLE `preferiti`
  ADD CONSTRAINT `preferiti_ibfk_1` FOREIGN KEY (`RefCodProdotto`) REFERENCES `articoli` (`CodProdotto`),
  ADD CONSTRAINT `preferiti_ibfk_2` FOREIGN KEY (`RefCodUtente`) REFERENCES `utenti` (`CodUtente`);

--
-- Limiti per la tabella `recensioni`
--
ALTER TABLE `recensioni`
  ADD CONSTRAINT `recensioni_ibfk_1` FOREIGN KEY (`CodProdotto`) REFERENCES `articoli` (`CodProdotto`),
  ADD CONSTRAINT `recensioni_ibfk_2` FOREIGN KEY (`CodUtente`) REFERENCES `utenti` (`CodUtente`);

--
-- Limiti per la tabella `spedizioneavvenuta`
--
ALTER TABLE `spedizioneavvenuta`
  ADD CONSTRAINT `spedizioneavvenuta_ibfk_1` FOREIGN KEY (`acquisto`) REFERENCES `acquisto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `spedizioneavvenuta_ibfk_2` FOREIGN KEY (`utente`) REFERENCES `utenti` (`CodUtente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limiti per la tabella `spedizioniincorso`
--
ALTER TABLE `spedizioniincorso`
  ADD CONSTRAINT `spedizioniincorso_ibfk_1` FOREIGN KEY (`utente`) REFERENCES `utenti` (`CodUtente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `spedizioniincorso_ibfk_2` FOREIGN KEY (`acquisto`) REFERENCES `acquisto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
