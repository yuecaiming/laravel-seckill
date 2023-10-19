## 预约活动信息表
CREATE TABLE `t_reserve_info` (
                                  `id` BIGINT ( 20 ) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '预约活动id',
                                  `sku_id` BIGINT ( 20 ) UNSIGNED DEFAULT NULL COMMENT '商品编号',
                                  `reserve_start_time` datetime DEFAULT NULL COMMENT '预约开始时间',
                                  `reserve_end_time` datetime DEFAULT NULL COMMENT '预约结束时间',
                                  `seckill_start_time` datetime DEFAULT NULL COMMENT '秒杀开始时间',
                                  `seckill_end_time` datetime DEFAULT NULL COMMENT '秒杀结束时间',
                                  `creator` VARCHAR ( 255 ) DEFAULT NULL COMMENT '活动创建人',
                                  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
                                  `yn` TINYINT ( 255 ) UNSIGNED DEFAULT NULL COMMENT '是否删除',
                                  PRIMARY KEY ( `id` )
) ENGINE = INNODB DEFAULT CHARSET = utf8;

## 用户预约关系表
CREATE TABLE `t_reserve_user` (
                                  `id` BIGINT ( 20 ) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '关系id',
                                  `reserve_info_id` BIGINT ( 20 ) UNSIGNED DEFAULT NULL COMMENT '预约活动id',
                                  `sku_id` BIGINT ( 20 ) UNSIGNED DEFAULT NULL COMMENT '商品编号',
                                  `user_name` VARCHAR ( 255 ) DEFAULT NULL COMMENT '用户名称',
                                  `reserve_time` datetime DEFAULT NULL COMMENT '预约时间',
                                  `yn` TINYINT ( 255 ) UNSIGNED DEFAULT NULL COMMENT '是否删除',
                                  PRIMARY KEY ( `id` ),
                                  KEY `reserve_id_ref` ( `reserve_info_id` )
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
