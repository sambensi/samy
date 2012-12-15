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
     * Affichage d'une page complète
     */
    protected function _render($view, $data)
    {
        $this->load->view('layout.phtml', array_merge($data, array(
            'content' => $this->load->view('images/' . $view . '.phtml', $data, true)
        )));
    }

    /**
     * Action principale : index
     */
    public function index()
    {
        $data['title'] = "List";
        $data['results'] = $this->imageModel->getAll();
        $this->_render('index', $data);
    }

    /**
     * Affichage d'une image
     */
    public function show($id)
    {
        $data['title'] = "Show";
        $data['results'] = $this->imageModel->get($id);
        $this->_render('index', $data);
    }

    /**
     * Suppression d'une image
     */
    public function destroy($id)
    {
        $data['title'] = "Destroy";
        $data['results'] = $this->imageModel->delete($id);
        $this->_render('index', $data);
    }

    /**
     * Recherche
     */
    public function search($q,$order=NULL)
    {
        $data['title'] = "Search";
        $data['results'] = $this->imageModel->query($q, $order);
        $this->_render('index', $data);
    }

}
