<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Properties extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Properties_model');

        $this->load->library([
            'session',
            'upload'
        ]);

        $this->load->helper([
            'url',
            'form'
        ]);
    }


    /**
     * ==========================================================
     * PROPERTY LIST
     * ==========================================================
     */
    public function index()
    {
        $data['title'] = 'Properties';

        $data['properties'] =
            $this->Properties_model->get_all();

        $this->load->view(
            'templates/header',
            [
                'title' => 'Properties'
            ]
        );

        $this->load->view(
            'templates/sidebar'
        );

        $this->load->view(
            'admin/properties/index',
            $data
        );

        $this->load->view(
            'templates/footer'
        );
    }

    public function create()
    {
        $data['title'] = 'Add Property';

        $data['categories'] =
            $this->Properties_model->get_categories();

        $data['locations'] =
            $this->Properties_model->get_locations();

        $this->load->view(
            'templates/header',
            [
                'title' => 'Add Property'
            ]
        );

        $this->load->view(
            'templates/sidebar'
        );

        $this->load->view(
            'admin/properties/form',
            $data
        );

        $this->load->view(
            'templates/footer'
        );
    }

    /**
     * ==========================================================
     * STORE PROPERTY
     * ==========================================================
     */
    public function store()
    {
        /*
        |--------------------------------------------------------------------------
        | ONLY POST
        |--------------------------------------------------------------------------
        */

        if ($this->input->method() !== 'post') {
            return redirect('properties');
        }


        /*
        |--------------------------------------------------------------------------
        | BASIC INPUT
        |--------------------------------------------------------------------------
        */

        $title = trim(
            $this->input->post('title', TRUE)
        );

        $category_id = (int)
            $this->input->post('category_id');

        $location_id = (int)
            $this->input->post('location_id');

        $status = trim(
            $this->input->post('status', TRUE)
        );

        $asset_class = trim(
            $this->input->post('asset_class', TRUE)
        );

        $asset_type = trim(
            $this->input->post('asset_type', TRUE)
        );

        $land_area = trim(
            $this->input->post('land_area', TRUE)
        );

        $building_area = trim(
            $this->input->post('building_area', TRUE)
        );

        $price = $this->_normalize_price(
            $this->input->post('price', TRUE)
        );

        $year_built = trim(
            $this->input->post('year_built', TRUE)
        );

        $address = trim(
            $this->input->post('address', TRUE)
        );

        $short_description = trim(
            $this->input->post('short_description', TRUE)
        );

        $description = trim(
            $this->input->post('description', TRUE)
        );

        $featured = $this->input->post('featured')
            ? 1
            : 0;


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        if ($title === '') {

            $this->session->set_flashdata(
                'error',
                'Property title is required.'
            );

            return redirect('properties/create');
        }


        if ($category_id <= 0) {

            $this->session->set_flashdata(
                'error',
                'Please select a category.'
            );

            return redirect('properties/create');
        }


        if ($location_id <= 0) {

            $this->session->set_flashdata(
                'error',
                'Please select a location.'
            );

            return redirect('properties/create');
        }

        if ($price === NULL || $price < 0) {

            $this->session->set_flashdata(
                'error',
                'Please enter a valid property price.'
            );

            return redirect(
                'properties/create'
            );
        }


        if (!in_array(
            $status,
            ['available', 'reserved', 'sold'],
            true
        )) {

            $this->session->set_flashdata(
                'error',
                'Invalid property status.'
            );

            return redirect('properties/create');
        }


        /*
        |--------------------------------------------------------------------------
        | IMAGE REQUIRED
        |--------------------------------------------------------------------------
        */

        if (
            !isset($_FILES['images']) ||
            empty($_FILES['images']['name'][0])
        ) {

            $this->session->set_flashdata(
                'error',
                'At least one property image is required.'
            );

            return redirect('properties/create');
        }


        /*
        |--------------------------------------------------------------------------
        | COVER INDEX
        |--------------------------------------------------------------------------
        */

        $cover_index = (int)
            $this->input->post('cover_index');


        /*
        |--------------------------------------------------------------------------
        | IMAGE CAPTIONS
        |--------------------------------------------------------------------------
        */

        $image_captions =
            $this->input->post(
                'image_captions'
            );

        if (!is_array($image_captions)) {
            $image_captions = [];
        }


        /*
        |--------------------------------------------------------------------------
        | DOCUMENT DATA
        |--------------------------------------------------------------------------
        */

        $document_titles =
            $this->input->post(
                'document_titles'
            );

        if (!is_array($document_titles)) {
            $document_titles = [];
        }


        /*
        |--------------------------------------------------------------------------
        | SLUG
        |--------------------------------------------------------------------------
        */

        $slug = $this->_generate_unique_slug(
            $title
        );


        /*
        |--------------------------------------------------------------------------
        | UPLOAD DIRECTORIES
        |--------------------------------------------------------------------------
        */

        $image_path =
            FCPATH . 'assets/uploads/properties/images/';

        $document_path =
            FCPATH . 'assets/uploads/properties/documents/';


        if (!is_dir($image_path)) {

            mkdir(
                $image_path,
                0755,
                true
            );
        }


        if (!is_dir($document_path)) {

            mkdir(
                $document_path,
                0755,
                true
            );
        }


        /*
        |--------------------------------------------------------------------------
        | START TRANSACTION
        |--------------------------------------------------------------------------
        */

        $this->db->trans_begin();


        /*
        |--------------------------------------------------------------------------
        | INSERT PROPERTY
        |--------------------------------------------------------------------------
        */

        $property_data = [

            'category_id' =>
                $category_id,

            'location_id' =>
                $location_id,

            'title' =>
                $title,

            'slug' =>
                $slug,

            'asset_class' =>
                $asset_class !== ''
                    ? $asset_class
                    : NULL,

            'asset_type' =>
                $asset_type !== ''
                    ? $asset_type
                    : NULL,

            'land_area' =>
                $land_area !== ''
                    ? $land_area
                    : NULL,

            'building_area' =>
                $building_area !== ''
                    ? $building_area
                    : NULL,

            'price' =>
                $price !== NULL
                    ? $price
                    : NULL,

            'year_built' =>
                $year_built !== ''
                    ? $year_built
                    : NULL,

            'thumbnail' =>
                NULL,

            'short_description' =>
                $short_description !== ''
                    ? $short_description
                    : NULL,

            'description' =>
                $description !== ''
                    ? $description
                    : NULL,

            'featured' =>
                $featured,

            'status' =>
                $status,

            'address' =>
                $address !== ''
                    ? $address
                    : NULL,

            'created_at' =>
                date('Y-m-d H:i:s'),

            'updated_at' =>
                date('Y-m-d H:i:s')
        ];


        $property_id =
            $this->Properties_model
                ->insert_property(
                    $property_data
                );


        if (!$property_id) {

            $this->db->trans_rollback();

            $this->session->set_flashdata(
                'error',
                'Failed to create property.'
            );

            return redirect('properties/create');
        }


        /*
        |--------------------------------------------------------------------------
        | UPLOAD IMAGES
        |--------------------------------------------------------------------------
        */

        $uploaded_images = [];

        $image_count =
            count(
                $_FILES['images']['name']
            );


        for (
            $i = 0;
            $i < $image_count;
            $i++
        ) {

            if (
                empty(
                    $_FILES['images']['name'][$i]
                )
            ) {
                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Rebuild single FILE array
            |--------------------------------------------------------------------------
            */

            $_FILES['single_image'] = [

                'name' =>
                    $_FILES['images']['name'][$i],

                'type' =>
                    $_FILES['images']['type'][$i],

                'tmp_name' =>
                    $_FILES['images']['tmp_name'][$i],

                'error' =>
                    $_FILES['images']['error'][$i],

                'size' =>
                    $_FILES['images']['size'][$i]
            ];


            /*
            |--------------------------------------------------------------------------
            | Upload configuration
            |--------------------------------------------------------------------------
            */

            $config = [

                'upload_path' =>
                    $image_path,

                'allowed_types' =>
                    'jpg|jpeg|png|webp',

                'max_size' =>
                    5120,

                'encrypt_name' =>
                    TRUE,

                'remove_spaces' =>
                    TRUE
            ];


            $this->load->library(
                'upload',
                $config
            );

            $this->upload->initialize(
                $config
            );


            if (
                !$this->upload
                    ->do_upload(
                        'single_image'
                    )
            ) {

                $error =
                    strip_tags(
                        $this->upload->display_errors(
                            '',
                            ''
                        )
                    );


                /*
                |--------------------------------------------------------------------------
                | Remove already uploaded files
                |--------------------------------------------------------------------------
                */

                foreach (
                    $uploaded_images
                    as $uploaded
                ) {

                    if (
                        file_exists(
                            $uploaded['full_path']
                        )
                    ) {

                        @unlink(
                            $uploaded['full_path']
                        );
                    }
                }


                $this->db->trans_rollback();


                $this->session->set_flashdata(
                    'error',
                    'Image upload failed: ' . $error
                );

                return redirect(
                    'properties/create'
                );
            }


            $upload_data =
                $this->upload->data();


            $uploaded_images[] = [

                'filename' =>
                    $upload_data['file_name'],

                'full_path' =>
                    $upload_data['full_path']
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | SAFETY CHECK
        |--------------------------------------------------------------------------
        */

        if (empty($uploaded_images)) {

            $this->db->trans_rollback();

            $this->session->set_flashdata(
                'error',
                'At least one valid property image is required.'
            );

            return redirect(
                'properties/create'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | NORMALIZE COVER INDEX
        |--------------------------------------------------------------------------
        */

        if (
            $cover_index < 0 ||
            $cover_index >= count($uploaded_images)
        ) {

            $cover_index = 0;
        }


        /*
        |--------------------------------------------------------------------------
        | INSERT PROPERTY IMAGES
        |--------------------------------------------------------------------------
        */

        $thumbnail = NULL;


        foreach (
            $uploaded_images
            as $index => $image
        ) {

            $is_cover =
                ($index === $cover_index)
                    ? 1
                    : 0;


            $caption =
                isset(
                    $image_captions[$index]
                )
                    ? trim(
                        $image_captions[$index]
                    )
                    : NULL;


            $image_data = [

                'property_id' =>
                    $property_id,

                'image' =>
                    'assets/uploads/properties/images/'
                    . $image['filename'],

                'caption' =>
                    $caption !== ''
                        ? $caption
                        : NULL,

                'sort_order' =>
                    $index + 1,

                'is_cover' =>
                    $is_cover,

                'created_at' =>
                    date('Y-m-d H:i:s')
            ];


            $inserted =
                $this->Properties_model
                    ->insert_property_image(
                        $image_data
                    );


            if (!$inserted) {

                foreach (
                    $uploaded_images
                    as $uploaded
                ) {

                    if (
                        file_exists(
                            $uploaded['full_path']
                        )
                    ) {

                        @unlink(
                            $uploaded['full_path']
                        );
                    }
                }


                $this->db->trans_rollback();


                $this->session->set_flashdata(
                    'error',
                    'Failed to save property images.'
                );

                return redirect(
                    'properties/create'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | COVER = THUMBNAIL
            |--------------------------------------------------------------------------
            */

            if ($is_cover) {

                $thumbnail =
                    'assets/uploads/properties/images/'
                    . $image['filename'];
            }
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE THUMBNAIL
        |--------------------------------------------------------------------------
        */

        $this->db
            ->where(
                'id',
                $property_id
            )
            ->update(
                'properties',
                [
                    'thumbnail' =>
                        $thumbnail,
                    'updated_at' =>
                        date('Y-m-d H:i:s')
                ]
            );


        /*
        |--------------------------------------------------------------------------
        | UPLOAD DOCUMENTS
        |--------------------------------------------------------------------------
        */

        if (
            isset($_FILES['documents']) &&
            !empty($_FILES['documents']['name'][0])
        ) {

            $document_count =
                count(
                    $_FILES['documents']['name']
                );


            for (
                $i = 0;
                $i < $document_count;
                $i++
            ) {

                if (
                    empty(
                        $_FILES['documents']['name'][$i]
                    )
                ) {
                    continue;
                }


                $document_title =
                    isset(
                        $document_titles[$i]
                    )
                        ? trim(
                            $document_titles[$i]
                        )
                        : '';


                if ($document_title === '') {

                    foreach (
                        $uploaded_images
                        as $uploaded
                    ) {

                        if (
                            file_exists(
                                $uploaded['full_path']
                            )
                        ) {

                            @unlink(
                                $uploaded['full_path']
                            );
                        }
                    }


                    $this->db->trans_rollback();


                    $this->session->set_flashdata(
                        'error',
                        'Document title is required.'
                    );

                    return redirect(
                        'properties/create'
                    );
                }


                $_FILES['single_document'] = [

                    'name' =>
                        $_FILES['documents']['name'][$i],

                    'type' =>
                        $_FILES['documents']['type'][$i],

                    'tmp_name' =>
                        $_FILES['documents']['tmp_name'][$i],

                    'error' =>
                        $_FILES['documents']['error'][$i],

                    'size' =>
                        $_FILES['documents']['size'][$i]
                ];


                $config = [

                    'upload_path' =>
                        $document_path,

                    'allowed_types' =>
                        'pdf|doc|docx|xls|xlsx',

                    'max_size' =>
                        10240,

                    'encrypt_name' =>
                        TRUE,

                    'remove_spaces' =>
                        TRUE
                ];


                $this->upload->initialize(
                    $config
                );


                if (
                    !$this->upload
                        ->do_upload(
                            'single_document'
                        )
                ) {

                    $error =
                        strip_tags(
                            $this->upload->display_errors(
                                '',
                                ''
                            )
                        );


                    foreach (
                        $uploaded_images
                        as $uploaded
                    ) {

                        if (
                            file_exists(
                                $uploaded['full_path']
                            )
                        ) {

                            @unlink(
                                $uploaded['full_path']
                            );
                        }
                    }


                    $this->db->trans_rollback();


                    $this->session->set_flashdata(
                        'error',
                        'Document upload failed: ' . $error
                    );

                    return redirect(
                        'properties/create'
                    );
                }


                $document_data =
                    $this->upload->data();


                $inserted =
                    $this->Properties_model
                        ->insert_property_document(
                            [
                                'property_id' =>
                                    $property_id,

                                'title' =>
                                    $document_title,

                                'file' =>
                                    'assets/uploads/properties/documents/'
                                    . $document_data['file_name'],

                                'sort_order' =>
                                    $i + 1
                            ]
                        );


                if (!$inserted) {

                    foreach (
                        $uploaded_images
                        as $uploaded
                    ) {

                        if (
                            file_exists(
                                $uploaded['full_path']
                            )
                        ) {

                            @unlink(
                                $uploaded['full_path']
                            );
                        }
                    }


                    if (
                        file_exists(
                            $document_data['full_path']
                        )
                    ) {

                        @unlink(
                            $document_data['full_path']
                        );
                    }


                    $this->db->trans_rollback();


                    $this->session->set_flashdata(
                        'error',
                        'Failed to save property document.'
                    );

                    return redirect(
                        'properties/create'
                    );
                }
            }
        }


        /*
        |--------------------------------------------------------------------------
        | COMPLETE TRANSACTION
        |--------------------------------------------------------------------------
        */

        if (
            $this->db->trans_status() === FALSE
        ) {

            /*
            |--------------------------------------------------------------------------
            | Remove uploaded files
            |--------------------------------------------------------------------------
            */

            foreach (
                $uploaded_images
                as $uploaded
            ) {

                if (
                    file_exists(
                        $uploaded['full_path']
                    )
                ) {

                    @unlink(
                        $uploaded['full_path']
                    );
                }
            }


            $this->db->trans_rollback();


            $this->session->set_flashdata(
                'error',
                'Failed to save property.'
            );

            return redirect(
                'properties/create'
            );
        }


        $this->db->trans_commit();


        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        $this->session->set_flashdata(
            'success',
            'Property successfully created.'
        );


        return redirect(
            'properties'
        );
    }

    /**
     * ==========================================================
     * EDIT PROPERTY
     * ==========================================================
     */
    public function edit($id)
    {
        if (empty($id)) {
            return redirect('properties');
        }


        /*
        |--------------------------------------------------------------------------
        | GET PROPERTY + IMAGES + DOCUMENTS
        |--------------------------------------------------------------------------
        */

        $property =
            $this->Properties_model
                ->get_detail($id);


        if (!$property) {

            $this->session->set_flashdata(
                'error',
                'Property tidak ditemukan.'
            );

            return redirect('properties');
        }


        /*
        |--------------------------------------------------------------------------
        | MASTER DATA
        |--------------------------------------------------------------------------
        */

        $data['categories'] =
            $this->Properties_model
                ->get_categories();

        $data['locations'] =
            $this->Properties_model
                ->get_locations();


        /*
        |--------------------------------------------------------------------------
        | PROPERTY
        |--------------------------------------------------------------------------
        */

        $data['property'] = $property;

        $data['title'] =
            'Edit Property - ' .
            $property['title'];


        /*
        |--------------------------------------------------------------------------
        | LOAD VIEW
        |--------------------------------------------------------------------------
        */

        $this->load->view(
            'templates/header',
            [
                'title' =>
                    'Edit Property - ' .
                    $property['title']
            ]
        );

        $this->load->view(
            'templates/sidebar'
        );

        $this->load->view(
            'admin/properties/edit',
            $data
        );

        $this->load->view(
            'templates/footer'
        );
    }

    /**
     * ==========================================================
     * UPDATE PROPERTY
     * ==========================================================
     */
    public function update($id) {
        /*
        |--------------------------------------------------------------------------
        | ONLY POST
        |--------------------------------------------------------------------------
        */

        if ($this->input->method() !== 'post') {
            return redirect('properties');
        }

        if (empty($id)) {
            return redirect('properties');
        }


        /*
        |--------------------------------------------------------------------------
        | GET EXISTING PROPERTY
        |--------------------------------------------------------------------------
        */

        $property =
            $this->Properties_model
                ->get_by_id($id);

        if (!$property) {

            $this->session->set_flashdata(
                'error',
                'Property tidak ditemukan.'
            );

            return redirect('properties');
        }


        /*
        |--------------------------------------------------------------------------
        | BASIC INPUT
        |--------------------------------------------------------------------------
        */

        $title = trim(
            $this->input->post('title', TRUE)
        );

        $category_id = (int)
            $this->input->post('category_id');

        $location_id = (int)
            $this->input->post('location_id');

        $status = trim(
            $this->input->post('status', TRUE)
        );

        $asset_class = trim(
            $this->input->post('asset_class', TRUE)
        );

        $asset_type = trim(
            $this->input->post('asset_type', TRUE)
        );

        $land_area = trim(
            $this->input->post('land_area', TRUE)
        );

        $building_area = trim(
            $this->input->post('building_area', TRUE)
        );

        $price = $this->_normalize_price(
            $this->input->post('price', TRUE)
        );

        $year_built = trim(
            $this->input->post('year_built', TRUE)
        );

        $address = trim(
            $this->input->post('address', TRUE)
        );

        $short_description = trim(
            $this->input->post('short_description', TRUE)
        );

        $description = trim(
            $this->input->post('description', TRUE)
        );

        $featured =
            $this->input->post('featured')
                ? 1
                : 0;


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        if ($title === '') {

            $this->session->set_flashdata(
                'error',
                'Property title is required.'
            );

            return redirect(
                'properties/edit/' . $id
            );
        }


        if ($category_id <= 0) {

            $this->session->set_flashdata(
                'error',
                'Please select a category.'
            );

            return redirect(
                'properties/edit/' . $id
            );
        }


        if ($location_id <= 0) {

            $this->session->set_flashdata(
                'error',
                'Please select a location.'
            );

            return redirect(
                'properties/edit/' . $id
            );
        }

        if ($price === NULL || $price < 0) {

            $this->session->set_flashdata(
                'error',
                'Please enter a valid property price.'
            );

            return redirect(
                'properties/edit/' . $id
            );
        }


        if (!in_array(
            $status,
            ['available', 'reserved', 'sold'],
            true
        )) {

            $this->session->set_flashdata(
                'error',
                'Invalid property status.'
            );

            return redirect(
                'properties/edit/' . $id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | POST ARRAYS
        |--------------------------------------------------------------------------
        */

        $delete_images =
            $this->input->post('delete_images');

        if (!is_array($delete_images)) {
            $delete_images = [];
        }


        $delete_documents =
            $this->input->post('delete_documents');

        if (!is_array($delete_documents)) {
            $delete_documents = [];
        }


        $existing_image_captions =
            $this->input->post(
                'existing_image_captions'
            );

        if (!is_array($existing_image_captions)) {
            $existing_image_captions = [];
        }


        $existing_document_titles =
            $this->input->post(
                'existing_document_titles'
            );

        if (!is_array($existing_document_titles)) {
            $existing_document_titles = [];
        }


        $document_titles =
            $this->input->post(
                'document_titles'
            );

        if (!is_array($document_titles)) {
            $document_titles = [];
        }


        /*
        |--------------------------------------------------------------------------
        | COVER IMAGE
        |--------------------------------------------------------------------------
        */

        $cover_image_id =
            (int) $this->input->post(
                'cover_image_id'
            );

        /*
        |--------------------------------------------------------------------------
        | NEW COVER IMAGE INDEX
        |--------------------------------------------------------------------------
        */

        $new_cover_index =
            $this->input->post('new_cover_index');

        if (
            $new_cover_index === NULL ||
            $new_cover_index === ''
        ) {
            $new_cover_index = -1;
        } else {
            $new_cover_index = (int) $new_cover_index;
        }


        /*
        |--------------------------------------------------------------------------
        | UPLOAD DIRECTORIES
        |--------------------------------------------------------------------------
        */

        $image_path =
            FCPATH .
            'assets/uploads/properties/images/';

        $document_path =
            FCPATH .
            'assets/uploads/properties/documents/';


        if (!is_dir($image_path)) {

            mkdir(
                $image_path,
                0755,
                true
            );
        }


        if (!is_dir($document_path)) {

            mkdir(
                $document_path,
                0755,
                true
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILE TRACKING
        |--------------------------------------------------------------------------
        */

        $uploaded_images = [];

        $uploaded_documents = [];

        $files_to_delete = [];


        /*
        |--------------------------------------------------------------------------
        | START TRANSACTION
        |--------------------------------------------------------------------------
        */

        $this->db->trans_begin();


        /*
        |--------------------------------------------------------------------------
        | UPDATE PROPERTY
        |--------------------------------------------------------------------------
        */

        $property_data = [

            'category_id' =>
                $category_id,

            'location_id' =>
                $location_id,

            'title' =>
                $title,

            'asset_class' =>
                $asset_class !== ''
                    ? $asset_class
                    : NULL,

            'asset_type' =>
                $asset_type !== ''
                    ? $asset_type
                    : NULL,

            'land_area' =>
                $land_area !== ''
                    ? $land_area
                    : NULL,

            'building_area' =>
                $building_area !== ''
                    ? $building_area
                    : NULL,

            'price' =>
                $price !== NULL
                    ? $price
                    : NULL,

            'year_built' =>
                $year_built !== ''
                    ? $year_built
                    : NULL,

            'short_description' =>
                $short_description !== ''
                    ? $short_description
                    : NULL,

            'description' =>
                $description !== ''
                    ? $description
                    : NULL,

            'featured' =>
                $featured,

            'status' =>
                $status,

            'address' =>
                $address !== ''
                    ? $address
                    : NULL,

            'updated_at' =>
                date('Y-m-d H:i:s')
        ];


        $updated =
            $this->Properties_model
                ->update_property(
                    $id,
                    $property_data
                );


        if (!$updated) {

            $this->db->trans_rollback();

            $this->session->set_flashdata(
                'error',
                'Failed to update property.'
            );

            return redirect(
                'properties/edit/' . $id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DELETE EXISTING IMAGES
        |--------------------------------------------------------------------------
        */

        foreach ($delete_images as $image_id) {

            $image_id = (int) $image_id;

            if ($image_id <= 0) {
                continue;
            }


            $image =
                $this->Properties_model
                    ->get_property_image(
                        $image_id,
                        $id
                    );


            if (!$image) {
                continue;
            }


            $files_to_delete[] =
                FCPATH . $image['image'];


            $deleted =
                $this->Properties_model
                    ->delete_property_image(
                        $image_id,
                        $id
                    );


            if (!$deleted) {

                $this->db->trans_rollback();

                foreach ($uploaded_images as $uploaded) {

                    if (
                        file_exists(
                            $uploaded['full_path']
                        )
                    ) {
                        @unlink(
                            $uploaded['full_path']
                        );
                    }
                }

                $this->session->set_flashdata(
                    'error',
                    'Failed to delete property image.'
                );

                return redirect(
                    'properties/edit/' . $id
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE EXISTING IMAGE CAPTIONS
        |--------------------------------------------------------------------------
        */

        foreach (
            $existing_image_captions
            as $image_id => $caption
        ) {

            $image_id = (int) $image_id;

            if ($image_id <= 0) {
                continue;
            }


            if (
                in_array(
                    $image_id,
                    array_map('intval', $delete_images),
                    true
                )
            ) {
                continue;
            }


            $caption =
                trim($caption);


            $this->Properties_model
                ->update_property_image(
                    $image_id,
                    $id,
                    [
                        'caption' =>
                            $caption !== ''
                                ? $caption
                                : NULL
                    ]
                );
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE COVER
        |--------------------------------------------------------------------------
        */

        if ($cover_image_id > 0) {

            $cover =
                $this->Properties_model
                    ->get_property_image(
                        $cover_image_id,
                        $id
                    );


            /*
            |--------------------------------------------------------------------------
            | Only allow existing image belonging to this property
            |--------------------------------------------------------------------------
            */

            if ($cover) {

                $this->Properties_model
                    ->clear_property_covers($id);

                $this->Properties_model
                    ->set_property_cover(
                        $cover_image_id,
                        $id
                    );

            }

        } else {

            /*
            |--------------------------------------------------------------------------
            | No cover selected
            |--------------------------------------------------------------------------
            |
            | If current cover was deleted, automatically select
            | the first remaining image.
            |
            */

            $current_cover =
                $this->Properties_model
                    ->get_property_cover($id);


            if (!$current_cover) {

                $first_image =
                    $this->Properties_model
                        ->get_first_property_image($id);


                if ($first_image) {

                    $this->Properties_model
                        ->clear_property_covers($id);

                    $this->Properties_model
                        ->set_property_cover(
                            $first_image['id'],
                            $id
                        );
                }
            }
        }


        /*
        |--------------------------------------------------------------------------
        | UPLOAD NEW IMAGES
        |--------------------------------------------------------------------------
        */

        if (
            isset($_FILES['images']) &&
            !empty($_FILES['images']['name'][0])
        ) {

            $image_count =
                count(
                    $_FILES['images']['name']
                );


            /*
            |--------------------------------------------------------------------------
            | Get current max sort order
            |--------------------------------------------------------------------------
            */

            $last_image =
                $this->Properties_model
                    ->get_last_property_image(
                        $id
                    );


            $sort_order =
                $last_image
                    ? ((int)$last_image['sort_order'] + 1)
                    : 1;


            for (
                $i = 0;
                $i < $image_count;
                $i++
            ) {

                if (
                    empty(
                        $_FILES['images']['name'][$i]
                    )
                ) {
                    continue;
                }


                $_FILES['single_image'] = [

                    'name' =>
                        $_FILES['images']['name'][$i],

                    'type' =>
                        $_FILES['images']['type'][$i],

                    'tmp_name' =>
                        $_FILES['images']['tmp_name'][$i],

                    'error' =>
                        $_FILES['images']['error'][$i],

                    'size' =>
                        $_FILES['images']['size'][$i]
                ];


                $config = [

                    'upload_path' =>
                        $image_path,

                    'allowed_types' =>
                        'jpg|jpeg|png|webp',

                    'max_size' =>
                        5120,

                    'encrypt_name' =>
                        TRUE,

                    'remove_spaces' =>
                        TRUE
                ];


                $this->upload->initialize(
                    $config
                );


                if (
                    !$this->upload
                        ->do_upload(
                            'single_image'
                        )
                ) {

                    $error =
                        strip_tags(
                            $this->upload
                                ->display_errors(
                                    '',
                                    ''
                                )
                        );


                    foreach (
                        $uploaded_images
                        as $uploaded
                    ) {

                        if (
                            file_exists(
                                $uploaded['full_path']
                            )
                        ) {
                            @unlink(
                                $uploaded['full_path']
                            );
                        }
                    }


                    $this->db->trans_rollback();

                    $this->session->set_flashdata(
                        'error',
                        'Image upload failed: ' .
                        $error
                    );

                    return redirect(
                        'properties/edit/' . $id
                    );
                }


                $upload_data =
                    $this->upload->data();


                $uploaded_images[] = [

                    'filename' =>
                        $upload_data['file_name'],

                    'full_path' =>
                        $upload_data['full_path']
                ];


                $image_data = [

                    'property_id' =>
                        $id,

                    'image' =>
                        'assets/uploads/properties/images/' .
                        $upload_data['file_name'],

                    'caption' =>
                        NULL,

                    'sort_order' =>
                        $sort_order,

                    'is_cover' =>
                        0,

                    'created_at' =>
                        date('Y-m-d H:i:s')
                ];


                $inserted =
                    $this->Properties_model
                        ->insert_property_image(
                            $image_data
                        );


                if (!$inserted) {

                    foreach (
                        $uploaded_images
                        as $uploaded
                    ) {

                        if (
                            file_exists(
                                $uploaded['full_path']
                            )
                        ) {
                            @unlink(
                                $uploaded['full_path']
                            );
                        }
                    }


                    $this->db->trans_rollback();

                    $this->session->set_flashdata(
                        'error',
                        'Failed to save new property image.'
                    );

                    return redirect(
                        'properties/edit/' . $id
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | NEW IMAGE COVER
                |--------------------------------------------------------------------------
                */

                if (
                    $new_cover_index >= 0 &&
                    $new_cover_index === $i
                ) {

                    $this->Properties_model
                        ->clear_property_covers($id);

                    $this->Properties_model
                        ->set_property_cover(
                            $inserted,
                            $id
                        );
                }


                $sort_order++;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | MINIMUM ONE IMAGE
        |--------------------------------------------------------------------------
        */

        $remaining_images =
            $this->Properties_model
                ->get_property_images($id);

        if (empty($remaining_images)) {

            foreach ($uploaded_images as $uploaded) {

                if (file_exists($uploaded['full_path'])) {
                    @unlink($uploaded['full_path']);
                }
            }

            foreach ($uploaded_documents as $uploaded) {

                if (file_exists($uploaded['full_path'])) {
                    @unlink($uploaded['full_path']);
                }
            }

            $this->db->trans_rollback();

            $this->session->set_flashdata(
                'error',
                'Property must have at least one image.'
            );

            return redirect(
                'properties/edit/' . $id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | DELETE EXISTING DOCUMENTS
        |--------------------------------------------------------------------------
        */

        foreach ($delete_documents as $document_id) {

            $document_id =
                (int) $document_id;

            if ($document_id <= 0) {
                continue;
            }


            $document =
                $this->Properties_model
                    ->get_property_document(
                        $document_id,
                        $id
                    );


            if (!$document) {
                continue;
            }


            $files_to_delete[] =
                FCPATH . $document['file'];


            $deleted =
                $this->Properties_model
                    ->delete_property_document(
                        $document_id,
                        $id
                    );


            if (!$deleted) {

                $this->db->trans_rollback();

                foreach (
                    $uploaded_images
                    as $uploaded
                ) {

                    if (
                        file_exists(
                            $uploaded['full_path']
                        )
                    ) {
                        @unlink(
                            $uploaded['full_path']
                        );
                    }
                }

                $this->session->set_flashdata(
                    'error',
                    'Failed to delete property document.'
                );

                return redirect(
                    'properties/edit/' . $id
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE EXISTING DOCUMENT TITLES
        |--------------------------------------------------------------------------
        */

        foreach (
            $existing_document_titles
            as $document_id => $document_title
        ) {

            $document_id =
                (int) $document_id;

            if ($document_id <= 0) {
                continue;
            }


            if (
                in_array(
                    $document_id,
                    array_map(
                        'intval',
                        $delete_documents
                    ),
                    true
                )
            ) {
                continue;
            }


            $document_title =
                trim($document_title);


            if ($document_title === '') {

                $this->db->trans_rollback();

                foreach (
                    $uploaded_images
                    as $uploaded
                ) {

                    if (
                        file_exists(
                            $uploaded['full_path']
                        )
                    ) {
                        @unlink(
                            $uploaded['full_path']
                        );
                    }
                }


                $this->session->set_flashdata(
                    'error',
                    'Document title is required.'
                );

                return redirect(
                    'properties/edit/' . $id
                );
            }


            $this->Properties_model
                ->update_property_document(
                    $document_id,
                    $id,
                    [
                        'title' =>
                            $document_title
                    ]
                );
        }


        /*
        |--------------------------------------------------------------------------
        | UPLOAD NEW DOCUMENTS
        |--------------------------------------------------------------------------
        */

        if (
            isset($_FILES['documents']) &&
            !empty($_FILES['documents']['name'][0])
        ) {

            $document_count =
                count(
                    $_FILES['documents']['name']
                );


            $last_document =
                $this->Properties_model
                    ->get_last_property_document(
                        $id
                    );


            $document_sort_order =
                $last_document
                    ? ((int)$last_document['sort_order'] + 1)
                    : 1;


            for (
                $i = 0;
                $i < $document_count;
                $i++
            ) {

                if (
                    empty(
                        $_FILES['documents']['name'][$i]
                    )
                ) {
                    continue;
                }


                $document_title =
                    isset(
                        $document_titles[$i]
                    )
                        ? trim(
                            $document_titles[$i]
                        )
                        : '';


                if ($document_title === '') {

                    foreach (
                        $uploaded_images
                        as $uploaded
                    ) {

                        if (
                            file_exists(
                                $uploaded['full_path']
                            )
                        ) {
                            @unlink(
                                $uploaded['full_path']
                            );
                        }
                    }


                    foreach (
                        $uploaded_documents
                        as $uploaded
                    ) {

                        if (
                            file_exists(
                                $uploaded['full_path']
                            )
                        ) {
                            @unlink(
                                $uploaded['full_path']
                            );
                        }
                    }


                    $this->db->trans_rollback();

                    $this->session->set_flashdata(
                        'error',
                        'Document title is required.'
                    );

                    return redirect(
                        'properties/edit/' . $id
                    );
                }


                $_FILES['single_document'] = [

                    'name' =>
                        $_FILES['documents']['name'][$i],

                    'type' =>
                        $_FILES['documents']['type'][$i],

                    'tmp_name' =>
                        $_FILES['documents']['tmp_name'][$i],

                    'error' =>
                        $_FILES['documents']['error'][$i],

                    'size' =>
                        $_FILES['documents']['size'][$i]
                ];


                $config = [

                    'upload_path' =>
                        $document_path,

                    'allowed_types' =>
                        'pdf|doc|docx|xls|xlsx',

                    'max_size' =>
                        10240,

                    'encrypt_name' =>
                        TRUE,

                    'remove_spaces' =>
                        TRUE
                ];


                $this->upload->initialize(
                    $config
                );


                if (
                    !$this->upload
                        ->do_upload(
                            'single_document'
                        )
                ) {

                    $error =
                        strip_tags(
                            $this->upload
                                ->display_errors(
                                    '',
                                    ''
                                )
                        );


                    foreach (
                        $uploaded_images
                        as $uploaded
                    ) {

                        if (
                            file_exists(
                                $uploaded['full_path']
                            )
                        ) {
                            @unlink(
                                $uploaded['full_path']
                            );
                        }
                    }


                    foreach (
                        $uploaded_documents
                        as $uploaded
                    ) {

                        if (
                            file_exists(
                                $uploaded['full_path']
                            )
                        ) {
                            @unlink(
                                $uploaded['full_path']
                            );
                        }
                    }


                    $this->db->trans_rollback();

                    $this->session->set_flashdata(
                        'error',
                        'Document upload failed: ' .
                        $error
                    );

                    return redirect(
                        'properties/edit/' . $id
                    );
                }


                $document_data =
                    $this->upload->data();


                $uploaded_documents[] = [

                    'filename' =>
                        $document_data['file_name'],

                    'full_path' =>
                        $document_data['full_path']
                ];


                $inserted =
                    $this->Properties_model
                        ->insert_property_document(
                            [
                                'property_id' =>
                                    $id,

                                'title' =>
                                    $document_title,

                                'file' =>
                                    'assets/uploads/properties/documents/' .
                                    $document_data['file_name'],

                                'sort_order' =>
                                    $document_sort_order
                            ]
                        );


                if (!$inserted) {

                    foreach (
                        $uploaded_images
                        as $uploaded
                    ) {

                        if (
                            file_exists(
                                $uploaded['full_path']
                            )
                        ) {
                            @unlink(
                                $uploaded['full_path']
                            );
                        }
                    }


                    foreach (
                        $uploaded_documents
                        as $uploaded
                    ) {

                        if (
                            file_exists(
                                $uploaded['full_path']
                            )
                        ) {
                            @unlink(
                                $uploaded['full_path']
                            );
                        }
                    }


                    $this->db->trans_rollback();

                    $this->session->set_flashdata(
                        'error',
                        'Failed to save property document.'
                    );

                    return redirect(
                        'properties/edit/' . $id
                    );
                }


                $document_sort_order++;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | RE-CHECK COVER
        |--------------------------------------------------------------------------
        */

        $current_cover =
            $this->Properties_model
                ->get_property_cover($id);


        if (!$current_cover) {

            $first_image =
                $this->Properties_model
                    ->get_first_property_image($id);


            if ($first_image) {

                $this->Properties_model
                    ->clear_property_covers($id);

                $this->Properties_model
                    ->set_property_cover(
                        $first_image['id'],
                        $id
                    );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDATE MINIMUM PROPERTY IMAGE
        |--------------------------------------------------------------------------
        */

        $remaining_images =
            $this->Properties_model
                ->get_property_images($id);

        if (empty($remaining_images)) {

            /*
            |--------------------------------------------------------------------------
            | Remove newly uploaded files
            |--------------------------------------------------------------------------
            */

            foreach (
                $uploaded_images
                as $uploaded
            ) {

                if (
                    file_exists(
                        $uploaded['full_path']
                    )
                ) {

                    @unlink(
                        $uploaded['full_path']
                    );
                }
            }


            foreach (
                $uploaded_documents
                as $uploaded
            ) {

                if (
                    file_exists(
                        $uploaded['full_path']
                    )
                ) {

                    @unlink(
                        $uploaded['full_path']
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Rollback database changes
            |--------------------------------------------------------------------------
            */

            $this->db->trans_rollback();


            $this->session->set_flashdata(
                'error',
                'At least one property image is required.'
            );


            return redirect(
                'properties/edit/' . $id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE PROPERTY THUMBNAIL
        |--------------------------------------------------------------------------
        */

        $current_cover =
            $this->Properties_model
                ->get_property_cover($id);


        $thumbnail =
            $current_cover
                ? $current_cover['image']
                : NULL;


        $this->Properties_model
            ->update_property(
                $id,
                [
                    'thumbnail' =>
                        $thumbnail,
                    'updated_at' =>
                        date('Y-m-d H:i:s')
                ]
            );


        /*
        |--------------------------------------------------------------------------
        | TRANSACTION CHECK
        |--------------------------------------------------------------------------
        */

        if (
            $this->db->trans_status() === FALSE
        ) {

            foreach (
                $uploaded_images
                as $uploaded
            ) {

                if (
                    file_exists(
                        $uploaded['full_path']
                    )
                ) {
                    @unlink(
                        $uploaded['full_path']
                    );
                }
            }


            foreach (
                $uploaded_documents
                as $uploaded
            ) {

                if (
                    file_exists(
                        $uploaded['full_path']
                    )
                ) {
                    @unlink(
                        $uploaded['full_path']
                    );
                }
            }


            $this->db->trans_rollback();

            $this->session->set_flashdata(
                'error',
                'Failed to update property.'
            );

            return redirect(
                'properties/edit/' . $id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | COMMIT
        |--------------------------------------------------------------------------
        */

        $this->db->trans_commit();


        /*
        |--------------------------------------------------------------------------
        | DELETE OLD FILES AFTER COMMIT
        |--------------------------------------------------------------------------
        */

        foreach ($files_to_delete as $file) {

            if (
                file_exists($file)
            ) {
                @unlink($file);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        $this->session->set_flashdata(
            'success',
            'Property successfully updated.'
        );


        return redirect(
            'properties');
    }

    /**
     * ==========================================================
     * PROPERTY DETAIL
     * ==========================================================
     */
    public function view($id)
    {
        if (empty($id)) {
            return redirect('properties');
        }


        $property =
            $this->Properties_model
                ->get_detail($id);


        if (!$property) {

            $this->session->set_flashdata(
                'error',
                'Property tidak ditemukan.'
            );

            return redirect('properties');
        }


        $data['title'] = $property['title'];

        $data['property'] = $property;


        $this->load->view(
            'templates/header',
            [
                'title' => $property['title']
            ]
        );


        $this->load->view(
            'templates/sidebar'
        );


        $this->load->view(
            'admin/properties/view',
            $data
        );


        $this->load->view(
            'templates/footer'
        );
    }

    public function delete($id)
    {
        if (empty($id)) {
            return redirect('properties');
        }

        $property =
            $this->Properties_model->get_by_id($id);

        if (!$property) {

            $this->session->set_flashdata(
                'error',
                'Property tidak ditemukan.'
            );

            return redirect('properties');
        }


        $deleted =
            $this->Properties_model->delete($id);


        if ($deleted) {

            $this->session->set_flashdata(
                'success',
                'Property berhasil dihapus.'
            );

        } else {

            $this->session->set_flashdata(
                'error',
                'Property gagal dihapus.'
            );
        }


        return redirect('properties');
    }

    public function quick_add_category()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $name = trim(
            $this->input->post('name', TRUE)
        );

        if ($name === '') {

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status'  => false,
                    'message' => 'Category name is required.'
                ]));
        }

        $existing =
            $this->Properties_model
                ->get_category_by_name($name);

        if ($existing) {

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status'  => false,
                    'message' => 'Category already exists.'
                ]));
        }

        $id =
            $this->Properties_model
                ->insert_category($name);

        if (!$id) {

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status'  => false,
                    'message' => 'Failed to add category.'
                ]));
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => true,
                'data' => [
                    'id'   => $id,
                    'name' => $name
                ]
            ]));
    }


    public function quick_add_location()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $city = trim(
            $this->input->post('city', TRUE)
        );

        $province = trim(
            $this->input->post('province', TRUE)
        );

        if ($city === '') {

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status'  => false,
                    'message' => 'City is required.'
                ]));
        }

        $existing =
            $this->Properties_model
                ->get_location(
                    $city,
                    $province
                );

        if ($existing) {

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status'  => false,
                    'message' => 'Location already exists.'
                ]));
        }

        $id =
            $this->Properties_model
                ->insert_location([
                    'city'     => $city,
                    'province' => $province
                ]);

        if (!$id) {

            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status'  => false,
                    'message' => 'Failed to add location.'
                ]));
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => true,
                'data' => [
                    'id'       => $id,
                    'city'     => $city,
                    'province' => $province
                ]
            ]));
    }

    /**
     * ==========================================================
     * GENERATE UNIQUE SLUG
     * ==========================================================
     */
    private function _generate_unique_slug($title)
    {
        $slug = url_title(
            strtolower($title),
            'dash',
            TRUE
        );

        if ($slug === '') {
            $slug = 'property';
        }


        $original_slug = $slug;
        $counter = 1;


        while (
            $this->db
                ->where(
                    'slug',
                    $slug
                )
                ->count_all_results(
                    'properties'
                ) > 0
        ) {

            $counter++;

            $slug =
                $original_slug .
                '-' .
                $counter;
        }


        return $slug;
    }

    /**
     * ==========================================================
     * DELETE UPLOADED FILE
     * ==========================================================
     */
    private function _delete_uploaded_file($path)
    {
        if (
            !empty($path) &&
            file_exists($path)
        ) {
            @unlink($path);
        }
    }

    /**
     * ==========================================================
     * NORMALIZE PROPERTY PRICE
     * ==========================================================
     */
    private function _normalize_price($price)
    {
        if ($price === NULL) {
            return NULL;
        }

        $price = trim((string) $price);

        if ($price === '') {
            return NULL;
        }

        /*
        |----------------------------------------------------------------------
        | Remove currency and thousand separators
        | Example:
        | Rp 5.000.000.000
        | 5.000.000.000
        | 5000000000
        |----------------------------------------------------------------------
        */

        $price = preg_replace('/[^0-9]/', '', $price);

        if ($price === '') {
            return NULL;
        }

        return (float) $price;
    }
}