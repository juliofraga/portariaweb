CREATE TABLE `portaria_ligacao_portaria` (
  `id` int(11) NOT NULL,
  `portaria_id_1` int(11) NOT NULL,
  `portaria_id_2` int(11) NOT NULL,
  `tipo` int(11) NOT NULL COMMENT '0 - Portaria 1 sai em portaria 2\r\n1 - Portaria 2 sai em portaria 1\r\n2 - Ambas saem em ambas'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `portaria_ligacao_portaria`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `portaria_ligacao_portaria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;