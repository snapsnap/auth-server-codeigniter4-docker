USE [Auth]
GO

/****** Object:  Table [dbo].[partner_credential]    Script Date: 10/3/2023 11:10:26 AM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[partner_credential](
	[credId] [numeric](18, 0) IDENTITY(1,1) NOT NULL,
	[appId] [varchar](50) NULL,
	[clientId] [varchar](100) NULL,
	[token] [text] NULL,
	[lastRequest] [datetime] NULL,
	[tokenCreateAt] [datetime] NULL,
	[expiredToken] [datetime] NULL,
 CONSTRAINT [PK_partner_credential] PRIMARY KEY CLUSTERED 
(
	[credId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO

