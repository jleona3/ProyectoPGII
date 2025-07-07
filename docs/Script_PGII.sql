-- =========================================================
--  ESQUEMA COMPLETO "TRZ6" – Administración de Condominios
--  (12 tablas + 3 catálogos)
--  Trasciende La Parroquia
--	Jorge Luis León Aceituno
--	5990-12-9172
-- =========================================================

/*------------------------------------------------------------
  A.  CATÁLOGOS BÁSICOS
------------------------------------------------------------*/
CREATE TABLE dbo.TRZ6_CAT_ESTADO (
    ID_ESTADO   INT IDENTITY PRIMARY KEY,
    CODIGO      VARCHAR(30)  NOT NULL UNIQUE,
    DESCRIPCION VARCHAR(150) NOT NULL,
	FE_CREACION     DATETIME2(3) NOT NULL DEFAULT SYSUTCDATETIME()
);

CREATE TABLE dbo.TRZ6_CAT_TIPO_PAGO (
    ID_TIPO_PAGO INT IDENTITY PRIMARY KEY,
    CODIGO       VARCHAR(30) NOT NULL UNIQUE,
    DESCRIPCION  VARCHAR(150) NOT NULL,
	FE_CREACION     DATETIME2(3) NOT NULL DEFAULT SYSUTCDATETIME()
);

CREATE TABLE dbo.TRZ6_CAT_SERVICIO (
    ID_SERVICIO  INT IDENTITY PRIMARY KEY,
    CODIGO       VARCHAR(30) NOT NULL UNIQUE,   -- AGUA, GAS, LUZ, etc.
    DESCRIPCION  VARCHAR(150) NOT NULL,
	FE_CREACION     DATETIME2(3) NOT NULL DEFAULT SYSUTCDATETIME()
);

-- DROP TABLE dbo.TRZ6_CAT_SERVICIO;

/*------------------------------------------------------------
  B.  PROVEEDORES (externos)
------------------------------------------------------------*/
CREATE TABLE dbo.TRZ6_PROVEEDOR (
    ID_PROVEEDOR    INT IDENTITY PRIMARY KEY,
    ID_SERVICIO     INT NOT NULL
        CONSTRAINT FK_PROV_SERVICIO  REFERENCES dbo.TRZ6_CAT_SERVICIO(ID_SERVICIO),
    NIT_PROVEEDOR   VARCHAR(20)  NOT NULL UNIQUE,
    RAZON_SOCIAL    VARCHAR(200) NOT NULL,
    TELEFONO        VARCHAR(50),
    CORREO          VARCHAR(150),
    NOM_CONTACTO    VARCHAR(150),
    CEL_CONTACTO    VARCHAR(50),
    CORREO_CONTACTO VARCHAR(150),
    DIRECCION       VARCHAR(300),
    UBICACION_MAPA  VARCHAR(300),            -- URL o coordenadas (opcional)
    FE_CREACION     DATETIME2(3) NOT NULL DEFAULT SYSUTCDATETIME()
);

/*------------------------------------------------------------
  C.  CONDOMINIO – Cada registro = 1 apartamento / unidad
------------------------------------------------------------*/
CREATE TABLE dbo.TRZ6_CONDOMINIO (
    ID_APTO     INT IDENTITY PRIMARY KEY,
    NUM_TORRE   INT          NOT NULL,
    NIVEL       INT          NOT NULL,
    NUM_APTO    VARCHAR(10)  NOT NULL,             -- Ej.: "A‑01"
    METROS_M2   DECIMAL(10,2) NOT NULL,
    ID_ESTADO   INT          NOT NULL
        CONSTRAINT FK_CONDO_ESTADO REFERENCES dbo.TRZ6_CAT_ESTADO(ID_ESTADO),
    FE_CREACION DATETIME2(3) NOT NULL DEFAULT SYSUTCDATETIME(),
    CONSTRAINT UQ_CONDO_TORRE_NIVEL_APTO UNIQUE (NUM_TORRE, NIVEL, NUM_APTO)
);

