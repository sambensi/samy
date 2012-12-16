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
    protected function _render($view, $data = array())
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

        if ($page == "all") {
            // On affiche toutes les images
            $data['results'] = $this->imageModel->getAll();

            // Log
            $this->_log("Affichage de toutes les images.", $page);
        } else {
            // On affiche une page
            $page = abs((int) $page);
            if ($page < 1) {
                $page = 1;
            }

            $this->load->library('pagination', array(
                'base_url' => $this->config->base_url('image/index'),
                'total_rows' => $this->imageModel->countRows(),
                'per_page' => $per_page = 20,
                'cur_page' => $page,
                'use_page_numbers' => true
            ));

            $data['results'] = $this->imageModel->getOffset($per_page * ($page - 1), $per_page);
            $data['links'] = $this->pagination->create_links();

            // Log
            $this->_log("Affichage de la page %d.", $page);
        }

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

    /**
     * Ajout d'une image : formulaire
     */
    public function add()
    {
        $this->_log("Formulaire d'ajout d'une image.");
        $this->_render('add', array('title' => 'Ajouter une image'));
    }

    /**
     * Ajout d'une image : validation du formulaire
     */
    public function addPost()
    {
        // Upload de l'image
        $config = array(
            'upload_path' => './images/',
            'allowed_types' => 'jpg',
            'max_size' => '100',
            'max_width' => '400',
            'max_height' => '300'
        );
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload()) {
            // L'upload n'a pas fonctionné
            $this->_log("Ajout d'une image en échec.");
            $this->session->set_flashdata('error', $this->upload->display_errors('',''));
        } else {
            // L'upload a fonctionné
            $data = $this->upload->data();

            // On ajoute même s'il n'y a pas de commentaire
            $comment = $this->input->post('comment');
            if (!$comment) {
                $comment = "";
            }

            // Ajout de l'image
            $this->imageModel->insert($data['raw_name'], $comment);

            // Message OK
            $this->session->set_flashdata('success', true);
            $this->_log("Ajout d'une image réussie : %s.jpg", $data['raw_name']);
        }

        redirect('image/add');
    }


}
