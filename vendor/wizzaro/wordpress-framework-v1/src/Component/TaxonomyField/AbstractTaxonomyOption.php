<?php
namespace Wizzaro\WPFramework\v1\Component\TaxonomyField;

use Wizzaro\WPFramework\v1\AbstractSingleton; 

abstract class AbstractTaxonomyOption extends AbstractSingleton {
    
    protected $_option_name = '';
    
    protected $_default_value = '';
    
    protected $_taxonomies;
    
    protected $_form_field_name;
    
    protected function _get_label() {
        return '';
    }
    
    protected function _get_description() {
        return '';
    }
    
    protected function _render_form_field( $value, $taxonomy ) {
        
    }
    
    protected function _validate_value( $value, $term_id ) {
        return false;
    }
    
    //----------------------------------------------------------------------------------------------------
    // automatization functions
    
    protected function _parse_option_name( $term_id ) {
        return $this->_option_name . _ . $term_id;
    }
    
    public function get_option( $term_id ) {
        return get_option( $this->_parse_option_name( $term_id ), $this->_default_value );
    }
    
    protected function _parse_actions( $tax_name ) {
        //add field actions
        add_action( $tax_name . '_add_form_fields', array( $this, 'render_form_field_for_add' ) );
        add_action( $tax_name . '_edit_form_fields', array( $this, 'render_form_field_for_edit' ), 10, 2 );
        
        //save actions
        add_action( 'create_' . $tax_name, array( $this, 'save' ) );
        add_action( 'edit_' . $tax_name, array( $this, 'save' ) );
         
        //delete actions
        add_action( 'delete_' . $tax_name, array( $this, 'delete' ) );
    }
    
    public function init_form_field() {
        if ( is_string( $this->_taxonomies ) ) {
            $this->_parse_actions( $this->_taxonomies );
        } elseif ( is_array( $this->_taxonomies ) ) {
            foreach( $this->_taxonomies as $t ) {
                $this->_parse_actions( $t );
            } 
        } else {
            $taxs = get_taxonomies( array( 'public' => true ) );
            
            foreach( $taxs as $t ) {
                $this->_parse_actions( $t );
            }    
        }
    }
    
    public function render_form_field_for_add( $taxonomy ) {
        ?>
        <div class="form-field">
            <label for="<?php echo $this->_form_field_name; ?>"><?php echo $this->_get_label(); ?></label>
            <?php
                $this->_render_form_field( $this->_default_value, $taxonomy );
                
                $description = $this->_get_description();
                
                if ( mb_strlen( $description ) > 0 ) {
                    echo '<p class="description">' . $description . '</p>';
                }
            ?>
        </div>
        <?php
    }
    
    public function render_form_field_for_edit( $tag, $taxonomy ) {
        ?>
        <tr class="form-field">
            <th scope="row" valign="top">
                <label for="<?php echo $this->_form_field_name; ?>"><?php echo $this->_get_label(); ?></label>
            </th>
            <td>
                <?php
                $this->_render_form_field( $this->get_option( $tag->term_id ), $taxonomy ); 
                
                $description = $this->_get_description();
                
                if ( mb_strlen( $description ) > 0 ) {
                    echo '<p class="description">' . $description . '</p>';
                }
                ?>
            </td>
        </tr>
        <?php
    }
    
    public function save( $term_id ) {
        
        if ( isset( $_POST[$this->_form_field_name] ) ) {
        
            $value = $this->_validate_value( $_POST[$this->_form_field_name], $term_id );
            
            if ( $value !== false ) {
                $option_name = $this->_parse_option_name( $term_id );
                
                if( ! update_option( $option_name, $value ) ) {
                    add_option( $option_name, $value );
                }
            }
        }
    }
    
    public function delete( $term_id ) {
        delete_option( $this->_parse_option_name( $term_id ) );
    }
}