/*------------------------------------------------------------
  D.  USUARIOS (propietario o inquilino principal)
------------------------------------------------------------*/
CREATE TABLE dbo.TRZ6_USUARIO (
    ID_USER        INT IDENTITY PRIMARY KEY,
    ID_APTO        INT NOT NULL
        CONSTRAINT FK_USER_APTO REFERENCES dbo.TRZ6_CONDOMINIO(ID_APTO),
    EMAIL          VARCHAR(150) NOT NULL,
    NOMBRES        VARCHAR(150) NOT NULL,
    APELLIDOS      VARCHAR(150) NOT NULL,
    DPI            VARCHAR(50)  NOT NULL,
    TELEFONO       VARCHAR(50),
    PASSWORD_HASH  VARBINARY(64)  NOT NULL,
    PASSWORD_SALT  VARBINARY(128) NOT NULL,
    FOTO_PERFIL    VARBINARY(MAX),
    ID_ESTADO      INT NOT NULL
        CONSTRAINT FK_USER_ESTADO REFERENCES dbo.TRZ6_CAT_ESTADO(ID_ESTADO),
    FE_CREACION    DATETIME2(3) NOT NULL DEFAULT SYSUTCDATETIME(),
    CONSTRAINT UQ_USER_EMAIL UNIQUE (EMAIL),
    CONSTRAINT UQ_USER_DPI   UNIQUE (DPI)
);

/*------------------------------------------------------------
  E.  ALQUILER_APTO  (historial de arrendamientos)
------------------------------------------------------------*/
CREATE TABLE dbo.TRZ6_ALQUILER_APTO (
    ID_ALQUILER_APTO INT IDENTITY PRIMARY KEY,
    ID_USER          INT NOT NULL
        CONSTRAINT FK_ALQ_USER REFERENCES dbo.TRZ6_USUARIO(ID_USER),
    FE_INICIO        DATE        NOT NULL,
    FE_FINAL         DATE,
    NOM_ARRENDATARIO VARCHAR(150) NOT NULL,
    APE_ARRENDATARIO VARCHAR(150) NOT NULL,
    MONTO            DECIMAL(18,2) NOT NULL,
    DPI_ARRENDATARIO VARCHAR(50)  NOT NULL,
    FOTO_DPI         VARBINARY(MAX),
    ID_ESTADO        INT NOT NULL
        CONSTRAINT FK_ALQ_ESTADO REFERENCES dbo.TRZ6_CAT_ESTADO(ID_ESTADO),
    OBSERVACIONES    VARCHAR(300),
    FE_CREACION      DATETIME2(3) NOT NULL DEFAULT SYSUTCDATETIME()
);

CREATE INDEX IX_ALQ_USER_FE ON dbo.TRZ6_ALQUILER_APTO (ID_USER, FE_INICIO);


/*------------------------------------------------------------
  F.  AUTO_INGRESO  (visitas autorizadas)
------------------------------------------------------------*/
CREATE TABLE dbo.TRZ6_AUTO_INGRESO (
    ID_AUTO_INGRESO  INT IDENTITY PRIMARY KEY,
    ID_USER          INT NOT NULL
        CONSTRAINT FK_AUTO_USER REFERENCES dbo.TRZ6_USUARIO(ID_USER),
    NOMBRES_VISITANTE   VARCHAR(150) NOT NULL,
    APELLIDOS_VISITANTE VARCHAR(150) NOT NULL,
    DPI_VISITANTE       VARCHAR(50)  NOT NULL,
    FE_VISITA           DATETIME2(3) NOT NULL,
    TOT_ADULTOS         INT NOT NULL DEFAULT 0,
    TOT_JOVENES         INT NOT NULL DEFAULT 0,
    TOT_MENORES         INT NOT NULL DEFAULT 0,
    FOTO_DPI_PRINCIPAL  VARBINARY(MAX),
    FE_CREACION         DATETIME2(3) NOT NULL DEFAULT SYSUTCDATETIME()
);

