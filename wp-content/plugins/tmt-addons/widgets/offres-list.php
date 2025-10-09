<?php
class Elementor_Offres_List extends \Elementor\Widget_Base {

	public function get_name(): string {
		return 'offres_list';
	}

	public function get_title(): string {
		return esc_html__( 'Offres', 'elementor-addon' );
	}

	public function get_icon(): string {
		return 'eicon-code';
	}

	public function get_categories(): array {
		return [ 'tmt-widgets' ];
	}

	public function get_keywords(): array {
		return [ 'tmt', 'offres' ];
	}

	protected function register_controls() {
      $this->start_controls_section(
          'section_content',
          [
              'label' => __( 'Content', 'custom-elementor-widgets' ),
          ]
      );
      
      $this->end_controls_section();
  }
  protected function render() {
      
      $args = [
            'post_type'      => 'offre',
            'posts_per_page' => '10',
            'post_status'    => 'publish',
        ];

        $query = new \WP_Query( $args );

        if ( $query->have_posts() ) {
            echo '<div class="offre-listing">';
            while ( $query->have_posts() ) {
                $query->the_post();
                $contrat =  get_field( 'contrat', get_the_ID ());

                $specialites = '';
                $idSpecialites =  get_field( 'specialite', get_the_ID ());
                if(is_array($idSpecialites) && sizeof($idSpecialites)>0){
                    foreach ($idSpecialites as $idSpecialite) {
                        $spec = get_term_by('id', $idSpecialite, 'specialite');
                        $image = get_field('image', $spec);
                        $specialites .= '<img src="' . $image['url'] . '" alt="' . $image['alt'] . '">';

                    }

                }
                
                ?>
                <div class="offre-list-item" id="offre-list-<?php the_ID(); ?>" >
                    <a href="<?php the_permalink(); ?>">
                        <div class="oi-title"><?php the_title(); ?></div>
                        <div class="oi-infos">
                            <div class="oi-specialite"><?php echo $specialites ?></div>
                            <div class="oi-contrat"><?php echo $contrat?></div>
                            <div class="oi-readmore">+</div>
                        </div>
                    </a>
                </div>
                <?php
            }
            echo '</div>';
            wp_reset_postdata();
        } else {
            echo '<p>' . esc_html__( 'Aucun post trouvé pour ce Custom Post Type.', 'textdomain' ) . '</p>';
        }
  }
}


