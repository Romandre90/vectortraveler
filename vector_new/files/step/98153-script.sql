/****** Object:  Database [vector]    Script Date: 19.08.2013 15:30:23 ******/
CREATE DATABASE [vector]
 CONTAINMENT = NONE
 ON  PRIMARY 
( NAME = N'vector', FILENAME = N'c:\Program Files\Microsoft SQL Server\MSSQL11.SQLEXPRESS\MSSQL\DATA\vector.mdf' , SIZE = 5120KB , MAXSIZE = UNLIMITED, FILEGROWTH = 1024KB )
 LOG ON 
( NAME = N'vector_log', FILENAME = N'c:\Program Files\Microsoft SQL Server\MSSQL11.SQLEXPRESS\MSSQL\DATA\vector_log.ldf' , SIZE = 1024KB , MAXSIZE = 2048GB , FILEGROWTH = 10%)
GO
ALTER DATABASE [vector] SET COMPATIBILITY_LEVEL = 110
GO
IF (1 = FULLTEXTSERVICEPROPERTY('IsFullTextInstalled'))
begin
EXEC [vector].[dbo].[sp_fulltext_database] @action = 'enable'
end
GO
ALTER DATABASE [vector] SET ANSI_NULL_DEFAULT OFF 
GO
ALTER DATABASE [vector] SET ANSI_NULLS OFF 
GO
ALTER DATABASE [vector] SET ANSI_PADDING OFF 
GO
ALTER DATABASE [vector] SET ANSI_WARNINGS OFF 
GO
ALTER DATABASE [vector] SET ARITHABORT OFF 
GO
ALTER DATABASE [vector] SET AUTO_CLOSE OFF 
GO
ALTER DATABASE [vector] SET AUTO_CREATE_STATISTICS ON 
GO
ALTER DATABASE [vector] SET AUTO_SHRINK OFF 
GO
ALTER DATABASE [vector] SET AUTO_UPDATE_STATISTICS ON 
GO
ALTER DATABASE [vector] SET CURSOR_CLOSE_ON_COMMIT OFF 
GO
ALTER DATABASE [vector] SET CURSOR_DEFAULT  GLOBAL 
GO
ALTER DATABASE [vector] SET CONCAT_NULL_YIELDS_NULL OFF 
GO
ALTER DATABASE [vector] SET NUMERIC_ROUNDABORT OFF 
GO
ALTER DATABASE [vector] SET QUOTED_IDENTIFIER OFF 
GO
ALTER DATABASE [vector] SET RECURSIVE_TRIGGERS OFF 
GO
ALTER DATABASE [vector] SET  DISABLE_BROKER 
GO
ALTER DATABASE [vector] SET AUTO_UPDATE_STATISTICS_ASYNC OFF 
GO
ALTER DATABASE [vector] SET DATE_CORRELATION_OPTIMIZATION OFF 
GO
ALTER DATABASE [vector] SET TRUSTWORTHY OFF 
GO
ALTER DATABASE [vector] SET ALLOW_SNAPSHOT_ISOLATION OFF 
GO
ALTER DATABASE [vector] SET PARAMETERIZATION SIMPLE 
GO
ALTER DATABASE [vector] SET READ_COMMITTED_SNAPSHOT OFF 
GO
ALTER DATABASE [vector] SET HONOR_BROKER_PRIORITY OFF 
GO
ALTER DATABASE [vector] SET RECOVERY SIMPLE 
GO
ALTER DATABASE [vector] SET  MULTI_USER 
GO
ALTER DATABASE [vector] SET PAGE_VERIFY CHECKSUM  
GO
ALTER DATABASE [vector] SET DB_CHAINING OFF 
GO
ALTER DATABASE [vector] SET FILESTREAM( NON_TRANSACTED_ACCESS = OFF ) 
GO
ALTER DATABASE [vector] SET TARGET_RECOVERY_TIME = 0 SECONDS 
GO
/****** Object:  User [vector]    Script Date: 19.08.2013 15:30:23 ******/
CREATE USER [vector] FOR LOGIN [vector] WITH DEFAULT_SCHEMA=[dbo]
GO
/****** Object:  User [as]    Script Date: 19.08.2013 15:30:24 ******/
CREATE USER [as] WITHOUT LOGIN WITH DEFAULT_SCHEMA=[dbo]
GO
ALTER ROLE [db_owner] ADD MEMBER [vector]
GO
ALTER ROLE [db_datareader] ADD MEMBER [as]
GO
ALTER ROLE [db_datawriter] ADD MEMBER [as]
GO
/****** Object:  Table [dbo].[comment]    Script Date: 19.08.2013 15:30:24 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[comment](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[text] [text] NOT NULL,
	[createTime] [datetime] NULL,
	[userId] [int] NULL,
	[stepId] [int] NULL,
	[updateTime] [datetime] NULL,
	[level] [int] NULL,
	[fileId] [int] NULL,
	[issueId] [int] NULL,
 CONSTRAINT [PK_comment] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]

GO
/****** Object:  Table [dbo].[components]    Script Date: 19.08.2013 15:30:24 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[components](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[name] [nchar](50) NOT NULL,
 CONSTRAINT [PK_components] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
/****** Object:  Table [dbo].[discrepancy]    Script Date: 19.08.2013 15:30:24 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[discrepancy](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[discrepancyDescription] [text] NULL,
	[discrepancyDescriptionBy] [int] NULL,
	[discrepancyDescriptionDate] [date] NULL,
	[causeOfNonconformance] [text] NULL,
	[causeOfNonconformanceBy] [int] NULL,
	[causeOfNonconformanceDate] [date] NULL,
	[disposition] [text] NULL,
	[dispositionBy] [int] NULL,
	[dispositionDate] [date] NULL,
	[dispositionVerifyNote] [text] NULL,
	[dispositionVerifyNoteBy] [int] NULL,
	[dispositionVerifyNoteDate] [date] NULL,
	[correctiveActionToPreventRecurrence] [text] NULL,
	[correctiveActionToPreventRecurrenceBy] [int] NULL,
	[correctiveActionToPreventRecurrenceDate] [date] NULL,
	[correctiveActionVerifyNote] [text] NULL,
	[correctiveActionVerifyNoteBy] [int] NULL,
	[correctiveActionVerifyNoteDate] [date] NULL,
	[identifiedProblemArea] [tinyint] NULL,
	[closeoutNote] [text] NULL,
	[reviewedBy] [int] NULL,
	[reviewedDate] [date] NULL,
	[stepId] [int] NOT NULL,
	[issueId] [int] NULL,
 CONSTRAINT [PK_discrepancy] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]

GO
/****** Object:  Table [dbo].[element]    Script Date: 19.08.2013 15:30:24 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[element](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[typeId] [int] NULL,
	[label] [nvarchar](255) NOT NULL,
	[stepId] [int] NOT NULL,
	[help] [nvarchar](255) NULL,
 CONSTRAINT [PK_element] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
/****** Object:  Table [dbo].[file]    Script Date: 19.08.2013 15:30:24 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[file](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[discrepancyId] [int] NULL,
	[createTime] [datetime] NOT NULL,
	[userId] [int] NOT NULL,
	[fileSelected] [nvarchar](255) NOT NULL,
	[link] [nvarchar](255) NOT NULL,
	[stepId] [int] NULL,
	[image] [bit] NULL,
	[copy] [bit] NULL,
 CONSTRAINT [PK_file] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
/****** Object:  Table [dbo].[issue]    Script Date: 19.08.2013 15:30:24 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[issue](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[travelerId] [int] NULL,
	[userId] [int] NULL,
	[createTime] [datetime] NULL,
	[updateTime] [datetime] NULL,
	[serie] [nchar](255) NOT NULL,
	[status] [bit] NOT NULL,
 CONSTRAINT [PK_issue_1] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
/****** Object:  Table [dbo].[project]    Script Date: 19.08.2013 15:30:24 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[project](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[name] [nchar](50) NOT NULL,
 CONSTRAINT [PK_project] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
/****** Object:  Table [dbo].[result]    Script Date: 19.08.2013 15:30:24 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[result](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[elementId] [int] NULL,
	[value] [nvarchar](255) NULL,
	[issueId] [int] NULL,
	[fileId] [int] NULL,
	[createTime] [datetime] NULL,
	[userId] [int] NULL,
 CONSTRAINT [PK_result] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
/****** Object:  Table [dbo].[step]    Script Date: 19.08.2013 15:30:24 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[step](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[name] [text] NOT NULL,
	[travelerId] [int] NOT NULL,
	[createTime] [datetime] NULL,
	[updateTime] [datetime] NULL,
	[parentId] [int] NULL,
	[position] [int] NULL,
	[discrepancyId] [int] NULL,
 CONSTRAINT [PK_issue] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]

GO
/****** Object:  Table [dbo].[traveler]    Script Date: 19.08.2013 15:30:24 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[traveler](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[userId] [int] NULL,
	[createTime] [datetime] NULL,
	[updateTime] [datetime] NULL,
	[revision] [int] NULL,
	[status] [int] NULL,
	[parentId] [int] NULL,
	[projectId] [int] NULL,
	[componentId] [int] NULL,
	[workId] [int] NULL,
 CONSTRAINT [PK_traveler] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY],
 CONSTRAINT [UQ_traveler] UNIQUE NONCLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
/****** Object:  Table [dbo].[user]    Script Date: 19.08.2013 15:30:24 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[user](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[ccid] [int] NULL,
	[login] [nvarchar](50) NULL,
	[username] [nvarchar](50) NULL,
	[email] [nvarchar](50) NULL,
	[firstName] [nvarchar](50) NULL,
	[lastName] [nvarchar](50) NULL,
	[telephoneNumber] [nvarchar](50) NULL,
	[department] [nvarchar](50) NULL,
	[createdTime] [datetime] NULL,
	[lastLogin] [datetime] NULL,
	[role] [tinyint] NULL,
 CONSTRAINT [PK_user] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY],
 CONSTRAINT [UQ_ccid] UNIQUE NONCLUSTERED 
(
	[ccid] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
/****** Object:  Table [dbo].[value]    Script Date: 19.08.2013 15:30:24 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[value](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[value] [nchar](10) NOT NULL,
	[elementId] [int] NOT NULL,
 CONSTRAINT [PK_value] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
/****** Object:  Table [dbo].[work]    Script Date: 19.08.2013 15:30:24 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE TABLE [dbo].[work](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[name] [nchar](50) NULL,
 CONSTRAINT [PK_work] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]

GO
ALTER TABLE [dbo].[comment]  WITH CHECK ADD  CONSTRAINT [FK_comment_step] FOREIGN KEY([stepId])
REFERENCES [dbo].[step] ([id])
ON UPDATE CASCADE
ON DELETE CASCADE
GO
ALTER TABLE [dbo].[comment] CHECK CONSTRAINT [FK_comment_step]
GO
ALTER TABLE [dbo].[comment]  WITH CHECK ADD  CONSTRAINT [FK_comment_user] FOREIGN KEY([userId])
REFERENCES [dbo].[user] ([id])
ON UPDATE CASCADE
ON DELETE CASCADE
GO
ALTER TABLE [dbo].[comment] CHECK CONSTRAINT [FK_comment_user]
GO
ALTER TABLE [dbo].[discrepancy]  WITH CHECK ADD  CONSTRAINT [FK_discrepancy_step] FOREIGN KEY([stepId])
REFERENCES [dbo].[step] ([id])
ON UPDATE CASCADE
ON DELETE CASCADE
GO
ALTER TABLE [dbo].[discrepancy] CHECK CONSTRAINT [FK_discrepancy_step]
GO
ALTER TABLE [dbo].[discrepancy]  WITH CHECK ADD  CONSTRAINT [FK_discrepancy_user] FOREIGN KEY([causeOfNonconformanceBy])
REFERENCES [dbo].[user] ([id])
GO
ALTER TABLE [dbo].[discrepancy] CHECK CONSTRAINT [FK_discrepancy_user]
GO
ALTER TABLE [dbo].[discrepancy]  WITH CHECK ADD  CONSTRAINT [FK_discrepancy_user1] FOREIGN KEY([correctiveActionToPreventRecurrenceBy])
REFERENCES [dbo].[user] ([id])
GO
ALTER TABLE [dbo].[discrepancy] CHECK CONSTRAINT [FK_discrepancy_user1]
GO
ALTER TABLE [dbo].[discrepancy]  WITH CHECK ADD  CONSTRAINT [FK_discrepancy_user2] FOREIGN KEY([discrepancyDescriptionBy])
REFERENCES [dbo].[user] ([id])
GO
ALTER TABLE [dbo].[discrepancy] CHECK CONSTRAINT [FK_discrepancy_user2]
GO
ALTER TABLE [dbo].[discrepancy]  WITH CHECK ADD  CONSTRAINT [FK_discrepancy_user3] FOREIGN KEY([discrepancyDescriptionBy])
REFERENCES [dbo].[user] ([id])
GO
ALTER TABLE [dbo].[discrepancy] CHECK CONSTRAINT [FK_discrepancy_user3]
GO
ALTER TABLE [dbo].[discrepancy]  WITH CHECK ADD  CONSTRAINT [FK_discrepancy_user4] FOREIGN KEY([dispositionBy])
REFERENCES [dbo].[user] ([id])
GO
ALTER TABLE [dbo].[discrepancy] CHECK CONSTRAINT [FK_discrepancy_user4]
GO
ALTER TABLE [dbo].[discrepancy]  WITH CHECK ADD  CONSTRAINT [FK_discrepancy_user5] FOREIGN KEY([dispositionVerifyNoteBy])
REFERENCES [dbo].[user] ([id])
GO
ALTER TABLE [dbo].[discrepancy] CHECK CONSTRAINT [FK_discrepancy_user5]
GO
ALTER TABLE [dbo].[discrepancy]  WITH CHECK ADD  CONSTRAINT [FK_discrepancy_user6] FOREIGN KEY([reviewedBy])
REFERENCES [dbo].[user] ([id])
GO
ALTER TABLE [dbo].[discrepancy] CHECK CONSTRAINT [FK_discrepancy_user6]
GO
ALTER TABLE [dbo].[discrepancy]  WITH CHECK ADD  CONSTRAINT [FK_discrepancy_user7] FOREIGN KEY([correctiveActionVerifyNoteBy])
REFERENCES [dbo].[user] ([id])
GO
ALTER TABLE [dbo].[discrepancy] CHECK CONSTRAINT [FK_discrepancy_user7]
GO
ALTER TABLE [dbo].[element]  WITH CHECK ADD  CONSTRAINT [FK_element_step] FOREIGN KEY([stepId])
REFERENCES [dbo].[step] ([id])
ON UPDATE CASCADE
ON DELETE CASCADE
GO
ALTER TABLE [dbo].[element] CHECK CONSTRAINT [FK_element_step]
GO
ALTER TABLE [dbo].[file]  WITH CHECK ADD  CONSTRAINT [FK_file_discrepancy] FOREIGN KEY([discrepancyId])
REFERENCES [dbo].[discrepancy] ([id])
ON UPDATE CASCADE
ON DELETE CASCADE
GO
ALTER TABLE [dbo].[file] CHECK CONSTRAINT [FK_file_discrepancy]
GO
ALTER TABLE [dbo].[file]  WITH CHECK ADD  CONSTRAINT [FK_file_user] FOREIGN KEY([userId])
REFERENCES [dbo].[user] ([id])
GO
ALTER TABLE [dbo].[file] CHECK CONSTRAINT [FK_file_user]
GO
ALTER TABLE [dbo].[issue]  WITH CHECK ADD  CONSTRAINT [FK_issue_traveler] FOREIGN KEY([travelerId])
REFERENCES [dbo].[traveler] ([id])
GO
ALTER TABLE [dbo].[issue] CHECK CONSTRAINT [FK_issue_traveler]
GO
ALTER TABLE [dbo].[issue]  WITH CHECK ADD  CONSTRAINT [FK_issue_user] FOREIGN KEY([userId])
REFERENCES [dbo].[user] ([id])
ON UPDATE CASCADE
ON DELETE CASCADE
GO
ALTER TABLE [dbo].[issue] CHECK CONSTRAINT [FK_issue_user]
GO
ALTER TABLE [dbo].[result]  WITH CHECK ADD  CONSTRAINT [FK_result_element] FOREIGN KEY([elementId])
REFERENCES [dbo].[element] ([id])
GO
ALTER TABLE [dbo].[result] CHECK CONSTRAINT [FK_result_element]
GO
ALTER TABLE [dbo].[result]  WITH CHECK ADD  CONSTRAINT [FK_result_result] FOREIGN KEY([issueId])
REFERENCES [dbo].[issue] ([id])
ON UPDATE CASCADE
ON DELETE CASCADE
GO
ALTER TABLE [dbo].[result] CHECK CONSTRAINT [FK_result_result]
GO
ALTER TABLE [dbo].[step]  WITH CHECK ADD  CONSTRAINT [FK_step_step] FOREIGN KEY([parentId])
REFERENCES [dbo].[step] ([id])
GO
ALTER TABLE [dbo].[step] CHECK CONSTRAINT [FK_step_step]
GO
ALTER TABLE [dbo].[step]  WITH CHECK ADD  CONSTRAINT [FK_step_traveler] FOREIGN KEY([travelerId])
REFERENCES [dbo].[traveler] ([id])
GO
ALTER TABLE [dbo].[step] CHECK CONSTRAINT [FK_step_traveler]
GO
ALTER TABLE [dbo].[traveler]  WITH CHECK ADD  CONSTRAINT [FK_traveler_user] FOREIGN KEY([userId])
REFERENCES [dbo].[user] ([id])
GO
ALTER TABLE [dbo].[traveler] CHECK CONSTRAINT [FK_traveler_user]
GO
ALTER TABLE [dbo].[value]  WITH CHECK ADD  CONSTRAINT [FK_value_element] FOREIGN KEY([elementId])
REFERENCES [dbo].[element] ([id])
ON UPDATE CASCADE
ON DELETE CASCADE
GO
ALTER TABLE [dbo].[value] CHECK CONSTRAINT [FK_value_element]
GO
ALTER DATABASE [vector] SET  READ_WRITE 
GO
