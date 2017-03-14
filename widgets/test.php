<?php
/**
* Adds Foo_Widget widget.
*/
class Foo_Widget extends WP_Widget {
/**
* Register widget with WordPress.
*/
function __construct() {
parent::__construct(
‘foo_widget’, // Base ID
esc_html__( ‘wp dropdown categories’, ‘text_domain’ ), // Name
array( ‘description’ => esc_html__( ‘A Foo Widget’, ‘text_domain’ ), ) // Args
);
}
/**
* Front-end display of widget.
*
* @see WP_Widget::widget()
*
* @param array $args Widget arguments.
* @param array $instance Saved values from database.
*/
public function widget( $args, $instance ) {
echo $args[‘before_widget’];
if ( ! empty( $instance[‘title’] ) ) {
echo $args[‘before_title’] . apply_filters( ‘widget_title’, $instance[‘title’] ) . $args[‘after_title’];
}
echo esc_html__( ‘Hello, World!’, ‘text_domain’ );
echo $args[‘after_widget’];
}
/**
* Back-end widget form.
*
* @see WP_Widget::form()
*
* @param array $instance Previously saved values from database.
*/
public function form( $instance ) {
$title = ! empty( $instance[‘title’] ) ? $instance[‘title’] : esc_html__( ‘New title’, ‘text_domain’ );
$post_slider_cat_id = isset( $instance[‘post-slider-cat’] ) ? $instance[‘post-slider-cat’] : ”;
?>

wp_dropdown_categories( array(
‘show_option_all’ => esc_html__( ‘Select Category’, ‘text_domain’ ),
‘show_count’ => true,
‘selected’ => absint( $post_slider_cat_id ),
‘name’ => esc_attr( $this->get_field_name( ‘post-slider-cat’ ) ),
‘id’ => esc_attr( $this->get_field_id(‘post-slider-category’ ) ),
‘class’ => ‘widefat’
) );
?>
}
/**
* Sanitize widget form values as they are saved.
*
* @see WP_Widget::update()
*
* @param array $new_instance Values just sent to be saved.
* @param array $old_instance Previously saved values from database.
*
* @return array Updated safe values to be saved.
*/
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance[‘title’] = ( ! empty( $new_instance[‘title’] ) ) ? strip_tags( $new_instance[‘title’] ) : ”;
//update selected category on database
$instance[ ‘post-slider-cat’ ] = ( ! empty( $new_instance[‘post-slider-cat’] ) ) ? absint( $new_instance[‘post-slider-cat’] ) : ”;
return $instance;
}
} // class Foo_Widge
//register the class on widget_init
function register_foo_widget() {
register_widget( ‘Foo_Widget’ );
}
add_action( ‘widgets_init’, ‘register_foo_widget’ );