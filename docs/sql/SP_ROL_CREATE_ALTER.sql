USE [TRZ6_CONDOMINIO]

SELECT COLUMN_NAME
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = 'TRZ6_ROL'
ORDER BY ORDINAL_POSITION
GO

select * from TRZ6_rol go
select * from TRZ6_CAT_ESTADO go
select * from TRZ6_USUARIO go

DELETE FROM TRZ6_ROL
WHERE ROL_NOM = 'Propietario_';
GO

EXEC SP_D_ROL_01 @ROL_ID = 12;
GO


PRINT 'Entró al SP con ROL_ID: ' + CAST(@ROL_ID AS NVARCHAR);

-- Verificar que el rol existe
SELECT * FROM TRZ6_ROL WHERE ROL_ID = 12;

-- Verificar que el estado "Inactivo" existe
SELECT * FROM TRZ6_CAT_ESTADO WHERE DESCRIPCION = 'Inactivo';
go

PRINT 'Iniciando SP';
PRINT 'ROL_ID: ' + CAST(@ROL_ID AS NVARCHAR);

-- Después de obtener @ID_INACTIVO
PRINT 'ID_INACTIVO: ' + CAST(@ID_INACTIVO AS NVARCHAR);
go


/* ============================================
   1. INSERTAR NUEVO ROL
   ============================================ */
CREATE OR ALTER PROCEDURE SP_I_ROL_01
    @CREADO_POR NVARCHAR(100),
    @ROL_NOM NVARCHAR(100),
    @MODIFICADO_POR NVARCHAR(100)
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @ID_ACTIVO INT;

    -- Buscar el ID del estado "Activo"
    SELECT @ID_ACTIVO = ID_ESTADO 
    FROM TRZ6_CAT_ESTADO 
    WHERE DESCRIPCION = 'Activo';

    IF @ID_ACTIVO IS NULL
    BEGIN
        RAISERROR('No existe el estado "Activo" en TRZ6_CAT_ESTADO.',16,1);
        RETURN;
    END

    -- Validar duplicados
    IF EXISTS(SELECT 1 FROM TRZ6_ROL WHERE ROL_NOM = @ROL_NOM)
    BEGIN
        RAISERROR('El nombre del rol ya existe.',16,1);
        RETURN;
    END

    -- Insertar el nuevo rol con estado Activo
    INSERT INTO TRZ6_ROL (ROL_NOM, FEC_CREA, CREADO_POR, MODIFICADO_POR, FE_MODIFICACION, ID_ESTADO)
    VALUES (@ROL_NOM, GETDATE(), @CREADO_POR, @MODIFICADO_POR, GETDATE(), @ID_ACTIVO);

    SELECT SCOPE_IDENTITY() AS ROL_ID;
END
GO


/* ============================================
   2. ACTUALIZAR ROL
   ============================================ */
CREATE OR ALTER PROCEDURE SP_U_ROL_01
    @ROL_ID INT,
    @ROL_NOM VARCHAR(50),
    @MODIFICADO_POR VARCHAR(100),
    @ID_ESTADO INT
