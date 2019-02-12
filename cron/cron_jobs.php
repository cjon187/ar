<?php
require_once('defines.php');
$db = new ARDB();

$jobs = CronJob::where('enabled',1)->get();

$jobby = new Jobby\Jobby();

foreach($jobs AS $job) {
    $job_arr = $job->toArray();
    $job_arr = array_filter($job_arr);

    if($job->useDefaultMailer()) {
        $job_arr['smtpHost'] = AR_SMTP_EMAIL_HOST;
        $job_arr['smtpPort'] = AR_SMTP_EMAIL_PORT;
        $job_arr['smtpUsername'] = AR_SMTP_EMAIL_USER;
        $job_arr['smtpPassword'] = AR_SMTP_EMAIL_PASS;
    }

    //the output log, if it exists, will only be a file in /var/log
    if(!empty($job->output)) {
        $job_arr['output'] = join(DIRECTORY_SEPARATOR,[CRONJOB_LOG_FOLDER,$job->output]);
    }
    $jobby->add($job->name,$job_arr);
}

$jobby->run();
?>