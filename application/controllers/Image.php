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

        // Tri
        $sortby = $this->input->get('sortby');
        if (!in_array($sortby, array('id', 'path', 'comment'))) {
            $sortby = 'id';
        }

        if ($page == "all") {
            // On affiche toutes les images
            $data['results'] = $this->imageModel->getAll($sortby);

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
                'total_rows' => $total = $this->imageModel->countRows(),
                'per_page' => $per_page = 20,
                'cur_page' => $page,
                'use_page_numbers' => true,
                'suffix' => ($sortby != 'id' ? '?sortby=' . $sortby : '')
            ));

            $data['results'] = $this->imageModel->getOffset($per_page * ($page - 1), $per_page);
            $data['links'] = $this->pagination->create_links();
            $data['pagination'] = $this->pagination;
            $data['total_pages'] = ceil($total / $per_page);

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
        $id = (int) $id;

        // Vérification de l'existence de l'image
        $image = $this->imageModel->get($id);

        if ($image === false) {
            $this->session->set_flashdata('error', "Cette image n'existe pas");
            redirect('image');
            return;
        }

        $data = array();
        $data['title'] = sprintf("Image #%d", $id);
        $data['image'] = $image;
        $this->_render('view', $data);
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
    public function search()
    {
        $q = $this->input->get('q');
        $data = array();
        $data['title'] = "Search";
        $data['results'] = $this->imageModel->search($q);
        $data['search'] = $q;
        $this->_render('search', $data);
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

    /**
     * Edition d'une image
     */
    public function edit($id)
    {
        $id = (int) $id;

        // On check que l'image existe
        $image = $this->imageModel->get($id);

        // Elle n'existe pas alors message d'erreur
        if ($image === false) {
            $this->session->set_flashdata('error', "Cette image n'existe pas");
            redirect('image');
            return;
        }

        $data['title'] = sprintf("Editer l'image numéro %d", $image->id);
        $data['image'] = $image;

        $this->_log("Formulaire d'édition de l'image %d.", $image->id);
        $this->_render('edit', $data);
    }

    /**
     * Edition d'une image : validation du formulaire
     */
    public function editPost($id)
    {
        $id = (int) $id;

        // On check que l'image existe
        $image = $this->imageModel->get($id);

        // Elle n'existe pas alors message d'erreur
        if ($image === false) {
            $this->session->set_flashdata('error', "Cette image n'existe pas");
            redirect('image');
            return;
        }

        // Edition
        $comment = $this->input->post('comment');
        if (!$comment) {
            $comment = "";
        }

        $this->_log("Édition de l'image %d.", $image->id);
        $this->imageModel->editComment($id, $comment);

        $this->session->set_flashdata('success', true);
        redirect('image/edit/' . $id);
    }

}
