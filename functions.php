<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementorChild
 */
/**
 * Load child theme css and optional scripts
 *
 * @return void
 */

//=====================================================================================
//                      Adds Bootstrap to the WP Theme
//=====================================================================================


function themebs_enqueue_styles() {

  wp_enqueue_style( 'bootstrap', 'https://www.contractcars.com/wp-content/themes/hello-elementor-child/css/bootstrap.min.css' );
}
add_action( 'wp_enqueue_scripts', 'themebs_enqueue_styles');

function themebs_enqueue_scripts() {
  wp_enqueue_script( 'bootstrap', 'https://www.contractcars.com/wp-content/themes/hello-elementor-child/js/vendor/bootstrap.bundle.min.js', array( 'jquery' ) );
}
add_action( 'wp_enqueue_scripts', 'themebs_enqueue_scripts');




add_theme_support( 'post-thumbnails' );

function hello_elementor_child_enqueue_scripts() {
    wp_enqueue_style('hello-elementor-child-style', get_stylesheet_directory_uri() . '/style.css',
        [
            'hello-elementor-theme-style',
        ],
        '1.0.0'
    );
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_scripts' );



// add_action( 'after_setup_theme', 'travel_eye_pro_setup' );
 
// function travel_eye_pro_setup() {
//     // remove_theme_support( 'wc-product-gallery-zoom' );
//     // add_theme_support( 'wc-product-gallery-lightbox' );
//     // add_theme_support( 'wc-product-gallery-slider' );
//     remove_theme_support( 'wc-product-gallery-zoom' );
//     remove_theme_support( 'wc-product-gallery-lightbox' );
//     remove_theme_support( 'wc-product-gallery-slider' );
// }


//Toms functions


// Removes stuff from the website

    // Remove price 

add_filter( 'woocommerce_get_price_html', function( $price ) {
    if ( is_admin() ) return $price;

    return '';
} );

remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart');
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title');


    // Remove Backend Woocommerce boxes 

add_action( 'add_meta_boxes_product', 'bbloomer_remove_metaboxes_edit_product', 9999 );
 
function bbloomer_remove_metaboxes_edit_product() {
 
        // e.g. remove short description
        remove_meta_box( 'postexcerpt', 'product', 'normal' );
 
        // e.g. remove product tags
        remove_meta_box( 'tagsdiv-product_tag', 'product', 'side' );
 
}

function remove_product_editor() {
  remove_post_type_support( 'product', 'editor' );
}
add_action( 'init', 'remove_product_editor' );


// ====================================================================================
//                               Update Woocommerce price
// ====================================================================================


function wcproduct_set_attributes($id) {

    global $product;

    $currentprice = get_field('main_business_price');

    // Now update the post with its new attributes
    wc_delete_product_transients($post_id);
    update_post_meta($id, '_regular_price', $currentprice);

}

// After inserting post
add_action( 'save_post_product', 'wcproduct_set_attributes', 10);

// ====================================================================================
//                               Start of the custom Archive
// ====================================================================================
    //Adds the key features logo

add_action('woocommerce_before_shop_loop_item_title','tjames_adds_key_feats', 20);

function tjames_adds_key_feats() {
    global $product;
    echo '<div class="key_feats_and_badge">';
                if( have_rows('main_details') ):
        // loop through the rows of data
        while ( have_rows('main_details') ) : the_row();
    if( $sale_badge = get_sub_field( 'sale_badge', $product->get_id() ) ) {
        $sale_badge = str_replace(' ', '_', $sale_badge);
        echo '<img src="/wp-content/uploads/'. $sale_badge . '.png" class="new_sale_badge" />';
    }
        endwhile;

    else :

    // no rows found

    endif;
        if( have_rows('key_features') ):
        // loop through the rows of data
        while ( have_rows('key_features') ) : the_row();
    
    if( $dab = get_sub_field( 'dab_radio', $product->get_id() ) ) {
        echo '<img src="/wp-content/uploads/dab-radio.png" class="dab_icon" />';
    }
   if( $bluetooth2 = get_sub_field( 'bluetooth', $product->get_id() ) ) {
        echo '<img src="/wp-content/uploads/bluetooth.png" alt="bluetooth" class="dab_icon" />';
    }
    if( $cruisecontrol2 = get_sub_field( 'cruise_control', $product->get_id() ) ) {
        echo '<img src="/wp-content/uploads/cruise-control.png" alt="cruise-control" class="dab_icon" />';
    }
    if( $aircon2 = get_sub_field( 'air_conditioning', $product->get_id() ) ) {
        echo '<img src="/wp-content/uploads/air-con.png" alt="air-con" class="dab_icon" />';
    }
    if( $satnav2 = get_sub_field( 'satellite_navigation', $product->get_id() ) ) {
        echo '<img src="/wp-content/uploads/sat-nav.png" alt="sat-nav" class="dab_icon" />';
    }
    if( $heatedseats2 = get_sub_field( 'heated_seats', $product->get_id() ) ) {
        echo '<img src="/wp-content/uploads/heated-seats.png" alt="heated-seats" class="dab_icon" />';
    }
    if( $applecc = get_sub_field( 'apple_carplay', $product->get_id() ) ) {
        echo '<img src="/wp-content/uploads/apple-carplay-1.png" alt="apple-carplay" class="dab_icon" />';
    }


    endwhile;

    else :

    // no rows found

    endif;

    echo '</div>';
}

// Adds the USP and the our price message

add_action('woocommerce_shop_loop_item_title','tjames_adds_the_usp',20);

function tjames_adds_the_usp() {
    
    global $product;

    $url2 = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

    echo '<div class="archive_prive_container">';
    echo '<h2 class="post-title">'.$product->post->post_title . '</h2>';
    echo '</div>';
    echo '<div class="usp">';
    if( have_rows('main_details') ):
    // loop through the rows of data
    while ( have_rows('main_details') ) : the_row();
        echo '<div class="usp_messages">';
    if ( $mainusps  = get_sub_field('usp_archive', $product->get_id()) ) {
        echo '<p class="main_usp">' . $mainusps . '';
    }
/*    if ( $secondusp = get_sub_field('second_usp', $product->get_id()) ) {
    echo '<p class="second_usp">' . $secondusp . '</p>';
    }
*/
    endwhile;

    else :

    // no rows found

    endif;
   echo '</div>';

    echo '</p>';
    /*if ( $subusps  = get_field('sub_usp', $product->get_id()) ) {
        echo '<p class="sub_usp">' . $subusps . '</p></p>';
    }*/
    echo '</div>';


}

add_action('woocommerce_shop_loop_item_title','add_archive_price',20);

function add_archive_price() {

    global $product;

    $currentprice = get_field('main_business_price');

    echo '<p class="ourprice">Our price</p>';
    echo '<div class="archive_price_container">';
    

    // PERSONAL
    echo '<span class="cc-show-personal">';
    echo '<p class="archive_price">£';
        
    //unless personal price is added
    if ( $personalprice  = get_field('main_personal_price_alt', $product->get_id()) ) {

        //display personal price
        the_field('main_personal_price_alt');
        echo '</p>';
        echo '<p class="vat">';
        echo 'inc VAT';
        echo '</p>';

    } elseif ($currentprice == 0) {

        echo 'CALL';
        echo '</p>';
        echo '<p class="vat">';
        echo 'inc VAT';
        echo '</p>';

    } else {

        //display business price with 20 percent tax
        $currentprice = get_field('main_business_price');
        $tax = $currentprice / 100 * 20;
        $changeprice = $currentprice + $tax;
        print number_format((float)$changeprice, 2, '.', '');
        echo '<p class="vat">';
        echo 'inc VAT';
        echo '</p>';

    } 
    echo ' </p>';
    echo '</span>';

    // BUSINESS
    echo '<span class="cc-show-business">';
    echo '<p class="archive_price">£';
    //display the original business price when url doesn't contain personal
    if ( $mainbusinessprice  = get_field('main_business_price', $product->get_id()) ) {
        //display personal price
        print number_format((float)$mainbusinessprice, 2, '.', '');
    }
    if ( $mainbusinessprice == 0){
        echo 'call';
    }
    echo '<p class="vat">';
    echo 'excl VAT';
    echo '</p>';
    echo ' </p>';
    echo '</span>';

    // ---------------
    
    echo '</div>';


    $link = $product->get_permalink();
    echo '<div class="quotes"> <a href="' . $link . '" class="button addtocartbutton">View Deal</a>';
    echo '<a href="' . $link . '" class="button addtocartbutton">Get a Quote</a></div>';
}

// Archive ends

// ====================================================================================
//                          Start of the Custom Product Page
// ====================================================================================

//adds the product title to the SPP

add_action('woocommerce_after_single_product','new_product_title', 20);

function new_product_title() {
        global $product;

        echo '<div class="container">';
            echo '<div class="row">';
                echo '<div class="col">';

                    if( $make = get_field( 'make', $product->get_id() ) ) {
                    echo '<p class="product-title"><h1 class="make">' . $make . '';
                    }
                    if( $model = get_field( 'model', $product->get_id() ) ) {
                    echo ' '. $model . '</br></h1>';
                    }
                    if( $variant = get_field( 'variant', $product->get_id() ) ) {
                    echo '<p class="car_variant">'. $variant . '</p>';
                    }
                echo '</div>';
            echo '</div>';
        echo '</div>';


    if( have_rows('main_details') ):
    
        // loop through the rows of data
        while ( have_rows('main_details') ) : the_row();
            
        echo '<div class="container">';
            echo '<div class="row">';
                echo '<div class="product_image col-lg-7">';
                    echo '<div class="sale_icon">';
            
                    if( $sale_badge = get_sub_field( 'sale_badge', $product->get_id() ) ) {
                
                    $sale_badge = str_replace(' ', '_', $sale_badge);
                    echo '<img src="/wp-content/uploads/'. $sale_badge . '.png" class="new_sale_badge" />';
                    }


                    endwhile;

                    else :

                    // no rows found

                    endif;

                    echo '</div>';


                do_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );

                echo '</div>';


            echo '<div class="right_details col-lg-5">';
                echo '<div class="logo_n_price">';
    

                    if( $manulogo = get_field( 'make', $product->get_id() ) ) {
                $manulogo = str_replace(' ', '-', $manulogo);
                echo '<a href="/make/' . $manulogo . '"><img src="/wp-content/uploads/' . strtoupper($manulogo). '-logo.png" class="manufacturer_logo" /></a>';;
                }

                $currentprice = get_field('main_business_price');

                //Personal 
                    echo '<div class="cc-show-personal price_type">';
                        echo '<p class="personal-lease">personal lease</p>';

                        echo '<p class="from_price">from </p>';

                        echo '<p class="price">£';

                        //unless personal price is added
                        if ( $personalprice  = get_field('main_personal_price_alt', $product->get_id()) ) {
                        //display personal price
                        the_field('main_personal_price_alt');
                        echo '</p>';
                        echo '<p class="vat">inc VAT</p>';
                        } elseif($currentprice == 0) {
                        echo 'call';
                        } else {
                        //display business price with 20 percent tax
                        $currentprice = get_field('main_business_price');
                        $tax = $currentprice / 100 * 20;
                        $changeprice = $currentprice + $tax;
                        print number_format((float)$changeprice, 2, '.', '');
                        echo '<p class="vat">inc VAT</p>';

                        }
                        echo ' </p>';
                    echo '</div>';

                //Business 
                echo '<div class="cc-show-business price_type"><p class="personal-lease">business lease</p>';

                    echo '<p class="from_price">from </p>';

                    echo '<p class="price">£';

                    //display the original business price when url doesn't contain personal
                    if ( $mainbusinessprice  = get_field('main_business_price', $product->get_id()) ) {
                    //display personal price
                    print number_format((float)$mainbusinessprice, 2, '.', '');
                    }
                    if ( $mainbusinessprice == 0){
                    echo 'call';
                    }
                    echo '<p class="vat">exc VAT</p>';
                echo '</div>';


            if( have_rows('main_details') ):
                // loop through the rows of data
                while ( have_rows('main_details') ) : the_row();

                    echo '<div class="main_usp">';

                        if ( $mainusp  = get_sub_field('usp_product_page', $product->get_id()) ) {
                        echo '<p class="main_usp">' . $mainusp . '</p>';
                        }

                    echo '</div>';

                endwhile;

                else :

                // no rows found

                endif;
  


            if( have_rows('key_features') ):
            // loop through the rows of data
            while ( have_rows('key_features') ) : the_row();

            echo '<p class="key_features">Key Features</p>';
                echo '<div class="key_feats_logos">';

                if( $dabtest = get_sub_field( 'dab_radio', $product->get_id() ) ) {
                echo wp_get_attachment_image( 7282 , 'thumbnail', "", ["class" => "kf_icon"]   );
                }
                if( $bluetooth2 = get_sub_field( 'bluetooth', $product->get_id() ) ) {
                echo wp_get_attachment_image( 7280 , 'thumbnail', "", ["class" => "kf_icon"]   );
                }
                if( $cruisecontrol2 = get_sub_field( 'cruise_control', $product->get_id() ) ) {
                echo wp_get_attachment_image( 861 , 'thumbnail', "", ["class" => "kf_icon"]   );
                }
                if( $aircon2 = get_sub_field( 'air_conditioning', $product->get_id() ) ) {
                echo wp_get_attachment_image( 7279 , 'thumbnail', "", ["class" => "kf_icon"]  );
                }
                if( $satnav2 = get_sub_field( 'satellite_navigation', $product->get_id() ) ) {
                echo wp_get_attachment_image( 7294 , 'thumbnail', "", ["class" => "kf_icon"]   );
                }
                if( $heatedseats2 = get_sub_field( 'heated_seats', $product->get_id() ) ) {
                echo wp_get_attachment_image( 7277 , 'thumbnail', "", ["class" => "kf_icon"]   );
                }
                if( $applecc = get_sub_field( 'apple_carplay', $product->get_id() ) ) {
                echo wp_get_attachment_image( 7294 , 'thumbnail', "", ["class" => "kf_icon"]   );
                }

                    echo '<div class="get_quote"><a href="#quote" class="get_quote_text">Get a Quote</a></div>';
                    echo '<div class="CALL NOW"><a href="tel:0161 928 3456" class="call-now">';



                    endwhile;

                    else :

                    // no rows found

                    endif;



                        echo '<div class="ss_button_container">';
                            echo '<div class="social_share">';
                                echo '<p class="share_this_deal">share this deal!</p>';
                                    echo '<div class="share_icons">';

                                    $url2 = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];


                                    if(get_option("social-share-facebook") == 1){
                                    print '<a href="https://www.facebook.com/sharer/sharer.php?u='.$url2.'" target="_blank"><img src="/wp-content/uploads/facebook.png" class="social_icon" alt="Facebook Icon"></a>';
                                    }
                                    if(get_option("social-share-twitter") == 1){
                                    print '<a href="https://twitter.com/intent/tweet?text='.$url3.'" target="_blank"><img src="/wp-content/uploads/twitter.png" class="social_icon" alt="Twitter Icon"></a>';
                                    }
                                    if(get_option("social-share-whatsapp") == 1){
                                    print '<a href="whatsapp://send?text='.$url3.'" target="_blank"><img src="/wp-content/uploads/whatsapp.png" class="social_icon" alt="Whatsapp Icon"></a>';
                                    }
    
                                    if(get_option("social-share-pinterest") == 1){
                                    print '<a href="https://pinterest.com/pin/create/button/?url='.$url3.'" target="_blank"><img src="/wp-content/uploads/pinterest.png" class="social_icon" alt="Pinterest Icon"></a>';
                                    }
                                    if(get_option("social-share-linkedin") == 1){
                                    print '<a href="https://www.linkedin.com/shareArticle?mini=true&url='.$url3.'" target="_blank"><img src="/wp-content/uploads/linkedin.png" class="social_icon" alt="Linkedin Icon"></a>';
                                    }
                                    if(get_option("social-share-email") == 1){
                                    print '<a href="mailto:enquires@contractcars.com" target="_blank"><img src="/wp-content/uploads/mail.png" class="social_icon" alt="Email Icon"></a>';
                                    }

                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
        echo '<p class="discalimer">Images are for illustration purposes only</p>';
    echo '</div>';
echo '</div>';

}

// Adds the Payment Profile to the Single Product page

add_action('woocommerce_after_single_product','newpaymentprofile', 20);

function newpaymentprofile() {

    global $product;

    $url2 = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
                
    echo '<div class="container">';
        echo '<div class="row">';
            echo '<div class="payment_profile">';
                echo '<p class="Payment_Profile_Title">Payment Profiles</p>';
                
                if (strpos($url2,'personal') || ($_SESSION['cc_pricing'] === 'personal' ))  { 
                    echo '<p class="business_rates">All personal leasing prices include VAT.</p>';
                } else {
                    echo '<p class="business_rates">All business leasing prices exclude VAT.</p>';
                }
            echo '<div class="payment-profile-toggle">';
            echo do_shortcode( '[toggle2]');
        echo '</div>';

        echo 


        '<table>
            <thead>
                <th>Contract Length</th>
                <th>Profile</th>
                <th>Mileage</th>
                <th class="price">Monthly Price</th>
            </thead>
        <tbody>';
        //start of 12 month payment profile//
        if( have_rows('12_month_profile') ):
        // loop through the rows of data
        while ( have_rows('12_month_profile') ) : the_row();
        // display a sub field value

        if( $businessturnon = get_sub_field( 'business_12', $product->get_id() ) ) {
            $businessprice129 = $businessturnon;
        }

        if( $turnon246 = get_sub_field( '6_month_replacement_12', $product->get_id() ) ) {
            $rep126 = $turnon246;
        }

        if( $turnon243 = get_sub_field( '3_month_replacement_12', $product->get_id() ) ) {
            $rep123 = $turnon243;
        }

        if( $turnon241 = get_sub_field( '1_month_replacement_12', $product->get_id() ) ) {
            $rep121 = $turnon241;
        }

        if( $turnonprofile = get_sub_field( 'fee_12', $product->get_id() ) ) {
            $profile12 = $turnonprofile;
        }

        if( $turnonpersonalprofile = get_sub_field( 'personal_12', $product->get_id() ) ) {
            $personal12 = $turnonprofile;
        }

        $personalprice129                   = ($businessprice129 / 100) * 20 + $businessprice129;
        $businessprice126                   = ($businessprice129 * 20 / 17) + 3;
        $personalprice126                   = ($businessprice126 / 100) * 20 + $businessprice126;
        $businessprice123                   = ($businessprice129 * 20 / 14) + 5;
        $personalprice123                   = ($businessprice123 / 100) * 20 + $businessprice123;
        $businessprice121                   = ($businessprice129 * 20 / 12) + 7.5;              
        $personalprice121                   = ($businessprice121 / 100) * 20 + $businessprice121;
        $personalprofile12                  = ($profile12 / 100) * 20 + $profile12;



        if( $switchon = get_sub_field( 'active_12', $product->get_id() ) ) {
            echo '<tr class="pp-table-row">';
                
            if( $contractlegnth12 = get_sub_field( 'contract_length_12', $product->get_id() ) ) {
                echo '<td>' . $contractlegnth12 . '</td>';
            }

            echo '<td class="cc-show-personal">';

                if( $profile12 = get_sub_field( 'fee_12', $product->get_id() ) ) {
                    echo '£' . $personalprofile12 . ' + 9 + 11';
                }

            echo '</td>';

            echo '<td class="cc-show-business">';

                if( $profile12 = get_sub_field( 'fee_12', $product->get_id() ) ) {
                    echo '£' . $profile12 . ' + 9 + 11';
                } 

            echo '</td>';

            //Mileage
            if( $mileage12 = get_sub_field( 'mileage_12', $product->get_id() ) ) {
                echo '<td>' . number_format($mileage12) . ' Per Annum</td>';
            }
            

            echo '<td class="cc-show-personal price">';

                if( $personal129 = get_sub_field( 'personal_12', $product->get_id() ) ) {
                    print '£'.number_format((float)$personal129, 2, '.', '').'<span class="lowprice">Lowest Price</span>';
                } else if ($personal129 === "0") {
                    echo 'CALL';
                } else {
                    print '£'.number_format((float)$rep129, 2, '.', '').'<span class="lowprice">Lowest Price</span>';
                } 

            echo '</td>';

            echo '<td class="cc-show-business price">';

                if( $business129 = get_sub_field( 'business_12', $product->get_id() ) ) {
                    print '£'.number_format((float)$businessprice129, 2, '.', '').'<span class="lowprice">Lowest Price</span>';
                } else if ($business129 === "0") {
                    echo 'CALL';
                }

            echo '</td>';

            echo '</tr>';

            if( $switchon612 = get_sub_field( '6_month_12', $product->get_id() ) ) {
                echo '<tr>';
                
                if( $contractlegnth12 = get_sub_field( 'contract_length_12', $product->get_id() ) ) {
                    echo '<td>' . $contractlegnth12 . '</td>';
                }

                echo '<td class="cc-show-personal">';
                
                    if( $profile12 = get_sub_field( 'fee_12', $product->get_id() ) ) {
                        echo '£' . $personalprofile12 . ' + 6 + 11';
                    }

                echo '</td>';

                echo '<td class="cc-show-business">';

                    if( $profile12 = get_sub_field( 'fee_12', $product->get_id() ) ) {
                        echo '£' . $profile12 . ' + 6 + 11';
                    }

                echo '</td>';

                if( $mileage12 = get_sub_field( 'mileage_12', $product->get_id() ) ) {
                    echo '<td>' . number_format($mileage12) . ' Per Annum</td>';
                }
            
                echo '<td class="cc-show-personal price">';

                    if( $rep6active12 = get_sub_field( '6_month_replacement_12', $product->get_id() ) ) {
                        print '£'.number_format((float)$rep6active12, 2, '.', '');
                    } else if ($personal129 === "0") {
                        echo 'CALL';
                    } else {
                        print '£'.number_format((float)$personalprice126, 2, '.', '');
                    } 
                
                echo '</td>';

                echo '<td class="cc-show-business price">';

                    if( $business6replace12 = get_sub_field( '6_month_replacement_12', $product->get_id() ) ) {
                        print '£'.number_format((float)$business6replace12, 2, '.', '');
                    } else if ($businessprice129 === '0') {
                        echo 'CALL';
                    } else {
                        print '£'.number_format((float)$businessprice126, 2, '.', '');
                    }

                echo '</td>';

                echo '</tr>';

            }

            if( $switchon312 = get_sub_field( '3_month_12', $product->get_id() ) ) {
                echo '<tr>';
                
                if( $contractlegnth12 = get_sub_field( 'contract_length_12', $product->get_id() ) ) {
                    echo '<td>' . $contractlegnth12 . '</td>';
                }


                echo '<td class="cc-show-personal">';
                
                    if( $profile12 = get_sub_field( 'fee_12', $product->get_id() ) ) {
                        echo '£' . $personalprofile12 . ' + 3 + 11';
                    }

                echo '</td>';

                echo '<td class="cc-show-business">';

                    if( $profile12 = get_sub_field( 'fee_12', $product->get_id() ) ) {
                        echo '£' . $profile12 . ' + 3 + 11';
                    }

                echo '</td>';

                if( $mileage12 = get_sub_field( 'mileage_12', $product->get_id() ) ) {
                    echo '<td>' . number_format($mileage12) . ' Per Annum</td>';
                }
    
                echo '<td class="cc-show-personal price">';

                    if( $rep6active12 = get_sub_field( '3_month_replacement_12', $product->get_id() ) ) {
                        print '£'.number_format((float)$rep123plusvat, 2, '.', '');
                    } else if ($personal129 === "0") {
                        echo 'CALL';
                    } else {
                        print '£'.number_format((float)$personalprice123, 2, '.', '');
                    } 
                
                echo '</td>';

                echo '<td class="cc-show-business price">';

                if ($businessprice129 === '0') {
                        echo 'CALL';
                    } else {
                        print '£'.number_format((float)$businessprice123, 2, '.', '');
                    }

                echo '</td>';

                echo '</tr>';
            }

            if( $switchon112 = get_sub_field( '1_month_12', $product->get_id() ) ) {
                
                echo '<tr>';
                
                if( $contractlegnth12 = get_sub_field( 'contract_length_12', $product->get_id() ) ) {
                    echo '<td>' . $contractlegnth12 . '</td>';
                }
                
                echo '<td class="cc-show-personal">';
                
                    if( $profile12 = get_sub_field( 'fee_12', $product->get_id() ) ) {
                        echo '£' . $personalprofile12 . ' + 1 + 11';
                    }

                echo '</td>';

                echo '<td class="cc-show-business">';

                    if( $profile12 = get_sub_field( 'fee_12', $product->get_id() ) ) {
                        echo '£' . $profile12 . ' + 1 + 11';
                    }

                echo '</td>';

                if( $mileage12 = get_sub_field( 'mileage_12', $product->get_id() ) ) {
                    echo '<td>' . number_format($mileage12) . ' Per Annum</td>';
                }

                echo '<td class="cc-show-personal price">';

                    if( $rep6active12 = get_sub_field( '1_month_replacement_12', $product->get_id() ) ) {
                        print '£'.number_format((float)$rep6active12, 2, '.', '').'<span class="lowdeposit">Lowest Down Payment</span>';
                    } else if ($personal129 === "0") {
                        echo 'CALL';
                    } else {
                        print '£'.number_format((float)$personalprice123, 2, '.', '').'<span class="lowdeposit">Lowest Down Payment</span>';
                    } 
                
                echo '</td>';

                echo '<td class="cc-show-business price">';

                    if ($businessprice129 === '0') {
                        echo 'CALL';
                    } else {
                        print '£'.number_format((float)$businessprice121, 2, '.', '').'<span class="lowdeposit">Lowest Down Payment</span>';
                    }

                echo '</td>';

                echo '</tr>';

            }

        }

        endwhile;

        else :

        // no rows found

        endif;

            //start of 18 month payment profile//
            if( have_rows('18_month_profile') ):
            // loop through the rows of data
            while ( have_rows('18_month_profile') ) : the_row();
            // display a sub field value

            if( $businessturnon = get_sub_field( 'business_18', $product->get_id() ) ) {
                     $businessprice189 = $businessturnon;
                    }

            if( $turnon246 = get_sub_field( '6_month_replacement_18', $product->get_id() ) ) {
                     $rep186 = $turnon246;
                    }

            if( $turnon243 = get_sub_field( '3_month_replacement_18', $product->get_id() ) ) {
                     $rep183 = $turnon243;
                    }

            if( $turnon241 = get_sub_field( '1_month_replacement_18', $product->get_id() ) ) {
                     $rep181 = $turnon241;
                    }

            if( $turnonprofile = get_sub_field( 'fee_18', $product->get_id() ) ) {
                     $profile18 = $turnonprofile;
                    }

                $personalprice189                   = ($businessprice189 / 100) * 20 + $businessprice189;
                $businessprice186                   = ($businessprice189 * 26 / 23) + 3;
                $personalprice186                   = ($businessprice186 / 100) * 20 + $businessprice186;
                $businessprice183                   = ($businessprice189 * 26 / 20) + 5;
                $personalprice183                   = ($businessprice183 / 100) * 20 + $businessprice183;
                $businessprice181                   = ($businessprice189 * 26 / 18) + 7.5;              
                $personalprice181                   = ($businessprice181 / 100) * 20 + $businessprice181;
                $personalprofile18                  = ($profile18 / 100) * 20 + $profile18;


      if( $switchon = get_sub_field( 'active_18', $product->get_id() ) ) {
            echo '<tr class="pp-table-row">';
                
            if( $contractlegnth18 = get_sub_field( 'contract_length_18', $product->get_id() ) ) {
                echo '<td>' . $contractlegnth18 . '</td>';
            }

            echo '<td class="cc-show-personal">';

                if( $profile18 = get_sub_field( 'fee_18', $product->get_id() ) ) {
                    echo '£' . $personalprofile18 . ' + 9 + 17';
                }

            echo '</td>';

            echo '<td class="cc-show-business">';

                if( $profile18 = get_sub_field( 'fee_18', $product->get_id() ) ) {
                    echo '£' . $profile18 . ' + 9 + 17';
                } 

            echo '</td>';

            //Mileage
            if( $mileage18 = get_sub_field( 'mileage_18', $product->get_id() ) ) {
                echo '<td>' . number_format($mileage18) . ' Per Annum</td>';
            }
            

            echo '<td class="cc-show-personal price">';

                if( $personal189 = get_sub_field( 'personal_18', $product->get_id() ) ) {
                    print '£'.number_format((float)$personal189, 2, '.', '').'<span class="lowprice">Lowest Price</span>';
                } else if ($personal189 === '0') {
                    echo 'CALL';
                } else {
                    print '£'.number_format((float)$personalprice189, 2, '.', '').'<span class="lowprice">Lowest Price</span>';
                } 

            echo '</td>';

            echo '<td class="cc-show-business price">';

                if( $business189 = get_sub_field( 'business_18', $product->get_id() ) ) {
                    print '£'.number_format((float)$businessprice189, 2, '.', '').'<span class="lowprice">Lowest Price</span>';
                } else if ($business189 === '0') {
                    echo 'CALL';
                }

            echo '</td>';

            echo '</tr>';

            if( $switchon618 = get_sub_field( '6_month_18', $product->get_id() ) ) {
                echo '<tr>';
                
                if( $contractlegnth18 = get_sub_field( 'contract_length_18', $product->get_id() ) ) {
                    echo '<td>' . $contractlegnth18 . '</td>';
                }

                echo '<td class="cc-show-personal">';
                
                    if( $profile18 = get_sub_field( 'fee_18', $product->get_id() ) ) {
                        echo '£' . $personalprofile18 . ' + 6 + 17';
                    }

                echo '</td>';

                echo '<td class="cc-show-business">';

                    if( $profile18 = get_sub_field( 'fee_18', $product->get_id() ) ) {
                        echo '£' . $profile18 . ' + 6 + 17';
                    }

                echo '</td>';

                if( $mileage18 = get_sub_field( 'mileage_18', $product->get_id() ) ) {
                    echo '<td>' . number_format($mileage18) . ' Per Annum</td>';
                }
            
                echo '<td class="cc-show-personal price">';

                    if( $rep6active18 = get_sub_field( '6_month_replacement_18', $product->get_id() ) ) {
                        print '£'.number_format((float)$rep6active18, 2, '.', '');
                    } else if ($personal189 === '0') {
                        echo 'CALL';
                    } else {
                        print '£'.number_format((float)$personalprice186, 2, '.', '');
                    } 
                
                echo '</td>';

                echo '<td class="cc-show-business price">';

                if ($businessprice189 === '0') {
                        echo 'CALL';
                    } else {
                        print '£'.number_format((float)$businessprice186, 2, '.', '');
                    }

                echo '</td>';

                echo '</tr>';

            }

            if( $switchon318 = get_sub_field( '3_month_18', $product->get_id() ) ) {
                echo '<tr>';
                
                if( $contractlegnth18 = get_sub_field( 'contract_length_18', $product->get_id() ) ) {
                    echo '<td>' . $contractlegnth18 . '</td>';
                }


                echo '<td class="cc-show-personal">';
                
                    if( $profile18 = get_sub_field( 'fee_18', $product->get_id() ) ) {
                        echo '£' . $personalprofile18 . ' + 3 + 17';
                    }

                echo '</td>';

                echo '<td class="cc-show-business">';

                    if( $profile18 = get_sub_field( 'fee_18', $product->get_id() ) ) {
                        echo '£' . $profile18 . ' + 3 + 17';
                    }

                echo '</td>';

                if( $mileage18 = get_sub_field( 'mileage_18', $product->get_id() ) ) {
                    echo '<td>' . number_format($mileage18) . ' Per Annum</td>';
                }
    
                echo '<td class="cc-show-personal price">';

                    if( $rep6active18 = get_sub_field( '3_month_replacement_18', $product->get_id() ) ) {
                        print '£'.number_format((float)$rep6active18, 2, '.', '');
                    } else if ($personal189 === '0') {
                        echo 'CALL';
                    } else {
                        print '£'.number_format((float)$personalprice183, 2, '.', '');
                    } 
                
                echo '</td>';

                echo '<td class="cc-show-business price">';

                 if ($businessprice189 === '0') {
                        echo 'CALL';
                    } else {
                        print '£'.number_format((float)$businessprice183, 2, '.', '');
                    }

                echo '</td>';

                echo '</tr>';
            }

            if( $switchon118 = get_sub_field( '1_month_18', $product->get_id() ) ) {
                
                echo '<tr>';
                
                if( $contractlegnth18 = get_sub_field( 'contract_length_18', $product->get_id() ) ) {
                    echo '<td>' . $contractlegnth18 . '</td>';
                }
                
                echo '<td class="cc-show-personal">';
                
                    if( $profile18 = get_sub_field( 'fee_18', $product->get_id() ) ) {
                        echo '£' . $personalprofile18 . ' + 1 + 17';
                    }

                echo '</td>';

                echo '<td class="cc-show-business">';

                    if( $profile18 = get_sub_field( 'fee_18', $product->get_id() ) ) {
                        echo '£' . $profile18 . ' + 1 + 11';
                    }

                echo '</td>';

                if( $mileage18 = get_sub_field( 'mileage_18', $product->get_id() ) ) {
                    echo '<td>' . number_format($mileage18) . ' Per Annum</td>';
                }

                echo '<td class="cc-show-personal price">';

                    if( $rep6active18 = get_sub_field( '1_month_replacement_18', $product->get_id() ) ) {
                        print '£'.number_format((float)$rep6active18, 2, '.', '').'<span class="lowdeposit">Lowest Upfront</span>';
                    } else if ($personal189 == '0') {
                        echo 'CALL';
                    } else {
                        print '£'.number_format((float)$personalprice183, 2, '.', '').'<span class="lowdeposit">Lowest Upfront</span>';
                    } 
                
                echo '</td>';

                echo '<td class="cc-show-business price">';

                    if ($businessprice189 === '0') {
                        echo 'CALL';
                    } else {
                        print '£'.number_format((float)$businessprice181, 2, '.', '').'<span class="lowdeposit">Lowest Upfront</span>';
                    }

                echo '</td>';

                echo '</tr>';

            }

        }

            endwhile;

            else :

            // no rows found

            endif;

            if( have_rows('24_month_profile') ):
            // loop through the rows of data
            while ( have_rows('24_month_profile') ) : the_row();
            // display a sub field value

            if( $businessturnon = get_sub_field( 'business', $product->get_id() ) ) {
                     $businessprice249 = $businessturnon;
                    }

            if( $turnon246 = get_sub_field( '6_month_replacement', $product->get_id() ) ) {
                     $rep246 = $turnon246;
                    }

            if ($rep246 === 0) {
                         $business6replace = 'CALL';
                    }

            if( $turnon243 = get_sub_field( '3_month_replacement', $product->get_id() ) ) {
                     $rep243 = $turnon243;
                    }

            if( $turnon241 = get_sub_field( '1_month_replacement', $product->get_id() ) ) {
                     $rep241 = $turnon241;
                    }

            if( $turnonprofile = get_sub_field( 'fee', $product->get_id() ) ) {
                     $profile = $turnonprofile;
                    }

            if( $turnonpersonalprofile = get_sub_field( 'personal', $product->get_id() ) ) {
            $personal = $turnonprofile;
        }


                $personalprice249                   = ($businessprice249 / 100) * 20 + $businessprice249;
                $businessprice246                   = ($businessprice249 * 32) / 29 + 3;
                $personalprice246                   = ($businessprice246 / 100) * 20 + $businessprice246;
                $businessprice243                   = ($businessprice249 * 32) / 26 + 5;
                $personalprice243                   = ($businessprice243 / 100) * 20 + $businessprice243;
                $businessprice241                   = ($businessprice249 * 32) / 24 + 7.5;              
                $personalprice241                   = ($businessprice241 / 100) * 20 + $businessprice241;
                $personalprofile                    = ($profile / 100) * 20 + $profile;




       if( $switchon = get_sub_field( 'active', $product->get_id() ) ) {
            echo '<tr class="pp-table-row">';
                
            if( $contractlegnth = get_sub_field( 'contract_length', $product->get_id() ) ) {
                echo '<td>' . $contractlegnth . '</td>';
            }

            echo '<td class="cc-show-personal">';

                if( $profile = get_sub_field( 'fee', $product->get_id() ) ) {
                    echo '£' . $personalprofile . ' + 9 + 23';
                }

            echo '</td>';

            echo '<td class="cc-show-business">';

                if( $profile18 = get_sub_field( 'fee', $product->get_id() ) ) {
                    echo '£' . $profile . ' + 9 + 23';
                } 

            echo '</td>';

            //Mileage
            if( $mileage = get_sub_field( 'mileage', $product->get_id() ) ) {
                echo '<td>' . number_format($mileage) . ' Per Annum</td>';
            }
            

            echo '<td class="cc-show-personal price">';

                if( $personal249 = get_sub_field( 'personal', $product->get_id() ) ) {
                    print '£'.number_format((float)$personal249, 2, '.', '').'<span class="lowprice">Lowest Price</span>';
                } else if ($personal249 === '0') {
                    echo 'CALL';
                } else {
                    print '£'.number_format((float)$personalprice249, 2, '.', '').'<span class="lowprice">Lowest Price</span>';
                } 

            echo '</td>';

            echo '<td class="cc-show-business price">';

                if( $business249 = get_sub_field( 'business', $product->get_id() ) ) {
                    print '£'.number_format((float)$businessprice249, 2, '.', '').'<span class="lowprice">Lowest Price</span>';
                } else if ($business249 === '0') {
                    echo 'CALL';
                }

            echo '</td>';

            echo '</tr>';

            if( $switchon6 = get_sub_field( '6_month', $product->get_id() ) ) {
                echo '<tr>';
                
                if( $contractlegnth18 = get_sub_field( 'contract_length', $product->get_id() ) ) {
                    echo '<td>' . $contractlegnth . '</td>';
                }

                echo '<td class="cc-show-personal">';
                
                    if( $profile = get_sub_field( 'fee', $product->get_id() ) ) {
                        echo '£' . $personalprofile . ' + 6 + 23';
                    }

                echo '</td>';

                echo '<td class="cc-show-business">';

                    if( $profile18 = get_sub_field( 'fee', $product->get_id() ) ) {
                        echo '£' . $profile . ' + 6 + 23';
                    }

                echo '</td>';

                if( $mileage = get_sub_field( 'mileage', $product->get_id() ) ) {
                    echo '<td>' . number_format($mileage) . ' Per Annum</td>';
                }
            
                echo '<td class="cc-show-personal price">';

                    if( $rep6active = get_sub_field( '6_month_replacement', $product->get_id() ) ) {
                        print '£'.number_format((float)$rep6active, 2, '.', '');
                    } else if ($personal249 === '0') {
                        echo 'CALL';
                    } else {
                        print '£'.number_format((float)$personalprice246, 2, '.', '');
                    } 
                
                echo '</td>';

                echo '<td class="cc-show-business price">';

                    if ($business249 === '0') {
                        echo 'CALL';
                    } else {
                        print '£'.number_format((float)$businessprice246, 2, '.', '');
                    }

                echo '</td>';

                echo '</tr>';

            }

            if( $switchon318 = get_sub_field( '3_month', $product->get_id() ) ) {
                echo '<tr>';
                
                if( $contractlegnth = get_sub_field( 'contract_length', $product->get_id() ) ) {
                    echo '<td>' . $contractlegnth . '</td>';
                }


                echo '<td class="cc-show-personal">';
                
                    if( $profile = get_sub_field( 'fee', $product->get_id() ) ) {
                        echo '£' . $personalprofile . ' + 3 + 23';
                    }

                echo '</td>';

                echo '<td class="cc-show-business">';

                    if( $profile18 = get_sub_field( 'fee', $product->get_id() ) ) {
                        echo '£' . $profile . ' + 3 + 23';
                    }

                echo '</td>';

                if( $mileage = get_sub_field( 'mileage', $product->get_id() ) ) {
                    echo '<td>' . number_format($mileage) . ' Per Annum</td>';
                }
    
                echo '<td class="cc-show-personal price">';

                    if( $rep6active = get_sub_field( '3_month_replacement', $product->get_id() ) ) {
                        print '£'.number_format((float)$rep6active, 2, '.', '');
                    } else if ($personal249 === '0') {
                        echo 'CALL';
                    } else {
                        print '£'.number_format((float)$personalprice243, 2, '.', '');
                    } 
                
                echo '</td>';

                echo '<td class="cc-show-business price">';

                    if ($business249 === '0') {
                        echo 'CALL';
                    } else {
                        print '£'.number_format((float)$businessprice243, 2, '.', '');
                    }

                echo '</td>';

                echo '</tr>';
            }

            if( $switchon238 = get_sub_field( '1_month', $product->get_id() ) ) {
                
                echo '<tr>';
                
                if( $contractlegnth = get_sub_field( 'contract_length', $product->get_id() ) ) {
                    echo '<td>' . $contractlegnth . '</td>';
                }
                
                echo '<td class="cc-show-personal">';
                
                    if( $profile = get_sub_field( 'fee', $product->get_id() ) ) {
                        echo '£' . $personalprofile . ' + 1 + 23';
                    }

                echo '</td>';

                echo '<td class="cc-show-business">';

                    if( $profile = get_sub_field( 'fee', $product->get_id() ) ) {
                        echo '£' . $profile18 . ' + 1 + 23';
                    }

                echo '</td>';

                if( $mileage18 = get_sub_field( 'mileage', $product->get_id() ) ) {
                    echo '<td>' . number_format($mileage18) . ' Per Annum</td>';
                }

                echo '<td class="cc-show-personal price">';

                    if( $rep6active = get_sub_field( '1_month_replacement', $product->get_id() ) ) {
                        print '£'.number_format((float)$rep6active, 2, '.', '').'<span class="lowdeposit">Lowest Upfront</span>';
                    } else if ($personal249 === '0') {
                        echo 'CALL';
                    } else {
                        print '£'.number_format((float)$personalprice241, 2, '.', '').'<span class="lowdeposit">Lowest Upfront</span>';
                    } 
                
                echo '</td>';

                echo '<td class="cc-show-business price">';

                    if ($business249 === '0') {
                        echo 'CALL';
                    } else {
                        print '£'.number_format((float)$businessprice241, 2, '.', '').'<span class="lowdeposit">Lowest Upfront</span>';
                    }

                echo '</td>';

                echo '</tr>';

            }

        }

                endwhile;

                else :

                // no rows found

                endif;

                                    // End of 24 month contract payment profile // 
            //start of 30 month payment profile//
            if( have_rows('30_month_profile') ):
            // loop through the rows of data
            while ( have_rows('30_month_profile') ) : the_row();
            // display a sub field value

            if( $businessturnon = get_sub_field( 'business_30', $product->get_id() ) ) {
                     $businessprice309 = $businessturnon;
                    }

            if( $turnon306 = get_sub_field( '6_month_replacement_30', $product->get_id() ) ) {
                     $rep306 = $turnon246;
                    }

            if( $turnon303 = get_sub_field( '3_month_replacement_30', $product->get_id() ) ) {
                     $rep303 = $turnon243;
                    }

            if( $turnon301 = get_sub_field( '1_month_replacement_30', $product->get_id() ) ) {
                     $rep301 = $turnon241;
                    }

            if( $turnonprofile = get_sub_field( 'fee_30', $product->get_id() ) ) {
                     $profile = $turnonprofile;
                    }

                $personalprice309                   = ($businessprice309 / 100) * 20 + $businessprice309;
                $businessprice306                   = ($businessprice309 * 39 / 36) + 3;
                $personalprice306                   = ($businessprice306 / 100) * 20 + $businessprice306;
                $businessprice303                   = ($businessprice309 * 39 / 33) + 5;
                $personalprice303                   = ($businessprice303 / 100) * 20 + $businessprice303;
                $businessprice301                   = ($businessprice309 * 39 / 30) + 7.5;              
                $personalprice301                   = ($businessprice301 / 100) * 20 + $businessprice301;
                $personalprofile30                  = ($profile / 100) * 20 + $profile;



        if( $switchon = get_sub_field( 'active_30', $product->get_id() ) ) {
            echo '<tr class="pp-table-row">';
                
            if( $contractlegnth30 = get_sub_field( 'contract_length_30', $product->get_id() ) ) {
                echo '<td>' . $contractlegnth30 . '</td>';
            }

            echo '<td class="cc-show-personal">';

                if( $profile30 = get_sub_field( 'fee_30', $product->get_id() ) ) {
                    echo '£' . $personalprofile30 . ' + 9 + 29';
                }

            echo '</td>';

            echo '<td class="cc-show-business">';

                if( $profile30 = get_sub_field( 'fee_30', $product->get_id() ) ) {
                    echo '£' . $profile30 . ' + 9 + 29';
                } 

            echo '</td>';

            //Mileage
            if( $mileage30 = get_sub_field( 'mileage_30', $product->get_id() ) ) {
                echo '<td>' . number_format($mileage30) . ' Per Annum</td>';
            }
            

            echo '<td class="cc-show-personal price">';

                if( $personal309 = get_sub_field( 'personal_30', $product->get_id() ) ) {
                    print '£'.number_format((float)$personal309, 2, '.', '').'<span class="lowprice">Lowest Price</span>';
                } else if ($personal309 === '0') {
                    echo 'CALL';
                } else {
                    print '£'.number_format((float)$personalprice309, 2, '.', '').'<span class="lowprice">Lowest Price</span>';
                } 

            echo '</td>';

            echo '<td class="cc-show-business price">';

                if( $business309 = get_sub_field( 'business_30', $product->get_id() ) ) {
                    print '£'.number_format((float)$businessprice309, 2, '.', '').'<span class="lowprice">Lowest Price</span>';
                }  else if ($businessprice309 === '0') {
                    echo 'CALL';
                }

            echo '</td>';

            echo '</tr>';

            if( $switchon630 = get_sub_field( '6_month_30', $product->get_id() ) ) {
                echo '<tr>';
                
                if( $contractlegnth30 = get_sub_field( 'contract_length_30', $product->get_id() ) ) {
                    echo '<td>' . $contractlegnth30 . '</td>';
                }

                echo '<td class="cc-show-personal">';
                
                    if( $profile30 = get_sub_field( 'fee_30', $product->get_id() ) ) {
                        echo '£' . $personalprofile30 . ' + 6 + 29';
                    }

                echo '</td>';

                echo '<td class="cc-show-business">';

                    if( $profile30 = get_sub_field( 'fee_30', $product->get_id() ) ) {
                        echo '£' . $profile30 . ' + 6 + 29';
                    }

                echo '</td>';

                if( $mileage30 = get_sub_field( 'mileage_30', $product->get_id() ) ) {
                    echo '<td>' . number_format($mileage30) . ' Per Annum</td>';
                }
            
                echo '<td class="cc-show-personal price">';

                    if( $rep6active30 = get_sub_field( '6_month_replacement_30', $product->get_id() ) ) {
                        print '£'.number_format((float)$rep6active30, 2, '.', '');
                    } else if ($personal309 === '0') {
                        echo 'CALL';
                    } else {
                        print '£'.number_format((float)$personalprice306, 2, '.', '');
                    } 
                
                echo '</td>';

                echo '<td class="cc-show-business price">';

                     if ($businessprice309 === '0') {
                        echo 'CALL';
                    } else {
                        print '£'.number_format((float)$businessprice306, 2, '.', '');
                    }

                echo '</td>';

                echo '</tr>';

            }

            if( $switchon330 = get_sub_field( '3_month_30', $product->get_id() ) ) {
                echo '<tr>';
                
                if( $contractlegnth30 = get_sub_field( 'contract_length_30', $product->get_id() ) ) {
                    echo '<td>' . $contractlegnth30 . '</td>';
                }


                echo '<td class="cc-show-personal">';
                
                    if( $profile30 = get_sub_field( 'fee_30', $product->get_id() ) ) {
                        echo '£' . $personalprofile30 . ' + 3 + 29';
                    }

                echo '</td>';

                echo '<td class="cc-show-business">';

                    if( $profile30 = get_sub_field( 'fee_30', $product->get_id() ) ) {
                        echo '£' . $profile30 . ' + 3 + 29';
                    }

                echo '</td>';

                if( $mileage30 = get_sub_field( 'mileage_30', $product->get_id() ) ) {
                    echo '<td>' . number_format($mileage30) . ' Per Annum</td>';
                }
    
                echo '<td class="cc-show-personal price">';

                    if( $rep6active30 = get_sub_field( '3_month_replacement_30', $product->get_id() ) ) {
                        print '£'.number_format((float)$rep6active30, 2, '.', '');
                    } else if ($personal309 === '0') {
                        echo 'CALL';
                    } else {
                        print '£'.number_format((float)$personalprice303, 2, '.', '');
                    } 
                
                echo '</td>';

                echo '<td class="cc-show-business price">';

                     if ($businessprice309 === '0') {
                        echo 'CALL';
                    } else {
                        print '£'.number_format((float)$businessprice303, 2, '.', '');
                    }

                echo '</td>';

                echo '</tr>';
            }

            if( $switchon130 = get_sub_field( '1_month_30', $product->get_id() ) ) {
                
                echo '<tr>';
                
                if( $contractlegnth30 = get_sub_field( 'contract_length_30', $product->get_id() ) ) {
                    echo '<td>' . $contractlegnth30 . '</td>';
                }
                
                echo '<td class="cc-show-personal">';
                
                    if( $profile30 = get_sub_field( 'fee_30', $product->get_id() ) ) {
                        echo '£' . $personalprofile30 . ' + 1 + 29';
                    }

                echo '</td>';

                echo '<td class="cc-show-business">';

                    if( $profile30 = get_sub_field( 'fee_30', $product->get_id() ) ) {
                        echo '£' . $profile30 . ' + 1 + 29';
                    }

                echo '</td>';

                if( $mileage30 = get_sub_field( 'mileage_30', $product->get_id() ) ) {
                    echo '<td>' . number_format($mileage30) . ' Per Annum</td>';
                }

                echo '<td class="cc-show-personal price">';

                    if( $rep6active30 = get_sub_field( '1_month_replacement_30', $product->get_id() ) ) {
                        print '£'.number_format((float)$rep6active30, 2, '.', '').'<span class="lowdeposit">Lowest Upfront</span>';
                    } else if ($personal309 == '0') {
                        echo 'CALL';
                    } else {
                        print '£'.number_format((float)$personalprice303, 2, '.', '').'<span class="lowdeposit">Lowest Upfront</span>';
                    } 
                
                echo '</td>';

                echo '<td class="cc-show-business price">';

                     if ($businessprice309 === '0') {
                        echo 'CALL';
                    } else {
                        print '£'.number_format((float)$businessprice301, 2, '.', '').'<span class="lowdeposit">Lowest Upfront</span>';
                    }

                echo '</td>';

                echo '</tr>';

            }

        }

                endwhile;

                else :

                // no rows found

                endif;   

            //start of 36 month payment profile//
            if( have_rows('36_month_profile') ):
            // loop through the rows of data
            while ( have_rows('36_month_profile') ) : the_row();
            // display a sub field value

            if( $businessturnon = get_sub_field( 'business_36', $product->get_id() ) ) {
                     $businessprice369 = $businessturnon;
                    }

            if( $turnon366 = get_sub_field( '6_month_replacement_36', $product->get_id() ) ) {
                     $rep366 = $turnon366;
                    }

            if( $turnon363 = get_sub_field( '3_month_replacement_36', $product->get_id() ) ) {
                     $rep363 = $turnon363;
                    }

            if( $turnon361 = get_sub_field( '1_month_replacement_36', $product->get_id() ) ) {
                     $rep361 = $turnon361;
                    }

            if( $turnonprofile36 = get_sub_field( 'fee_36', $product->get_id() ) ) {
                     $profile36 = $turnonprofile36;
                    }

                $personalprice369                   = ($businessprice369 / 100) * 20 + $businessprice369;
                $businessprice366                   = ($businessprice369 * 45 / 42) + 3;
                $personalprice366                   = ($businessprice366 / 100) * 20 + $businessprice366;
                $businessprice363                   = ($businessprice369 * 45 / 39) + 5;
                $personalprice363                   = ($businessprice363 / 100) * 20 + $businessprice363;
                $businessprice361                   = ($businessprice369 * 45 / 36) + 7.5;              
                $personalprice361                   = ($businessprice361 / 100) * 20 + $businessprice361;
                $personalprofile36                  = ($profile36 / 100) * 20 + $profile36;



           if( $switchon = get_sub_field( 'active_36', $product->get_id() ) ) {
            echo '<tr class="pp-table-row">';
                
            if( $contractlegnth36 = get_sub_field( 'contract_length_36', $product->get_id() ) ) {
                echo '<td>' . $contractlegnth36 . '</td>';
            }

            echo '<td class="cc-show-personal">';

                if( $profile36 = get_sub_field( 'fee_36', $product->get_id() ) ) {
                    echo '£' . $personalprofile36 . ' + 9 + 35';
                }

            echo '</td>';

            echo '<td class="cc-show-business">';

                if( $profile36 = get_sub_field( 'fee_36', $product->get_id() ) ) {
                    echo '£' . $profile36 . ' + 9 + 35';
                } 

            echo '</td>';

            //Mileage
            if( $mileage36 = get_sub_field( 'mileage_36', $product->get_id() ) ) {
                echo '<td>' . number_format($mileage36) . ' Per Annum</td>';
            }
            

            echo '<td class="cc-show-personal price">';

                if( $personal369 = get_sub_field( 'personal_36', $product->get_id() ) ) {
                    print '£'.number_format((float)$personal369, 2, '.', '').'<span class="lowprice">Lowest Price</span>';
                } else if ($personal369 === '0') {
                    echo 'CALL';
                } else {
                    print '£'.number_format((float)$personalprice369, 2, '.', '').'<span class="lowprice">Lowest Price</span>';
                } 

            echo '</td>';

            echo '<td class="cc-show-business price">';

                if( $business369 = get_sub_field( 'business_36', $product->get_id() ) ) {
                    print '£'.number_format((float)$business369, 2, '.', '').'<span class="lowprice">Lowest Price</span>';
                } else if ($business369 === '0') {
                    echo 'CALL';
                }

            echo '</td>';

            echo '</tr>';

            if( $switchon636 = get_sub_field( '6_month_36', $product->get_id() ) ) {
                echo '<tr>';
                
                if( $contractlegnth36 = get_sub_field( 'contract_length_36', $product->get_id() ) ) {
                    echo '<td>' . $contractlegnth36 . '</td>';
                }

                echo '<td class="cc-show-personal">';
                
                    if( $profile36 = get_sub_field( 'fee_36', $product->get_id() ) ) {
                        echo '£' . $personalprofile36 . ' + 6 + 35';
                    }

                echo '</td>';

                echo '<td class="cc-show-business">';

                    if( $profile36 = get_sub_field( 'fee_36', $product->get_id() ) ) {
                        echo '£' . $profile36 . ' + 6 + 35';
                    }

                echo '</td>';

                if( $mileage36 = get_sub_field( 'mileage_36', $product->get_id() ) ) {
                    echo '<td>' . number_format($mileage36) . ' Per Annum</td>';
                }
            
                echo '<td class="cc-show-personal price">';

                    if( $rep6active36 = get_sub_field( '6_month_replacement_36', $product->get_id() ) ) {
                        print '£'.number_format((float)$rep6active36, 2, '.', '');
                    } else if ($personal369 === '0') {
                        echo 'CALL';
                    } else {
                        print '£'.number_format((float)$personalprice366, 2, '.', '');
                    } 
                
                echo '</td>';

                echo '<td class="cc-show-business price">';

                    if ($business369 === '0') {
                        echo 'CALL';
                    } else {
                        print '£'.number_format((float)$businessprice366, 2, '.', '');
                    }

                echo '</td>';

                echo '</tr>';

            }

            if( $switchon336 = get_sub_field( '3_month_36', $product->get_id() ) ) {
                echo '<tr>';
                
                if( $contractlegnth36 = get_sub_field( 'contract_length_36', $product->get_id() ) ) {
                    echo '<td>' . $contractlegnth36 . '</td>';
                }


                echo '<td class="cc-show-personal">';
                
                    if( $profile36 = get_sub_field( 'fee_36', $product->get_id() ) ) {
                        echo '£' . $personalprofile36 . ' + 3 + 35';
                    }

                echo '</td>';

                echo '<td class="cc-show-business">';

                    if( $profile36 = get_sub_field( 'fee_36', $product->get_id() ) ) {
                        echo '£' . $profile36 . ' + 3 + 35';
                    }

                echo '</td>';

                if( $mileage36 = get_sub_field( 'mileage_36', $product->get_id() ) ) {
                    echo '<td>' . number_format($mileage36) . ' Per Annum</td>';
                }
    
                echo '<td class="cc-show-personal price">';

                    if( $rep6active36 = get_sub_field( '3_month_replacement_36', $product->get_id() ) ) {
                        print '£'.number_format((float)$rep6active36, 2, '.', '');
                    } else if ($personal369 == '0') {
                        echo 'CALL';
                    } else {
                        print '£'.number_format((float)$personalprice363, 2, '.', '');
                    } 
                
                echo '</td>';

                echo '<td class="cc-show-business price">';

                    if ($business369 === '0') {
                        echo 'CALL';
                    } else {
                        print '£'.number_format((float)$businessprice363, 2, '.', '');
                    }

                echo '</td>';

                echo '</tr>';
            }

            if( $switchon136 = get_sub_field( '1_month_36', $product->get_id() ) ) {
                
                echo '<tr>';
                
                if( $contractlegnth36 = get_sub_field( 'contract_length_36', $product->get_id() ) ) {
                    echo '<td>' . $contractlegnth36 . '</td>';
                }
                
                echo '<td class="cc-show-personal">';
                
                    if( $profile36 = get_sub_field( 'fee_36', $product->get_id() ) ) {
                        echo '£' . $personalprofile36 . ' + 1 + 35';
                    }

                echo '</td>';

                echo '<td class="cc-show-business">';

                    if( $profile36 = get_sub_field( 'fee_36', $product->get_id() ) ) {
                        echo '£' . $profile36 . ' + 1 + 35';
                    }

                echo '</td>';

                if( $mileage36 = get_sub_field( 'mileage_36', $product->get_id() ) ) {
                    echo '<td>' . number_format($mileage36) . ' Per Annum</td>';
                }

                echo '<td class="cc-show-personal price">';

                    if( $rep6active36 = get_sub_field( '1_month_replacement_36', $product->get_id() ) ) {
                        print '£'.number_format((float)$rep6active36, 2, '.', '').'<span class="lowdeposit">Lowest Upfront</span>';
                    } else if ($personal369 == '0') {
                        echo 'CALL';
                    } else {
                        print '£'.number_format((float)$personalprice361, 2, '.', '').'<span class="lowdeposit">Lowest Upfront</span>';
                    } 
                
                echo '</td>';

                echo '<td class="cc-show-business price">';

                    if ($business369 === '0') {
                        echo 'CALL';
                    } else {
                        print '£'.number_format((float)$businessprice361, 2, '.', '').'<span class="lowdeposit">Lowest Upfront</span>';
                    }

                echo '</td>';

                echo '</tr>';

            }

        }

                endwhile;

                else :

                // no rows found

                endif;

            echo ' 
 



            </tbody>
                </table>
                    <p class="contracttncs">The rental quoted is commission free. Excess mileage charges will apply. This is a non-maintained contract and the responsibility is with the hirer to maintain the vehicle (driver maintained), at the main dealers as per the manufacturer&#96;s warranty guidelines. At the end of the contract you simply hand the vehicle back (subject to terms and conditions of your agreement).</p>
                </div>
            </div>
        </div>
    </div>
</div>';

}


function arphabet_widgets_init() {

    register_sidebar( array(
        'name'          => 'product search',
        'id'            => 'productsearch',
        'before_widget' => '<div>',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="rounded">',
        'after_title'   => '</h2>',
    ) );

    register_sidebar( array(
    'name' => 'Footer Sidebar 1',
    'id' => 'footer-sidebar-1',
    'description' => 'Appears in the footer area',
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget' => '</aside>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
) );

}
add_action( 'widgets_init', 'arphabet_widgets_init' );


/* find the hook on a page

$debug_tags = array();
add_action( 'all', function ( $tag ) {
    global $debug_tags;
    if ( in_array( $tag, $debug_tags ) ) {
        return;
    }
    echo "<pre>" . $tag . "</pre>";
    $debug_tags[] = $tag;
} );

*/


function do_test_shortcode() {

    echo 'this works!';

}

add_shortcode('testcode', 'do_text_shortcode');




// Edit WooCommerce dropdown menu item of shop page//
// Options: menu_order, popularity, rating, date, price, price-desc
 
function my_woocommerce_catalog_orderby( $orderby ) {
    unset($orderby["popularity"]);
    unset($orderby["rating"]);
    unset($orderby["date"]);
    unset($orderby["price-desc"]);
    unset($orderby["price"]);

    return $orderby;
}
add_filter( "woocommerce_catalog_orderby", "my_woocommerce_catalog_orderby", 20 );


function skyverge_add_postmeta_ordering_args( $sort_args ) {
        
    $orderby_value = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
    switch( $orderby_value ) {
    

                
        case 'ACF_price_asc':
            $sort_args['orderby'] = 'meta_value_num';
            // We use meta_value_num here because points are a number and we want to sort in numerical order
            $sort_args['order'] = 'asc';
            $sort_args['meta_key'] = 'main_business_price';

            break;

        case 'ACF_price_desc':
            $sort_args['orderby'] = 'meta_value_num';
            // We use meta_value_num here because points are a number and we want to sort in numerical order
            $sort_args['order'] = 'desc';
            $sort_args['meta_key'] = 'main_business_price';

            break;
        
    }
    
    return $sort_args;
}
add_filter( 'woocommerce_get_catalog_ordering_args', 'skyverge_add_postmeta_ordering_args' );


// Add these new sorting arguments to the sortby options on the frontend
function skyverge_add_new_postmeta_orderby( $sortby ) {
    
    // Adjust the text as desired
    $sortby['ACF_price_asc'] = __( 'Sort by Price Low to High', 'woocommerce' );
    $sortby['ACF_price_desc'] = __( 'Sort by Price High to Low', 'woocommerce' );
    
    return $sortby;
}
add_filter( 'woocommerce_default_catalog_orderby_options', 'skyverge_add_new_postmeta_orderby' );
add_filter( 'woocommerce_catalog_orderby', 'skyverge_add_new_postmeta_orderby' );

add_filter( 'loop_shop_per_page', 'bbloomer_redefine_products_per_page', 9999 );
 
function bbloomer_redefine_products_per_page( $per_page ) {
  $per_page = 36;
  return $per_page;
}


add_filter( 'manage_edit-product_columns', 'ammojest_brand_column', 20 );
function ammojest_brand_column( $columns_array ) {
 
    // I want to display Brand column just after the product name column
    return array_slice( $columns_array, 1, NULL, true )
    + array( 'business price' => 'Business Price' )
    + array_slice( $columns_array, 4, NULL, true );
 
 
}
 
add_action( 'manage_posts_custom_column', 'misha_populate_brands' );
function misha_populate_brands( $column_name ) {
 
    if( $column_name  == 'business price' ) {
        // if you suppose to display multiple brands, use foreach();
        $product = wc_get_product( $product_id );
        $businessprice = get_field('main_business_price', $post->ID);
        print '£'.number_format((float)$businessprice, 2, '.', '');
    }
 
} 

/**
 * Extend WordPress search to include custom fields
 *
 * https://adambalee.com
 */

/**
 * Join posts and postmeta tables
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_join
 */
function cf_search_join( $join ) {
    global $wpdb;

    if ( is_search() ) {    
        $join .=' LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
    }

    return $join;
}
add_filter('posts_join', 'cf_search_join' );

/**
 * Modify the search query with posts_where
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_where
 */
function cf_search_where( $where ) {
    global $pagenow, $wpdb;

    if ( is_search() ) {
        $where = preg_replace(
            "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
            "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where );
    }

    return $where;
}
add_filter( 'posts_where', 'cf_search_where' );
