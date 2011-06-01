<?php

class SendCommand extends CConsoleCommand {
    public function actionIndex ($limit=5) {
        echo "SIPonline SMS sending CronJob: ALL\nChecking drafts...";
        $drafts = Sms::model()->checkDrafts($limit);
        echo $drafts." drafts were rendered and sent. \nSending register informations...";
        $register = Sms::model()->checkToSend($limit);
        echo $register. " messages were sent.\n";
        echo "Total messages sent: ".($register+$drafts)."\n";

    }
    public function actionDrafts($limit=5) {
        echo "SIPonline SMS sending CronJob: DRAFTS\n";
        echo "Checking drafts...";
        $drafts = Sms::model()->checkDrafts($limit);
        echo $drafts." drafts were rendered and sent.\n";
    }
    public function actionRegister($limit=5) {
        echo "SIPonline SMS sending CronJob: REGISTER\n";
        $register = Sms::model()->checkToSend($limit);
        echo $register. " messages were sent.\n";
    }
}


