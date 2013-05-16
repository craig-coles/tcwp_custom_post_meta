<?php 

	class TCWPCustomPostMeta {
	
		private $customPostMetaID = "";
		private $customPostMetaTitle = "";
		private $customPostMetaInputType = "text";
		private $customPostMetaPostType = "post";
		private $customPostMetaContext = "advanced";
		private $customPostMetaPriority = "default";
		private $customPostMetaCallBackArgs = array();
		private $customPostMetaLabel = "";
		private $beforeSaveFilter = "sanitize_text_field";
		
		public function init(){
			$this->__construct();
		}
		
		public function __construct($params = array()){
			if($this->setPostMetaParams($params)){
				add_action( 'load-post.php', array($this,'TCWPCustomPostMeta::tc_post_meta_boxes_setup') );
				add_action( 'load-post-new.php', array($this,'TCWPCustomPostMeta::tc_post_meta_boxes_setup') );	
			} else {
				echo "dude check your params, the required ones are missing!";
			}
			
		}
		
		private function setPostMetaParams($params){
			if(!isset($params['id']) || !isset($params['title'])){
				return false;
			} else {
				$this->customPostMetaID = $params['id'];
				$this->customPostMetaTitle = $params['title'];
				$this->customPostMetaInputType = $params['input_type'] ? $params['input_type'] : $this->customPostMetaInputType;
				$this->customPostMetaPostType = $params['post_type'] ? $params['post_type'] : $this->customPostMetaPostType;
				$this->customPostMetaContext = $params['context'] ?  $params['context'] : $this->customPostMetaContext;
				$this->customPostMetaPriority = $params['priority'] ? $params['priority'] : $this->customPostMetaPriority;
				$this->customPostMetaCallBackArgs = $params['callback_args'] ? $params['callback_args'] : $this->customPostMetaCallBackArgs;
				$this->customPostMetaLabel = $params['label'] ? $params['label'] : $this->customPostMetaLabel;
				$this->beforeSaveFilter = $params['before_save_filter'] ? $params['before_save_filter'] : $this->beforeSaveFilter; 
				return true;
			}
			
			
			
		}
		
		function render_custom_meta_box( $object, $box ) { 
			//TODO determine what type of custom post type object to output here (input, checkbox, select, radio, textarea)
			if($this->customPostMetaInputType == "text") {
				?>
			
				<?php wp_nonce_field( basename( __FILE__ ), $this->customPostMetaID.'_nonce' ); ?>
			
					<p>
						<label for="<?php echo $this->customPostMetaID;?>"><?php echo $this->customPostMetaLabel; ?></label>
						<br />
						<input class="widefat" type="text" name="<?php echo $this->customPostMetaID;?>" id="<?php echo $this->customPostMetaID;?>" value="<?php echo esc_attr( get_post_meta( $object->ID, $this->customPostMetaID, true ) ); ?>" size="30" />
					</p>
				<?php 
			} else if($this->customPostMetaInputType == "checkbox"){
				?>
					<?php wp_nonce_field( basename( __FILE__ ), $this->customPostMetaID.'_nonce' ); ?>
			
					<p>
						<input type="checkbox" name="<?php echo $this->customPostMetaID;?>" id="<?php echo $this->customPostMetaID;?>" <?php echo get_post_meta( $object->ID, $this->customPostMetaID, true ) ? "checked" : "";?>  />
						<label for="<?php echo $this->customPostMetaID;?>"><?php echo $this->customPostMetaLabel; ?></label>
					</p>
				<?php
			} 
			
			
		}
	
		/* Save the meta box's post metadata. */
		function save_custom_meta( $post_id, $post ) {
			
			/* Verify the nonce before proceeding. */
			if ( !isset( $_POST[$this->customPostMetaID.'_nonce'] ) || !wp_verify_nonce( $_POST[$this->customPostMetaID.'_nonce'], basename( __FILE__ ) ) )
				return $post_id;
			
			/* Get the post type object. */
			$post_type = get_post_type_object( $post->post_type );
		
			/* Check if the current user has permission to edit the post. */
			if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
				return $post_id;
			
			if($this->customPostMetaInputType == "checkbox"){
				$new_meta_value = isset( $_POST[$this->customPostMetaID] ) ? 1 : 0;
			} else {
				/* Get the posted data and sanitize it */
				$new_meta_value = ( isset( $_POST[$this->customPostMetaID] ) ? call_user_func($this->beforeSaveFilter,$_POST[$this->customPostMetaID]) : '' );
			}
			
			
			/* Get the meta key. */
			$meta_key = $this->customPostMetaID;
		
			/* Get the meta value of the custom field key. */
			$meta_value = get_post_meta( $post_id, $meta_key, true );
			
			/* If a new meta value was added and there was no previous value, add it. */
			if ( $new_meta_value && '' == $meta_value )
				add_post_meta( $post_id, $meta_key, $new_meta_value, true );
		
			/* If the new meta value does not match the old value, update it. */
			elseif ( $new_meta_value && $new_meta_value != $meta_value )
				update_post_meta( $post_id, $meta_key, $new_meta_value );
		
			/* If there is no new meta value but an old value exists, delete it. */
			elseif ( '' == $new_meta_value && $meta_value )
				delete_post_meta( $post_id, $meta_key, $meta_value );
		}
	
		function tc_add_post_meta_boxes(){
			
			add_meta_box(
				$this->customPostMetaID,			
				$this->customPostMetaTitle,		
				array($this,'TCWPCustomPostMeta::render_custom_meta_box'),		
				$this->customPostMetaPostType,					
				$this->customPostMetaContext,					
				$this->customPostMetaPriority,					
				$this->customPostMetaCallBackArgs
			);
		}
	
		function tc_post_meta_boxes_setup(){
			add_action('add_meta_boxes',array($this,'TCWPCustomPostMeta::tc_add_post_meta_boxes'));
			add_action( 'save_post', array($this,'TCWPCustomPostMeta::save_custom_meta'), 10, 2 );
		}
	
		
		
	}



?>