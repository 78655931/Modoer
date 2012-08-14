index\.php

(item|coupon|article|fenlei|party|tuan|ask)\.php\?act=detail&amp;id=([0-9]+)   $1/$2
(item|coupon|article|fenlei|party|tuan|ask)\/([0-9]+)    $1.php?act=detail&id=$2

item\.php\?act=list&amp;catid=([0-9]+)   item/list-$1
item\/list\-([0-9]+)    item.php?act=list&catid=$1

exchange\.php\?act=gift&amp;id=([0-9]+)   exchange/gift/$1
exchange\/gift\/([0-9]+)    exchange.php?act=gift&id=$1

space\.php\?act=index&amp;uid=([0-9]+)   space/$1
space\/([0-9]+)    space.php?act=index&uid=$1

(member|item|card|coupon|article|link|fenlei|party|tuan|ask)\.php\?act=index  $1
(member|item|card|coupon|article|link|fenlei|party|tuan|ask)(\/|)    $1.php?act=index