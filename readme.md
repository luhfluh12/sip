Short description
=================
My project for contests in 2011. A manager for high-school academic records (marks and absences) with some graphs as statistics and SMSes to parents when necessary.

Project at [Infoeducatie in 2011](http://infoeducatie.ro/2011/rezultate.php?page=web).

See some [screenshots on imgur](http://imgur.com/a/nOgIc).

Requirements
============
- mysql database
- apache2
- php
- curl
- clickatell account
- YiiFramework 1.1

Setup
=====

You must have a folder ``config`` in ``sipcore/`` (``sipcore/config``) in which you will have a file ``main.php`` and one ``console.php``. There should be a databse with one of the schemas provided in this repositories (if one doesn't work, try another one).

For what should be there you can default configuration files from YiiFramework. It need a cache component, a db component and some configuration for yii-mail and ClickatellSms but it can work without e-mails and SMSes.
