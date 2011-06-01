<?php

class PopulateController extends Controller
{
    
        public function actionDrafts () {
            var_dump(Sms::model()->checkDrafts());
            Yii::app()->end();
        }
    
        public function actionTestMail () {
            $message = new YiiMailMessage;
            $message->setBody('Just testing...','text/html');
            $message->addTo($this->email);
            $message->from=Yii::app()->params['adminEmail'];
            Yii::app()->mail->send($message);
        }
        
        public function randomString($len,$type=false,$alphabet="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ") {
            $max = strlen($alphabet)-1;
            $r="";
            if ($type==='email') {
                for ($i=1;$i<=30;$i++) $r.=$alphabet[rand(0,$max)];
                $r.='@';
                for ($i=1;$i<=10;$i++) $r.=$alphabet[rand(0,$max)];
                $r.='.com';
            } elseif ($type==='name') {
                $len-=3;
                $half = ((int) $len/2);
                $r=strtoupper($alphabet[rand(0,$max)]);
                for ($i=1;$i<$half;$i++) $r.=$alphabet[rand(0,$max)];
                $r.=' ';
                for ($i=$half;$i<=$len;$i++) $r.=$alphabet[rand(0,$max)];
                $r = ucwords(strtolower($r));
            } else
                for ($i=1;$i<$len;$i++) $r.=$alphabet[rand(0,$max)];
            return $r;
        }
        
        public function randomNo($len) {
            $r=0;
            for ($i=1;$i<=$len;$i++) {
                $r = (10*$r) + mt_rand(0,9);
            }
            return $r;
        }
        
        public function actionPhones() {
            $accounts = Account::model()->findAll('phone=:p', array(':p'=>''));
            $i=0;
            foreach ($accounts as $account) {
                $account->phone = '407'.$this->randomNo(8);
                echo $account->phone." ";
                if ($account->save()) {
                    $i++;
                    echo "saved";
                } else {
                    var_dump($account->getErrors());
                }
                echo " <hr />";
            }
            echo "done; $i accounts updated";
            Yii::app()->end();
        }
        
        public function actionIndex() {
            $no_schools = 1;
            $no_classes = 20;
            $no_students = 30;
            
            $file = fopen('/var/www/sip/accounts.txt','a');
            for ($i = 1; $i<=$no_schools; $i++) {
                $school = $this->createSchool($file);
                if (!$school) die("school creation failed ($i)");
                for ($j=1;$j<=$no_classes;$j++) {
                    $class = $this->createClass($school,$file);
                    if (!$class) die("class creation failed $i, $j");
                    for ($k=1;$k<$no_students;$k++) {
                        $student=$this->createStudent($class, $school, $file);
                        if (!$student) die("student creation failed $i, $j, $k");
                    }
                }
            }
            fclose($file);
            echo "done;";
        }
        
        protected function createStudent($class, $school, $file) {
            $parent = new Parents;
            $parent -> name = $this->randomString(15,'name');
            $parent -> phone = "074".  mt_rand(1000000, 9999999);
            $parent -> related = 'tata';
            $parent -> adress = $this->randomString(23);
            if ($parent->save()) {
                $parent_account_log = $this->createAccount($parent->id, Account::TYPE_PARENT);
                if ($parent_account_log) {
                    $student = new Student;
                    $student->name = $this->randomString(18, 'name');
                    $student->parent=$parent->id;
                    $student->class=$class;
                    $student->school=$school;
                    if ($student->save()) {
                        $student_account_log = $this->createAccount($student->id, Account::TYPE_STUDENT);
                        if ($student_account_log) {
                            $log = ' - - - parinte: '.$parent->name.' - '.$parent_account_log."\n".
                                    ' - - - - student: '.$student->name.' - '.$student_account_log;
                            fwrite($file, $log."\n");
                            return $student->id;
                        } else{
                            $student->delete();
                            $parent->delete();
                            return false;
                        }
                    } else {
                        $parent->delete();
                        return false;
                    }
                } else{
                    $parent->delete();
                    return false;
                }
            }
        }

        protected function createClass($school,$file) {
            $class = new Classes;
            $log = ' - - ';
            $class->name=strtoupper($this->randomString(2,false,'ABCDG'));
            $class->school=$school;
            $class->grade=mt_rand(9,12);
            $log .= 'clasa '.$class->grade.' '.$class->name.' ;';
            $class->profile=$this->randomString(8,'name');
            echo $log;
            if ($class->save()) {
                $teacher = new Teacher;
                $teacher->class=$class->id;
                $teacher->school=$school;
                $teacher->name=$this->randomString(24,'name');
                if ($teacher->save()) {
                    $log .= " - ".$teacher->name." - ";
                    $accountLog = $this->createAccount($teacher->id, Account::TYPE_TEACHER);
                    if ($accountLog) {
                        fwrite($file, $log.$accountLog."\n");
                        return $class->id;
                    } else {
                        $teacher->delete();
                        $class->delete();
                        return false;
                    }
                } else {
                    $class->delete();
                    return false;
                }
            } else
                return false;
        }
        
        protected function createSchool($file) {
                $school = new School;
                $school->name = $this->randomString(15, false);
                $log = 'scoala - '.$school->name.' - ';
                $school->city = $this->randomString(10);

                if ($school->save()) {
                    $accountLog = $this->createAccount($school->id,Account::TYPE_SCHOOL);
                    if ($accountLog) {
                        fwrite($file, $log.$accountLog."\n");
                        return $school->id;
                    } else{
                        $school->delete();
                        return false;
                    }
                } else {
                    return false;
                }
        }
        protected function createAccount($info,$type) {
            $account = new Account;
            $account->email=$this->randomString(0, 'email');
            $password=$account->randomString(13);
            $account->password=$password;
            $account->type=$type;
            $account->info=$info;
            if ($account->save())
                return $account->email.' - '.$password;
            return false;
        }
}