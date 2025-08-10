USE [TRZ6_CONDOMINIO]

SELECT COLUMN_NAME
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = 'TRZ6_MENU'
ORDER BY ORDINAL_POSITION
GO

SELECT COLUMN_NAME, DATA_TYPE 
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = 'TRZ6_MENU';

EXEC sp_help 'TRZ6_MENU';

select * from TRZ6_MENU go
select * from TRZ6_MENU_DETALLE go

CREATE OR ALTER PROCEDURE SP_L_MENU_01
	@ROL_ID INT
AS
BEGIN
	SELECT        
		TRZ6_MENU_DETALLE.ID_DETMENU, 
		TRZ6_MENU_DETALLE.MEN_ID, 
		TRZ6_MENU_DETALLE.ROL_ID, 
		TRZ6_MENU_DETALLE.ID_ESTADO, 
		TRZ6_MENU_DETALLE.PERMISO_DETMENU, 
		TRZ6_MENU_DETALLE.FEC_CREACION,
		TRZ6_MENU_DETALLE.FEC_ACTUALIZACION,

		TRZ6_ROL.ROL_NOM,

		TRZ6_MENU.MEN_NOM,
		TRZ6_MENU.MEN_RUTA,
		TRZ6_MENU.MEN_IDENTI,

		TRZ6_MENU.MEN_GRUPO 
	FROM
		TRZ6_MENU_DETALLE
		INNER JOIN
		TRZ6_ROL ON TRZ6_MENU_DETALLE.ROL_ID = TRZ6_ROL.ROL_ID
		INNER JOIN
		TRZ6_MENU ON TRZ6_MENU_DETALLE.MEN_ID = TRZ6_MENU.MEN_ID
	WHERE
		TRZ6_MENU_DETALLE.ROL_ID = @ROL_ID
	END
GO

SELECT COLUMN_NAME, DATA_TYPE 
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = 'trz6_menu_detalle';


INSERT INTO TRZ6_MENU (MEN_NOM, MEN_RUTA, MEN_IDENTI, FEC_CREACION, ID_ESTADO)
VALUES
('Dashboard', '../home/', 'dashboard', GETDATE(), 1),
('Estados', '../MntEstados/', 'estados', GETDATE(), 1),
('Servicios', '../MntServicios/', 'servicios', GETDATE(), 1),
('Tipo Pago', '../MntTipoPago/', 'tipoPago', GETDATE(), 1),
('Apartamentos', '../MntApartamentos/', 'apartamentos', GETDATE(), 1),
('Propietarios', '../MntPropietarios/', 'propietarios', GETDATE(), 1),
('Proveedores', '../MntProveedores/', 'proveedores', GETDATE(), 1),
('Roles', '../MntRoles/', 'roles', GETDATE(), 1),
('Pago Mantenimiento', '../MntPagoMantto/', 'pagoMantenimiento', GETDATE(), 1),
('Autorizaci�n Ingreso', '../MntAutoIngreso/', 'autorizacionIngreso', GETDATE(), 1),
('Alquiler Apartamentos', '../MntAlquilerApto/', 'alquilerApartamentos', GETDATE(), 1),
('Lectura Principal', '../MntLecturaPrincipal/', 'lecturaPrincipal', GETDATE(), 1),
('Lectura Propietario', '../MntLecturaPropietario/', 'lecturaPropietario', GETDATE(), 1),
('Incidencias', '../MntIncidencias/', 'incidencias', GETDATE(), 1);

/******************* MENU DETALLE ********************/
-- Ingresos
INSERT INTO TRZ6_MENU_DETALLE (MEN_ID, ROL_ID, ID_ESTADO, PERMISO_DETMENU, FEC_CREACION, FEC_ACTUALIZACION)
VALUES
(1, 1, 1, 'NO', GETDATE(), GETDATE()),
(2, 1, 1, 'NO', GETDATE(), GETDATE()),
(3, 2, 1, 'NO', GETDATE(), GETDATE()); 

INSERT INTO TRZ6_MENU_DETALLE (MEN_ID, ROL_ID, ID_ESTADO, PERMISO_DETMENU, FEC_CREACION, FEC_ACTUALIZACION)
VALUES
(4, 1, 1, 'NO', GETDATE(), GETDATE()),
(5, 1, 1, 'NO', GETDATE(), GETDATE()),
(6, 1, 1, 'NO', GETDATE(), GETDATE()),
(7, 1, 1, 'NO', GETDATE(), GETDATE()),
(8, 1, 1, 'NO', GETDATE(), GETDATE()),
(9, 1, 1, 'NO', GETDATE(), GETDATE()),
(10, 1, 1, 'SI', GETDATE(), GETDATE()),
(11, 1, 1, 'SI', GETDATE(), GETDATE()),
(12, 1, 1, 'SI', GETDATE(), GETDATE()),
(13, 1, 1, 'SI', GETDATE(), GETDATE()),
(14, 1, 1, 'SI', GETDATE(), GETDATE());