CREATE INDEX IX_AUTO_USER_FE ON dbo.TRZ6_AUTO_INGRESO (ID_USER, FE_VISITA);


/*------------------------------------------------------------
  G.  PAGO_MANTTO  (pagos de mantenimiento)
------------------------------------------------------------*/
CREATE TABLE dbo.TRZ6_PAGO_MANTTO (
    ID_PAGO_MANTTO INT IDENTITY PRIMARY KEY,
    ID_USER        INT NOT NULL
        CONSTRAINT FK_PAGO_USER REFERENCES dbo.TRZ6_USUARIO(ID_USER),
    ID_TIPO_PAGO   INT NOT NULL
        CONSTRAINT FK_PAGO_TIPO REFERENCES dbo.TRZ6_CAT_TIPO_PAGO(ID_TIPO_PAGO),
    MONTO          DECIMAL(18,2) NOT NULL,
    FE_PAGO        DATE          NOT NULL,
    ID_ESTADO      INT NOT NULL
        CONSTRAINT FK_PAGO_ESTADO REFERENCES dbo.TRZ6_CAT_ESTADO(ID_ESTADO),
    COMPROBANTE    VARBINARY(MAX),
    FE_CREACION    DATETIME2(3)  NOT NULL DEFAULT SYSUTCDATETIME()
);

CREATE INDEX IX_PAGO_USER_FE ON dbo.TRZ6_PAGO_MANTTO (ID_USER, FE_PAGO);


/*------------------------------------------------------------
  H.  CONTADOR PRINCIPAL DE AGUA  (medidor maestro)
------------------------------------------------------------*/
CREATE TABLE dbo.TRZ6_CONT_PRIN_AGUA (
    ID_CONT_PRIN_AGUA  INT IDENTITY PRIMARY KEY,
    ID_PROVEEDOR       INT NOT NULL
        CONSTRAINT FK_CONTPRIN_PROV  REFERENCES dbo.TRZ6_PROVEEDOR(ID_PROVEEDOR),
    ID_SERVICIO        INT NOT NULL        -- redundante para consultas, siempre = AGUA
        CONSTRAINT FK_CONTPRIN_SERV  REFERENCES dbo.TRZ6_CAT_SERVICIO(ID_SERVICIO),
    FECHA_INGRESO      DATE        NOT NULL,
    NUMERO_BOLETA      VARCHAR(50) NOT NULL,
    TOTAL_M3           DECIMAL(18,2) NOT NULL,
    MONTO              DECIMAL(18,2) NOT NULL,
    COSTO_XM3          DECIMAL(18,4) NOT NULL,
    LECTURA_INICIAL    DECIMAL(18,2) NOT NULL,
    LECTURA_FINAL      DECIMAL(18,2) NOT NULL,
    MTS_CUBICOS        AS (LECTURA_FINAL - LECTURA_INICIAL) PERSISTED,
    INVENTARIO_FINAL_M3 DECIMAL(18,2),
    OBSERVACIONES      VARCHAR(300),
    FOTO_BOLETA        VARBINARY(MAX),
    FE_CREACION        DATETIME2(3) NOT NULL DEFAULT SYSUTCDATETIME(),
    CONSTRAINT UQ_CONTPRIN_PROV_BOLETA UNIQUE (ID_PROVEEDOR, NUMERO_BOLETA)
);

CREATE INDEX IX_CONTPRIN_FECHA ON dbo.TRZ6_CONT_PRIN_AGUA (FECHA_INGRESO);


