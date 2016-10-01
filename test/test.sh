INI=${PRJ_ROOT}/conf/used/test_php.ini
PHPUNIT=/usr/local/php/bin/phpunit
XML=${PRJ_ROOT}/test/phpunit.xml
echo "/usr/local/php-5.6/bin/php -c $INI $PHPUNIT  --configuration $XML "
/usr/local/php-5.6/bin/php -c $INI $PHPUNIT  --configuration $XML
