CREATE TABLE `basket` (
  `ID` int(11) NOT NULL auto_increment,
  `CUSTOMERID` int(11) NOT NULL default '0',
  `PRODUCTID` varchar(60) NOT NULL default '0',
  `STATUS` varchar(15) NOT NULL default '',
  `ORDERID` int(11) NOT NULL default '0',
  `LINEADDDATE` varchar(20) NOT NULL default '',
  `QTY` int(11) NOT NULL default '1',
  `DESCRIPTION` varchar(255) NOT NULL default '',
  `PRICE` double NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ;

CREATE TABLE `category` (
  `ID` int(11) NOT NULL auto_increment,
  `DESC` varchar(40) NOT NULL default '',
  `GROUPID` varchar(11) NOT NULL default '',
  PRIMARY KEY  (`ID`)
) ;

CREATE TABLE `customer` (
  `ID` int(11) NOT NULL auto_increment,
  `LOGINNAME` varchar(20) NOT NULL default '',
  `PASSWORD` varchar(15) NOT NULL default '',
  `LASTNAME` varchar(50) NOT NULL default '',
  `MIDDLENAME` varchar(10) NOT NULL default '',
  `INITIALS` varchar(10) NOT NULL default '',
  `IP` varchar(20) NOT NULL default '',
  `ADDRESS` varchar(100) NOT NULL default '',
  `ZIP` varchar(20) NOT NULL default '',
  `CITY` varchar(75) NOT NULL default '',
  `PHONE` varchar(30) NOT NULL default '',
  `EMAIL` varchar(75) NOT NULL default '',
  `GROUP` varchar(15) NOT NULL default 'CUSTOMER',
  `COUNTRY` varchar(75) NOT NULL default '',
  `COMPANY` varchar(75) NOT NULL default '',
  `JOINDATE` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`ID`)
) ;

CREATE TABLE `group` (
  `ID` int(11) NOT NULL auto_increment,
  `NAME` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`ID`)
) ;

CREATE TABLE `order` (
  `ID` int(11) NOT NULL auto_increment,
  `DATE` varchar(20) NOT NULL default '',
  `STATUS` tinyint(1) NOT NULL default '0',
  `SHIPPING` tinyint(1) NOT NULL default '0',
  `PAYMENT` tinyint(1) NOT NULL default '0',
  `CUSTOMERID` int(11) NOT NULL default '0',
  `TOPAY` double NOT NULL default '0',
  `WEBID` varchar(25) default NULL,
  `NOTES` longtext,
  PRIMARY KEY  (`ID`)
) ;

CREATE TABLE `product` (
  `ID` int(11) NOT NULL auto_increment,
  `PRODUCTID` varchar(60) NOT NULL default '0',
  `CATID` int(11) NOT NULL default '0',
  `DESCRIPTION` longtext NOT NULL,
  `PRICE` double NOT NULL default '0',
  `STOCK` int(1) default NULL,
  `FRONTPAGE` tinyint(1) NOT NULL default '0',
  `NEW` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) ;

