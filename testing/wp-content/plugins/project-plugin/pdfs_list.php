<?php

if(!class_exists('WP_List_Table')){

    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

include_once("inc/ProjectManager_func.php");

class projectList extends WP_List_Table {   

	public $notify='';
	public $user_prefix=1000;

    function __construct(){

        global $status, $page;

        //Set parent defaults

        parent::__construct( array(

            'singular'  => 'id',     //singular name of the listed records
            'plural'    => 'ids',    //plural name of the listed records
            'ajax'      => false         //does this table support ajax?
        ) );


    }    



    /** ************************************************************************

     * For more detailed insight into how columns are handled, take a look at 

     * WP_List_Table::single_row_columns()
     * 
     * @param array $item A singular item (one full row's worth of data)
     * @param array $column_name The name/slug of the column to be processed
     * @return string Text or HTML to be placed inside the column <td>

     **************************************************************************/



    function column_default($item, $column_name){

		global $wpdb;

		$arr=array();

        switch($column_name){
            /*case 'id':*/ 
            case 'pdf_title':
            case 'site_cost':
            case 'property':
            case 'acknowledgement':
            case 'action':

                return $item->$column_name;

            default:


                return print_r($item,true); //Show the whole array for troubleshooting purposes

        }

    }



    /** ************************************************************************    
     * @see WP_List_Table::::single_row_columns()
     * @param array $item A singular item (one full row's worth of data)
     * @return string Text to be placed inside the column <td> (movie title only)
     **************************************************************************/
/*
    function column_id($item){
        //Build row actions
        $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&id=%s">Edit</a>',$_REQUEST['page'],'edit',$item->id),
            //'delete'    => sprintf('<a href="?page=%s&action=%s&id[]=%s">Delete</a>',$_REQUEST['page'],'delete',$item->id),
        );

        return sprintf('%1$s %2$s',

             sprintf($item->id),

             $this->row_actions($actions)

        );

    } */

    function column_pdf_title($item){
        //Build row actions
        $actions = array(
            'edit'      => sprintf('<a href="?page=%s&id=%s">Edit</a>','edit_pdfs',$item->id),
            //'delete'    => sprintf('<a href="?page=%s&action=%s&id[]=%s">Delete</a>',$_REQUEST['page'],'delete',$item->id),
        );

        return sprintf('%1$s %2$s',

             sprintf($item->pdf_title),

             $this->row_actions($actions)

        );

    } 

   

    /** ************************************************************************

     * @see WP_List_Table::::single_row_columns()
     * @param array $item A singular item (one full row's worth of data)
     * @return string Text to be placed inside the column <td> (movie title only)

     **************************************************************************/
    function column_cb($item){

        return sprintf(

            '<input type="checkbox" name="%1$s[]" value="%2$s" />',

            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")

            /*$2%s*/ $item->id                //The value of the checkbox should be the record's id

        );



    }


    function column_action($item){
        echo '<a href="'.site_url().'/wp-admin/admin.php?page=pdfs&action=delete&id='.$item->id.'">Delete</a>';
    }


    /** ************************************************************************
     * @see WP_List_Table::::single_row_columns()
     * @return array An associative array containing column information: 'slugs'=>'Visible Titles'
     **************************************************************************/

    function get_columns(){

        $columns = array(

            //'cb'  => '<input type="checkbox" />', //Render a checkbox instead of text
			//'id' => 'id',
            'pdf_title' => 'PDF Title',
            'site_cost' => 'Site Cost',
            'property' => 'Property',
            'acknowledgement'=>'Acknowledgement',
            'action'  => 'Action'

        );

        return $columns;

    }


    /** ************************************************************************

     * @return array An associative array containing all the columns that should be sortable: 'slugs'=>array('data_values',bool)

     **************************************************************************/

    function get_sortable_columns() {
        $sortable_columns = array(			
			//'id'     => array('id',false),
            'pdf_title'     => array('pdf_title',false),     //true means its already sorted
			
        );

        return $sortable_columns;

    }

    



    /** ************************************************************************

     * @return array An associative array containing all the bulk actions: 'slugs'=>'Visible Titles'

     **************************************************************************/


/*
    function get_bulk_actions() {

        $actions = array(
			'delete'    => 'Delete'
        );
        return $actions;

    } */

    
    /** ************************************************************************

     * @see $this->prepare_items()

     **************************************************************************/



    function process_bulk_action(){
        echo "hello";
        exit;
    } 



    /** ************************************************************************

     * @uses $this->_column_headers
     * @uses $this->items
     * @uses $this->get_columns()
     * @uses $this->get_sortable_columns()
     * @uses $this->get_pagenum()
     * @uses $this->set_pagination_args()

     **************************************************************************/

    function prepare_items() {

		$per_page = 25; 

		$columns = $this->get_columns();
		$hidden = array();

		$sortable = $this->get_sortable_columns();
		$this->_column_headers = array($columns, $hidden, $sortable);
		//$this->process_bulk_action();                

	    global $wpdb;

		$orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'id'; //If no sort, default to title
	    $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc'; //If no order, default to asc
		
        $query="SELECT * FROM {$wpdb->prefix}property_pdf where 1 order by ". $orderby . " " .$order;
        
        //echo $query="SELECT {$wpdb->prefix}property_pdf.*,{$wpdb->prefix}pdf_property_lookup.* FROM {$wpdb->prefix}property_pdf LEFT JOIN {$wpdb->prefix}pdf_property_lookup ON {$wpdb->prefix}property_pdf.id={$wpdb->prefix}pdf_property_lookup.property_pdf_id "; 


	    $datas = $wpdb->get_results($query);
        
        foreach($datas as $row){
            //get property
            $q= "SELECT {$wpdb->prefix}pdf_property_lookup.property_id FROM {$wpdb->prefix}pdf_property_lookup where {$wpdb->prefix}pdf_property_lookup.property_pdf_id= ".$row->id." ";
            $ids=$wpdb->get_results($q);
            $property_str='';
            foreach($ids as $idrow){
                $property_str.=get_the_title ($idrow->property_id)."|";
            }
            
            
            $row->property= $property_str;
            //$row->site_cost= $row->site_area."m<sup>2</sup> | ".$row->site_cost;
            $data[]=$row;
            
            
        } 

		//echo "<pre>";
        //print_r($data);

		//echo var_dump($data);

        $current_page = $this->get_pagenum();  
        $total_items = count($data); 
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
        $this->items = $data;

        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages

        ) );



    }

}

?>