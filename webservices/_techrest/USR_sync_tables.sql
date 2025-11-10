/*
 Navicat Premium Data Transfer

 Source Server         : Pombaldata 192_168_1_91
 Source Server Type    : SQL Server
 Source Server Version : 15002000
 Source Host           : 192.168.1.91:1433
 Source Catalog        : Emp_SB779
 Source Schema         : dbo

 Target Server Type    : SQL Server
 Target Server Version : 15002000
 File Encoding         : 65001

 Date: 07/08/2023 16:37:11
*/


-- ----------------------------
-- Table structure for USR_sync_config
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[USR_sync_config]') AND type IN ('U'))
	DROP TABLE [dbo].[USR_sync_config]
GO

CREATE TABLE [dbo].[USR_sync_config] (
  [hash] nvarchar(max) COLLATE Latin1_General_CI_AS  NULL,
  [lastLogin] smalldatetime  NULL,
  [params] nvarchar(max) COLLATE Latin1_General_CI_AS  NULL,
  [id] int  IDENTITY(1,1) NOT NULL,
  [api_version] nvarchar(50) COLLATE Latin1_General_CI_AS  NULL
)
GO

ALTER TABLE [dbo].[USR_sync_config] SET (LOCK_ESCALATION = TABLE)
GO


-- ----------------------------
-- Records of USR_sync_config
-- ----------------------------
BEGIN TRANSACTION
GO

SET IDENTITY_INSERT [dbo].[USR_sync_config] ON
GO

INSERT INTO [dbo].[USR_sync_config] ([hash], [lastLogin], [params], [id], [api_version]) VALUES (N'yT6H0WebWTAVVlG9Ue4aMqZG1hIspp6Sq2nQHH4W', N'2023-08-07 16:29:00', N'a:7:{s:10:"nivFamilia";s:1:"1";s:11:"nivSFamilia";s:1:"2";s:15:"criaAutoFamilia";s:1:"1";s:16:"criaAutoSFamilia";s:1:"1";s:10:"codSubZona";s:2:"PT";s:11:"dataIniSync";s:10:"2023-01-01";s:4:"loja";a:3:{s:15:"00000000000L1P0";a:2:{s:4:"sync";s:1:"0";s:6:"seccao";s:3:"LJ1";}s:15:"00000000000L2P0";a:2:{s:4:"sync";s:1:"0";s:6:"seccao";s:1:"2";}s:15:"00000000000L3P0";a:2:{s:4:"sync";s:1:"0";s:6:"seccao";s:3:"LJ1";}}}', N'1', N'1.36')
GO

SET IDENTITY_INSERT [dbo].[USR_sync_config] OFF
GO

COMMIT
GO


-- ----------------------------
-- Auto increment value for USR_sync_config
-- ----------------------------
DBCC CHECKIDENT ('[dbo].[USR_sync_config]', RESEED, 1)
GO


-- ----------------------------
-- Primary Key structure for table USR_sync_config
-- ----------------------------
ALTER TABLE [dbo].[USR_sync_config] ADD CONSTRAINT [PK__USR_sync__3213E83F9D6BD32B] PRIMARY KEY CLUSTERED ([id])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY]
GO





-- ----------------------------
-- Table structure for USR_sync_log
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[USR_sync_log]') AND type IN ('U'))
	DROP TABLE [dbo].[USR_sync_log]
GO

CREATE TABLE [dbo].[USR_sync_log] (
  [tipo] nvarchar(128) COLLATE Latin1_General_CI_AS  NULL,
  [entity] nvarchar(64) COLLATE Latin1_General_CI_AS  NULL,
  [msg] nvarchar(max) COLLATE Latin1_General_CI_AS  NULL,
  [dta] smalldatetime  NULL,
  [recnum] nvarchar(32) COLLATE Latin1_General_CI_AS  NULL,
  [act] nvarchar(64) COLLATE Latin1_General_CI_AS  NULL,
  [id] int  IDENTITY(1,1) NOT NULL
)
GO

ALTER TABLE [dbo].[USR_sync_log] SET (LOCK_ESCALATION = TABLE)
GO


-- ----------------------------
-- Auto increment value for USR_sync_log
-- ----------------------------
DBCC CHECKIDENT ('[dbo].[USR_sync_log]', RESEED, 32924)
GO


-- ----------------------------
-- Primary Key structure for table USR_sync_log
-- ----------------------------
ALTER TABLE [dbo].[USR_sync_log] ADD CONSTRAINT [PK__USR_sync__3213E83F06D2E5FF] PRIMARY KEY CLUSTERED ([id])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY]
GO



-- ----------------------------
-- Table structure for USR_sync_taxonomy
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[USR_sync_taxonomy]') AND type IN ('U'))
	DROP TABLE [dbo].[USR_sync_taxonomy]
GO

CREATE TABLE [dbo].[USR_sync_taxonomy] (
  [tipo] nvarchar(128) COLLATE Latin1_General_CI_AS  NULL,
  [techval] nvarchar(max) COLLATE Latin1_General_CI_AS  NULL,
  [erpval] nvarchar(max) COLLATE Latin1_General_CI_AS  NULL,
  [dtasync] smalldatetime  NULL,
  [sync] bit DEFAULT 0 NOT NULL,
  [id] int  IDENTITY(1,1) NOT NULL
)
GO

ALTER TABLE [dbo].[USR_sync_taxonomy] SET (LOCK_ESCALATION = TABLE)
GO


-- ----------------------------
-- Auto increment value for USR_sync_taxonomy
-- ----------------------------
DBCC CHECKIDENT ('[dbo].[USR_sync_taxonomy]', RESEED, 11526)
GO


-- ----------------------------
-- Primary Key structure for table USR_sync_taxonomy
-- ----------------------------
ALTER TABLE [dbo].[USR_sync_taxonomy] ADD CONSTRAINT [PK__USR_sync__3213E83F1988D183] PRIMARY KEY CLUSTERED ([id])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY]
GO

