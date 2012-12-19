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
    public function getAll($sortby = 'id', $dir = 'asc') // si sortby non renseigné, il prend par défaut id
    {
        $query = $this->db
            ->order_by($sortby, $dir)
            ->get($this->_table);
        return $query->result();
    }

    /**
     * Retourne un offset
     */
    public function getOffset($offset, $count, $sortby = 'id', $dir = 'asc')
    {
        $this->db
            ->from($this->_table)
            ->limit($count)
            ->offset($offset)
            ->order_by($sortby, $dir);

        return $this->db->get()->result();
    }

    /**
     * Retourne le nombre total d'entrées
     */
    public function countRows()
    {
        return $this->db->count_all($this->_table);
    }

    /**
     * Recherche
     */
    public function search($q, $order = null, $dir = 'asc')
    {
        $this->db->like('comment', $q); //on cherche soit par comment
        $this->db->or_like('path', $q); //soit par path
        $this->db->from($this->_table); //dans la table
        if ($order) { //si il y a un ordre, on tri
            $this->db->order_by($order, $dir);
        }
        $query = $this->db->get(); // on récupère
        return $query->result(); //on retourne le résultat
    }

    /**
     * Récupère une seule image
     */
    public function get($id)
    {
        $query = $this->db->get_where($this->_table, array('id' => $id));
        return current($query->result());
    }

    /**
     * Supprime une image
     */
    public function delete($id)
    {
        $this->db->delete($this->_table, array('id' => $id));
    }

    /**
     * Ajout d'une image
     */
    public function insert($path, $comment)
    {
        $this->db->query("INSERT INTO ".$this->_table." (path, comment) VALUES(?, ?)", array(
            $path,
            $comment
        ));
    }

    /**
     * Mise à jour du commentaire d'une image
     */
    public function editComment($id, $comment)
    {
        $this->db->query("UPDATE " . $this->_table . " SET comment = ? WHERE id = ?", array(
            $comment,
            $id
        ));
    }
	
	public function communiqJava()
	{
		$command = 'java -cp emptyRanking.jar upmc.imw.bin.Empty_InteractiveLearning images > test.txt'; //Creation de la fonction pour lancer le .jar 
		//avec le parametre et la redirection
		system($command, $res );
		
	}
}