CREATE TABLE `settings` (
  `theme` varchar(50) default NULL,
  `send_default_country` varchar(50) default NULL,
  `sendcosts_default_country` double default NULL,
  `sendcosts_other_country` double default NULL,
  `rembours_costs` double default NULL,
  `currency` varchar(10) default NULL,
  `currency_symbol` varchar(10) default NULL,
  `paymentdays` tinyint(4) default NULL,
  `vat` double default NULL,
  `show_vat` varchar(10) default NULL,
  `db_prices_including_vat` tinyint(1) default NULL,
  `sales_mail` varchar(50) default NULL,
  `shopname` varchar(100) default NULL,
  `shopurl` varchar(100) default NULL,
  `default_lang` char(2) default NULL,
  `order_prefix` varchar(10) default NULL,
  `order_suffix` varchar(10) default NULL,
  `stock_enabled` tinyint(1) default NULL,
  `ordering_enabled` tinyint(1) default NULL,
  `shop_disabled` tinyint(1) default NULL,
  `shop_disabled_title` varchar(50) default NULL,
  `shop_disabled_reason` varchar(100) default NULL,
  `webmaster_mail` varchar(50) default NULL,
  `shoptel` varchar(50) default NULL,
  `shopfax` varchar(50) default NULL,
  `bankaccount` varchar(50) default NULL,
  `bankaccountowner` varchar(50) default NULL,
  `bankcity` varchar(50) default NULL,
  `bankcountry` varchar(50) default NULL,
  `bankname` varchar(50) default NULL,
  `bankiban` varchar(50) default NULL,
  `bankbic` varchar(50) default NULL,
  `start_year` int(4) default NULL,
  `shop_logo` varchar(50) default NULL,
  `background` varchar(50) default NULL,
  `slogan` varchar(200) default NULL,
  `page_title` varchar(200) default NULL,
  `page_footer` varchar(100) default NULL,
  `shipping_postal` tinyint(1) default NULL,
  `shipping_atstore` tinyint(1) default NULL,
  `shipping_unused` tinyint(1) default NULL,
  `number_format` varchar(8) default NULL,
  `max_description` tinyint(2) default NULL,
  `no_vat` tinyint(1) default NULL,
  `pricelist_format` tinyint(1) default NULL,
  `date_format` varchar(15) default NULL,
  `search_prodgfx` tinyint(1) default NULL,
  `use_prodgfx` tinyint(1) default NULL,
  `pay_bank` tinyint(1) default NULL,
  `pay_atstore` tinyint(1) default NULL,
  `pay_paypal` tinyint(1) default NULL,
  `pay_onreceive` tinyint(1) default NULL,
  `pay_unused` tinyint(1) default NULL,
  `paypal_email` varchar(100) default NULL,
  `paypal_currency` char(3) default NULL,
  `thumbs_in_pricelist` tinyint(1) default NULL,
  `keywords` varchar(200) default NULL,
  `charset` varchar(50) default NULL,
  `conditions_page` tinyint(1) default NULL,
  `guarantee_page` tinyint(1) default NULL,
  `shipping_page` tinyint(1) default NULL,
  `pictureid` tinyint(1) default NULL,
  `aboutus_page` tinyint(1) default NULL,
  `live_news` tinyint(1) default NULL,
  `pricelist_thumb_width` tinyint(2) default NULL,
  `pricelist_thumb_height` tinyint(2) default NULL,
  `category_thumb_width` tinyint(2) default NULL,
  `category_thumb_height` tinyint(2) default NULL,
  `product_max_width` int(5) default NULL,
  `product_max_height` int(5) default NULL
) ;

CREATE TABLE `shipping` (
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(150) default NULL,
  `rate` double default NULL,
  `country` tinyint(1) default NULL,
  `system` tinyint(1) default NULL,
  PRIMARY KEY  (`id`)
) ;

CREATE TABLE `payment` (
  `id` int(11) NOT NULL auto_increment,
  `description` varchar(150) default NULL,
  `code` longtext,
  `system` tinyint(1) default NULL,
  PRIMARY KEY  (`id`)
) ;

CREATE TABLE `shipping_payment` (
  `shippingid` int(11) default NULL,
  `paymentid` int(11) default NULL
) ;

