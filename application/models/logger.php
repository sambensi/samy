<?php

class Logger extends CI_Model
{

    /**
     * Nom de la table
     */
    private $_table = 'log';

    /**
     * Enregistrement d'un log
     */
    public function append($action)
    {
        $sessionId = $this->session->userdata('session_id');
        $date = date('Y/m/d H:i:s');
        $this->db->query("INSERT INTO ".$this->_table." (session_id, action, created_at) VALUES (?, ?, ?)", array(
            'session' => $sessionId,
            'action' => $action,
            'created_at' => $date
        ));
    }

}
