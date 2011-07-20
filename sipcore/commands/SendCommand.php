<?php

class SendCommand extends CConsoleCommand {
    public function actionIndex () {
        echo "SIPonline SMS sending CronJob: ",SMS::sendCron()," sent.";
    }
}


