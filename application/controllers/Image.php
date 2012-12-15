<?php

class Image extends CI_Controller
{

    /**
     * Construction du contrôleur
     * - Chargement du model imageModel
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('imageModel');
    }

    /**
     * Action principale : index
     */
    public function index()
    {
        $data['title'] = "List";
        $data['results'] = $this->imageModel->getAll();
        $this->load->view('images/index.php', $data);
    }

    /**
     * Affichage d'une image
     */
    public function show($id)
    {
        $data['title'] = "Show";
        $data['results'] = $this->imageModel->get($id);
        $this->load->view('images/index.php', $data);
    }

    /**
     * Suppression d'une image
     */
    public function destroy($id)
    {
        $data['title'] = "Destroy";
        $data['results'] = $this->imageModel->delete($id);
        $this->load->view('images/index.php', $data);
    }

    /**
     * Recherche
     */
    public function search($q,$order=NULL)
    {
        $data['title'] = "Search";
        $data['results'] = $this->imageModel->query($q, $order);
        $this->load->view('images/index.php', $data);
    }

}