/*------------------------------------------------------------
  I.  CONTADOR APARTAMENTOS (consumo por unidad)
------------------------------------------------------------*/
CREATE TABLE dbo.TRZ6_CONT_APTO_AGUA (
    ID_CONT_APTO_AGUA INT IDENTITY PRIMARY KEY,
    ID_APTO           INT NOT NULL
        CONSTRAINT FK_CONTAPTO_APTO REFERENCES dbo.TRZ6_CONDOMINIO(ID_APTO),
    ID_CONT_PRIN_AGUA INT NULL
        CONSTRAINT FK_CONTAPTO_PRIN REFERENCES dbo.TRZ6_CONT_PRIN_AGUA(ID_CONT_PRIN_AGUA),
    FE_DESDE          DATE NOT NULL,
    FE_HASTA          DATE NULL,
    RESERVA_EN_CISTERNA_M3 DECIMAL(18,2) DEFAULT 0,
    TOTAL_M3          DECIMAL(18,2) NOT NULL,
    TOTAL_A_CANCELAR  DECIMAL(18,2) NOT NULL,
    LECTURA_INICIAL   DECIMAL(18,2) NOT NULL,
    LECTURA_FINAL     DECIMAL(18,2) NOT NULL,
    DIF_LECTURA       AS (LECTURA_FINAL - LECTURA_INICIAL) PERSISTED,
    SALDO_ANTERIOR    DECIMAL(18,2) DEFAULT 0,
    OBSERVACIONES     VARCHAR(300),
    FE_CREACION       DATETIME2(3) NOT NULL DEFAULT SYSUTCDATETIME(),
    CONSTRAINT UQ_CONTAPTO_PERIODO UNIQUE (ID_APTO, FE_DESDE)
);

CREATE INDEX IX_CONTAPTO_APTO_FE ON dbo.TRZ6_CONT_APTO_AGUA (ID_APTO, FE_DESDE);


/*------------------------------------------------------------
  J.  INCIDENCIAS
------------------------------------------------------------*/
CREATE TABLE dbo.TRZ6_INCIDENCIA (
    ID_INCIDENCIA      INT IDENTITY PRIMARY KEY,
    ID_APTO            INT NOT NULL
        CONSTRAINT FK_INC_APTO REFERENCES dbo.TRZ6_CONDOMINIO(ID_APTO),
    ID_REPORTADO_POR   INT NOT NULL
        CONSTRAINT FK_INC_USER REFERENCES dbo.TRZ6_USUARIO(ID_USER),
    FE_REPORTE         DATETIME2(3) NOT NULL,
    DESCRIPCION_GENERAL VARCHAR(MAX) NOT NULL,
    FOTO_EVIDENCIA     VARBINARY(MAX),
    FE_CREACION        DATETIME2(3) NOT NULL DEFAULT SYSUTCDATETIME()
);

CREATE INDEX IX_INC_APTO_FE ON dbo.TRZ6_INCIDENCIA (ID_APTO, FE_REPORTE);


-- =========================================================
--  FIN DEL ESQUEMA – Total: 12 tablas + 3 catálogos
-- =========================================================


/*============================================================
  PROCEDIMIENTOS ALMACENADOS
============================================================*/

/*------------------------------------------------------------
  1.  Registrar Pago de Mantenimiento
------------------------------------------------------------*/
IF OBJECT_ID('dbo.sp_PagoMantto_Registrar','P') IS NOT NULL
    DROP PROCEDURE dbo.sp_PagoMantto_Registrar;
GO
CREATE PROCEDURE dbo.sp_PagoMantto_Registrar
    @IdUser       INT,
    @IdTipoPago   INT,
    @Monto        DECIMAL(18,2),
    @FechaPago    DATE,
    @Comprobante  VARBINARY(MAX) = NULL,
    @IdPagoMantto INT OUTPUT
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        BEGIN TRAN;

        -- Validaciones
        IF NOT EXISTS (SELECT 1 FROM dbo.TRZ6_USUARIO WHERE ID_USER = @IdUser)
            RAISERROR('Usuario no existe',16,1);

        IF NOT EXISTS (SELECT 1 FROM dbo.TRZ6_CAT_TIPO_PAGO WHERE ID_TIPO_PAGO = @IdTipoPago)
            RAISERROR('Tipo de pago inválido',16,1);

        INSERT dbo.TRZ6_PAGO_MANTTO
              (ID_USER, ID_TIPO_PAGO, MONTO, FE_PAGO, ID_ESTADO, COMPROBANTE)
        VALUES(@IdUser, @IdTipoPago, @Monto, @FechaPago,
               (SELECT ID_ESTADO FROM dbo.TRZ6_CAT_ESTADO WHERE CODIGO='ACTIVO'),
               @Comprobante);

        SET @IdPagoMantto = SCOPE_IDENTITY();
        COMMIT;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK;
        DECLARE @ErrMsg NVARCHAR(4000) = ERROR_MESSAGE();
        RAISERROR (@ErrMsg,16,1);
    END CATCH
