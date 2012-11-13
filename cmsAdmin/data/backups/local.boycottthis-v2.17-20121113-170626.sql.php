-- <?php die('This is not a program file.'); exit; ?>


--
-- Table structure for table `#TABLE_PREFIX#__accesslist`
--

DROP TABLE IF EXISTS `#TABLE_PREFIX#__accesslist`;

CREATE TABLE `#TABLE_PREFIX#__accesslist` (
  `num` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userNum` int(10) unsigned NOT NULL,
  `tableName` varchar(255) NOT NULL,
  `accessLevel` tinyint(3) unsigned NOT NULL,
  `maxRecords` int(10) unsigned DEFAULT NULL,
  `randomSaveId` varchar(255) NOT NULL,
  PRIMARY KEY (`num`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


--
-- Dumping data for table `#TABLE_PREFIX#__accesslist`
--

INSERT INTO `#TABLE_PREFIX#__accesslist` VALUES("1","1","all","9",NULL,"1234567890");

--
-- Table structure for table `#TABLE_PREFIX#__outgoing_mail`
--

DROP TABLE IF EXISTS `#TABLE_PREFIX#__outgoing_mail`;

CREATE TABLE `#TABLE_PREFIX#__outgoing_mail` (
  `num` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `createdDate` datetime NOT NULL,
  `from` mediumtext,
  `to` mediumtext,
  `subject` mediumtext,
  `text` mediumtext,
  `html` mediumtext,
  `sent` datetime NOT NULL,
  `headers` mediumtext,
  `customData` mediumtext,
  `backgroundSend` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`num`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Dumping data for table `#TABLE_PREFIX#__outgoing_mail`
--


--
-- Table structure for table `#TABLE_PREFIX#_about_us`
--

DROP TABLE IF EXISTS `#TABLE_PREFIX#_about_us`;

CREATE TABLE `#TABLE_PREFIX#_about_us` (
  `num` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `createdDate` datetime NOT NULL,
  `createdByUserNum` int(10) unsigned NOT NULL,
  `updatedDate` datetime NOT NULL,
  `updatedByUserNum` int(10) unsigned NOT NULL,
  `content` mediumtext,
  PRIMARY KEY (`num`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


--
-- Dumping data for table `#TABLE_PREFIX#_about_us`
--

INSERT INTO `#TABLE_PREFIX#_about_us` VALUES("1","2012-11-13 17:04:35","1","2012-11-13 17:04:35","1","<p>Who we are, what we\'re doing and why.</p>");

--
-- Table structure for table `#TABLE_PREFIX#_accounts`
--

DROP TABLE IF EXISTS `#TABLE_PREFIX#_accounts`;

CREATE TABLE `#TABLE_PREFIX#_accounts` (
  `num` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `createdDate` datetime NOT NULL,
  `createdByUserNum` int(10) unsigned NOT NULL,
  `updatedDate` datetime NOT NULL,
  `updatedByUserNum` int(10) unsigned NOT NULL,
  `fullname` mediumtext,
  `email` mediumtext,
  `username` mediumtext,
  `password` mediumtext,
  `lastLoginDate` datetime NOT NULL,
  `expiresDate` datetime NOT NULL,
  `neverExpires` tinyint(1) unsigned NOT NULL,
  `isAdmin` tinyint(1) unsigned NOT NULL,
  `disabled` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`num`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


--
-- Dumping data for table `#TABLE_PREFIX#_accounts`
--

INSERT INTO `#TABLE_PREFIX#_accounts` VALUES("1","2012-11-12 02:59:23","0","2012-11-12 02:59:23","0","admin","admin@example.com","admin","$sha1$5dd61c5c506acf34962ea9c342e755f93f5d3b96","2012-11-13 17:06:26","0000-00-00 00:00:00","1","1","0");

--
-- Table structure for table `#TABLE_PREFIX#_announcements`
--

DROP TABLE IF EXISTS `#TABLE_PREFIX#_announcements`;

CREATE TABLE `#TABLE_PREFIX#_announcements` (
  `num` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `createdDate` datetime NOT NULL,
  `createdByUserNum` int(10) unsigned NOT NULL,
  `updatedDate` datetime NOT NULL,
  `updatedByUserNum` int(10) unsigned NOT NULL,
  `date` datetime NOT NULL,
  `title` mediumtext,
  `content` mediumtext,
  `hidden` tinyint(1) unsigned NOT NULL,
  `summary` mediumtext,
  PRIMARY KEY (`num`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


--
-- Dumping data for table `#TABLE_PREFIX#_announcements`
--

INSERT INTO `#TABLE_PREFIX#_announcements` VALUES("1","2012-11-13 02:55:31","1","2012-11-13 02:55:31","1","2012-11-13 02:54:00","Unpublished sample news item","<p>This is the main body text of an unpublished news items.  It is not to be viewed by the public. </p>","0","We don\'t want to see this in listings - it\'s unpublished.");
INSERT INTO `#TABLE_PREFIX#_announcements` VALUES("2","2012-11-13 02:56:14","1","2012-11-13 02:56:57","1","2012-11-10 02:55:00","Older test announcement","<p>This is the main body text of an announcement about how we\'re now able to post annoucements.  Feel the freedom of communication!</p>","0","We\'re now able to post announcements.  This is the summary/preview text of one.");
INSERT INTO `#TABLE_PREFIX#_announcements` VALUES("3","2012-11-13 02:57:50","1","2012-11-13 02:57:50","1","2012-11-13 02:57:00","Quite recent test annoucement","<p>No complaints from me.  Things are fitting together very nicely indeed.</p>","0","This is just to annouce that early development is going swimmingly.");

--
-- Table structure for table `#TABLE_PREFIX#_categories`
--

DROP TABLE IF EXISTS `#TABLE_PREFIX#_categories`;

CREATE TABLE `#TABLE_PREFIX#_categories` (
  `num` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `createdDate` datetime NOT NULL,
  `createdByUserNum` int(10) unsigned NOT NULL,
  `updatedDate` datetime NOT NULL,
  `updatedByUserNum` int(10) unsigned NOT NULL,
  `dragSortOrder` int(10) unsigned NOT NULL,
  `title` mediumtext,
  PRIMARY KEY (`num`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;


--
-- Dumping data for table `#TABLE_PREFIX#_categories`
--

INSERT INTO `#TABLE_PREFIX#_categories` VALUES("1","2012-11-12 03:32:20","1","2012-11-13 03:25:19","1","1352691140","Extrajudicial crime");
INSERT INTO `#TABLE_PREFIX#_categories` VALUES("2","2012-11-13 02:58:12","1","2012-11-13 02:58:12","1","1352775492","Food and beverages");
INSERT INTO `#TABLE_PREFIX#_categories` VALUES("3","2012-11-13 02:58:19","1","2012-11-13 02:58:19","1","1352775499","Groceries");
INSERT INTO `#TABLE_PREFIX#_categories` VALUES("4","2012-11-13 02:58:33","1","2012-11-13 16:49:55","1","1352775513","Copyright");
INSERT INTO `#TABLE_PREFIX#_categories` VALUES("5","2012-11-13 02:58:49","1","2012-11-13 02:58:49","1","1352775529","Workers\' rights");
INSERT INTO `#TABLE_PREFIX#_categories` VALUES("7","2012-11-13 03:24:08","1","2012-11-13 03:24:08","1","1352777048","Environment");
INSERT INTO `#TABLE_PREFIX#_categories` VALUES("8","2012-11-13 16:50:36","1","2012-11-13 16:50:36","1","1352825436","Music");

--
-- Table structure for table `#TABLE_PREFIX#_concerns`
--

DROP TABLE IF EXISTS `#TABLE_PREFIX#_concerns`;

CREATE TABLE `#TABLE_PREFIX#_concerns` (
  `num` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `createdDate` datetime NOT NULL,
  `createdByUserNum` int(10) unsigned NOT NULL,
  `updatedDate` datetime NOT NULL,
  `updatedByUserNum` int(10) unsigned NOT NULL,
  `title` mediumtext,
  `content` mediumtext,
  `pledge_count` mediumtext,
  `organization` mediumtext,
  PRIMARY KEY (`num`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Dumping data for table `#TABLE_PREFIX#_concerns`
--


--
-- Table structure for table `#TABLE_PREFIX#_contact_us`
--

DROP TABLE IF EXISTS `#TABLE_PREFIX#_contact_us`;

CREATE TABLE `#TABLE_PREFIX#_contact_us` (
  `num` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `createdDate` datetime NOT NULL,
  `createdByUserNum` int(10) unsigned NOT NULL,
  `updatedDate` datetime NOT NULL,
  `updatedByUserNum` int(10) unsigned NOT NULL,
  `content` mediumtext,
  PRIMARY KEY (`num`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


--
-- Dumping data for table `#TABLE_PREFIX#_contact_us`
--

INSERT INTO `#TABLE_PREFIX#_contact_us` VALUES("1","2012-11-13 17:05:21","1","2012-11-13 17:05:21","1","<p>Contact us to suggest an issue, set up an interview, or ask any questions. </p>");

--
-- Table structure for table `#TABLE_PREFIX#_issues`
--

DROP TABLE IF EXISTS `#TABLE_PREFIX#_issues`;

CREATE TABLE `#TABLE_PREFIX#_issues` (
  `num` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `createdDate` datetime NOT NULL,
  `createdByUserNum` int(10) unsigned NOT NULL,
  `updatedDate` datetime NOT NULL,
  `updatedByUserNum` int(10) unsigned NOT NULL,
  `hidden` tinyint(1) unsigned NOT NULL,
  `resolved` tinyint(1) unsigned NOT NULL,
  `organization` mediumtext,
  `title` mediumtext,
  `categories` mediumtext,
  `summary` mediumtext,
  `content` mediumtext,
  `links` mediumtext,
  `pledge_count` mediumtext,
  `date_resolved` datetime NOT NULL,
  `date_posted` datetime NOT NULL,
  `keywords` mediumtext,
  PRIMARY KEY (`num`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;


--
-- Dumping data for table `#TABLE_PREFIX#_issues`
--

INSERT INTO `#TABLE_PREFIX#_issues` VALUES("1","2012-11-12 03:27:55","1","2012-11-13 03:53:46","1","0","0","2","Dole wants corporations to get away with murder. Literally.","	2	1	6	","Dole signed an amicus brief supporting the oil company Shell, which was prosecuted for human rights abuses.","<p>Today, one of the <strong>most significant U.S. Supreme Court cases</strong> of our time begins. At stake is whether or not corporations can literally get <strong>away with murder.</strong></p>\n<p>When the Ogoni people of Nigeria began to nonviolently protest Shell’s oil development, Shell colluded with the Nigerian military regime to violently suppress opposition through extrajudicial killing, torture, and crimes against humanity. More than <strong>60 villages were raided, over 800 people were killed, and 30,000 more were displaced from their homes.</strong></p>\n<p>This precedent-setting Supreme Court case could finally bring justice for the Ogoni people of Nigeria, but <strong>corporations like Dole Foods filed briefs to the Supreme Court in support of Shell</strong> to protect their own interests and make sure they can’t be held accountable for human rights abuses abroad either.</p>\n<p><strong>Will you join us in telling Dole Foods to immediately pull its name from its amicus brief in the Kiobel v Shell case before the court makes its ruling?</strong></p>\n<p>Dole is one of the largest public-facing food corporations in the world, and it cares about its public image – and that’s why everyone needs to know what it is up to. It knows that the stakes are high in the <em>Kiobel</em> case: It has previously been accused of hiring a paramilitary organization that was designated a terrorist organization by the U.S. government in 2001 to provide ‘violent services’ for Dole’s banana operations in Colombia. These services include <strong>murdering trade union leaders and intimidating Dole’s banana workers</strong> so that they would not dare to join unions or demand collective negotiations. The paramilitaries allegedly also murdered small farmers so that they would flee their land and permit Dole to plant bananas, and keep profits high.</p>\n<p>This case comes down to the fact that<strong> corporations want to be people — but not when it comes to being prosecuted for rape, murder and torture.</strong> If Shell wins Kiobel then it will mean that corporations can no longer be prosecuted in the US for crimes against humanity committed overseas — overturning decades of established case law that have protected human rights activists all over the world.</p>\n<p>The landmark <em>Kiobel</em> case could finally hold <strong>Shell Oil accountable for sanctioning extrajudicial killing, torture, crimes against humanity</strong>, and the murder of Ken Saro-Wiwa. If Shell wins, it will overturn a 200-year-old law used to compensate Holocaust survivors when corporations profited from slavery and forced labor during World War II. Corporations could no longer be prosecuted in the US for crimes against humanity committed overseas, and it would overturn decades of established case law that have protected human rights activists all over the world.</p>\n<p>The landmark Kiobel case could finally hold <strong>Shell Oil accountable for sanctioning extrajudicial killing, torture, crimes against humanity</strong>, and the murder of the late Dr. Barinem Kiobel – an outspoken Ogoni leader – and eleven other Nigerians from the Ogoni area of the Niger Delta. But if Dole gets its way next week, <strong>a vital check on corporate power will be lost</strong>.</p>\n<p>Dole has worked hard to build a benign public image — a remarkable feat considering the atrocities the corporation has committed in poor countries. If the SumOfUs.org community can expose Dole for what it really is, then we can begin to hold Dole and other companies accountable for the actions they take in their relentless pursuit of profit.</p>\n<p>Corporations should not be able to get in the way of justice, and Dole should know that when it attempts to <strong>protect corporate interests and power at the expense of human rights</strong>, that we’ll come together and hold it accountable.</p>\n<p><strong>Sign our petition to Dole Foods now demanding that it immediately pull its name from its amicus brief in the Kiobel v Shell case.</strong></p>","[{\"url\":\"http:\\/\\/sumofus.org\\/campaigns\\/shell-kiobel\\/\",\"title\":\"SumOfUs letter-writing campaign\"}]","94","0000-00-00 00:00:00","2012-11-12 15:06:00","");
INSERT INTO `#TABLE_PREFIX#_issues` VALUES("4","2012-11-13 03:08:18","1","2012-11-13 17:01:31","1","0","0","4","Mars conducts cruel and unnecessary animal testing of its candy","	2	","Got a sweet tooth? Think twice before picking up a Mars candy bar! You should know that candymaker Mars, Inc.—creator of M&M\'s, Snickers, Twix, Dove, Three Musketeers, Starburst, Skittles, and other candies—funds deadly animal tests, even though there are more reliable human studies and not one of the tests is required by law.","<p>Got a sweet tooth? Think twice before picking up a Mars candy bar! You should know that candymaker Mars, Inc.—creator of M&amp;M\'s, Snickers, Twix, Dove, Three Musketeers, Starburst, Skittles, and <a href=\"http://marscandykills.com/pdf/MARSposter300.pdf\" target=\"_blank\">other candies</a>—funds deadly animal tests, even though there are more reliable human studies and not one of the tests is required by law.</p>\n<p>Mars recently funded a deadly experiment on rats to determine the effects of chocolate ingredients on their blood vessels. Experimenters force-fed the rats by shoving plastic tubes down their throats and then cut open the rats\' legs to expose an artery, which was clamped shut to block blood flow. After the experiment, the animals were killed. Mars has also funded cruel experiments in which mice were fed a candy ingredient and forced to swim in a pool of a water mixed with white paint. The mice had to find a hidden platform to avoid drowning, only to be killed and dissected later on. In yet another experiment supported by Mars, rats were fed cocoa and anesthetized with carbon dioxide so that their blood could be collected by injecting a needle directly into their hearts, which can lead to internal bleeding and other deadly complications.</p>\n<p><a href=\"http://marscandykills.com/experiments.asp\">Click here</a> to find out more about Mars\' cruel experiments.</p>\n<p>Mars\' top competitor, Hershey\'s, has pledged not to fund or conduct experiments on animals. Other major food corporations—including Coca-Cola, PepsiCo, Ocean Spray, Welch\'s, and POM Wonderful—have also publicly ended animal tests after hearing from PETA.</p>\n<p><a href=\"http://marscandykills.com/pdf/Mars_Paul_Michaels_letter.pdf\" target=\"_blank\">Click here</a> to read PETA\'s letters to Mars CEO Paul Michaels.</p>","[{\"url\":\"http:\\/\\/marscandykills.com\\/\",\"title\":\"MarsCandyKills website\"}]","2","0000-00-00 00:00:00","2012-11-13 03:07:00","");
INSERT INTO `#TABLE_PREFIX#_issues` VALUES("2","2012-11-12 05:54:58","1","2012-11-13 03:58:51","1","0","0","1","63 percent of warehouse workers have been hurt on the job","	5	3	6	","Every year, Walmart ships hundreds of millions of tons of goods from Asia, through warehouses in the southern California desert and outside Chicago, and then on to local stores. And conditions in those warehouses are scandalous. UCLA’s Labor Occupational Safety and Health Program has found that 63 percent of workers have been hurt on the job.","<p>For fifty years Walmart has been fighting a war against workers, driving down wages and crushing attempts to organize around the world. But just this week, an incredible new chapter opened in the fight against Walmart\'s race-to-the-bottom economics -- <strong>workers in Walmart’s California and Chicago warehouses have gone on strike.</strong></p>\n<p>Workers are sick of working in 100+-degree heat without access to clean water, they’re sick of poverty wages, and most of all, they’re sick of being ignored by management. So this week, dozens of workers have walked off the job, and <strong>the ones in California are marching 50 miles to Walmart’s to downtown Los Angeles to confront some of Walmart top executives</strong>. By the time they arrive, we want the workers (and the bosses) to know that thousands of people around the world are standing with them.</p>\n<p><strong>Support the warehouse workers courageous stand: Tell Walmart to come to the table and improve working conditions.</strong></p>\n<p>Every year, <strong>Walmart ships hundreds of millions of tons of goods</strong> from Asia, through warehouses in the southern California desert and outside Chicago, and then on to local stores. And conditions in those warehouses are scandalous. UCLA’s Labor Occupational Safety and Health Program has found that <strong>63 percent of workers have been hurt on the job</strong>. Investigations by California state regulators have led to numerous citations and fines for breaking labor laws for the companies that manage the warehouses.<strong>As long as Walmart can escape responsibility for its suppliers’ behavior, there will be no systematic changes at the warehouses.</strong></p>\n<p>Walmart may not hire the warehouse workers directly, but it built the warehouses and hired subcontractors to manage them. And since 90% of goods moving through these warehouses are destined for Walmart, the world’s largest corporation clearly has the power to raise standards throughout the industry.</p>\n<p>Workers have tried to meet with Walmart executive before, but they\'ve been ignored. Now they\'re doing something that can\'t be ignored. They don’t have an officially recognized union, so they’re taking on a substantial risk by going out on strike. But the workers understand that by taking a stand, <strong>they’re challenging a business model that has made life worse for millions of workers around the globe</strong>.</p>\n<p>As a community, we’ve taken on Walmart before. Thousands of us shared an infographic about Walmart’s devastating impact on the global economy, and tens of thousands of us have spoken out against abusive conditions in Walmart’s Thai suppliers. Let’s let Walmart know that every time workers fight back against its destructive business model, we’ll be standing with the workers.</p>\n<p><strong>Tell Walmart: take responsibility for conditions in your warehouses and meet with the warehouse workers.</strong></p>","[{\"url\":\"http:\\/\\/www.warehouseworkersunited.org\\/video-warehouse-workers-strike\\/\",\"title\":\"\"}]","470","0000-00-00 00:00:00","2012-11-12 05:49:00","");
INSERT INTO `#TABLE_PREFIX#_issues` VALUES("3","2012-11-12 06:12:23","1","2012-11-13 03:10:17","1","0","0","3","Academic publishers exercise monopolist control over science","	4	","Academic publishers such as Elsevier charge exorbitant prices for access to the results of publicly-funded scientific research - preventing the poor from accessing the world\'s knowledge.","<p>Academics have protested against Elsevier\'s business practices for years with little effect. These are some of their objections:</p>\n<ol class=\"toplist\">\n<li>They charge exorbitantly high prices for subscriptions to individual journals.</li>\n<li>In the light of these high prices, the only realistic option for many libraries is to agree to buy very large \"bundles\", which will include many journals that those libraries do not actually want. Elsevier thus makes huge profits by exploiting the fact that some of their journals are essential.</li>\n<li>They support measures such as SOPA and PIPA that aim to restrict the free exchange of information.</li>\n</ol>\n<p>The key to all these issues is the right of authors to achieve easily-accessible distribution of their work. If you would like to declare publicly that you will not support any Elsevier journal unless they radically change how they operate, then you can do so by filling in your details on this page.</p>","[{\"url\":\"http:\\/\\/thecostofknowledge.com\\/\",\"title\":\"The Cost of Knowledge\"}]","12907","0000-00-00 00:00:00","2012-11-12 06:09:00","");
INSERT INTO `#TABLE_PREFIX#_issues` VALUES("5","2012-11-13 16:53:50","1","2012-11-13 17:05:36","1","0","0","5","The RIAA routinely exhibits contempt for the law and for basic civil liberties","	4	","Copyright reform will undoubtedly be extremely difficult to achieve due to the fact that the RIAA’s entire purpose is to lobby our government to change the law in their favor.","<p>DRM and copy-protected CDs lock up the music forever, even after the work in question returns to public domain. Such was NOT part of the copyright bargain our forefathers struck, providing specific rights to the authors for a LIMITED TIME. These copyright protections were intended for the artist and creator, not the corporations. Also, copyright was never intended to provide income for the heirs of the copyright holder in perpetuity. Today, the major labels OWN virtually all of the sound recording copyrights that should belong to their artists. In its present form, copyright law has ceased to fulfill its true purpose for being.</p>","[{\"url\":\"http:\\/\\/boycott-riaa.com\\/\",\"title\":\"\"}]","31","0000-00-00 00:00:00","2012-11-13 16:50:00","");
INSERT INTO `#TABLE_PREFIX#_issues` VALUES("6","2012-11-13 16:58:26","1","2012-11-13 17:02:32","1","0","0","6","Boycott BP stations until the spill is cleaned up!","	7	","BP brands to boycott include Castrol, Arco, Aral, am/pm, Amoco, and Wild Bean Cafe.","<p>BP didn\'t do a good enough job on the cleanup and is still trying to weasel out of paying for it.</p>","[]","416","0000-00-00 00:00:00","2012-11-02 16:54:00","");

--
-- Table structure for table `#TABLE_PREFIX#_members`
--

DROP TABLE IF EXISTS `#TABLE_PREFIX#_members`;

CREATE TABLE `#TABLE_PREFIX#_members` (
  `num` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `createdDate` datetime NOT NULL,
  `createdByUserNum` int(10) unsigned NOT NULL,
  `updatedDate` datetime NOT NULL,
  `updatedByUserNum` int(10) unsigned NOT NULL,
  `username` mediumtext,
  `content` mediumtext,
  `email` mediumtext,
  PRIMARY KEY (`num`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Dumping data for table `#TABLE_PREFIX#_members`
--


--
-- Table structure for table `#TABLE_PREFIX#_nevermail`
--

DROP TABLE IF EXISTS `#TABLE_PREFIX#_nevermail`;

CREATE TABLE `#TABLE_PREFIX#_nevermail` (
  `num` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `createdDate` datetime NOT NULL,
  `email` mediumtext,
  PRIMARY KEY (`num`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Dumping data for table `#TABLE_PREFIX#_nevermail`
--


--
-- Table structure for table `#TABLE_PREFIX#_organizations`
--

DROP TABLE IF EXISTS `#TABLE_PREFIX#_organizations`;

CREATE TABLE `#TABLE_PREFIX#_organizations` (
  `num` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `createdDate` datetime NOT NULL,
  `createdByUserNum` int(10) unsigned NOT NULL,
  `updatedDate` datetime NOT NULL,
  `updatedByUserNum` int(10) unsigned NOT NULL,
  `title` mediumtext,
  `content` mediumtext,
  `pledge_count` mediumtext,
  PRIMARY KEY (`num`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;


--
-- Dumping data for table `#TABLE_PREFIX#_organizations`
--

INSERT INTO `#TABLE_PREFIX#_organizations` VALUES("1","2012-11-13 02:37:26","1","2012-11-13 03:02:56","1","Walmart","<p>America, unfortunately, is still defined by its consumerism . . . and the average American is still defined by their lack of discernment in what they purchase.  Nothing represents the American consumer so well as the mega-corporation Wal-Mart.  It is here, then, that the average American can display their power.  If you don\'t care about <a href=\"http://www.humanevents.com/article.php?id=16577\" target=\"_blank\">slave labor</a> in far-off lands working \'till they drop so that you can buy clothes and electronics at pennies on the real value; if you don\'t care about <a href=\"http://www.npr.org/2010/12/06/131849958/high-court-to-hear-wal-mart-discrimination-case?ft=1&amp;f=1001\" target=\"_blank\">Wal-Mart\'s discrimination against women</a>; if you don\'t care about <a href=\"http://topdocumentaryfilms.com/wal-mart-the-high-cost-of-low-price/\" target=\"_blank\">slave wages</a> and <a href=\"http://theeconomiccollapseblog.com/archives/111-obamacare-waivers-and-counting-can-the-rest-of-us-get-waivers-from-having-to-comply-with-obamacare-please\" target=\"_blank\">healthcare waivers</a>, then at least care about the foundation of freedom itself.<br /><br />It is no mystery why Homeland Security has chosen Wal-Mart as its testing ground for its <a href=\"http://www.infowars.com/wal-mart-invasion-part-of-larger-dhs-takeover-of-america/\" target=\"_blank\"><i>1984</i>-style takeover of America and the Constitution</a>: they know their agenda doesn\'t have much more time left to get installed before there is open rebellion.  So, they have chosen America\'s largest employer and largest consumer goods store to set the precedent for what the average American will put up with.  </p>","");
INSERT INTO `#TABLE_PREFIX#_organizations` VALUES("2","2012-11-13 02:37:31","1","2012-11-13 03:02:10","1","Dole","<h2>Dole\'s Early Years</h2>\n<p><br />Castle and Cook to Standard Fruit to Dole, the name changes but Dole\'s dirty game stays the same because dirty is the way Dole likes to play. American naturalist Dan Koeppel relates an often forgotten story surrounding some of Dole\'s initial business dealings in the Honduras.<br /><br />In 1903 the town hall in La Ceiba burned down taking with it marriage, birth, and land records. (<a href=\"http://www.dn.se/kultur-noje/essa/det-sekellanga-kriget-om-bananerna\" target=\"_blank\">http://www.dn.se/kultur-noje/essa/det-sekellanga-kriget-om-bananerna</a>)<br /><br />The destruction of the land records left the officials with no official determination of who owned what land. Joseph Vaccato and his company Standard Fruit capitalized on this event in two ways- they no longer had to pay plot owners for using their land to grow bananas and the Dole plantations surrounding the village got most of the land in the reshuffle. Fast forward to 2011 where Dole is now the largest fresh fruit company with revenues over 7 billion dollars.<br /><br /></p>\n<h2>Dole and DBCP</h2>\n<p><br />In June 1989, Mother Jones went down to Costa Rica to get a first hand account from workers in the small villages of Rio Frio and Valle de la Estrella regarding the effect of the toxin DBCP on their lives. DBCP, formally known as dibromochloroproprane, is often referred to by its street name Nemagon.<br /><br />This nematicde was the all the rage with fruit companies because it was extremely effective at killing a microscopic worm that feeds on <a href=\"http://www.naturalnews.com/banana.html\">banana</a> plant roots without damaging the plants. The problem is that Nemagon is directly tied to sterility in men, miscarriages, stillbirths, birth defects, various types of cancer, depression, impotence and the list goes on.<br /><br />The workers interviewed explained that direct exposure resulted from workers carrying the chemical in containers on their backs as they treated the banana plants. One worker reported that the chemical felt at first hot and stingy, then quickly went to cool and icy and that \"it smelled awful and made us sleepy\". Workers also commented on the environmental effects, \" All the frogs and toads were gone in the valley after we used Nemagon... the fish would die in the rivers\". (<a href=\"http://books.google.com/books?id=NucDAAAAMBAJ&amp;pg=PA20&amp;lpg=PA20&amp;dq=dark%20history%20of%20dole%20banana&amp;source=bl&amp;ots=h9OpHIZdlP&amp;sig=h0vS5IgGNlaZ_FbOuOy3WcYWH8o&amp;hl=en&amp;ei=3W_mTfWTGpGjtgeloPHCCg&amp;sa=X&amp;oi=book_result&amp;ct=result&amp;resnum=1&amp;ved=0CBsQ6AEwADgU#v=onepage&amp;q&amp;f=false\" target=\"_blank\">http://books.google.com/books?id=NucDAAAAMBAJ&amp;pg=PA20&amp;lpg=PA20&amp;dq=dar...</a>)<br /><br />Both the manufactures of Nemagon and the U.S. government have been aware of its sterility and carcinogenic effects since the 1950\'s. Yet it wasn\'t until 1977 when workers at an Occidental plant noticed that they all were sterile, that the true effects of Nemagon became painfully clear. In the midst of the news storm that followed the discovery- Dow and Shell closed down production of DBCP.<br /><br />Also, the EPA began the process of deregulating the toxin: making it illegal to use within the United States. Main competitors Chiquita and Del Monte stopped using the chemical in 1977, but not <a href=\"http://www.naturalnews.com/Dole.html\">Dole</a>. Instead Dole took the position of favoring its profits over the health and well being of its workers. When Dow tried to back out of shipping Dole DBCP, Dole threatened to sue them for breach of contract. (<a href=\"http://books.google.com/books?id=DY4r1GsMq1wC&amp;pg=PT192&amp;dq=dole%20banana&amp;hl=en&amp;ei=WHPmTbvAJM-UtwfX9vXuCg&amp;sa=X&amp;oi=book_result&amp;ct=result&amp;resnum=1&amp;sqi=2&amp;ved=0CC4Q6AEwAA#v=onepage&amp;q=dole%20banana&amp;f=false\" target=\"_blank\">http://books.google.com/books?id=DY4r1GsMq1wC&amp;pg=PT192&amp;dq=dole%20bana...</a>)<br /><br />Mother Jones reporters Constance Mattheissen and David Weir spoke with Jack Dement, who was at the time of the Nemagon scandal, in charge of overseeing what chemicals were to be used on Dole plantations abroad. In an internal memo, dated August 6, 1977, Dement made the decision to continue to use DBCP until it was banned in the company\'s areas of operation. EPA findings be damned - Dole publicly used the chemical in various countries around the world until mid- 1978. However, Mother Jones reported that another internal report existed that contained evidence that Dole was using Nemagon in its foreign banana plantations as late as November 1980.<br /><br />Again, in 1988 45 Dole workers were poisoned by a DBCP substitute named Temik a highly toxic known disrupter of the human immune system. In 2002 the Human Rights watch uncovered abuses by Dole in Ecuador, a country that supplies Dole with one-third of all of their <a href=\"http://www.naturalnews.com/bananas.html\">bananas</a>. The report contained evidence that children as young as 8 were working 12-hour days, using sharp and dangerous equipment, frequently exposed to pesticides, and subject to sexual harassment. Granted similar abuses existed on other fruit company plantations, however seventy percent of the children interviewed worked on a plantation that supplied Dole. (<a href=\"http://www.globalexchange.org/campaigns/bananas/hrw042502.html\" target=\"_blank\">http://www.globalexchange.org/campaigns/bananas/hrw042502.html</a>)<br /><br />In 2010 Dole was included on the scrooge list of the International Labor Rights Forum as a company notorious for suppressing workers right to organize. The company has been accused of making payment to a paramilitary group in Columbia known as the AUC. Statements given by AUC commanders attest to receiving this payment from Dole and other multi-national corporations. A lawsuit (that was subsequently dismissed) alleged that Dole was the mastermind being the murder of 51 <a href=\"http://www.naturalnews.com/men.html\">men</a>, in a bid to takeover their land. (<a href=\"http://www.laborrights.org/sites/default/files/publications-and-resources/WorkingForScrooge2010.pdf\" target=\"_blank\">http://www.laborrights.org/sites/default/files/publications-and-resou...</a>)<br /><br /></p>\n<h2>Dole and DBCP Litigation</h2>\n<p><br />Early litigation cases against Dole were relatively successful- in 1992 over 1000 Costa Rican workers won a damage awared of of 20 million. In 1993 another class action suit against Dole, Chiquita, Del Monte, Dow, Shell and Occidental resulted in a 41.5 settlement for workers from Costa Rica, Ecuador, El Salvador, Guatemala, Honduras, Nicaragua, and the Philippines. Yet other cases have fallen short, Dole alleges fraud, and somehow verdicts are lessened and judgments are overturned.<br /><br />In a now infamous case, Attorney Juan Dominguez filed suit in 2008 against both Dow and Dole. In a classic Dole move the company sought to have the case dismissed on the grounds of fraud by Juan Dominguez and some of the Plaintiffs. In 2009, relying on testimony from John Doe witnesses (Dole successfully won a motion for them to remain anonymous due to concern for their safety), Judge Victoria Cheney dismissed the case.<br /><br />Another victory for Dole, but maybe just temporarily, in January 2010, the plaintiff\'s new attorney made a motion challenging the dismissal. It seems as if some of those \"Jane Doe\" witnesses have come forward and publicly stated that their testimony was procured by Dole via bribery. <a href=\"http://www.elnuevodiario.com.ni/nacionales/74115\" target=\"_blank\">http://www.elnuevodiario.com.ni/nacionales/74115</a> Earlier this year the Tellez case received another small victory - Juan Dominguez, the attorney whom Dole accused of participating in the fraud, was cleared of any wrongdoing by the State Bar of California<br /><br /></p>\n<h2>Dole Goes BANANAS!</h2>\n<p><br /><br />Swedish filmmaker Frederick Gertten filmed a documentary about the Tellez case and when a trailer was shown after the film\'s entry into the 2009 LA Film Festival competition, Dole flipped their wig. Dole didn\'t even wait until it\'d seen the movie to begin its campaign of harassment. Dole deployed their hired gun- law firm Gibson, Dunn, &amp; Crutcher - and sent numerous cease and desist letters to individuals involved directly and indirectly with the film. Dole even went as far as to petition Judge Cheney and the Swedish Embassy to step in and block the film. (<a href=\"http://www.bananasthemovie.com/wp-content/uploads/resources/letter_from_dole_may8_09.pdf\" target=\"_blank\">http://www.bananasthemovie.com/wp-content/uploads/resources/letter_fr...</a> ) (<a href=\"http://www.bananasthemovie.com/wp-content/uploads/resources/letter_dole-hafstrom_june5_09.pdf\" target=\"_blank\">http://www.bananasthemovie.com/wp-content/uploads/resources/letter_do...</a>)<br /><br />Soon after the films limited release Dole filed a SLAPP suit, then they got slapped.... in the face with a furious backlash from the Swedish community that climbed as high as the Swedish Parliament. Soon a bi-partisan petition was circulating in Parliament that called upon Dole to dismiss its suit, which it eventually did. The anti-SLAPP filed by Gertten and his crew was granted and Dole was ordered to pay their attorneys fees.<br /><br />Dole\'s reaction to the film was telling, this is a company that doesn\'t want their machinations revealed to the American public. Those bananas on your kitchen counter come at a pretty steep price - the destruction of workers lives and their environment- the only question left to be answered is whether or not this system is one in which you want to be an active participant.<br /><br />Learn more: <a href=\"http://www.naturalnews.com/032794_Dole_bananas.html#ixzz2C4HO2NvJ\">http://www.naturalnews.com/032794_Dole_bananas.html#ixzz2C4HO2NvJ</a></p>","");
INSERT INTO `#TABLE_PREFIX#_organizations` VALUES("3","2012-11-13 02:37:36","1","2012-11-13 03:04:03","1","Elsevier","<p>Academics have protested against Elsevier\'s business practices for years with little effect. These are some of their objections:</p>\n<ol class=\"toplist\">\n<li>They charge exorbitantly high prices for subscriptions to individual journals.</li>\n<li>In the light of these high prices, the only realistic option for many libraries is to agree to buy very large \"bundles\", which will include many journals that those libraries do not actually want. Elsevier thus makes huge profits by exploiting the fact that some of their journals are essential.</li>\n<li>They support measures such as SOPA and PIPA, that aim to restrict the free exchange of information.</li>\n</ol>\n<p>The key to all these issues is the right of authors to achieve easily-accessible distribution of their work. If you would like to declare publicly that you will not support any Elsevier journal unless they radically change how they operate, then you can do so by filling in your details on this page.</p>","");
INSERT INTO `#TABLE_PREFIX#_organizations` VALUES("4","2012-11-13 03:06:58","1","2012-11-13 03:07:17","1","Mars","<p>One of the world\'s leading food manufacturers.</p>","");
INSERT INTO `#TABLE_PREFIX#_organizations` VALUES("5","2012-11-13 16:49:37","1","2012-11-13 16:49:37","1","RIAA","<p>The <b>Recording Industry Association of America</b> (<b>RIAA</b>) is a trade organization that represents the recording industry distributors in the United States. Its members consist of record labels and distributors, which the RIAA say \"create, manufacture and/or distribute approximately 85% of all legally sold recorded music in the United States.\" RIAA has its headquarters in Washington, D.C.<sup class=\"reference\"><br /></sup></p>\n<p>The RIAA was formed in 1952 primarily to administer the RIAA equalization curve, a technical standard of frequency response applied to vinyl records during recording. The RIAA participates in the collective rights management of sound recording. The association is also responsible for certifying Gold and Platinum albums and singles in the USA.</p>\n<p>The RIAA lists its goals as:<sup class=\"reference\"><br /></sup></p>\n<ol>\n<li>to protect intellectual property rights and the First Amendment rights of artists;</li>\n<li>to perform research about the music industry;</li>\n<li>to monitor and review relevant laws, regulations and policies;</li>\n</ol>","0");
INSERT INTO `#TABLE_PREFIX#_organizations` VALUES("6","2012-11-13 16:58:16","1","2012-11-13 16:58:16","1","BP","","0");

--
-- Table structure for table `#TABLE_PREFIX#_pledges`
--

DROP TABLE IF EXISTS `#TABLE_PREFIX#_pledges`;

CREATE TABLE `#TABLE_PREFIX#_pledges` (
  `num` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `createdDate` datetime NOT NULL,
  `createdByUserNum` int(10) unsigned NOT NULL,
  `updatedDate` datetime NOT NULL,
  `updatedByUserNum` int(10) unsigned NOT NULL,
  `member` mediumtext,
  `concern` mediumtext,
  `email` mediumtext,
  `ip` mediumtext,
  `issue` mediumtext,
  `organization` mediumtext,
  `subscribed` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`num`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


--
-- Dumping data for table `#TABLE_PREFIX#_pledges`
--

INSERT INTO `#TABLE_PREFIX#_pledges` VALUES("1","2012-11-12 03:33:28","1","2012-11-12 03:33:28","1","","1","borisjones@dropsy.ooh","127.0.0.1",NULL,NULL,"0");

--
-- Table structure for table `#TABLE_PREFIX#_updates`
--

DROP TABLE IF EXISTS `#TABLE_PREFIX#_updates`;

CREATE TABLE `#TABLE_PREFIX#_updates` (
  `num` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `createdDate` datetime NOT NULL,
  `createdByUserNum` int(10) unsigned NOT NULL,
  `updatedDate` datetime NOT NULL,
  `updatedByUserNum` int(10) unsigned NOT NULL,
  `issue` mediumtext,
  `title` mediumtext,
  `content` mediumtext,
  `date` datetime NOT NULL,
  `issue_summary` mediumtext,
  `issue_resolved` tinyint(1) unsigned NOT NULL,
  `links` mediumtext,
  PRIMARY KEY (`num`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


--
-- Dumping data for table `#TABLE_PREFIX#_updates`
--

INSERT INTO `#TABLE_PREFIX#_updates` VALUES("1","2012-11-12 03:34:41","1","2012-11-12 03:34:41","1","1","Filipino Banana Workers Frustrated in Battle Over Dole’s Pesticides","<p>FOR FUCK\'S SAKE DOLE EVERYONE\'S MAD</p>","0000-00-00 00:00:00",NULL,"0",NULL);
INSERT INTO `#TABLE_PREFIX#_updates` VALUES("2","2012-11-13 17:00:51","1","2012-11-13 17:00:51","1","6","BP Disaster Survivors Removed From Federal Courtroom during Fairness Hearing","<p>Three BP oil disaster survivors and community advocates were forcibly removed from the fairness hearing on the BP class-action settlement yesterday, moments before the federal court heard objections to how that settlement would compensate people made sick by the disaster. The fairness hearing allowed U.S. District Judge Carl Barbier to hear arguments from those who negotiated the deal, as well as those with objections to the class action, before the settlement is accepted or rejected.</p>","2012-11-09 16:58:00","BP brands to boycott include Castrol, Arco, Aral, am/pm, Amoco, and Wild Bean Cafe.","0","[{\"url\":\"http:\\/\\/bridgethegulfproject.com\\/node\\/713\",\"title\":\"Bridge the Gulf\"}]");
INSERT INTO `#TABLE_PREFIX#_updates` VALUES("3","2012-11-13 17:03:52","1","2012-11-13 17:03:52","1","6","U.S. kept photos of dead whale under wraps during spill, Greenpeace says","<p>The environmental group Greenpeace is raising new questions about why it took the federal government more than two years to release information about a dead sperm whale that was discovered during the BP oil spill. The 26-foot juvenile whale, an endangered species, was discovered by a NOAA research vessel about 77 miles from the Deepwater Horizon disaster.</p>","2012-10-24 17:02:00","BP brands to boycott include Castrol, Arco, Aral, am/pm, Amoco, and Wild Bean Cafe.","0","[{\"url\":\"http:\\/\\/www.nola.com\\/news\\/gulf-oil-spill\\/index.ssf\\/2012\\/10\\/us_kept_photos_of_dead_whale_u.html\",\"title\":\"The Times-Picayune, Greater New Orleans\"}]");

--
-- Table structure for table `#TABLE_PREFIX#_uploads`
--

DROP TABLE IF EXISTS `#TABLE_PREFIX#_uploads`;

CREATE TABLE `#TABLE_PREFIX#_uploads` (
  `num` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order` int(10) unsigned NOT NULL,
  `createdTime` datetime NOT NULL,
  `tableName` varchar(255) NOT NULL,
  `fieldName` varchar(255) NOT NULL,
  `recordNum` varchar(255) NOT NULL,
  `preSaveTempId` varchar(255) NOT NULL,
  `filePath` varchar(255) NOT NULL,
  `urlPath` varchar(255) NOT NULL,
  `width` int(10) unsigned NOT NULL,
  `height` int(10) unsigned NOT NULL,
  `thumbFilePath` varchar(255) NOT NULL,
  `thumbUrlPath` varchar(255) NOT NULL,
  `thumbWidth` int(10) unsigned NOT NULL,
  `thumbHeight` int(10) unsigned NOT NULL,
  `thumbFilePath2` varchar(255) NOT NULL,
  `thumbUrlPath2` varchar(255) NOT NULL,
  `thumbWidth2` int(10) unsigned NOT NULL,
  `thumbHeight2` int(10) unsigned NOT NULL,
  `thumbFilePath3` varchar(255) NOT NULL,
  `thumbUrlPath3` varchar(255) NOT NULL,
  `thumbWidth3` int(10) unsigned NOT NULL,
  `thumbHeight3` int(10) unsigned NOT NULL,
  `thumbFilePath4` varchar(255) NOT NULL,
  `thumbUrlPath4` varchar(255) NOT NULL,
  `thumbWidth4` int(10) unsigned NOT NULL,
  `thumbHeight4` int(10) unsigned NOT NULL,
  `info1` varchar(255) NOT NULL,
  `info2` varchar(255) NOT NULL,
  `info3` varchar(255) NOT NULL,
  `info4` varchar(255) NOT NULL,
  `info5` varchar(255) NOT NULL,
  PRIMARY KEY (`num`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Dumping data for table `#TABLE_PREFIX#_uploads`
--


-- Dump completed on 2012-11-13 17:06:26 +0000

