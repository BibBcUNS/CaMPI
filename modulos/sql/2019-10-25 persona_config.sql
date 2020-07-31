CREATE TABLE `persona_config` (
  `persona_id` int(11) NOT NULL,
  `notificacion_proximo_a_vencer` tinyint(4) NOT NULL DEFAULT '1',
  `notificacion_espera` tinyint(4) NOT NULL DEFAULT '0',
  `notificacion_prestamo` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `persona_config`
  ADD PRIMARY KEY (`persona_id`),
  ADD KEY `usuario_id` (`persona_id`);

ALTER TABLE `persona_config`
  ADD CONSTRAINT `persona_config_ibfk_1` FOREIGN KEY (`persona_id`) REFERENCES `persona` (`id`);