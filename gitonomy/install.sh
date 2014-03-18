#!/bin/bash
/usr/local/php/bin/php app/console doctrine:schema:create
/usr/local/php/bin/php app/console doctrine:fixtures:load --append --fixtures=src/Gitonomy/Bundle/CoreBundle/DataFixtures/ORM/Load
