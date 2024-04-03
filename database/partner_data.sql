USE [Auth]
GO

/****** Object:  Table [dbo].[partner_data]    Script Date: 10/3/2023 11:10:53 AM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[partner_data](
	[partnerId] [numeric](18, 0) IDENTITY(1,1) NOT NULL,
	[partnerName] [varchar](100) NULL,
	[partnerCompany] [varchar](100) NULL,
	[partnerAddress] [varchar](250) NULL,
	[partnerPhone] [varchar](20) NULL,
	[userId] [varchar](20) NULL,
	[createDate] [datetime] NULL,
	[createUser] [varchar](100) NULL,
	[lastUpdate] [datetime] NULL,
	[lastUser] [varchar](100) NULL,
 CONSTRAINT [PK_partner_data] PRIMARY KEY CLUSTERED 
(
	[partnerId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO

