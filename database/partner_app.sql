USE [Auth]
GO

/****** Object:  Table [dbo].[partner_app]    Script Date: 10/3/2023 11:06:46 AM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[partner_app](
	[appId] [numeric](18, 0) IDENTITY(1,1) NOT NULL,
	[partnerId] [varchar](50) NULL,
	[appName] [varchar](100) NULL,
	[clientId] [varchar](200) NULL,
	[clientSecret] [varchar](200) NULL,
	[clientPubKey] [varchar](100) NULL,
	[mafPrivKey] [varchar](100) NULL,
	[mafPubKey] [varchar](100) NULL,
	[appStatus] [smallint] NULL,
	[active] [smallint] NULL,
	[createDate] [datetime] NULL,
	[createUser] [varchar](50) NULL,
	[lastUpdate] [datetime] NULL,
	[lastUser] [varchar](50) NULL,
 CONSTRAINT [PK_partner_app] PRIMARY KEY CLUSTERED 
(
	[appId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'0 = pengajuan, 1 = approved' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'partner_app', @level2type=N'COLUMN',@level2name=N'appStatus'
GO