END
GO

/*------------------------------------------------------------
  2.  Registrar Lectura del Contador Maestro de Agua
------------------------------------------------------------*/
IF OBJECT_ID('dbo.sp_ContPrinAgua_Registrar','P') IS NOT NULL
    DROP PROCEDURE dbo.sp_ContPrinAgua_Registrar;
GO
CREATE PROCEDURE dbo.sp_ContPrinAgua_Registrar
    @IdProveedor  INT,
    @FechaIngreso DATE,
    @NumeroBoleta VARCHAR(50),
    @LectInicial  DECIMAL(18,2),
    @LectFinal    DECIMAL(18,2),
    @Monto        DECIMAL(18,2),
    @InventarioFinal DECIMAL(18,2) = NULL,
    @Observaciones   VARCHAR(300) = NULL,
    @FotoBoleta      VARBINARY(MAX) = NULL,
    @IdContPrinAgua  INT OUTPUT
AS
BEGIN
    SET NOCOUNT ON;
    DECLARE @IdServicioAgua INT = (SELECT ID_SERVICIO FROM dbo.TRZ6_CAT_SERVICIO WHERE CODIGO='AGUA');
    BEGIN TRY
        BEGIN TRAN;

        IF NOT EXISTS (SELECT 1 FROM dbo.TRZ6_PROVEEDOR WHERE ID_PROVEEDOR=@IdProveedor AND ID_SERVICIO=@IdServicioAgua)
            RAISERROR('Proveedor no es de servicio Agua',16,1);

        DECLARE @TotalM3 DECIMAL(18,2) = @LectFinal - @LectInicial;
        DECLARE @CostoXM3 DECIMAL(18,4) = CASE WHEN @TotalM3<>0 THEN @Monto/@TotalM3 ELSE 0 END;

        INSERT dbo.TRZ6_CONT_PRIN_AGUA
              (ID_PROVEEDOR, ID_SERVICIO, FECHA_INGRESO, NUMERO_BOLETA,
               TOTAL_M3, MONTO, COSTO_XM3, LECTURA_INICIAL, LECTURA_FINAL,
               INVENTARIO_FINAL_M3, OBSERVACIONES, FOTO_BOLETA)
        VALUES (@IdProveedor, @IdServicioAgua, @FechaIngreso, @NumeroBoleta,
                @TotalM3, @Monto, @CostoXM3, @LectInicial, @LectFinal,
                @InventarioFinal, @Observaciones, @FotoBoleta);

        SET @IdContPrinAgua = SCOPE_IDENTITY();
        COMMIT;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK;
        DECLARE @ErrMsg NVARCHAR(4000) = ERROR_MESSAGE();
        RAISERROR (@ErrMsg,16,1);
    END CATCH
END
GO

/*------------------------------------------------------------
  3.  Generar Consumo por Apartamento
------------------------------------------------------------*/
IF OBJECT_ID('dbo.sp_ContAptoAgua_Generar','P') IS NOT NULL
    DROP PROCEDURE dbo.sp_ContAptoAgua_Generar;
