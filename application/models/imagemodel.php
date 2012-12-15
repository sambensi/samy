<?php

class ImageModel extends CI_Model
{

    /**
     * Nom de la table en BDD
     */
    private $_table = 'image';

    /**
     * Constructeur du modèle
     */
    public function __construct()
    {
        // On appel le parent
        parent::__construct();
    }

    /**
     * Récupère toutes les images
     */
    public function getAll()
    {
        $query = $this->db->get($this->_table);
        return $query->result();
    }

    /**
     * Recherche
     */
    public function query($q, $order)
    {
        $this->db->like('comment', $q);
        $this->db->or_like('path', $q);
        $this->db->from($this->_table);
        if ($order) {
            $this->db->order_by($order);
        }
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Récupère une seule image
     */
    public function get($id)
    {
        $query = $this->db->get_where($this->_table, array('id' => $id));
        return $query->result();
    }

    /**
     * Supprime une image
     */
    public function delete($id)
    {
        $this->db->delete($this->_table, array('id' => $id));
    }

}
