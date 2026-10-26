<?php

namespace App\Constants;


abstract class SqlConstants 
{
   public const string TYPE_NUMERIC = 'int';
   public const string TYPE_BIG_NUMERIC = 'bigint';
   public const string TYPE_FLOAT_NUMBER = 'float';
   public const string TYPE_VARCHAR50 = 'varchar(50)';
   public const string TYPE_VARCHAR150 = 'varchar(150)';
   public const string TYPE_VARCHAR500 = 'varchar(500)';
   public const string TYPE_STRING = 'text';
   public const string TYPE_DATE = 'date';
   public const array NUMERIC_SQL_TYPES = [self::TYPE_NUMERIC, self::TYPE_BIG_NUMERIC, self::TYPE_FLOAT_NUMBER];
   public const array NUMERIC_PROPERTY_TYPES = ['int', '?int', 'bigint', '?bigint'];
   public const array TEXT_PROPERTY_TYPES = ['string', '?string'];
}
