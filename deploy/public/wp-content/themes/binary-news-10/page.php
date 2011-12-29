<?php get_header(); ?>

<div class="main_content">
	<?php get_sidebar(); ?>
	<div class="sd_left">
		<div class="text_padding">	
						
		<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>

		<div class="post" id="post-<?php the_ID(); ?>">

		<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2>


			<div class="entry">

			<?php the_content('Read the rest of this entry &raquo;'); ?>

			</div>

		
		</div>

	

		<?php endwhile; ?>

		<p align="center"><?php next_posts_link('&laquo; Previous Entries') ?> <?php previous_posts_link('Next Entries &raquo;') ?></p>

		<?php else : ?>

		<h2 align="center">Not Found</h2>

		<p align="center">Sorry, but you are looking for something that isn't here.</p>

		<?php endif; ?>


		</div>
	</div>
	
	<?php get_footer(); ?>
			
				
			</div>
	</div>
	
</body>
</html>
