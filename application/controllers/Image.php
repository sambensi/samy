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
     * Log une action
     */
    protected function _log()
    {
        $args = func_get_args();
        $action = array_shift($args);
        log_message('user', vsprintf($action, $args));
    }

    /**
     * Action principale : index
     */
    public function index($page = 1)
    {
        $data = array();
        $data['title'] = "List";

        $this->load->library('pagination', array(
            'base_url' => $this->config->base_url('image/index'),
            'total_rows' => $this->imageModel->countRows(),
            'per_page' => $per_page = 20,
            'cur_page' => (int) $page,
            'use_page_numbers' => true
        ));

        $data['results'] = $this->imageModel->getOffset($per_page * ($page - 1), $per_page);
        $data['links'] = $this->pagination->create_links();

        // Log
        $this->_log("Affichage de la page %d.", $page);

        $this->_render('index', $data);
    }

    /**
     * Affichage d'une image
     */
    public function show($id)
    {
        $data = array();
        $data['title'] = "Show";
        $data['results'] = $this->imageModel->get($id);
        $this->_render('index', $data);
    }

    /**
     * Suppression d'une image
     */
    public function destroy($id)
    {
        $id = (int) $id;

        // On vérifie que l'image existe bien
        $image = $this->imageModel->get($id);

        if ($image !== false) {
            // On la supprime
            $this->imageModel->delete((int) $id);

            // Log
            $this->_log("Suppression de l'image %d.", $id);

            // Message OK
            $this->session->set_flashdata("image_destroy", "success");
        } else {
            // Message NOK
            $this->session->set_flashdata("image_destroy", "failure");
        }

        // Redirection sur l'index
        redirect('image/index');
    }

    /**
     * Recherche
     */
    public function search($q,$order=NULL)
    {
        $data = array();
        $data['title'] = "Search";
        $data['results'] = $this->imageModel->query($q, $order);
        $this->_render('index', $data);
    }

}
