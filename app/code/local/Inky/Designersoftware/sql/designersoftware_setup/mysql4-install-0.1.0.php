<?php

$installer = $this;

$installer->startSetup();

$installer->run("

--
-- Creating Structure for table `inky_angles`
--

-- DROP TABLE IF EXISTS {$this->getTable('inky_angles')};
CREATE TABLE {$this->getTable('inky_angles')} (
  `angles_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `filename` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '0',
  `created_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`angles_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `inky_angles`
--

INSERT INTO {$this->getTable('inky_angles')}  (`title`, `filename`, `content`, `status`, `created_time`, `update_time`) VALUES
('000', '', '', 1, now(), now()),
('045', '', '', 1, now(), now()),
('090', '', '', 1, now(), now()),
('135', '', '', 1, now(), now()),
('180', '', '', 1, now(), now()),
('225', '', '', 1, now(), now()),
('270', '', '', 1, now(), now()),
('315', '', '', 1, now(), now());


--
-- Creating Structure for table `inky_clipart`
--

-- DROP TABLE IF EXISTS {$this->getTable('inky_clipart')};
CREATE TABLE {$this->getTable('inky_clipart')} (
  `clipart_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `clipart_category_id` int(11),
  `color_ids` text NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `filename` varchar(255) NOT NULL DEFAULT '',
  `colorable` smallint(6) NOT NULL,
  `price` decimal(11,4) NOT NULL, 	
  `content` text NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '0',
  `created_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`clipart_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


--
-- Creating Structure for table `inky_clipart_category`
--


-- DROP TABLE IF EXISTS {$this->getTable('inky_clipart_category')};
CREATE TABLE {$this->getTable('inky_clipart_category')} (
  `clipart_category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `filename` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `sort_order` int(11), 
  `status` smallint(6) NOT NULL DEFAULT '0',
  `created_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`clipart_category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


--
-- Creating Structure for table `inky_clipart_upload`
--


-- DROP TABLE IF EXISTS {$this->getTable('inky_clipart_upload')};
CREATE TABLE {$this->getTable('inky_clipart_upload')} (
  `clipart_upload_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `filename` varchar(255) NOT NULL DEFAULT '',
  `session_id` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '0',
  `created_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`clipart_upload_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


--
-- Creating Structure for table `inky_color`
--

-- DROP TABLE IF EXISTS {$this->getTable('inky_color')};
CREATE TABLE {$this->getTable('inky_color')} (
  `color_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `texture_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `filename` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `colorcode` varchar(10) NOT NULL,
  `clip_price` decimal(11,4),
  `text_price` decimal(11,4),
  `clip_status` smallint(6) NOT NULL DEFAULT '0',
  `text_status` smallint(6) NOT NULL DEFAULT '0',
  `status` smallint(6) NOT NULL DEFAULT '0',
  `created_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`color_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Dumping data for table `inky_color`
--

INSERT INTO {$this->getTable('inky_color')} (`color_id`, `title`, `filename`, `content`, `colorcode`, `clip_price`, `text_price`, `clip_status`, `text_status`, `status`, `created_time`, `update_time`) VALUES
(1, 'Red', '', '', 'B00000', 1, 0.0000, 0.0000, 1, 1, 1, now(), now()),
(2, 'Pink', '', '', 'C4518B', 1, 0.0000, 0.0000, 1, 1, 1, now(), now()),
(3, 'Purple', '', '', '551A8B', 1, 0.0000, 0.0000, 1, 1, 1, now(), now()),
(4, 'Blue', '', '', '68AAE3', 1, 0.0000, 0.0000, 1, 1, 1, now(), now()),
(5, 'Green', '', '', '029E07', 1, 0.0000, 0.0000, 1, 1, 1, now(), now()),
(6, 'Yellow', '', '', 'E6E600', 1, 0.0000, 0.0000, 1, 1, 1, now(), now()),
(7, 'Grey', '', '', 'C77422', 1, 0.0000, 0.0000, 1, 1, 1, now(), now()),
(8, 'Black', '', '', '000000', 1, 0.0000, 0.0000, 1, 1, 1, now(), now()),
(9, 'White', '', '', 'FFFFFF', 1, 0.0000, 0.0000, 1, 1, 1, now(), now());


--
-- Creating Structure for table `inky_designersoftware`
--


-- DROP TABLE IF EXISTS {$this->getTable('inky_designersoftware')};
CREATE TABLE {$this->getTable('inky_designersoftware')} (
  `designersoftware_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `style_design_code` varchar(20) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `sizes_info` varchar(255) NOT NULL,
  `total_price` decimal(11,4) NOT NULL,
  `fashion_lining` decimal(11,4) NOT NULL,
  `shoe_path` varchar(255) NOT NULL,
  `price_info_arr` text NOT NULL,
  `side_data_array` text NOT NULL,
  `design_data_array` text NOT NULL,
  `part_dropdown_arr` text NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `filename` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '0',
  `created_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`designersoftware_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Creating Structure for table `inky_font`
--

-- DROP TABLE IF EXISTS {$this->getTable('inky_font')};
CREATE TABLE {$this->getTable('inky_font')} (
  `font_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `font_ai` varchar(255) NOT NULL,
  `filename_boldttf` varchar(255) NOT NULL DEFAULT '',
  `filename_ttf` varchar(255) NOT NULL,
  `filename_italicttf` varchar(255) NOT NULL,
  `filename_bolditalicttf` varchar(255) NOT NULL,
  `ttf_image_name` varchar(255) NOT NULL,
  `boldttf_image_name` varchar(255) NOT NULL,
  `italicttf_image_name` varchar(255) NOT NULL,
  `bolditalicttf_image_name` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '0',
  `created_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`font_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


--
-- Creating Structure for table `inky_parts`
--


-- DROP TABLE IF EXISTS {$this->getTable('inky_parts')};
CREATE TABLE {$this->getTable('inky_parts')} (
  `parts_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `code` varchar(20) NOT NULL,
  `filename` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `sort_order` int(6) NOT NULL,  
  `parts_category` smallint(6) NOT NULL,
  `extras_category` int(11) NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '0',  
  `created_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`parts_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


--
-- Creating Structure for table `inky_parts_layers`
--


-- DROP TABLE IF EXISTS {$this->getTable('inky_parts_layers')};
CREATE TABLE {$this->getTable('inky_parts_layers')} (
  `parts_layers_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `texture_ids` text NOT NULL,
  `parts_id` int(11) NOT NULL,
  `parts_style_id` int(11) NOT NULL,
  `color_ids` text NOT NULL,
  `color_price` text NOT NULL,
  `default_color_id` int(11) NOT NULL,
  `default_color_price` decimal(11,4) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `layer_code` varchar(10) NOT NULL,
  `filename` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `sort_order` int(6) NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '0',
  `created_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`parts_layers_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


--
-- Creating Structure for table `inky_parts_layers_texture`
--

-- DROP TABLE IF EXISTS {$this->getTable('inky_parts_layers_texture')};
CREATE TABLE {$this->getTable('inky_parts_layers_texture')} (
  `parts_layers_texture_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parts_id` int(11) NOT NULL,
  `parts_layers_id` int(11) NOT NULL,
  `texture_id` text NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `filename` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `sort_order` int(6) NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '0',
  `created_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`parts_layers_texture_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


--
-- Creating Structure for table `inky_parts_style`
--


-- DROP TABLE IF EXISTS {$this->getTable('inky_parts_style')};
CREATE TABLE {$this->getTable('inky_parts_style')} (
  `parts_style_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parts_id` int(11) unsigned NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `code` varchar(20) NOT NULL,
  `price` decimal(11,4) NOT NULL,
  `filename` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `sort_order` int(6) NOT NULL,
  `parts_category` smallint(6) NOT NULL,
  `extras_category` int(11) NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '0',
  `created_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`parts_style_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;


--
-- Creating Structure for table `inky_sizes`
--

-- DROP TABLE IF EXISTS {$this->getTable('inky_sizes')};
CREATE TABLE {$this->getTable('inky_sizes')} (
  `sizes_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `color_ids` text NOT NULL,
  `color_price` text NOT NULL,
  `default_color_id` int(11) NOT NULL,
  `default_color_price` decimal(11,4) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `filename` varchar(255) NOT NULL DEFAULT '',
  `price` decimal(11,4) NOT NULL,
  `content` text NOT NULL,
  `colorable` smallint(6) NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '0',
  `created_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`sizes_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;



--
-- Creating Structure for table `inky_style`
--

-- DROP TABLE IF EXISTS {$this->getTable('inky_style')};
CREATE TABLE {$this->getTable('inky_style')} (
  `style_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `filename` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '0',
  `created_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`style_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



--
-- Creating Structure for table `inky_text`
--

-- DROP TABLE IF EXISTS {$this->getTable('inky_text')};
CREATE TABLE {$this->getTable('inky_text')} (
  `text_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `filename` varchar(255) NOT NULL DEFAULT '',
  `cost` decimal(11,2) NOT NULL,
  `min_price` double(11,2) NOT NULL,
  `max_char` int(11) NOT NULL,
  `content` text NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '0',
  `created_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`text_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Creating Structure for table `inky_texture`
--

-- DROP TABLE IF EXISTS {$this->getTable('inky_texture')};
CREATE TABLE {$this->getTable('inky_texture')} (
  `texture_id` int(11) unsigned NOT NULL AUTO_INCREMENT, 
  `title` varchar(255) NOT NULL DEFAULT '',  
  `filename` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT '0',
  `created_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`texture_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 