GO
CREATE PROCEDURE dbo.sp_ContAptoAgua_Generar
    @IdApto           INT,
    @IdContPrinAgua   INT,
    @LectInicial      DECIMAL(18,2),
    @LectFinal        DECIMAL(18,2),
    @FechaDesde       DATE,
    @FechaHasta       DATE = NULL,
    @ReservaCisterna  DECIMAL(18,2) = 0,
    @SaldoAnterior    DECIMAL(18,2) = 0,
    @Observaciones    VARCHAR(300) = NULL,
    @IdDetalle        INT OUTPUT
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        BEGIN TRAN;

        DECLARE @TotalM3 DECIMAL(18,2) = @LectFinal - @LectInicial;
        DECLARE @CostoXM3 DECIMAL(18,4) = (SELECT TOP 1 COSTO_XM3 FROM dbo.TRZ6_CONT_PRIN_AGUA WHERE ID_CONT_PRIN_AGUA=@IdContPrinAgua);
        DECLARE @TotalPagar DECIMAL(18,2) = @TotalM3 * @CostoXM3;

        INSERT dbo.TRZ6_CONT_APTO_AGUA
              (ID_APTO, ID_CONT_PRIN_AGUA, FE_DESDE, FE_HASTA,
               RESERVA_EN_CISTERNA_M3, TOTAL_M3, TOTAL_A_CANCELAR,
               LECTURA_INICIAL, LECTURA_FINAL, SALDO_ANTERIOR, OBSERVACIONES)
        VALUES (@IdApto, @IdContPrinAgua, @FechaDesde, @FechaHasta,
                @ReservaCisterna, @TotalM3, @TotalPagar,
                @LectInicial, @LectFinal, @SaldoAnterior, @Observaciones);

        SET @IdDetalle = SCOPE_IDENTITY();
        COMMIT;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK;
        DECLARE @ErrMsg NVARCHAR(4000) = ERROR_MESSAGE();
        RAISERROR (@ErrMsg,16,1);
    END CATCH
END
GO

/*------------------------------------------------------------
  4.  Registrar Incidencia
------------------------------------------------------------*/
IF OBJECT_ID('dbo.sp_Incidencia_Registrar','P') IS NOT NULL
    DROP PROCEDURE dbo.sp_Incidencia_Registrar;
GO
CREATE PROCEDURE dbo.sp_Incidencia_Registrar
    @IdApto        INT,
    @IdReportadoPor INT,
    @Descripcion   VARCHAR(MAX),
    @Foto          VARBINARY(MAX) = NULL,
    @IdIncidencia  INT OUTPUT
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        INSERT dbo.TRZ6_INCIDENCIA
              (ID_APTO, ID_REPORTADO_POR, FE_REPORTE, DESCRIPCION_GENERAL,
               FOTO_EVIDENCIA)
        VALUES (@IdApto, @IdReportadoPor, SYSUTCDATETIME(), @Descripcion, @Foto);

        SET @IdIncidencia = SCOPE_IDENTITY();
    END TRY
    BEGIN CATCH
        DECLARE @ErrMsg NVARCHAR(4000) = ERROR_MESSAGE();
        RAISERROR (@ErrMsg,16,1);
    END CATCH
END
GO

/*------------------------------------------------------------
  5.  Registrar Visita (Auto Ingreso)
------------------------------------------------------------*/
IF OBJECT_ID('dbo.sp_AutoIngreso_Registrar','P') IS NOT NULL
    DROP PROCEDURE dbo.sp_AutoIngreso_Registrar;
GO
CREATE PROCEDURE dbo.sp_AutoIngreso_Registrar
    @IdUser        INT,
    @Nombres       VARCHAR(150),
    @Apellidos     VARCHAR(150),
    @Dpi           VARCHAR(50),
    @FechaVisita   DATETIME2(3),
    @Adultos       INT = 0,
    @Jovenes       INT = 0,
    @Menores       INT = 0,
    @FotoDpi       VARBINARY(MAX) = NULL,
    @IdAutoIngreso INT OUTPUT
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        BEGIN TRAN;
        INSERT dbo.TRZ6_AUTO_INGRESO
              (ID_USER, NOMBRES_VISITANTE, APELLIDOS_VISITANTE, DPI_VISITANTE,
               FE_VISITA, TOT_ADULTOS, TOT_JOVENES, TOT_MENORES, FOTO_DPI_PRINCIPAL)
        VALUES (@IdUser, @Nombres, @Apellidos, @Dpi, @FechaVisita,
                @Adultos, @Jovenes, @Menores, @FotoDpi);

        SET @IdAutoIngreso = SCOPE_IDENTITY();
        COMMIT;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK;
        DECLARE @ErrMsg NVARCHAR(4000) = ERROR_MESSAGE();
        RAISERROR (@ErrMsg,16,1);
    END CATCH
