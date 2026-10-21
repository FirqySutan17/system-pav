<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Properties_model extends CI_Model
{
    protected $table = 'properties';


    /**
     * ==========================================================
     * GET ALL PROPERTIES
     * ==========================================================
     */
    public function get_all()
    {
        return $this->db
            ->select('
                p.id,
                p.title,
                p.slug,
                p.asset_class,
                p.asset_type,
                p.price,
                p.thumbnail,
                p.featured,
                p.status,
                p.created_at,

                pc.name AS category_name,

                pl.city,
                pl.province
            ')
            ->from('properties p')
            ->join(
                'property_categories pc',
                'pc.id = p.category_id',
                'left'
            )
            ->join(
                'property_locations pl',
                'pl.id = p.location_id',
                'left'
            )
            ->order_by(
                'p.created_at',
                'DESC'
            )
            ->get()
            ->result_array();
    }


    /**
     * ==========================================================
     * GET PROPERTY BY ID
     * ==========================================================
     */
    public function get_by_id($id)
    {
        return $this->db
            ->select('
                p.*,

                pc.name AS category_name,

                pl.city,
                pl.province
            ')
            ->from('properties p')
            ->join(
                'property_categories pc',
                'pc.id = p.category_id',
                'left'
            )
            ->join(
                'property_locations pl',
                'pl.id = p.location_id',
                'left'
            )
            ->where(
                'p.id',
                $id
            )
            ->get()
            ->row_array();
    }


    /**
     * ==========================================================
     * DELETE PROPERTY
     * ==========================================================
     */
    public function delete($id)
    {
        /*
        |--------------------------------------------------------------------------
        | GET PROPERTY FILES
        |--------------------------------------------------------------------------
        */

        $images =
            $this->get_property_images($id);

        $documents =
            $this->get_property_documents($id);


        /*
        |--------------------------------------------------------------------------
        | START TRANSACTION
        |--------------------------------------------------------------------------
        */

        $this->db->trans_begin();


        /*
        |--------------------------------------------------------------------------
        | DELETE PROPERTY IMAGES
        |--------------------------------------------------------------------------
        */

        $this->db
            ->where(
                'property_id',
                $id
            )
            ->delete('property_images');


        /*
        |--------------------------------------------------------------------------
        | DELETE PROPERTY DOCUMENTS
        |--------------------------------------------------------------------------
        */

        $this->db
            ->where(
                'property_id',
                $id
            )
            ->delete('property_documents');


        /*
        |--------------------------------------------------------------------------
        | DELETE PROPERTY
        |--------------------------------------------------------------------------
        */

        $this->db
            ->where(
                'id',
                $id
            )
            ->delete($this->table);


        /*
        |--------------------------------------------------------------------------
        | TRANSACTION CHECK
        |--------------------------------------------------------------------------
        */

        if (
            $this->db->trans_status() === FALSE
        ) {

            $this->db->trans_rollback();

            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | COMMIT
        |--------------------------------------------------------------------------
        */

        $this->db->trans_commit();


        /*
        |--------------------------------------------------------------------------
        | DELETE PHYSICAL IMAGE FILES
        |--------------------------------------------------------------------------
        */

        foreach (
            $images as $image
        ) {

            if (
                !empty($image['image'])
            ) {

                $file =
                    FCPATH .
                    $image['image'];


                if (
                    file_exists($file)
                ) {

                    @unlink($file);

                }
            }
        }


        /*
        |--------------------------------------------------------------------------
        | DELETE PHYSICAL DOCUMENT FILES
        |--------------------------------------------------------------------------
        */

        foreach (
            $documents as $document
        ) {

            if (
                !empty($document['file'])
            ) {

                $file =
                    FCPATH .
                    $document['file'];


                if (
                    file_exists($file)
                ) {

                    @unlink($file);

                }
            }
        }


        return true;
    }


    /**
     * ==========================================================
     * CATEGORY
     * ==========================================================
     */

    public function get_categories()
    {
        return $this->db
            ->select('id, name')
            ->from('property_categories')
            ->order_by('name', 'ASC')
            ->get()
            ->result_array();
    }


    public function get_category_by_name($name)
    {
        return $this->db
            ->where(
                'LOWER(name)',
                strtolower($name)
            )
            ->get('property_categories')
            ->row_array();
    }


    public function insert_category($name)
    {
        /*
        |--------------------------------------------------------------------------
        | GENERATE SLUG
        |--------------------------------------------------------------------------
        */

        $slug = url_title(
            strtolower($name),
            'dash',
            TRUE
        );

        if ($slug === '') {
            $slug = 'category';
        }


        /*
        |--------------------------------------------------------------------------
        | MAKE SLUG UNIQUE
        |--------------------------------------------------------------------------
        */

        $original_slug = $slug;
        $counter = 1;

        while (
            $this->db
                ->where('slug', $slug)
                ->count_all_results('property_categories') > 0
        ) {

            $counter++;

            $slug =
                $original_slug .
                '-' .
                $counter;
        }


        /*
        |--------------------------------------------------------------------------
        | INSERT CATEGORY
        |--------------------------------------------------------------------------
        */

        $insert = $this->db->insert(
            'property_categories',
            [
                'name'       => $name,
                'slug'       => $slug,
                'icon'       => NULL,
                'sort_order' => 0,
                'status'     => 'Active',
                'created_at' => date('Y-m-d H:i:s')
            ]
        );


        if (!$insert) {

            log_message(
                'error',
                'PROPERTY CATEGORY INSERT ERROR: ' .
                $this->db->error()['code'] .
                ' - ' .
                $this->db->error()['message']
            );

            return false;
        }


        return $this->db->insert_id();
    }

    /**
     * ==========================================================
     * GET PROPERTY DETAIL
     * ==========================================================
     */
    public function get_detail($id)
    {
        /**
         * ------------------------------------------------------
         * PROPERTY
         * ------------------------------------------------------
         */

        $property = $this->db
            ->select('
                p.*,

                pc.name AS category_name,

                pl.city,
                pl.province
            ')
            ->from('properties p')

            ->join(
                'property_categories pc',
                'pc.id = p.category_id',
                'left'
            )

            ->join(
                'property_locations pl',
                'pl.id = p.location_id',
                'left'
            )

            ->where(
                'p.id',
                $id
            )

            ->get()
            ->row_array();


        if (!$property) {

            return null;

        }


        /**
         * ------------------------------------------------------
         * PROPERTY IMAGES
         * ------------------------------------------------------
         */

        $property['images'] = $this->db
            ->select('
                id,
                property_id,
                image,
                caption,
                sort_order,
                is_cover,
                created_at
            ')
            ->from('property_images')

            ->where(
                'property_id',
                $id
            )

            ->order_by(
                'is_cover',
                'DESC'
            )

            ->order_by(
                'sort_order',
                'ASC'
            )

            ->order_by(
                'id',
                'ASC'
            )

            ->get()
            ->result_array();


        /**
         * ------------------------------------------------------
         * PROPERTY DOCUMENTS
         * ------------------------------------------------------
         */

        $property['documents'] = $this->db
            ->select('
                id,
                property_id,
                title,
                file,
                sort_order
            ')
            ->from('property_documents')

            ->where(
                'property_id',
                $id
            )

            ->order_by(
                'sort_order',
                'ASC'
            )

            ->order_by(
                'id',
                'ASC'
            )

            ->get()
            ->result_array();


        return $property;
    }


    /**
     * ==========================================================
     * LOCATION
     * ==========================================================
     */

    public function get_locations()
    {
        return $this->db
            ->select('
                id,
                city,
                province
            ')
            ->from('property_locations')
            ->order_by('city', 'ASC')
            ->get()
            ->result_array();
    }


    public function get_location($city, $province)
    {
        $this->db
            ->where(
                'LOWER(city)',
                strtolower($city)
            );

        if ($province !== '') {

            $this->db->where(
                'LOWER(province)',
                strtolower($province)
            );

        }

        return $this->db
            ->get('property_locations')
            ->row_array();
    }


    public function insert_location($data)
    {
        $this->db->insert(
            'property_locations',
            $data
        );

        return $this->db->insert_id();
    }


    /**
     * ==========================================================
     * INSERT PROPERTY
     * ==========================================================
     */

    public function insert_property($data)
    {
        $this->db->insert(
            $this->table,
            $data
        );

        return $this->db->insert_id();
    }

    /**
     * ==========================================================
     * UPDATE PROPERTY
     * ==========================================================
     */
    public function update_property($id, $data)
    {
        return $this->db
            ->where(
                'id',
                $id
            )
            ->update(
                $this->table,
                $data
            );
    }


    /**
     * ==========================================================
     * INSERT PROPERTY IMAGE
     * ==========================================================
     */

    public function insert_property_image($data)
    {
        $inserted = $this->db->insert(
            'property_images',
            $data
        );

        if (!$inserted) {
            return false;
        }

        return $this->db->insert_id();
    }

    /**
     * ==========================================================
     * UPDATE PROPERTY IMAGE
     * ==========================================================
     */
    public function update_property_image($id, $property_id, $data)
    {
        return $this->db
            ->where(
                'id',
                $id
            )
            ->where(
                'property_id',
                $property_id
            )
            ->update(
                'property_images',
                $data
            );
    }


    /**
     * ==========================================================
     * DELETE PROPERTY IMAGE
     * ==========================================================
     */
    public function delete_property_image($id, $property_id)
    {
        return $this->db
            ->where(
                'id',
                $id
            )
            ->where(
                'property_id',
                $property_id
            )
            ->delete(
                'property_images'
            );
    }


    /**
     * ==========================================================
     * INSERT PROPERTY DOCUMENT
     * ==========================================================
     */

    public function insert_property_document($data)
    {
        return $this->db->insert(
            'property_documents',
            $data
        );
    }

    /**
     * ==========================================================
     * UPDATE PROPERTY DOCUMENT
     * ==========================================================
     */
    public function update_property_document($id, $property_id, $data)
    {
        return $this->db
            ->where(
                'id',
                $id
            )
            ->where(
                'property_id',
                $property_id
            )
            ->update(
                'property_documents',
                $data
            );
    }


    /**
     * ==========================================================
     * DELETE PROPERTY DOCUMENT
     * ==========================================================
     */
    public function delete_property_document($id, $property_id)
    {
        return $this->db
            ->where(
                'id',
                $id
            )
            ->where(
                'property_id',
                $property_id
            )
            ->delete(
                'property_documents'
            );
    }

    /**
     * ==========================================================
     * GET PROPERTY IMAGES
     * ==========================================================
     */
    public function get_property_images($property_id)
    {
        return $this->db
            ->select('
                id,
                property_id,
                image,
                caption,
                sort_order,
                is_cover,
                created_at
            ')
            ->from('property_images')
            ->where(
                'property_id',
                $property_id
            )
            ->order_by(
                'sort_order',
                'ASC'
            )
            ->get()
            ->result_array();
    }

    /**
     * ==========================================================
     * GET PROPERTY IMAGE
     * ==========================================================
     */
    public function get_property_image($id, $property_id)
    {
        return $this->db
            ->select('
                id,
                property_id,
                image,
                caption,
                sort_order,
                is_cover,
                created_at
            ')
            ->from('property_images')
            ->where(
                'id',
                $id
            )
            ->where(
                'property_id',
                $property_id
            )
            ->get()
            ->row_array();
    }

    /**
     * ==========================================================
     * CLEAR PROPERTY COVERS
     * ==========================================================
     */
    public function clear_property_covers($property_id)
    {
        return $this->db
            ->where(
                'property_id',
                $property_id
            )
            ->update(
                'property_images',
                [
                    'is_cover' => 0
                ]
            );
    }


    /**
     * ==========================================================
     * SET PROPERTY COVER
     * ==========================================================
     */
    public function set_property_cover($image_id, $property_id)
    {
        return $this->db
            ->where(
                'id',
                $image_id
            )
            ->where(
                'property_id',
                $property_id
            )
            ->update(
                'property_images',
                [
                    'is_cover' => 1
                ]
            );
    }


    /**
     * ==========================================================
     * GET PROPERTY COVER
     * ==========================================================
     */
    public function get_property_cover($property_id)
    {
        return $this->db
            ->select('
                id,
                property_id,
                image,
                caption,
                sort_order,
                is_cover,
                created_at
            ')
            ->from('property_images')
            ->where(
                'property_id',
                $property_id
            )
            ->where(
                'is_cover',
                1
            )
            ->order_by(
                'id',
                'ASC'
            )
            ->limit(1)
            ->get()
            ->row_array();
    }

    /**
     * ==========================================================
     * GET FIRST PROPERTY IMAGE
     * ==========================================================
     */
    public function get_first_property_image($property_id)
    {
        return $this->db
            ->select('
                id,
                property_id,
                image,
                caption,
                sort_order,
                is_cover,
                created_at
            ')
            ->from('property_images')
            ->where(
                'property_id',
                $property_id
            )
            ->order_by(
                'sort_order',
                'ASC'
            )
            ->order_by(
                'id',
                'ASC'
            )
            ->limit(1)
            ->get()
            ->row_array();
    }

    /**
     * ==========================================================
     * GET LAST PROPERTY IMAGE
     * ==========================================================
     */
    public function get_last_property_image($property_id)
    {
        return $this->db
            ->select('
                id,
                property_id,
                image,
                caption,
                sort_order,
                is_cover,
                created_at
            ')
            ->from('property_images')
            ->where(
                'property_id',
                $property_id
            )
            ->order_by(
                'sort_order',
                'DESC'
            )
            ->order_by(
                'id',
                'DESC'
            )
            ->limit(1)
            ->get()
            ->row_array();
    }

    /**
     * ==========================================================
     * GET PROPERTY DOCUMENTS
     * ==========================================================
     */
    public function get_property_documents($property_id)
    {
        return $this->db
            ->select('
                id,
                property_id,
                title,
                file,
                sort_order
            ')
            ->from('property_documents')
            ->where(
                'property_id',
                $property_id
            )
            ->order_by(
                'sort_order',
                'ASC'
            )
            ->get()
            ->result_array();
    }

    /**
     * ==========================================================
     * GET PROPERTY DOCUMENT
     * ==========================================================
     */
    public function get_property_document($id, $property_id)
    {
        return $this->db
            ->select('
                id,
                property_id,
                title,
                file,
                sort_order
            ')
            ->from('property_documents')
            ->where(
                'id',
                $id
            )
            ->where(
                'property_id',
                $property_id
            )
            ->get()
            ->row_array();
    }

    /**
     * ==========================================================
     * GET LAST PROPERTY DOCUMENT
     * ==========================================================
     */
    public function get_last_property_document($property_id)
    {
        return $this->db
            ->select('
                id,
                property_id,
                title,
                file,
                sort_order
            ')
            ->from('property_documents')
            ->where(
                'property_id',
                $property_id
            )
            ->order_by(
                'sort_order',
                'DESC'
            )
            ->order_by(
                'id',
                'DESC'
            )
            ->limit(1)
            ->get()
            ->row_array();
    }

}