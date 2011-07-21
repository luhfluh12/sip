<?php

class SendCommand extends CConsoleCommand {
    public function actionIndex () {
        echo '[',date('j F Y H:i:s'),'] SIP SMS sending CronJob: ',Sms::sendCron()," sent.\n";
    }
}