END
GO

/*------------------------------------------------------------
  6.  Abrir Arrendamiento (Alquiler)
------------------------------------------------------------*/
IF OBJECT_ID('dbo.sp_AlquilerApto_Abrir','P') IS NOT NULL
    DROP PROCEDURE dbo.sp_AlquilerApto_Abrir;
GO
CREATE PROCEDURE dbo.sp_AlquilerApto_Abrir
    @IdUser            INT,
    @NomArrendatario   VARCHAR(150),
    @ApeArrendatario   VARCHAR(150),
    @DpiArrendatario   VARCHAR(50),
    @Monto             DECIMAL(18,2),
    @FechaInicio       DATE,
    @Observaciones     VARCHAR(300) = NULL,
    @FotoDpi           VARBINARY(MAX) = NULL,
    @IdAlquiler        INT OUTPUT
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        BEGIN TRAN;

        -- Verifica que no exista alquiler activo.
        IF EXISTS (SELECT 1 FROM dbo.TRZ6_ALQUILER_APTO WHERE ID_USER=@IdUser AND FE_FINAL IS NULL)
            RAISERROR('Ya hay un arrendamiento activo para este usuario.',16,1);

        INSERT dbo.TRZ6_ALQUILER_APTO
              (ID_USER, FE_INICIO, NOM_ARRENDATARIO, APE_ARRENDATARIO,
               MONTO, DPI_ARRENDATARIO, FOTO_DPI, ID_ESTADO, OBSERVACIONES)
        VALUES (@IdUser, @FechaInicio, @NomArrendatario, @ApeArrendatario,
               @Monto, @DpiArrendatario, @FotoDpi,
               (SELECT ID_ESTADO FROM dbo.TRZ6_CAT_ESTADO WHERE CODIGO='ACTIVO'),
               @Observaciones);

        SET @IdAlquiler = SCOPE_IDENTITY();
        COMMIT;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK;
        DECLARE @ErrMsg NVARCHAR(4000) = ERROR_MESSAGE();
        RAISERROR (@ErrMsg,16,1);
    END CATCH
END
GO

/*------------------------------------------------------------
  7.  Cerrar Arrendamiento
------------------------------------------------------------*/
IF OBJECT_ID('dbo.sp_AlquilerApto_Cerrar','P') IS NOT NULL
    DROP PROCEDURE dbo.sp_AlquilerApto_Cerrar;
GO
CREATE PROCEDURE dbo.sp_AlquilerApto_Cerrar
    @IdAlquiler   INT,
    @FechaFin     DATE
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        UPDATE dbo.TRZ6_ALQUILER_APTO
        SET    FE_FINAL = @FechaFin,
               ID_ESTADO = (SELECT ID_ESTADO FROM dbo.TRZ6_CAT_ESTADO WHERE CODIGO='INACTIVO')
        WHERE  ID_ALQUILER_APTO = @IdAlquiler;

        IF @@ROWCOUNT = 0
            RAISERROR('Arrendamiento no encontrado',16,1);
    END TRY
    BEGIN CATCH
        DECLARE @ErrMsg NVARCHAR(4000) = ERROR_MESSAGE();
        RAISERROR (@ErrMsg,16,1);
    END CATCH
END
GO

-- =========================================================
--  FIN DEL ESQUEMA + SPs
-- =========================================================