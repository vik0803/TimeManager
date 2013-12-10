<?php
/**
 * @Author: Kenyon Haliwell
 * @Date Created: 12/09/13
 * @Date Modified: 12/10/13
 * @Purpose: Used as a wrapper for various methods surrounding jobs
 * @Version: 1.0
 */

 class model_timeclock_jobs {
    public function __construct() {
        global $sys;
        $this->sys = &$sys;
        
        if (array_key_exists('add_job', $_POST)) {
            $this->add();
        }
        if (array_key_exists('edit_job', $_POST)) {
            $this->edit();
        }
        if (array_key_exists('remove_job', $_POST)) {
            $this->remove();
        }
    }
    
    protected function add() {
        $error = '';
        
        if (!array_key_exists('client', $_POST) || '' === $_POST['client']) {
            $error = '<p>Please select a client.</p>';
        }
        if (!array_key_exists('job_name', $_POST) || '' === $_POST['job_name']) {
            $error .= '<p>Please enter a job name.</p>';
        }
        if ((!array_key_exists('uid', $_POST) || '' === $_POST['uid']) && !array_key_exists('generate_uid', $_POST)) {
            $error .= '<p>Please either enter a UID or select \'Generate ID.\'</p>';
        }
        
        if (array_key_exists('generate_uid', $_POST) && (!array_key_exists('uid', $_POST) || '' === $_POST['uid'])) {
            do {
                $id = mt_rand(0, 99999);
                
                $query = $this->sys->db->query("SELECT `job_id` FROM `jobs` WHERE `job_uid`=:id", array(
                    ':id' => $id
                ));
            } while (!empty($query));
            
        } else {
            $id = $_POST['uid'];
            
            $check_job = $this->sys->db->query("SELECT `job_name`, `client` FROM `jobs` WHERE `job_uid`=:id", array(
                ':id' => $id
            ));
            $client = $this->sys->db->query("SELECT `client_name` FROM `clients` WHERE `client_id`=:client_id", array(
                'client_id' => $check_job[0]['client']
            ));
            
            if (!empty($check_job)) {
                $error .= 'That UID is already in use by the job ' . $check_job[0]['job_name'] . ' for ' . $client[0]['client_name'] . '.';
                $this->sys->template->response = '<div class="form_failed">' . $error . '</div>';
                return false;
            }
        }
        
        $add_job = ('' === $error) ? $this->sys->db->query("INSERT INTO `jobs` (`job_id`, `job_uid`, `job_name`, `client`, `status`) VALUES (NULL, :id, :name, :client, 'na')", array(
            ':id'       => $id,
            ':name'     => $_POST['job_name'],
            ':client'   => $_POST['client']
        )) : '';
        
        if (!empty($error)) {
            $this->sys->template->response = '<div class="form_failed">' . $error . '</div>';
            return false;
        } else {
            $this->sys->template->response = '<div class="form_success">Job Added Successfully</div>';
            return true;
        }
    }
    
    protected function edit() {
        $error = '';
        
        $check_job = $this->sys->db->query("SELECT `job_uid` FROM `jobs` WHERE `job_id`=:id", array(
            ':id' => (int) $_POST['id']
        ));
        
        if (empty($check_job)) {
            $error = '<p>That job doesn\'t exist.</p>';
        }
        if (!array_key_exists('client', $_POST) || '' === $_POST['client']) {
            $error .= '<p>Please select a client.</p>';
        }
        if (!array_key_exists('job_name', $_POST) || '' === $_POST['job_name']) {
            $error .= '<p>Please enter a job name.</p>';
        }
        if ((!array_key_exists('uid', $_POST) || '' === $_POST['uid']) && !array_key_exists('generate_uid', $_POST)) {
            $error .= '<p>Please either enter a UID or select \'Generate ID.\'</p>';
        }
        
        if (array_key_exists('generate_uid', $_POST) && (!array_key_exists('uid', $_POST) || '' === $_POST['uid'])) {
            do {
                $uid = mt_rand(0, 99999);
                
                $query = $this->sys->db->query("SELECT `job_id` FROM `jobs` WHERE `job_uid`=:id", array(
                    ':id' => $uid
                ));
            } while (!empty($query));
            
        } else {
            $uid = $_POST['uid'];
            
            if ($check_job[0]['job_uid'] != $_POST['uid']) {
                $check_uid = $this->sys->db->query("SELECT `job_name`, `client` FROM `jobs` WHERE `job_uid`=:uid", array(
                    ':uid' => $uid
                ));
                
                if (!empty($check_uid)) {
                    $client = $this->sys->db->query("SELECT `client_name` FROM `clients` WHERE `client_id`=:client_id", array(
                        ':client_id' => $check_uid[0]['client']
                    ));
                    
                    $error .= 'That UID is already in use by the job ' . $check_uid[0]['job_name'] . ' for ' . $client[0]['client_name'] . '.';
                    $this->sys->template->response = '<div class="form_failed">' . $error . '</div>';
                    return false;
                }
            }
        }
        
        switch ($_POST['status']) {
            case 'na':
                $status = 'na';
                break;
            case 'wip':
                $status = 'wip';
                break;
            default: //c
                $status = 'c';
        }
        
        $edit_job = ('' === $error) ? $this->sys->db->query("UPDATE `jobs` SET `job_uid`=:uid, `job_name`=:name, `client`=:client, `status`=:status WHERE `job_id`=:id", array(
            ':id'       => (int) $_POST['id'],
            ':uid'      => $uid,
            ':name'     => $_POST['job_name'],
            ':client'   => $_POST['client'],
            ':status'   => $status
        )) : '';
        
        if (!empty($error)) {
            $this->sys->template->response = '<div class="form_failed">' . $error . '</div>';
            return false;
        } else {
            $this->sys->template->response = '<div class="form_success">Job Updated Successfully</div>';
            return true;
        }
    }
    
    protected function remove() {
        
    }
    
    public function get_jobs($job_id='all') {
        /**
         * Add a setting to make this sortable by job id, client or job name
         */
        if (is_numeric($job_id)) {
            $jobs = $this->sys->db->query("SELECT * FROM `jobs` WHERE `job_id`=:id", array(
                ':id' => (int) $job_id
            ));
            if (!empty($jobs)) {
                $jobs = $jobs[0];
            } else {
                return false;
            }
        } else {
            $jobs = $this->sys->db->query("SELECT * FROM `jobs`");
            $clients = $this->sys->db->query("SELECT * FROM `clients`");
        
            foreach ($clients as $client) {
                $jobs['client'][$client['client_id']] = $client['client_name'];
            }
        }
        
        return $jobs;
    }
 }

//End file