AS
BEGIN
    SET NOCOUNT ON;

    -- Validar duplicado en otros registros
    IF EXISTS(SELECT 1 FROM TRZ6_ROL WHERE ROL_NOM = @ROL_NOM AND ROL_ID <> @ROL_ID)
    BEGIN
        RAISERROR('El nombre del rol ya existe.',16,1);
        RETURN;
    END

    UPDATE TRZ6_ROL
    SET 
        ROL_NOM = @ROL_NOM,
        MODIFICADO_POR = @MODIFICADO_POR,
        FE_MODIFICACION = GETDATE(),
        ID_ESTADO = @ID_ESTADO
    WHERE ROL_ID = @ROL_ID;

    -- Devolver registro actualizado
    SELECT 
        R.ROL_ID,
        R.ROL_NOM,
        CONVERT(VARCHAR(10), R.FEC_CREA, 105) + ' ' +
        RIGHT('0' + CONVERT(VARCHAR(2), 
            CASE WHEN DATEPART(HOUR, R.FEC_CREA) % 12 = 0 THEN 12 ELSE DATEPART(HOUR, R.FEC_CREA) % 12 END
        ), 2) + ':' +
        RIGHT('0' + CONVERT(VARCHAR(2), DATEPART(MINUTE, R.FEC_CREA)), 2) + ' ' +
        CASE WHEN DATEPART(HOUR, R.FEC_CREA) >= 12 THEN 'PM' ELSE 'AM' END AS FE_CREA,
        R.CREADO_POR,
        R.MODIFICADO_POR,
        CONVERT(VARCHAR(10), R.FE_MODIFICACION, 105) + ' ' +
        RIGHT('0' + CONVERT(VARCHAR(2),
            CASE WHEN DATEPART(HOUR, R.FE_MODIFICACION) % 12 = 0 THEN 12 ELSE DATEPART(HOUR, R.FE_MODIFICACION) % 12 END
        ), 2) + ':' +
        RIGHT('0' + CONVERT(VARCHAR(2), DATEPART(MINUTE, R.FE_MODIFICACION)), 2) + ' ' +
        CASE WHEN DATEPART(HOUR, R.FE_MODIFICACION) >= 12 THEN 'PM' ELSE 'AM' END AS FE_MODIFICACION,
        R.ID_ESTADO
    FROM TRZ6_ROL R
    WHERE R.ROL_ID = @ROL_ID;
END
GO


/* ============================================
   ELIMINAR ROL (FÍSICO)
   ============================================ */
CREATE OR ALTER PROCEDURE SP_D_ROL_01
    @ROL_ID INT
AS
BEGIN
    SET NOCOUNT ON;

    -- Verificar que el rol exista
    IF NOT EXISTS(SELECT 1 FROM TRZ6_ROL WHERE ROL_ID = @ROL_ID)
    BEGIN
        RAISERROR('No se encontró el ROL_ID especificado.',16,1);
        RETURN;
    END

    -- Eliminar el rol definitivamente
    DELETE FROM TRZ6_ROL
    WHERE ROL_ID = @ROL_ID;

    -- Confirmación
    SELECT 'Rol eliminado correctamente.' AS Mensaje;
END
GO


/* ============================================
   4. LISTAR TODOS LOS ROLES
   ============================================ */
CREATE OR ALTER PROCEDURE SP_L_ROL_TODOS
AS
BEGIN
    SET NOCOUNT ON;

    SELECT 
        R.ROL_ID,
        R.ROL_NOM,
        CONVERT(VARCHAR(10), R.FEC_CREA, 105) + ' ' +
        RIGHT('0' + CONVERT(VARCHAR(2), 
            CASE WHEN DATEPART(HOUR, R.FEC_CREA) % 12 = 0 THEN 12 ELSE DATEPART(HOUR, R.FEC_CREA) % 12 END
        ), 2) + ':' +
        RIGHT('0' + CONVERT(VARCHAR(2), DATEPART(MINUTE, R.FEC_CREA)), 2) + ' ' +
        CASE WHEN DATEPART(HOUR, R.FEC_CREA) >= 12 THEN 'PM' ELSE 'AM' END AS FE_CREA,
        R.CREADO_POR,
        R.MODIFICADO_POR,
        CONVERT(VARCHAR(10), R.FE_MODIFICACION, 105) + ' ' +
        RIGHT('0' + CONVERT(VARCHAR(2),
            CASE WHEN DATEPART(HOUR, R.FE_MODIFICACION) % 12 = 0 THEN 12 ELSE DATEPART(HOUR, R.FE_MODIFICACION) % 12 END
        ), 2) + ':' +
        RIGHT('0' + CONVERT(VARCHAR(2), DATEPART(MINUTE, R.FE_MODIFICACION)), 2) + ' ' +
        CASE WHEN DATEPART(HOUR, R.FE_MODIFICACION) >= 12 THEN 'PM' ELSE 'AM' END AS FE_MODIFICACION,
        R.ID_ESTADO
    FROM TRZ6_ROL R;
END
GO


/* ============================================
   5. OBTENER ROL POR ID
   ============================================ */
CREATE OR ALTER PROCEDURE SP_O_ROL_01
    @ROL_ID INT
AS
BEGIN
    SET NOCOUNT ON;

    SELECT 
        R.ROL_ID,
        R.ROL_NOM,
        CONVERT(VARCHAR(10), R.FEC_CREA, 105) + ' ' +
        RIGHT('0' + CONVERT(VARCHAR(2), 
            CASE WHEN DATEPART(HOUR, R.FEC_CREA) % 12 = 0 THEN 12 ELSE DATEPART(HOUR, R.FEC_CREA) % 12 END
        ), 2) + ':' +
        RIGHT('0' + CONVERT(VARCHAR(2), DATEPART(MINUTE, R.FEC_CREA)), 2) + ' ' +
        CASE WHEN DATEPART(HOUR, R.FEC_CREA) >= 12 THEN 'PM' ELSE 'AM' END AS FE_CREA,
        R.CREADO_POR,
        R.MODIFICADO_POR,
        CONVERT(VARCHAR(10), R.FE_MODIFICACION, 105) + ' ' +
        RIGHT('0' + CONVERT(VARCHAR(2),
            CASE WHEN DATEPART(HOUR, R.FE_MODIFICACION) % 12 = 0 THEN 12 ELSE DATEPART(HOUR, R.FE_MODIFICACION) % 12 END
        ), 2) + ':' +
        RIGHT('0' + CONVERT(VARCHAR(2), DATEPART(MINUTE, R.FE_MODIFICACION)), 2) + ' ' +
        CASE WHEN DATEPART(HOUR, R.FE_MODIFICACION) >= 12 THEN 'PM' ELSE 'AM' END AS FE_MODIFICACION,
        R.ID_ESTADO
    FROM TRZ6_ROL R
    WHERE R.ROL_ID = @ROL_ID;
END
GO

/* ============================================
   1. INSERTAR NUEVO ROL (DEVUELVE EL REGISTRO)
   ============================================ */
CREATE OR ALTER PROCEDURE SP_I_ROL_01
    @CREADO_POR VARCHAR(100),
    @ROL_NOM VARCHAR(50),
    @ID_ESTADO INT
AS
BEGIN
    SET NOCOUNT ON;

    -- Validar duplicado
    IF EXISTS(SELECT 1 FROM TRZ6_ROL WHERE ROL_NOM = @ROL_NOM)
    BEGIN
        RAISERROR('El nombre del rol ya existe.',16,1);
        RETURN;
    END

    DECLARE @NEW_ID INT;

    INSERT INTO TRZ6_ROL (ROL_NOM, FEC_CREA, CREADO_POR, ID_ESTADO)
    VALUES (@ROL_NOM, GETDATE(), @CREADO_POR, @ID_ESTADO);

    SET @NEW_ID = SCOPE_IDENTITY();

    -- Devolver registro recién insertado
    SELECT 
        R.ROL_ID,
        R.ROL_NOM,
        CONVERT(VARCHAR(10), R.FEC_CREA, 105) + ' ' +
        RIGHT('0' + CONVERT(VARCHAR(2), 
            CASE WHEN DATEPART(HOUR, R.FEC_CREA) % 12 = 0 THEN 12 ELSE DATEPART(HOUR, R.FEC_CREA) % 12 END
        ), 2) + ':' +
        RIGHT('0' + CONVERT(VARCHAR(2), DATEPART(MINUTE, R.FEC_CREA)), 2) + ' ' +
        CASE WHEN DATEPART(HOUR, R.FEC_CREA) >= 12 THEN 'PM' ELSE 'AM' END AS FE_CREA,
        R.CREADO_POR,
        R.MODIFICADO_POR,
        CONVERT(VARCHAR(10), R.FE_MODIFICACION, 105) + ' ' +
        RIGHT('0' + CONVERT(VARCHAR(2),
            CASE WHEN DATEPART(HOUR, R.FE_MODIFICACION) % 12 = 0 THEN 12 ELSE DATEPART(HOUR, R.FE_MODIFICACION) % 12 END
        ), 2) + ':' +
        RIGHT('0' + CONVERT(VARCHAR(2), DATEPART(MINUTE, R.FE_MODIFICACION)), 2) + ' ' +
        CASE WHEN DATEPART(HOUR, R.FE_MODIFICACION) >= 12 THEN 'PM' ELSE 'AM' END AS FE_MODIFICACION,
        R.ID_ESTADO
    FROM TRZ6_ROL R
    WHERE R.ROL_ID = @NEW_ID;
