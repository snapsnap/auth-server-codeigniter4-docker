USE [Auth]
GO

/****** Object:  Table [dbo].[resource_data]    Script Date: 10/3/2023 11:11:40 AM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[resource_data](
	[resId] [numeric](18, 0) IDENTITY(1,1) NOT NULL,
	[resName] [varchar](250) NOT NULL,
	[resSecret] [varchar](20) NULL,
	[resCode] [varchar](250) NOT NULL,
	[active] [smallint] NULL,
	[createDate] [datetime] NULL,
	[createUser] [varchar](10) NULL,
	[lastUpdate] [datetime] NULL,
	[lastUser] [varchar](10) NULL,
 CONSTRAINT [PK_resource_data] PRIMARY KEY CLUSTERED 
(
	[resId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO

