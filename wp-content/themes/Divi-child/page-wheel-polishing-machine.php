<?php

get_header();

$is_page_builder_used = et_pb_is_pagebuilder_used( get_the_ID() );

?>

<div id="main-content">

<?php if ( ! $is_page_builder_used ) : ?>

	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">

<?php endif; ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php if ( ! $is_page_builder_used ) : ?>

					<h1 class="entry-title main_title"><?php the_title(); ?></h1>
				<?php
					$thumb = '';

					$width = (int) apply_filters( 'et_pb_index_blog_image_width', 1080 );

					$height = (int) apply_filters( 'et_pb_index_blog_image_height', 675 );
					$classtext = 'et_featured_image';
					$titletext = get_the_title();
					$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
					$thumb = $thumbnail["thumb"];

					if ( 'on' === et_get_option( 'divi_page_thumbnails', 'false' ) && '' !== $thumb )
						print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height );
				?>

				<?php endif; ?>

					<div class="entry-content">
						<h1 id="jem_heading">The JEM Wheel Machine</h1>
            <div class="product_container">
               <!-- Settings Menu -->
               <section class="settings">
                  <div class="settings__panel">
                     <div class="settings__panel-wrap">
                        <div class="settings__btn-holder settings__btn-holder--right">
                           <button class="settings__add-btn" title="Add new slide">
                              <i class="icon-plus"></i>
                           </button>
                        </div>

                        <ul class="settings__list">
                           <li class="settings__item settings__item--header">
                              <span class="settings__column--header">NO</span>
                              <span class="settings__column--header">X-axis %</span>
                              <span class="settings__column--header">Y-axis %</span>
                              <span class="settings__column--header">Zoom</span>
                              <span class="settings__column--header">Carousel Title</span>
                              <span class="settings__column--header">Carousel Description</span>
                           </li>

                           <li class="settings__item js-item" id="setting-0">
                              <span class="settings__column js-no">#1</span>
                              <span class="settings__column">
                                 <input type="number" class="settings__number js-input-x" min="0" max="100" value="50">
                              </span>
                              <span class="settings__column">
                                 <input type="number" class="settings__number js-input-y" min="0" max="100" value="50">
                              </span>
                              <span class="settings__column">
                                 <select name="zoom" id="select-zoom" class="settings__select js-select">
                                    <option value="100" selected>1</option>
                                    <option value="150">1.5</option>
                                    <option value="200">2</option>
                                    <option value="250">2.5</option>
                                    <option value="300">3</option>
                                 </select>
                              </span>
                              <span class="settings__column">
                                 <input type="text" class="settings__text js-input-title" value="The Evolution Of The Legendary" size="30">
                              </span>
                              <span class="settings__column">
                                 <input type="text" class="settings__text js-input-description" size="30" maxlength="100" value="It's combine fresh style and sporty personality with cutting-edge technology.">
                              </span>
                              <span class="settings__column">
                                 <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/829617/delete_icon.png" alt="" class="settings__delete-icon js-delete-btn">
                              </span>
                           </li>
                           <li class="settings__item js-item" id="setting-1">
                              <span class="settings__column js-no">#2</span>
                              <span class="settings__column">
                                 <input type="number" class="settings__number js-input-x" min="0" max="100" value="65">
                              </span>
                              <span class="settings__column">
                                 <input type="number" class="settings__number js-input-y" min="0" max="100" value="5">
                              </span>
                              <span class="settings__column">
                                 <select name="zoom" id="select-zoom" class="settings__select js-select">
                                    <option value="100">1</option>
                                    <option value="150">1.5</option>
                                    <option value="200">2</option>
                                    <option value="250">2.5</option>
                                    <option value="300" selected>3</option>
                                 </select>
                              </span>
                              <span class="settings__column">
                                 <input type="text" class="settings__text js-input-title" value="Genuine Parts Are Better" size="30">
                              </span>
                              <span class="settings__column">
                                 <input type="text" class="settings__text js-input-description" size="30" maxlength="100" value="Simple and easy to use mechanical antitheft system which locks the handlebar to the vehicle with a fastener system fixed to the body.">
                              </span>
                              <span class="settings__column">
                                 <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/829617/delete_icon.png" alt="" class="settings__delete-icon js-delete-btn">
                              </span>
                           </li>
                           <li class="settings__item js-item" id="setting-2">
                              <span class="settings__column js-no">#3</span>
                              <span class="settings__column">
                                 <input type="number" class="settings__number js-input-x" min="0" max="100" value="10">
                              </span>
                              <span class="settings__column">
                                 <input type="number" class="settings__number js-input-y" min="0" max="100" value="85">
                              </span>
                              <span class="settings__column">
                                 <select name="zoom" id="select-zoom" class="settings__select js-select">
                                    <option value="100">1</option>
                                    <option value="150">1.5</option>
                                    <option value="200">2</option>
                                    <option value="250" selected>2.5</option>
                                    <option value="300">3</option>
                                 </select>
                              </span>
                              <span class="settings__column">
                                 <input type="text" class="settings__text js-input-title" value="Highest Performing Engine" size="30">
                              </span>
                              <span class="settings__column">
                                 <input type="text" class="settings__text js-input-description" size="30" maxlength="100" value="With its single engine capacity, is definitely the long range touring.">
                              </span>
                              <span class="settings__column">
                                 <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/829617/delete_icon.png" alt="" class="settings__delete-icon js-delete-btn">
                              </span>
                           </li>
                           <li class="settings__item js-item" id="setting-3">
                              <span class="settings__column js-no">#4</span>
                              <span class="settings__column">
                                 <input type="number" class="settings__number js-input-x" min="0" max="100" value="100">
                              </span>
                              <span class="settings__column">
                                 <input type="number" class="settings__number js-input-y" min="0" max="100" value="100">
                              </span>
                              <span class="settings__column">
                                 <select name="zoom" id="select-zoom" class="settings__select js-select">
                                    <option value="100">1</option>
                                    <option value="150">1.5</option>
                                    <option value="200" selected>2</option>
                                    <option value="250">2.5</option>
                                    <option value="300">3</option>
                                 </select>
                              </span>
                              <span class="settings__column">
                                 <input type="text" class="settings__text js-input-title" value="Safty First" size="30">
                              </span>
                              <span class="settings__column">
                                 <input type="text" class="settings__text js-input-description" size="30" maxlength="100" value="The sensor system installed on the front wheel is able to prevent locking and guarantee stability & effective braking.">
                              </span>
                              <span class="settings__column">
                                 <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/829617/delete_icon.png" alt="" class="settings__delete-icon js-delete-btn">
                              </span>
                           </li>
                        </ul>

                        <div class="settings__btn-holder">
                           <button class="settings__submit-btn">See the Magic</button>
                        </div>
                     </div>
                  </div>
                  <div class="settings__btn-holder">
                     <button class="settings__edit-btn">SETTINGS</button>
                  </div>
               </section>

               <section class="feature-wrap">
                  <!--  Bounce Loading Animation -->
                  <div class="bounce-loading">
                     <div class="bounce-loading__dot bounce-loading__dot--1"></div>
                     <div class="bounce-loading__dot bounce-loading__dot--2"></div>
                     <div class="bounce-loading__dot bounce-loading__dot--3"></div>
                  </div>

                  <!--  Zooming Box -->
                  <div class="feature-zoom">
                     <div class="feature-zoom__img" style="background-image: url('https://trello-attachments.s3.amazonaws.com/5bd5353da03a0729178427fb/5c49dcfa37508824fb3a3a8d/b357f5c64abc6e6e440d736647a9db9e/jem-1.jpg');"></div>
                  </div>

                  <!--  Carousel -->
                  <div class="feature-carousel">
                     <div class="feature-carousel__list"></div>
                  </div>
               </section>
            </div>






					<?php
						the_content();

						if ( ! $is_page_builder_used )
							wp_link_pages( array( 'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'Divi' ), 'after' => '</div>' ) );
					?>
					</div> <!-- .entry-content -->

				<?php
					if ( ! $is_page_builder_used && comments_open() && 'on' === et_get_option( 'divi_show_pagescomments', 'false' ) ) comments_template( '', true );
				?>

				</article> <!-- .et_pb_post -->

			<?php endwhile; ?>

<?php if ( ! $is_page_builder_used ) : ?>

			</div> <!-- #left-area -->

			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->

<?php endif; ?>

</div> <!-- #main-content -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"></script>
<script src="https://goshineon.com/wp-content/themes/Divi-child/js/main.js"></script>
<script
  src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
  integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
  crossorigin="anonymous"></script>
<script type="text/javascript">
const scooter = (() => {

   //
   // Variables
   //
   const settings_list = document.querySelector('.settings__list');
   const carousel = document.querySelector('.feature-carousel');
   const add_slide_btn = document.querySelector('.settings__add-btn');
   const submit_btn = document.querySelector('.settings__submit-btn');
   const settings_btn = document.querySelector('.settings__edit-btn');
   let storage = []; // Save setting's value
   let panel_is_open = false;


   //
   // Methods
   //
   const calculateSettingsListNumber = () => {
      const settings_items = settings_list.querySelectorAll('.js-item');
      return settings_items.length;
   }

   const addSettingsItem = () => {
      const list_number = calculateSettingsListNumber();
      const li = document.createElement('li');
      const template = `
         <span class="settings__column js-no">#${list_number + 1}</span>
         <span class="settings__column">
            <input type="number" class="settings__number js-input-x" min="0" max="100" value=50>
         </span>
         <span class="settings__column">
            <input type="number" class="settings__number js-input-y" min="0" max="100" value=50>
         </span>
         <span class="settings__column">
            <select name="zoom" id="select-zoom" class="settings__select js-select">
               <option value="100">1</option>
               <option value="150">1.5</option>
               <option value="200" selected>2</option>
               <option value="250">2.5</option>
               <option value="300">3</option>
            </select>
         </span>
         <span class="settings__column">
            <input type="text" class="settings__text js-input-title" value="" size="30">
         </span>
         <span class="settings__column">
            <input type="text" class="settings__text js-input-description" size="30" maxlength="100" value="">
         </span>
         <span class="settings__column js-delete-btn">
            <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/829617/delete_icon.png" alt="" class="settings__delete-icon js-delete-btn">
         </span>`;

      li.classList.add('settings__item', 'js-item');
      li.id = `setting-${list_number}`;
      li.innerHTML = template;
      settings_list.appendChild(li);
   };

   const saveSettingsValue = () => {
      storage = []; // Remember to clear all the values

      const lists = document.querySelectorAll('.js-item');

      lists.forEach((list, index) => {
         const x_value = list.querySelector('.js-input-x').value;
         const y_value = list.querySelector('.js-input-y').value;
         const select_value = list.querySelector('.js-select').value;
         const title_value = list.querySelector('.js-input-title').value;
         const description_value = list.querySelector('.js-input-description').value;
         const data = {
            x: x_value,
            y: y_value,
            zoom: select_value,
            title: title_value,
            description: description_value,
         }

         storage[index] = data;
      });

      displayLoadingEffect();
   };

   const displayLoadingEffect = () => {
      document.querySelector('.feature-wrap').classList.add('is-loading');

      renderCarouselData(isClear = true);
      setTimeout(removeLoadingEffect, 1000);
      toggleSettings();
   };

   const removeLoadingEffect = () => {
      document.querySelector('.feature-wrap').classList.remove('is-loading');
   };

   const clearCarousel = (slickObj) => {
      let count = slickObj.slick("getSlick").slideCount;

      // Remove all slides
      while (count > 0) {
         slickObj.slick('slickRemove', 0);
         count--;
      }
   };

   const renderCarouselData = (isClear = false) => {
      const carousel_list = $('.feature-carousel__list');

      if (isClear) {
         clearCarousel(carousel_list);
      }

      let carousel_content = storage.map((data, index) => {
         return `
            <div class="feature-carousel__item">
               <p class="feature-carousel__label">
                  <span class="feature-carousel__label-text js-item-number">${index + 1} / ${storage.length} </span>
                  <span class="feature-carousel__label-line"></span>
                  <span class="feature-carousel__label-text">${data.title}</span>
               </p>
               <p class="feature-carousel__description">${data.description}</p>
            </div>`;
      }).join('');

      carousel_list.slick('slickAdd', carousel_content);

      moveZoomPosition();
   };

   const moveZoomPosition = () => {
      const current_index = document.querySelector('.slick-active').dataset.slickIndex;
      const img = document.querySelector('.feature-zoom__img');

      img.style.backgroundPosition = `${storage[current_index].x}% ${storage[current_index].y}%`;
      img.style.backgroundSize = `${storage[current_index].zoom}%`;
   };

   const deleteSettingsItem = (e) => {
      const target = e.target;
      if (!target.classList.contains('js-delete-btn')) return;

      const target_item = target.closest('.js-item');
      const target_item_id = target_item.id.split('-')[1];
      const settings_list = target_item.parentElement;

      settings_list.removeChild(target_item);

      updateListNumberAfterDelete();
   };

   const updateListNumberAfterDelete = () => {
      const no_columns = document.querySelectorAll('.js-no');

      no_columns.forEach((column, index) => {
         column.innerHTML = `#${index + 1}`;
      });
   };

   const checkInputNumberValues = (e) => {
      const target = e.target;
      const input_numbers = target.classList.contains('settings__number');

      if (!input_numbers) return;

      let input_value = target.value;

      if (input_value < 0 || input_value > 100) {
         alert('The value must be between 1 ~ 100');
         e.target.value = 1;
      };
   };

   const updatePanelMoveUpDistance = (SettingsObj) => {
      const panel = SettingsObj.querySelector('.settings__panel');
      const panel_height = panel.clientHeight;

      return panel_height;
   };

   const toggleSettings = () => {
      const settings = document.querySelector('.settings');
      const distance = updatePanelMoveUpDistance(settings);

      panel_is_open = !panel_is_open;

      if (panel_is_open) {
         settings.style.transform = `translateY(${0})`;
      } else {
         settings.style.transform = `translateY(${-distance}px)`;
      }
   };

   const callCarousel = () => {
      const carousel_list = $('.feature-carousel__list');
      const slick_options = {
         slidesToShow: 1,
         infinite: false,
         prevArrow: '<button class="feature-carousel__btn feature-carousel__btn--prev js-trigger-btn"></button>',
         nextArrow: '<button class="feature-carousel__btn feature-carousel__btn--next js-trigger-btn"></button>',
      }

      carousel_list.slick(slick_options);
   };

   const setDefaultCarouselContent = () => {
      storage = [
         {x: "50", y: "50", zoom: "100", title: "JEM Wheel Machine", description: "For a superior shine on your wheels."},
         {x: "60", y: "40", zoom: "500", title: "Machine Controls", description: "Change your speed, tilt, and more with control right at your fingertips."},
         {x: "3", y: "80", zoom: "500", title: "Anti-Static Foot Pads", description: "Dissipate the excess static created by the polishing process, making the machine safer to operate."},
         {x: "30", y: "50", zoom: "200", title: "Adjustable Handle", description: "Handle position can be moved up, down, forward, and backward."},
        {x: "90", y: "15", zoom: "300", title: "Phase Options", description: "Both single and three phase options available."},
         {x: "30", y: "42", zoom: "500", title: "Variable Speed Arbor", description: "Helps save product and time by allowing the operator to fine tune the process."}
      ]

      renderCarouselData();
   };

   const is_desktop = () => {
      const minimum_width = 1024;
      return Math.min(document.documentElement.clientWidth, window.innerWidth, screen.width) > minimum_width;
   };

   const setZoomBoxWidth = () => {
      const zoom_box = document.querySelector('.feature-zoom');
      const percentage = 0.7;

      if (is_desktop()) {
         zoom_box.style.width = `${window.innerWidth * percentage}px`;
         zoom_box.style.height = `${window.innerHeight}px`;
      }
      else {
         zoom_box.style.width = '';
         zoom_box.style.height = '';
      }
   };

   const init = () => {
      callCarousel();
      setDefaultCarouselContent();
      setZoomBoxWidth();
   };


   //
   // Inits & Event Listeners
   //
   init();

   add_slide_btn.addEventListener('click', addSettingsItem);
   settings_list.addEventListener('click', deleteSettingsItem);
   submit_btn.addEventListener('click', saveSettingsValue);
   carousel.addEventListener('click', (e) => {
      const has_nav_btns = e.target.classList.contains('js-trigger-btn');
      if (!has_nav_btns) return;

      moveZoomPosition();
   });
   settings_list.addEventListener('keyup', checkInputNumberValues);
   settings_btn.addEventListener('click', toggleSettings);

   window.addEventListener('resize', setZoomBoxWidth);
})();



</script>
<?php

get_footer();