INSERT INTO `customer` VALUES (1, 'admin', 'admin_1234', 'ADMIN', '', 'A', '', 'Test address 12', '1234 TT', 'Amsterdam', '012344-323', 'webmaster@yourdomain.com', 'ADMIN', 'Netherlands', '', '');
INSERT INTO `group` VALUES (1, 'Test group');
INSERT INTO `category` VALUES (1, 'Test category', '1');
INSERT INTO `product` VALUES (1, 'TestID', 1, 'This is a test product.<br />Enjoy using <strong>FreeWebshop.org</strong>', 1234.56, 1, 0, 1);
INSERT INTO `settings` (`theme`, `send_default_country`, `sendcosts_default_country`, `sendcosts_other_country`, `rembours_costs`, `currency`, `currency_symbol`, `paymentdays`, `vat`, `show_vat`, `db_prices_including_vat`, `sales_mail`, `shopname`, `shopurl`, `default_lang`, `order_prefix`, `order_suffix`, `stock_enabled`, `ordering_enabled`, `shop_disabled`, `shop_disabled_title`, `shop_disabled_reason`, `webmaster_mail`, `shoptel`, `shopfax`, `bankaccount`, `bankaccountowner`, `bankcity`, `bankcountry`, `bankname`, `bankiban`, `bankbic`, `start_year`, `shop_logo`, `background`, `slogan`, `page_title`, `page_footer`, `shipping_postal`, `shipping_atstore`, `shipping_unused`, `number_format`, `max_description`, `no_vat`, `pricelist_format`, `date_format`, `search_prodgfx`, `use_prodgfx`, `pay_bank`, `pay_atstore`, `pay_paypal`, `pay_onreceive`, `pay_unused`, `paypal_email`, `paypal_currency`, `thumbs_in_pricelist`, `keywords`, `charset`, `conditions_page`, `guarantee_page`, `shipping_page`, `pictureid`, `aboutus_page`, `live_news`, `pricelist_thumb_width`, `pricelist_thumb_height`, `category_thumb_width`, `category_thumb_height`, `product_max_width`, `product_max_height`) VALUES ('grey_business', 'United Kingdom', 10, 25, 5, 'EUR', '€', 14, 1.19, '19%', 1, 'webmaster@yourshop.com', 'FreeWebshop.org', 'http://www.yourshop.com/shop', 'uk', 'WEB-', '-06', 0, 1, 0, 'Closed', 'Dear visitor, the demo shop is temporarely down.', 'info@yourshop.com', '012-3456789', '012-3456788', '12345678', 'YourName', 'BankCity', 'BankCountry', 'TestBank', 'BankIBAN', 'BankBIC/Swiftcode', 2006, 'logo.gif', 'bg.gif', 'Your Shop slogan', 'Your Shopname', 'Place Footer Text Here', 1, 1, 0, '1.234,56', 60, 0, 2, 'd-m-Y @ G:i', 1, 1, 1, 1, 1, 1, NULL, 'paypal@yourdomain.com', 'EUR', 1, 'these, are, keywords', 'ISO-8859-1', 1, 1, 1, 1, 1, 1, 30, 30, 50, 50, 450, 350);
INSERT INTO `payment` (`id`, `description`, `code`, `system`) VALUES (1, 'Bank', '', 1);
INSERT INTO `payment` (`id`, `description`, `code`, `system`) VALUES (2, 'Betaal bij afhalen / Pay at store', '', 1);
INSERT INTO `payment` (`id`, `description`, `code`, `system`) VALUES (3, 'PayPal', '<form action="https://www.paypal.com/cgi-bin/webscr" method="post">\r\n<input type="hidden" name="cmd" value="_xclick">\r\n<input type="hidden" name="business" value="paypal@xxxxxx.com">\r\n<input type="hidden" name="item_name" value="%webid%">\r\n<input type="hidden" name="currency_code" value="EUR">\r\n<input type="hidden" name="amount" value="%total%">\r\n<input type="hidden" name="invoice" value="%webid%">\r\n<input type="image" src="http://www.paypal.com/en_US/i/btn/x-click-but01.gif" name="submit" alt="PayPal">\r\n</form>', 0);
INSERT INTO `payment` (`id`, `description`, `code`, `system`) VALUES (4, 'iDeal', '<FORM METHOD="post" ACTION="https://idealtest.secure-ing.com/ideal/mpiPayInitIng.do" id="form1" name="form1">\r\n<INPUT type="hidden" NAME="merchantID" value="0050xxxxx">\r\n<INPUT type="hidden" NAME="subID" value="0">\r\n<INPUT type="hidden" NAME="amount" VALUE="%total%" >\r\n<INPUT type="hidden" NAME="purchaseID" VALUE="%webid%">\r\n<INPUT type="hidden" NAME="language" VALUE="nl">\r\n<INPUT type="hidden" NAME="currency" VALUE="EUR">\r\n<INPUT type="hidden" NAME="description" VALUE="iDeal Payment">\r\n<INPUT type="hidden" NAME="itemNumber1" VALUE="%webid%">\r\n<INPUT type="hidden" NAME="itemDescription1" VALUE="%webid%">\r\n<INPUT type="hidden" NAME="itemQuantity1" VALUE="1">\r\n<INPUT type="hidden" NAME="itemPrice1" VALUE="%total%">\r\n<INPUT type="hidden" NAME="paymentType" VALUE="ideal">\r\n<INPUT type="hidden" NAME="validUntil" VALUE=" 2016-01-01T12:00:00:0000Z">\r\n<INPUT type="hidden" NAME="urlCancel" VALUE="%shopurl%">\r\n<INPUT type="hidden" NAME="urlSuccess" VALUE="%shopurl%">\r\n<INPUT type="hidden" NAME="urlError" VALUE="%shopurl%">\r\n<INPUT type="submit" NAME="submit2" VALUE="Betaal nu met iDeal" id="submit2">\r\n</form>', 0);
INSERT INTO `shipping` (`id`, `description`, `rate`, `country`, `system`) VALUES (1, 'Pakketdienst / Postal service', 15, 0, 1);
INSERT INTO `shipping` (`id`, `description`, `rate`, `country`, `system`) VALUES (2, 'Zelf ophalen / Pickup at store', 0, 1, 1);
INSERT INTO `shipping_payment` (`shippingid`, `paymentid`) VALUES (1, 1);
INSERT INTO `shipping_payment` (`shippingid`, `paymentid`) VALUES (1, 3);
INSERT INTO `shipping_payment` (`shippingid`, `paymentid`) VALUES (1, 4);
INSERT INTO `shipping_payment` (`shippingid`, `paymentid`) VALUES (2, 2);