END
GO


/* ============================================
   2. ACTUALIZAR ROL (DEVUELVE EL REGISTRO)
   ============================================ */
CREATE OR ALTER PROCEDURE SP_U_ROL_01
    @ROL_ID INT,
    @ROL_NOM VARCHAR(50),
    @MODIFICADO_POR VARCHAR(100),
    @ID_ESTADO INT
AS
BEGIN
    SET NOCOUNT ON;

    -- Validar duplicado en otros registros
    IF EXISTS(SELECT 1 FROM TRZ6_ROL WHERE ROL_NOM = @ROL_NOM AND ROL_ID <> @ROL_ID)
    BEGIN
        RAISERROR('El nombre del rol ya existe.',16,1);
        RETURN;
    END

    UPDATE TRZ6_ROL
    SET 
        ROL_NOM = @ROL_NOM,
        MODIFICADO_POR = @MODIFICADO_POR,
        FE_MODIFICACION = GETDATE(),
        ID_ESTADO = @ID_ESTADO
    WHERE ROL_ID = @ROL_ID;

    -- Devolver registro actualizado
    SELECT 
        R.ROL_ID,
        R.ROL_NOM,
        CONVERT(VARCHAR(10), R.FEC_CREA, 105) + ' ' +
        RIGHT('0' + CONVERT(VARCHAR(2), 
            CASE WHEN DATEPART(HOUR, R.FEC_CREA) % 12 = 0 THEN 12 ELSE DATEPART(HOUR, R.FEC_CREA) % 12 END
        ), 2) + ':' +
        RIGHT('0' + CONVERT(VARCHAR(2), DATEPART(MINUTE, R.FEC_CREA)), 2) + ' ' +
        CASE WHEN DATEPART(HOUR, R.FEC_CREA) >= 12 THEN 'PM' ELSE 'AM' END AS FE_CREA,
        R.CREADO_POR,
        R.MODIFICADO_POR,
        CONVERT(VARCHAR(10), R.FE_MODIFICACION, 105) + ' ' +
        RIGHT('0' + CONVERT(VARCHAR(2),
            CASE WHEN DATEPART(HOUR, R.FE_MODIFICACION) % 12 = 0 THEN 12 ELSE DATEPART(HOUR, R.FE_MODIFICACION) % 12 END
        ), 2) + ':' +
        RIGHT('0' + CONVERT(VARCHAR(2), DATEPART(MINUTE, R.FE_MODIFICACION)), 2) + ' ' +
        CASE WHEN DATEPART(HOUR, R.FE_MODIFICACION) >= 12 THEN 'PM' ELSE 'AM' END AS FE_MODIFICACION,
        R.ID_ESTADO
    FROM TRZ6_ROL R
    WHERE R.ROL_ID = @ROL_ID;
END
GO

-- PRUEBAS
EXEC SP_I_ROL_01 
    @CREADO_POR = 'Jorge Luis', 
    @ROL_NOM = 'Nuevo Rol', 
    @MODIFICADO_POR = 'Jorge Luis';