([a-z]+)\.php\?act=index  $1.html
([a-z]+)\.html    $1.php?act=index

(item|coupon|article)\.php\?act=detail&amp;id=([0-9]+)   $1-$2.html
(item|coupon|article)\-([0-9]+)\.html    $1.php?act=detail&id=$2

item\.php\?act=list&amp;catid=([0-9]+)   item-list-$1.html
item\-list\-([0-9]+)\.html    item.php?act=list&catid=$1

exchange\.php\?act=gift&amp;id=([0-9]+)   exchange-gift-$1.html
exchange\-gift\-([0-9]+)\.html    exchange.php?act=gift&id=$1

pace\.php\?act=index&amp;uid=([0-9]+)   space-$1.html
^space\-([0-9]+)\.html    space.php?act=index&uid=$1