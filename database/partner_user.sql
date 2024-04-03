USE [Auth]
GO

/****** Object:  Table [dbo].[partner_user]    Script Date: 10/3/2023 11:11:14 AM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[partner_user](
	[userId] [numeric](18, 0) IDENTITY(1,1) NOT NULL,
	[fullName] [varchar](100) NULL,
	[email] [varchar](100) NULL,
	[password] [varchar](250) NULL,
	[role] [smallint] NULL,
	[createDate] [datetime] NULL,
	[active] [smallint] NULL,
	[lastUpdate] [datetime] NULL,
 CONSTRAINT [PK_user] PRIMARY KEY CLUSTERED 
(
	[userId] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, OPTIMIZE_FOR_SEQUENTIAL_KEY = OFF) ON [PRIMARY]
) ON [PRIMARY]
GO

